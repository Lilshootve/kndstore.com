<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/support_credits.php';
require_once __DIR__ . '/../../includes/knd_avatar.php';

try {
    api_require_login();

    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);

    $userId = current_user_id();

    release_available_points_if_due($pdo, $userId);
    expire_points_if_due($pdo, $userId);

    $loadout = avatar_get_user_loadout($pdo, $userId);
    $inventory = avatar_get_inventory($pdo, $userId);
    $kpBalance = get_available_points($pdo, $userId);

    $invIds = array_column($inventory, 'id');

    json_success([
        'loadout' => $loadout,
        'inventory' => $inventory,
        'inventory_ids' => $invIds,
        'kp_balance' => $kpBalance,
    ]);
} catch (\Throwable $e) {
    error_log('avatar/state error: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An unexpected error occurred.', 500);
}
