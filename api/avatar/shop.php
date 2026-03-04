<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/knd_avatar.php';

try {
    api_require_login();

    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);

    $items = avatar_get_shop_items($pdo);

    json_success(['items' => $items]);
} catch (\Throwable $e) {
    error_log('avatar/shop error: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An unexpected error occurred.', 500);
}
