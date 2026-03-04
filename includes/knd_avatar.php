<?php
// KND Avatar v1 - Modular avatar system with KP shop

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/support_credits.php';

/** Allowed slot names (DB column suffix) */
define('AVATAR_SLOTS', ['hair', 'top', 'bottom', 'shoes', 'accessory1', 'bg', 'frame']);

/** Map API slot to DB column */
function avatar_slot_to_column(string $slot): ?string {
    $map = [
        'hair' => 'hair_item_id',
        'top' => 'top_item_id',
        'bottom' => 'bottom_item_id',
        'shoes' => 'shoes_item_id',
        'accessory1' => 'accessory1_item_id',
        'bg' => 'bg_item_id',
        'frame' => 'frame_item_id',
    ];
    return $map[$slot] ?? null;
}

/** Map item slot (from knd_avatar_items) to user_avatar column */
function avatar_item_slot_to_column(string $itemSlot): ?string {
    if ($itemSlot === 'accessory') return 'accessory1_item_id';
    $col = $itemSlot . '_item_id';
    return in_array($col, ['hair_item_id','top_item_id','bottom_item_id','shoes_item_id','accessory1_item_id','bg_item_id','frame_item_id']) ? $col : null;
}

/**
 * Get user's equipped loadout and colors.
 */
function avatar_get_user_loadout(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare('SELECT * FROM knd_user_avatar WHERE user_id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return [
            'hair_item_id' => null, 'top_item_id' => null, 'bottom_item_id' => null,
            'shoes_item_id' => null, 'accessory1_item_id' => null,
            'bg_item_id' => null, 'frame_item_id' => null,
            'colors_json' => null,
        ];
    }
    return $row;
}

/**
 * Get shop items (active only).
 */
function avatar_get_shop_items(PDO $pdo): array {
    $stmt = $pdo->prepare("SELECT id, code, slot, name, rarity, price_kp, asset_path FROM knd_avatar_items WHERE is_active = 1 ORDER BY slot, rarity, price_kp");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get user's inventory (owned item IDs with item details).
 */
function avatar_get_inventory(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare(
        "SELECT i.id, i.code, i.slot, i.name, i.rarity, i.asset_path, inv.acquired_at
         FROM knd_user_avatar_inventory inv
         JOIN knd_avatar_items i ON i.id = inv.item_id
         WHERE inv.user_id = ?
         ORDER BY i.slot, i.rarity, i.name"
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Buy item with KP. Transactional.
 * @return array ['ok' => true, 'available_after' => int] or ['error' => string]
 */
function avatar_buy_item(PDO $pdo, int $userId, int $itemId): array {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT * FROM knd_avatar_items WHERE id = ? AND is_active = 1 FOR UPDATE');
        $stmt->execute([$itemId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$item) {
            $pdo->rollBack();
            return ['error' => 'ITEM_NOT_FOUND'];
        }

        $stmt = $pdo->prepare('SELECT 1 FROM knd_user_avatar_inventory WHERE user_id = ? AND item_id = ?');
        $stmt->execute([$userId, $itemId]);
        if ($stmt->fetch()) {
            $pdo->rollBack();
            return ['error' => 'ALREADY_OWNED'];
        }

        release_available_points_if_due($pdo, $userId);
        expire_points_if_due($pdo, $userId);
        $available = get_available_points($pdo, $userId);
        $price = (int) $item['price_kp'];
        if ($available < $price) {
            $pdo->rollBack();
            return ['error' => 'INSUFFICIENT_KP', 'available' => $available, 'required' => $price];
        }

        $now = gmdate('Y-m-d H:i:s');
        $stmt = $pdo->prepare(
            "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, created_at)
             VALUES (?, 'avatar_shop', ?, 'spend', 'spent', ?, ?)"
        );
        $stmt->execute([$userId, $itemId, -$price, $now]);

        $stmt = $pdo->prepare(
            "INSERT INTO knd_user_avatar_inventory (user_id, item_id, acquired_at) VALUES (?, ?, ?)"
        );
        $stmt->execute([$userId, $itemId, $now]);

        $pdo->commit();
        $newBalance = get_available_points($pdo, $userId);
        return ['ok' => true, 'available_after' => $newBalance];
    } catch (\Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Equip item. Verifies ownership.
 */
function avatar_equip_item(PDO $pdo, int $userId, string $slot, ?int $itemId): bool {
    $col = avatar_slot_to_column($slot);
    if (!$col) return false;

    if ($itemId !== null) {
        $stmt = $pdo->prepare('SELECT id, slot FROM knd_avatar_items WHERE id = ? AND is_active = 1');
        $stmt->execute([$itemId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$item) return false;

        $itemCol = avatar_item_slot_to_column($item['slot']);
        if ($itemCol !== $col) return false;

        $stmt = $pdo->prepare('SELECT 1 FROM knd_user_avatar_inventory WHERE user_id = ? AND item_id = ?');
        $stmt->execute([$userId, $itemId]);
        if (!$stmt->fetch()) return false;
    }

    $stmt = $pdo->prepare("INSERT INTO knd_user_avatar (user_id, $col, updated_at) VALUES (?, ?, NOW())
                          ON DUPLICATE KEY UPDATE $col = ?, updated_at = NOW()");
    $stmt->execute([$userId, $itemId, $itemId]);
    return true;
}

/**
 * Set colors. Validates allowed keys and hex format.
 * Allowed: av-primary, av-secondary, av-accent, av-metal, av-glass
 */
function avatar_set_colors(PDO $pdo, int $userId, array $colors): bool {
    $allowed = ['av-primary', 'av-secondary', 'av-accent', 'av-metal', 'av-glass'];
    $valid = [];
    foreach ($allowed as $k) {
        $v = $colors[$k] ?? null;
        if ($v === null) continue;
        $v = trim((string) $v);
        if (preg_match('/^#[0-9a-fA-F]{3,8}$/', $v)) {
            $valid[$k] = $v;
        }
    }
    $json = empty($valid) ? null : json_encode($valid);

    $stmt = $pdo->prepare("INSERT INTO knd_user_avatar (user_id, colors_json, updated_at) VALUES (?, ?, NOW())
                          ON DUPLICATE KEY UPDATE colors_json = ?, updated_at = NOW()");
    $stmt->execute([$userId, $json, $json]);
    return true;
}

/**
 * Render inline SVG from path. Restricts to /assets/avatars/, strips scripts.
 * @param string $path Web path e.g. /assets/avatars/hair/hair_01.svg
 * @return string HTML-safe SVG markup or empty string
 */
function render_inline_svg(string $path): string {
    if (!preg_match('#^/assets/avatars/[a-z0-9_/-]+\.svg$#i', $path)) {
        return '';
    }
    $base = $_SERVER['DOCUMENT_ROOT'] ?? realpath(__DIR__ . '/..');
    $absPath = realpath($base . $path);
    $allowedDir = realpath($base . '/assets/avatars');
    if (!$absPath || !$allowedDir || strpos($absPath, $allowedDir) !== 0) {
        return '';
    }
    if (!is_readable($absPath)) {
        return '';
    }
    $content = file_get_contents($absPath);
    if ($content === false) return '';
    $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);
    return $content;
}
