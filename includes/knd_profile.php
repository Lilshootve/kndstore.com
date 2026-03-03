<?php
// KND Profile - XP, Level, stats aggregation for My Profile page

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/knd_xp.php';

define('XP_MAX_LEVEL', 30);

/**
 * Compute XP progress toward next level.
 * Curve: required_xp_to_reach_level(L) = 100 * L^2
 */
function profile_xp_progress(int $xp, int $level): array {
    $nextLevel = min($level + 1, XP_MAX_LEVEL);
    $nextThreshold = 100 * ($nextLevel ** 2);
    $prevThreshold = $level > 1 ? (100 * ($level ** 2)) : 0;
    $span = $nextThreshold - $prevThreshold;
    $inSpan = $xp - $prevThreshold;
    $progressPct = $span > 0 ? max(0, min(1, $inSpan / $span)) : 1;
    $xpToNext = $level < XP_MAX_LEVEL ? ($nextThreshold - $xp) : 0;
    return [
        'prevThreshold'  => $prevThreshold,
        'nextThreshold'  => $nextThreshold,
        'progressPct'    => $progressPct,
        'xpToNext'       => max(0, $xpToNext),
        'isMaxLevel'     => $level >= XP_MAX_LEVEL,
    ];
}

/**
 * Fetch full profile data for a user.
 * Requires login (caller must check).
 */
function profile_get_data(PDO $pdo, int $userId): array {
    $username = null;
    $stmt = $pdo->prepare('SELECT username FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u) $username = $u['username'];

    $xp = 0;
    $level = 1;
    $stmt = $pdo->prepare('SELECT xp, level FROM knd_user_xp WHERE user_id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $ux = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ux) {
        $xp = (int) $ux['xp'];
        $level = min(XP_MAX_LEVEL, max(1, (int) $ux['level']));
    } else {
        $stmt = $pdo->prepare('SELECT xp FROM user_xp WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $ux = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ux) {
            $xp = (int) $ux['xp'];
            $level = xp_calc_level($xp);
        }
    }

    $progress = profile_xp_progress($xp, $level);

    // LastRoll: from deathroll_games_1v1 (finished games where user participated)
    $lastroll = ['matches' => 0, 'wins' => 0, 'losses' => 0, 'winrate' => null];
    try {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) AS matches,
                    SUM(CASE WHEN winner_user_id = ? THEN 1 ELSE 0 END) AS wins,
                    SUM(CASE WHEN loser_user_id = ? THEN 1 ELSE 0 END) AS losses
             FROM deathroll_games_1v1
             WHERE status = 'finished' AND (player1_user_id = ? OR player2_user_id = ?) AND winner_user_id IS NOT NULL"
        );
        $stmt->execute([$userId, $userId, $userId, $userId]);
        $lr = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($lr) {
            $lastroll['matches'] = (int) ($lr['matches'] ?? 0);
            $lastroll['wins'] = (int) ($lr['wins'] ?? 0);
            $lastroll['losses'] = (int) ($lr['losses'] ?? 0);
            $total = $lastroll['wins'] + $lastroll['losses'];
            $lastroll['winrate'] = $total > 0 ? round(100 * $lastroll['wins'] / $total, 1) : null;
        }
    } catch (\Throwable $e) { /* table may not exist */ }

    // Above/Under: from above_under_rolls
    $aboveUnder = ['rolls' => 0, 'wins' => 0, 'winrate' => null];
    try {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) AS rolls, SUM(is_win) AS wins FROM above_under_rolls WHERE user_id = ?"
        );
        $stmt->execute([$userId]);
        $au = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($au) {
            $aboveUnder['rolls'] = (int) ($au['rolls'] ?? 0);
            $aboveUnder['wins'] = (int) ($au['wins'] ?? 0);
            $aboveUnder['winrate'] = $aboveUnder['rolls'] > 0
                ? round(100 * $aboveUnder['wins'] / $aboveUnder['rolls'], 1) : null;
        }
    } catch (\Throwable $e) { /* table may not exist */ }

    // Drops: from knd_drops
    $drops = ['total' => 0, 'best_rarity' => null, 'avg_reward' => null];
    $rarityOrder = ['common' => 1, 'rare' => 2, 'epic' => 3, 'legendary' => 4];
    try {
        $stmt = $pdo->prepare(
            "SELECT rarity, reward_kp FROM knd_drops WHERE user_id = ?"
        );
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            $drops['total'] = count($rows);
            $bestVal = 0;
            $bestRarity = 'common';
            $sumReward = 0;
            foreach ($rows as $r) {
                $ro = $rarityOrder[$r['rarity'] ?? 'common'] ?? 1;
                if ($ro > $bestVal) {
                    $bestVal = $ro;
                    $bestRarity = $r['rarity'];
                }
                $sumReward += (int) $r['reward_kp'];
            }
            $drops['best_rarity'] = $bestRarity;
            $drops['avg_reward'] = round($sumReward / count($rows), 1);
        }
    } catch (\Throwable $e) { /* table may not exist */ }

    // Seasonal snapshot
    $season = null;
    $seasonXp = 0;
    $seasonRank = null;
    try {
        $season = get_active_season($pdo);
        if ($season) {
            $stmt = $pdo->prepare(
                "SELECT xp_earned FROM knd_season_stats WHERE season_id = ? AND user_id = ? LIMIT 1"
            );
            $stmt->execute([$season['id'], $userId]);
            $ss = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($ss) {
                $seasonXp = (int) $ss['xp_earned'];
                $stmt = $pdo->prepare(
                    "SELECT 1 + COUNT(*) AS r FROM knd_season_stats WHERE season_id = ? AND xp_earned > ?"
                );
                $stmt->execute([$season['id'], $seasonXp]);
                $seasonRank = (int) $stmt->fetchColumn();
            }
        }
    } catch (\Throwable $e) { /* ignore */ }

    return [
        'username'    => $username,
        'xp'          => $xp,
        'level'       => $level,
        'progress'    => $progress,
        'lastroll'    => $lastroll,
        'above_under' => $aboveUnder,
        'drops'       => $drops,
        'season'      => $season ? [
            'name'     => $season['name'],
            'ends_at'  => $season['ends_at'],
            'xp_earned'=> $seasonXp,
            'rank'     => $seasonRank,
        ] : null,
    ];
}
