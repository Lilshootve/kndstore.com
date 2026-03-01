<?php
// KND Store - Presence ping endpoint (Death Roll 1v1)

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/deathroll_1v1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
}

csrf_guard();
api_require_login();

$pdo = getDBConnection();
$userId = current_user_id();
$now = gmdate('Y-m-d H:i:s');

$stmt = $pdo->prepare(
    'INSERT INTO user_presence (user_id, last_seen) VALUES (?, ?)
     ON DUPLICATE KEY UPDATE last_seen = VALUES(last_seen)'
);
$stmt->execute([$userId, $now]);

json_success(['last_seen' => $now]);
