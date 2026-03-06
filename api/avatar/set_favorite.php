<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Invalid method']);
    exit;
}

if (!isLoggedIn()) {
    echo json_encode(['ok' => false, 'error' => 'Not logged in']);
    exit;
}

$userId = (int)$_SESSION['dr_user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['item_id'] ?? null;

$pdo = getDBConnection();
if (!$pdo) {
    echo json_encode(['ok' => false, 'error' => 'DB error']);
    exit;
}

try {
    // Ensure column exists
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN favorite_avatar_id INT NULL");
        $pdo->exec("ALTER TABLE users ADD CONSTRAINT fk_user_fav_avatar FOREIGN KEY (favorite_avatar_id) REFERENCES knd_avatar_items(id) ON DELETE SET NULL");
    } catch (\Throwable $e) {
        // Ignore if already exists
    }

    if ($itemId === null) {
        // Clear favorite
        $stmt = $pdo->prepare("UPDATE users SET favorite_avatar_id = NULL WHERE id = ?");
        $stmt->execute([$userId]);
        echo json_encode(['ok' => true]);
        exit;
    }

    // Check if user owns the item
    $stmt = $pdo->prepare("SELECT id FROM knd_user_avatar_inventory WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$userId, $itemId]);
    if (!$stmt->fetch()) {
        echo json_encode(['ok' => false, 'error' => 'Item not owned']);
        exit;
    }

    // Set favorite
    $stmt = $pdo->prepare("UPDATE users SET favorite_avatar_id = ? WHERE id = ?");
    $stmt->execute([$itemId, $userId]);

    echo json_encode(['ok' => true]);

} catch (\Throwable $e) {
    error_log($e->getMessage());
    echo json_encode(['ok' => false, 'error' => 'Server error']);
}
