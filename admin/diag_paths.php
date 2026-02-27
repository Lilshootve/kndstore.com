<?php
require_once __DIR__ . '/../includes/session.php';

$secretsPath = __DIR__ . '/../config/admin_secrets.local.php';
$secretsExists = file_exists($secretsPath);
$secretsReadable = $secretsExists && is_readable($secretsPath);

$adminSecrets = null;
$credsValid = false;

if ($secretsExists) {
    $adminSecrets = require $secretsPath;
    $credsValid = is_array($adminSecrets)
        && !empty(trim($adminSecrets['admin_user'] ?? ''))
        && !empty(trim($adminSecrets['admin_pass'] ?? ''));
}

if (!$secretsExists) {
    http_response_code(500);
    echo 'Admin secrets file not found at: ' . htmlspecialchars($secretsPath);
    exit;
}
if (!$credsValid) {
    http_response_code(500);
    echo 'admin_user/admin_pass empty in: ' . htmlspecialchars($secretsPath);
    exit;
}
if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo 'Not authenticated. Log in at /admin/orders.php first.';
    exit;
}

header('Content-Type: text/html; charset=utf-8');

$projectRoot = dirname(__DIR__);

$expectedFiles = [
    'admin/orders.php'             => __DIR__ . '/orders.php',
    'admin/test_order.php'         => __DIR__ . '/test_order.php',
    'admin/debug_storage.php'      => __DIR__ . '/debug_storage.php',
    'includes/storage.php'         => $projectRoot . '/includes/storage.php',
    'api/other/create_request.php' => $projectRoot . '/api/other/create_request.php',
    'config/admin_secrets.local.php' => $secretsPath,
];
?>
<!DOCTYPE html>
<html>
<head><title>Path Diagnostics — KND Admin</title>
<style>
    body { font-family: 'Courier New', monospace; background: #0a0a0a; color: #e0e0e0; padding: 2rem; }
    h1 { color: #00d4ff; margin-bottom: 1.5rem; }
    .section { background: #111; border: 1px solid #222; padding: 1rem; margin-bottom: 1rem; border-radius: 6px; }
    .label { color: #888; }
    .ok { color: #4ade80; }
    .warn { color: #facc15; }
    .fail { color: #f87171; }
    table { width: 100%; border-collapse: collapse; }
    td, th { text-align: left; padding: .4rem .6rem; border-bottom: 1px solid #222; }
    th { color: #888; font-weight: normal; }
    a { color: #00d4ff; }
    code { background: #1a1a1a; padding: .15rem .4rem; border-radius: 3px; }
</style>
</head>
<body>
<h1>Path Diagnostics</h1>
<p><a href="/admin/orders.php">&larr; Admin Panel</a> &nbsp;|&nbsp; <a href="/admin/debug_storage.php">Debug Storage</a></p>

<div class="section">
    <h3>Core Paths</h3>
    <table>
        <tr><th>Key</th><th>Value</th></tr>
        <tr><td class="label">__DIR__ (admin/)</td><td><code><?php echo htmlspecialchars(__DIR__); ?></code></td></tr>
        <tr><td class="label">Project root</td><td><code><?php echo htmlspecialchars($projectRoot); ?></code></td></tr>
        <tr><td class="label">secretsPath</td><td><code><?php echo htmlspecialchars($secretsPath); ?></code></td></tr>
        <tr>
            <td class="label">Secrets file exists</td>
            <td class="<?php echo $secretsExists ? 'ok' : 'fail'; ?>"><?php echo $secretsExists ? 'YES' : 'NO'; ?></td>
        </tr>
        <tr>
            <td class="label">Secrets file readable</td>
            <td class="<?php echo $secretsReadable ? 'ok' : 'fail'; ?>"><?php echo $secretsReadable ? 'YES' : 'NO'; ?></td>
        </tr>
        <tr>
            <td class="label">Credentials valid</td>
            <td class="<?php echo $credsValid ? 'ok' : 'fail'; ?>"><?php echo $credsValid ? 'YES (non-empty user+pass)' : 'NO'; ?></td>
        </tr>
    </table>
</div>

<div class="section">
    <h3>Expected Files</h3>
    <table>
        <tr><th>Relative Path</th><th>Absolute Path</th><th>Exists</th></tr>
        <?php foreach ($expectedFiles as $rel => $abs): ?>
        <?php $exists = file_exists($abs); ?>
        <tr>
            <td><code><?php echo htmlspecialchars($rel); ?></code></td>
            <td><code><?php echo htmlspecialchars($abs); ?></code></td>
            <td class="<?php echo $exists ? 'ok' : 'fail'; ?>"><?php echo $exists ? 'YES' : 'MISSING'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="section">
    <h3>PHP Environment</h3>
    <table>
        <tr><td class="label">PHP version</td><td><?php echo htmlspecialchars(PHP_VERSION); ?></td></tr>
        <tr><td class="label">SAPI</td><td><?php echo htmlspecialchars(php_sapi_name()); ?></td></tr>
        <tr><td class="label">DOCUMENT_ROOT</td><td><code><?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? '-'); ?></code></td></tr>
        <tr><td class="label">SCRIPT_FILENAME</td><td><code><?php echo htmlspecialchars($_SERVER['SCRIPT_FILENAME'] ?? '-'); ?></code></td></tr>
    </table>
</div>
</body>
</html>
