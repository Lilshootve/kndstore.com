<?php
// KND Store - Create room endpoint (Death Roll 1v1)

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/rate_limit.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/deathroll_1v1.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
}

csrf_guard();
api_require_login();

$pdo = getDBConnection();
if (!$pdo) { json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500); }
$userId = current_user_id();

rate_limit_guard($pdo, "create_room:{$userId}", 10, 60);

$visibility = $_POST['visibility'] ?? 'public';
if (!in_array($visibility, ['public', 'private'], true)) {
    json_error('INVALID_VISIBILITY', 'Visibility must be public or private.');
}

// Prevent creating a room if user already has a waiting room
$stmt = $pdo->prepare(
    'SELECT code FROM deathroll_games_1v1
     WHERE created_by_user_id = ? AND status = "waiting"
     LIMIT 1'
);
$stmt->execute([$userId]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);
if ($existing) {
    json_error('ALREADY_WAITING', 'You already have a waiting room: ' . $existing['code']);
}

$code = generate_room_code($pdo);
$now = gmdate('Y-m-d H:i:s');

$stmt = $pdo->prepare(
    'INSERT INTO deathroll_games_1v1
     (code, visibility, status, created_by_user_id, player1_user_id, current_max, turn_user_id, created_at, updated_at)
     VALUES (?, ?, "waiting", ?, ?, 1000, ?, ?, ?)'
);
$stmt->execute([$code, $visibility, $userId, $userId, $userId, $now, $now]);

json_success([
    'code' => $code,
    'join_url' => '/death-roll-game.php?code=' . $code,
]);
