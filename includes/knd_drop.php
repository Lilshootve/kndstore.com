<?php
// KND Drop Chamber - business logic

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/support_credits.php';

if (!defined('DROP_ENTRY_KP')) define('DROP_ENTRY_KP', 100);
if (!defined('DROP_COOLDOWN_SEC')) define('DROP_COOLDOWN_SEC', 3);

$DROP_XP_MAP = [
    'common'    => 2,
    'rare'      => 4,
    'epic'      => 7,
    'legendary' => 12,
];

function get_active_drop_season(PDO $pdo): ?array {
    $now = gmdate('Y-m-d H:i:s');

    $pdo->prepare(
        "UPDATE knd_drop_seasons SET is_active = 0 WHERE is_active = 1 AND ends_at <= ?"
    )->execute([$now]);

    $stmt = $pdo->prepare(
        "SELECT * FROM knd_drop_seasons WHERE is_active = 1 AND starts_at <= ? AND ends_at > ? ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute([$now, $now]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function get_drop_configs_for_display(PDO $pdo, int $seasonId): array {
    $stmt = $pdo->prepare(
        "SELECT rarity, reward_kp FROM knd_drop_configs WHERE season_id = ? AND is_active = 1 ORDER BY FIELD(rarity,'common','rare','epic','legendary'), reward_kp ASC"
    );
    $stmt->execute([$seasonId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];
    foreach ($rows as $r) {
        $grouped[$r['rarity']][] = (int)$r['reward_kp'];
    }
    return $grouped;
}

function pick_drop_config(PDO $pdo, int $seasonId): array {
    $stmt = $pdo->prepare(
        "SELECT * FROM knd_drop_configs WHERE season_id = ? AND is_active = 1"
    );
    $stmt->execute([$seasonId]);
    $configs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($configs)) {
        throw new \RuntimeException('No drop configs available.');
    }

    $totalWeight = 0;
    foreach ($configs as $c) {
        $totalWeight += (int)$c['weight'];
    }

    $roll = random_int(1, $totalWeight);
    $cumulative = 0;
    foreach ($configs as $c) {
        $cumulative += (int)$c['weight'];
        if ($roll <= $cumulative) {
            return $c;
        }
    }

    return $configs[count($configs) - 1];
}

function drop_play(PDO $pdo, int $userId): array {
    global $DROP_XP_MAP;

    $now = gmdate('Y-m-d H:i:s');
    $entryKp = DROP_ENTRY_KP;

    $season = get_active_drop_season($pdo);
    if (!$season) {
        return ['error' => 'NO_SEASON', 'message' => 'No active drop season.'];
    }

    // Cooldown check
    $stmt = $pdo->prepare(
        "SELECT created_at FROM knd_drops WHERE user_id = ? ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute([$userId]);
    $lastDrop = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($lastDrop) {
        $elapsed = time() - strtotime($lastDrop['created_at']);
        if ($elapsed < DROP_COOLDOWN_SEC) {
            $wait = DROP_COOLDOWN_SEC - $elapsed;
            return ['error' => 'COOLDOWN', 'message' => "Wait {$wait}s before next drop."];
        }
    }

    $pdo->beginTransaction();
    try {
        $available = get_available_points($pdo, $userId);
        if ($available < $entryKp) {
            $pdo->rollBack();
            return ['error' => 'INSUFFICIENT_POINTS', 'message' => "Need {$entryKp} KP. You have {$available}."];
        }

        // Debit entry
        $pdo->prepare(
            "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, created_at)
             VALUES (?, 'drop_entry', 0, 'spend', 'spent', ?, ?)"
        )->execute([$userId, -$entryKp, $now]);

        $config = pick_drop_config($pdo, (int)$season['id']);
        $rarity = $config['rarity'];
        $rewardKp = (int)$config['reward_kp'];
        $xp = $DROP_XP_MAP[$rarity] ?? 2;

        // Credit reward if > 0
        if ($rewardKp > 0) {
            $expiresAt = gmdate('Y-m-d H:i:s', strtotime('+12 months'));
            $pdo->prepare(
                "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, available_at, expires_at, created_at)
                 VALUES (?, 'drop_reward', 0, 'earn', 'available', ?, ?, ?, ?)"
            )->execute([$userId, $rewardKp, $now, $expiresAt, $now]);
        }

        // XP
        if ($xp > 0) {
            $pdo->prepare(
                "INSERT INTO user_xp (user_id, xp, updated_at) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE xp = xp + VALUES(xp), updated_at = VALUES(updated_at)"
            )->execute([$userId, $xp, $now]);
        }

        // Record drop
        $pdo->prepare(
            "INSERT INTO knd_drops (user_id, season_id, entry_kp, rarity, reward_kp, config_id, xp_awarded, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        )->execute([$userId, (int)$season['id'], $entryKp, $rarity, $rewardKp, (int)$config['id'], $xp, $now]);

        $pdo->commit();
        unset($_SESSION['sc_badge_cache']);

        return [
            'ok'       => true,
            'season'   => ['name' => $season['name'], 'ends_at' => $season['ends_at']],
            'entry'    => $entryKp,
            'rarity'   => $rarity,
            'reward_kp'=> $rewardKp,
            'xp_awarded'=> $xp,
            'balance'  => get_available_points($pdo, $userId),
        ];
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $e;
    }
}
