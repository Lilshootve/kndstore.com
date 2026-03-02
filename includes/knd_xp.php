<?php
// KND XP & Season system - leaderboard / level logic

require_once __DIR__ . '/config.php';

function xp_calc_level(int $xp): int {
    return max(1, (int) floor(sqrt($xp / 100)) + 1);
}

function get_active_season(PDO $pdo): ?array {
    $pdo->prepare(
        "UPDATE knd_seasons SET is_active = 0 WHERE is_active = 1 AND ends_at <= NOW()"
    )->execute();

    $stmt = $pdo->prepare(
        "SELECT * FROM knd_seasons WHERE is_active = 1 AND starts_at <= NOW() AND ends_at > NOW() ORDER BY id DESC LIMIT 1"
    );
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function ensure_active_season(PDO $pdo): ?array {
    $season = get_active_season($pdo);
    if ($season) return $season;

    $pdo->prepare(
        "INSERT INTO knd_seasons (code, name, starts_at, ends_at, is_active) VALUES ('GENESIS_S1', 'KND Genesis Season', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1)"
    )->execute();
    return get_active_season($pdo);
}

function xp_add(PDO $pdo, int $userId, int $xpDelta, ?int $seasonId = null, ?bool $isWin = null, bool $countMatch = true): void {
    if ($xpDelta <= 0) return;

    $season = $seasonId ? null : ensure_active_season($pdo);
    $sid = $seasonId ?? ($season ? (int) $season['id'] : null);
    if (!$sid) return;

    $stmt = $pdo->prepare("SELECT xp FROM knd_user_xp WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $newXp = ($row ? (int) $row['xp'] : 0) + $xpDelta;
    $level = xp_calc_level($newXp);

    $pdo->prepare(
        "INSERT INTO knd_user_xp (user_id, xp, level, updated_at) VALUES (?, ?, ?, NOW())
         ON DUPLICATE KEY UPDATE xp = xp + ?, level = ?, updated_at = NOW()"
    )->execute([$userId, $newXp, $level, $xpDelta, $level]);

    if ($countMatch) {
        $w = $isWin === true ? 1 : 0;
        $l = $isWin === false ? 1 : 0;
        $pdo->prepare(
            "INSERT INTO knd_season_stats (season_id, user_id, xp_earned, matches_played, wins, losses, updated_at)
             VALUES (?, ?, ?, 1, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE
               xp_earned = xp_earned + ?,
               matches_played = matches_played + 1,
               wins = wins + ?,
               losses = losses + ?,
               updated_at = NOW()"
        )->execute([$sid, $userId, $xpDelta, $w, $l, $xpDelta, $w, $l]);
    } else {
        $pdo->prepare(
            "INSERT INTO knd_season_stats (season_id, user_id, xp_earned, matches_played, wins, losses, updated_at)
             VALUES (?, ?, ?, 0, 0, 0, NOW())
             ON DUPLICATE KEY UPDATE xp_earned = xp_earned + ?, updated_at = NOW()"
        )->execute([$sid, $userId, $xpDelta, $xpDelta]);
    }

    $pdo->prepare(
        "INSERT INTO user_xp (user_id, xp, updated_at) VALUES (?, ?, NOW())
         ON DUPLICATE KEY UPDATE xp = xp + ?, updated_at = NOW()"
    )->execute([$userId, $xpDelta, $xpDelta]);
}
