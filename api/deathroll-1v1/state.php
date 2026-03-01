<?php
// KND Store - Game state endpoint (Death Roll 1v1)

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/deathroll_1v1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_error('METHOD_NOT_ALLOWED', 'GET only.', 405);
}

api_require_login();

$code = strtoupper(trim($_GET['code'] ?? ''));
if (!validate_room_code($code)) {
    json_error('INVALID_CODE', 'Invalid room code.');
}

$pdo = getDBConnection();
$userId = current_user_id();

$stmt = $pdo->prepare('SELECT * FROM deathroll_games_1v1 WHERE code = ? LIMIT 1');
$stmt->execute([$code]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    json_error('ROOM_NOT_FOUND', 'Room not found.', 404);
}

$state = build_game_state($pdo, $game, $userId);
json_success($state);
