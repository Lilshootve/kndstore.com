<?php
// KND Store - Death Roll 1v1 helper functions (isolated from single-player)

/**
 * Generate a unique 8-char uppercase alphanumeric room code.
 */
function generate_room_code(PDO $pdo): string {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no I/O/0/1 to avoid confusion
    $maxAttempts = 20;
    for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        $stmt = $pdo->prepare('SELECT 1 FROM deathroll_games_1v1 WHERE code = ? LIMIT 1');
        $stmt->execute([$code]);
        if (!$stmt->fetch()) {
            return $code;
        }
    }
    throw new RuntimeException('Could not generate unique room code');
}

/**
 * Send a JSON success response.
 */
function json_success(array $data = []): void {
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Send a JSON error response.
 */
function json_error(string $code, string $message, int $httpStatus = 400): void {
    http_response_code($httpStatus);
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => false,
        'error' => ['code' => $code, 'message' => $message]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Validate room code format.
 */
function validate_room_code(string $code): bool {
    return (bool) preg_match('/^[A-Z0-9]{8}$/', $code);
}

/**
 * Validate username format: 3-24 chars, alphanumeric + underscore.
 */
function validate_username(string $username): bool {
    return (bool) preg_match('/^[A-Za-z0-9_]{3,24}$/', $username);
}

/**
 * Build the full game state array for API responses.
 */
function build_game_state(PDO $pdo, array $game, int $currentUserId): array {
    $p1 = null;
    $p2 = null;

    $stmt = $pdo->prepare('SELECT id, username FROM users WHERE id = ?');
    $stmt->execute([$game['player1_user_id']]);
    $p1 = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($game['player2_user_id']) {
        $stmt->execute([$game['player2_user_id']]);
        $p2 = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $canRoll = ($game['status'] === 'playing')
        && ((int)$game['turn_user_id'] === $currentUserId);

    $stmt = $pdo->prepare(
        'SELECT r.max_value, r.roll_value, r.created_at, u.username
         FROM deathroll_game_rolls_1v1 r
         JOIN users u ON u.id = r.user_id
         WHERE r.game_id = ?
         ORDER BY r.created_at ASC
         LIMIT 50'
    );
    $stmt->execute([$game['id']]);
    $rolls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'game' => [
            'code'           => $game['code'],
            'visibility'     => $game['visibility'],
            'status'         => $game['status'],
            'current_max'    => (int) $game['current_max'],
            'turn_user_id'   => $game['turn_user_id'] ? (int) $game['turn_user_id'] : null,
            'winner_user_id' => $game['winner_user_id'] ? (int) $game['winner_user_id'] : null,
            'loser_user_id'  => $game['loser_user_id'] ? (int) $game['loser_user_id'] : null,
        ],
        'players' => [
            'p1' => $p1 ? ['id' => (int)$p1['id'], 'username' => $p1['username']] : null,
            'p2' => $p2 ? ['id' => (int)$p2['id'], 'username' => $p2['username']] : null,
        ],
        'me' => [
            'id'       => $currentUserId,
            'username' => ($p1 && (int)$p1['id'] === $currentUserId) ? $p1['username']
                        : (($p2 && (int)$p2['id'] === $currentUserId) ? $p2['username'] : null),
            'can_roll' => $canRoll,
        ],
        'rolls' => $rolls,
    ];
}
