<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/rate_limit.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/knd_drop.php';
require_once __DIR__ . '/../../includes/knd_daily.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
    }

    csrf_guard();
    api_require_verified_email();

    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_FAIL', 'Database error.', 500);

    $userId = current_user_id();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    rate_limit_guard($pdo, "drop_user:{$userId}", 10, 3600);
    rate_limit_guard($pdo, "drop_ip:{$ip}", 30, 3600);

    if (has_risk_flag($pdo, $userId)) {
        json_error('ACCOUNT_FLAGGED', 'Your account has been flagged.', 403);
    }

    $result = drop_play($pdo, $userId);

    if (isset($result['error'])) {
        json_error($result['error'], $result['message']);
    }

    try {
        mission_increment($pdo, $userId, 'make_drop_1');
    } catch (\Throwable $e) {
        error_log('mission_increment drop error: ' . $e->getMessage());
    }

    json_success($result);
} catch (\Throwable $e) {
    error_log('drop play error: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An error occurred.', 500);
}
