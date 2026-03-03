<?php
// KND Drop Chamber - business logic

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/support_credits.php';
require_once __DIR__ . '/knd_xp.php';

if (!defined('DROP_ENTRY_KP')) define('DROP_ENTRY_KP', 100);
if (!defined('DROP_COOLDOWN_SEC')) define('DROP_COOLDOWN_SEC', 3);

$DROP_XP_MAP = [
    'common'    => 2,
    'rare'      => 4,
    'epic'      => 7,
    'legendary' => 12,
];

function get_active_drop_season(PDO $pdo): ?array {
    $pdo->prepare(
        "UPDATE knd_drop_seasons SET is_active = 0 WHERE is_active = 1 AND ends_at <= NOW()"
    )->execute();

    $stmt = $pdo->prepare(
        "SELECT * FROM knd_drop_seasons WHERE is_active = 1 AND starts_at <= NOW() AND ends_at > NOW() ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute();
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

    $entryKp = DROP_ENTRY_KP;

    $season = get_active_drop_season($pdo);
    if (!$season) {
        return ['error' => 'NO_SEASON', 'message' => 'No active drop season.'];
    }

    // Cooldown check — done entirely in DB to avoid PHP/DB timezone mismatch
    $stmt = $pdo->prepare(
        "SELECT TIMESTAMPDIFF(SECOND, created_at, NOW()) AS elapsed
         FROM knd_drops WHERE user_id = ? ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute([$userId]);
    $lastDrop = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($lastDrop && (int)$lastDrop['elapsed'] < DROP_COOLDOWN_SEC) {
        $wait = DROP_COOLDOWN_SEC - (int)$lastDrop['elapsed'];
        return ['error' => 'COOLDOWN', 'message' => "Wait {$wait}s before next drop."];
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
             VALUES (?, 'drop_entry', 0, 'spend', 'spent', ?, NOW())"
        )->execute([$userId, -$entryKp]);

        $config = pick_drop_config($pdo, (int)$season['id']);
        $rarity = $config['rarity'];
        $rewardKp = (int)$config['reward_kp'];
        $xp = $DROP_XP_MAP[$rarity] ?? 2;

        // Credit reward if > 0
        if ($rewardKp > 0) {
            $pdo->prepare(
                "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, available_at, expires_at, created_at)
                 VALUES (?, 'drop_reward', 0, 'earn', 'available', ?, NOW(), DATE_ADD(NOW(), INTERVAL 12 MONTH), NOW())"
            )->execute([$userId, $rewardKp]);
        }

        // Record drop first to get ID for audit
        $pdo->prepare(
            "INSERT INTO knd_drops (user_id, season_id, entry_kp, rarity, reward_kp, config_id, xp_awarded, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
        )->execute([$userId, (int)$season['id'], $entryKp, $rarity, $rewardKp, (int)$config['id'], $xp]);
        $dropId = (int) $pdo->lastInsertId();

        $levelUp = null;
        if ($xp > 0) {
            $xpRes = xp_add($pdo, $userId, $xp, 'drop_reward', 'knd_drop', $dropId);
            if ($xpRes['level_up']) {
                $levelUp = ['level_up' => true, 'old_level' => $xpRes['old_level'], 'new_level' => $xpRes['new_level']];
            }
        }

        $pdo->commit();
        unset($_SESSION['sc_badge_cache'], $_SESSION['xp_badge_cache']);

        $out = [
            'ok'       => true,
            'season'   => ['name' => $season['name'], 'ends_at' => $season['ends_at']],
            'entry'    => $entryKp,
            'rarity'   => $rarity,
            'reward_kp'=> $rewardKp,
            'xp_awarded'=> $xp,
            'balance'  => get_available_points($pdo, $userId),
        ];
        if ($levelUp) {
            $out['level_up'] = true;
            $out['old_level'] = $levelUp['old_level'];
            $out['new_level'] = $levelUp['new_level'];
        } else {
            $out['level_up'] = false;
        }
        return $out;
    } catch (\Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $e;
    }
}
