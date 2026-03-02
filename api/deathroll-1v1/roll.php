<?php
// KND Store - Roll endpoint (Death Roll 1v1)

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

$code = strtoupper(trim($_POST['code'] ?? ''));
if (!validate_room_code($code)) {
    json_error('INVALID_CODE', 'Invalid room code.');
}

$pdo = getDBConnection();
if (!$pdo) { json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500); }
$userId = current_user_id();

rate_limit_guard($pdo, "roll:{$userId}:{$code}", 5, 10);

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

    if ($game['status'] !== 'playing') {
        $pdo->rollBack();
        json_error('GAME_NOT_PLAYING', 'Game is not in progress.');
    }

    if ((int) $game['turn_user_id'] !== $userId) {
        $pdo->rollBack();
        json_error('NOT_YOUR_TURN', 'It is not your turn.');
    }

    $opponent = ((int) $game['player1_user_id'] === $userId)
        ? (int) $game['player2_user_id']
        : (int) $game['player1_user_id'];

    if (!$opponent) {
        $pdo->rollBack();
        json_error('NO_OPPONENT', 'No opponent in this game.');
    }

    $maxBefore = (int) $game['current_max'];
    $rolled = random_int(1, $maxBefore);
    $now = gmdate('Y-m-d H:i:s');

    // Insert roll record
    $stmt = $pdo->prepare(
        'INSERT INTO deathroll_game_rolls_1v1 (game_id, user_id, max_value, roll_value, created_at)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$game['id'], $userId, $maxBefore, $rolled, $now]);

    if ($rolled === 1) {
        // Current roller loses
        $stmt = $pdo->prepare(
            'UPDATE deathroll_games_1v1
             SET status = "finished", current_max = ?, loser_user_id = ?, winner_user_id = ?,
                 turn_user_id = NULL, updated_at = ?
             WHERE id = ?'
        );
        $stmt->execute([$rolled, $userId, $opponent, $now, $game['id']]);

        $game['status'] = 'finished';
        $game['current_max'] = $rolled;
        $game['loser_user_id'] = $userId;
        $game['winner_user_id'] = $opponent;
        $game['turn_user_id'] = null;
    } else {
        // Game continues, switch turn
        $stmt = $pdo->prepare(
            'UPDATE deathroll_games_1v1
             SET current_max = ?, turn_user_id = ?, updated_at = ?
             WHERE id = ?'
        );
        $stmt->execute([$rolled, $opponent, $now, $game['id']]);

        $game['current_max'] = $rolled;
        $game['turn_user_id'] = $opponent;
    }

    $state = build_game_state($pdo, $game, $userId);

    $pdo->commit();
    json_success($state);
} catch (Exception $e) {
    $pdo->rollBack();
    json_error('SERVER_ERROR', 'An error occurred.', 500);
}
