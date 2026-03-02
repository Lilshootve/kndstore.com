<?php
// KND Store - Join room endpoint (Death Roll 1v1)

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/csrf.php';
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

$code = strtoupper(trim($_POST['code'] ?? ''));
if (!validate_room_code($code)) {
    json_error('INVALID_CODE', 'Room code must be 8 uppercase alphanumeric characters.');
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare(
        'SELECT * FROM deathroll_games_1v1 WHERE code = ? FOR UPDATE'
    );
    $stmt->execute([$code]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        $pdo->rollBack();
        json_error('ROOM_NOT_FOUND', 'Room not found.');
    }

    if ($game['status'] !== 'waiting') {
        $pdo->rollBack();
        json_error('ROOM_NOT_WAITING', 'This room is no longer accepting players.');
    }

    if ($game['player2_user_id'] !== null) {
        $pdo->rollBack();
        json_error('ROOM_FULL', 'This room is already full.');
    }

    if ((int) $game['player1_user_id'] === $userId) {
        $pdo->rollBack();
        json_error('CANNOT_JOIN_OWN', 'You cannot join your own room.');
    }

    $now = gmdate('Y-m-d H:i:s');
    $stmt = $pdo->prepare(
        'UPDATE deathroll_games_1v1
         SET player2_user_id = ?, status = "playing",
             turn_started_at = ?, updated_at = ?, last_activity_at = ?
         WHERE id = ?'
    );
    $stmt->execute([$userId, $now, $now, $now, $game['id']]);

    $game['player2_user_id'] = $userId;
    $game['status'] = 'playing';
    $game['turn_started_at'] = $now;

    $state = build_game_state($pdo, $game, $userId);

    $pdo->commit();
    json_success($state);
} catch (Exception $e) {
    $pdo->rollBack();
    json_error('SERVER_ERROR', 'An error occurred.', 500);
}
