<?php
// KND Store - Rematch endpoint (Death Roll 1v1)

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/rate_limit.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/deathroll_1v1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
}

csrf_guard();
api_require_login();

$code = strtoupper(trim($_POST['code'] ?? ''));
if (!validate_room_code($code)) {
    json_error('INVALID_CODE', 'Invalid room code.');
}

$pdo = getDBConnection();
$userId = current_user_id();

rate_limit_guard($pdo, "rematch:{$userId}", 10, 60);

$stmt = $pdo->prepare('SELECT * FROM deathroll_games_1v1 WHERE code = ? LIMIT 1');
$stmt->execute([$code]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    json_error('ROOM_NOT_FOUND', 'Room not found.');
}

if ($game['status'] !== 'finished') {
    json_error('GAME_NOT_FINISHED', 'Game is not finished yet.');
}

$isPlayer = in_array($userId, [(int) $game['player1_user_id'], (int) $game['player2_user_id']], true);
if (!$isPlayer) {
    json_error('NOT_A_PLAYER', 'You are not a player in this game.');
}

// The loser starts the rematch
$loserId = (int) $game['loser_user_id'];
$winnerId = (int) $game['winner_user_id'];

$newCode = generate_room_code($pdo);
$now = gmdate('Y-m-d H:i:s');

$stmt = $pdo->prepare(
    'INSERT INTO deathroll_games_1v1
     (code, visibility, status, created_by_user_id, player1_user_id, player2_user_id,
      current_max, turn_user_id, created_at, updated_at)
     VALUES (?, ?, "playing", ?, ?, ?, 1000, ?, ?, ?)'
);
$stmt->execute([
    $newCode,
    $game['visibility'],
    $userId,
    $loserId,
    $winnerId,
    $loserId,  // loser goes first
    $now,
    $now,
]);

json_success([
    'new_code' => $newCode,
    'join_url' => '/death-roll-game.php?code=' . $newCode,
]);
