<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
header('X-Robots-Tag: noindex, nofollow');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/config.php';

$secretsPath = __DIR__ . '/../config/admin_secrets.local.php';
if (!file_exists($secretsPath)) { http_response_code(403); echo 'Auth config missing'; exit; }
$adminSecrets = require $secretsPath;
if (!is_array($adminSecrets)) { http_response_code(403); echo 'Bad auth config'; exit; }
$adminUser = trim($adminSecrets['admin_user'] ?? $adminSecrets['username'] ?? '');
$adminPass = trim($adminSecrets['admin_pass'] ?? $adminSecrets['password'] ?? '');
if (empty($_SESSION['admin_logged_in'])) { header('Location: /admin/orders.php'); exit; }

$results = [];

// 1. Send LiteSpeed purge header for /admin/*
if (isset($_SERVER['HTTP_X_LSCACHE']) || stripos($_SERVER['SERVER_SOFTWARE'] ?? '', 'litespeed') !== false) {
    header('X-LiteSpeed-Purge: /admin/*');
    $results[] = 'LiteSpeed: sent X-LiteSpeed-Purge: /admin/*';
} else {
    $results[] = 'LiteSpeed: not detected (header not sent)';
}

// 2. Clear OPcache for admin PHP files if available
if (function_exists('opcache_reset')) {
    opcache_reset();
    $results[] = 'OPcache: full reset OK';
} elseif (function_exists('opcache_invalidate')) {
    $adminFiles = glob(__DIR__ . '/*.php');
    $count = 0;
    foreach ($adminFiles as $f) {
        if (opcache_invalidate($f, true)) $count++;
    }
    $results[] = "OPcache: invalidated $count admin files";
} else {
    $results[] = 'OPcache: not available';
}

// 3. Clear PHP stat cache
clearstatcache(true);
$results[] = 'PHP stat cache: cleared';

// 4. Touch .htaccess to bust any file-mtime-based caches
$htaccess = __DIR__ . '/.htaccess';
if (file_exists($htaccess)) {
    touch($htaccess);
    $results[] = '.htaccess: touched (mtime updated)';
}

// 5. Set a flash message and redirect back
$_SESSION['purge_results'] = $results;
header('Location: /admin/orders.php?purged=1&tab=' . urlencode($_GET['tab'] ?? 'paypal'));
exit;
