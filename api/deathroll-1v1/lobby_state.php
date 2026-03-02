<?php
// KND Store - Lobby state endpoint (Death Roll 1v1)

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/deathroll_1v1.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_error('METHOD_NOT_ALLOWED', 'GET only.', 405);
}

api_require_login();

$pdo = getDBConnection();
if (!$pdo) { json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500); }

// Online users (seen in last 60 seconds)
$stmt = $pdo->prepare(
    'SELECT u.id, u.username
     FROM user_presence p
     JOIN users u ON u.id = p.user_id
     WHERE p.last_seen >= DATE_SUB(UTC_TIMESTAMP(), INTERVAL 60 SECOND)
     ORDER BY p.last_seen DESC
     LIMIT 30'
);
$stmt->execute();
$onlineUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cast ids to int
foreach ($onlineUsers as &$u) {
    $u['id'] = (int) $u['id'];
}
unset($u);

// Public waiting rooms
$stmt = $pdo->prepare(
    'SELECT g.code, g.created_at, u.username AS creator
     FROM deathroll_games_1v1 g
     JOIN users u ON u.id = g.created_by_user_id
     WHERE g.visibility = "public" AND g.status = "waiting"
     ORDER BY g.updated_at DESC
     LIMIT 30'
);
$stmt->execute();
$publicRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Active games count
$stmt = $pdo->prepare(
    'SELECT COUNT(*) as cnt FROM deathroll_games_1v1 WHERE status = "playing"'
);
$stmt->execute();
$activeGames = (int) $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];

json_success([
    'online_users' => $onlineUsers,
    'public_rooms' => $publicRooms,
    'active_games' => $activeGames,
]);
