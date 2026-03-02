<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/knd_xp.php';

define('LB_CACHE_SEC', 15);
define('LB_TOP_LIMIT', 100);

try {
    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_FAIL', 'Database error.', 500);

    $userId = isLoggedIn() ? current_user_id() : null;

    // Season info
    $season = ensure_active_season($pdo);
    $seasonInfo = null;
    if ($season) {
        $seasonInfo = [
            'name'     => $season['name'],
            'code'     => $season['code'],
            'starts_at'=> $season['starts_at'],
            'ends_at'  => $season['ends_at'],
        ];
    }

    // Simple file cache for top lists
    $cacheDir = sys_get_temp_dir() . '/knd_lb';
    if (!is_dir($cacheDir)) @mkdir($cacheDir, 0755, true);
    $cacheFile = $cacheDir . '/state.json';
    $useCache = false;
    if (file_exists($cacheFile)) {
        $age = time() - filemtime($cacheFile);
        if ($age < LB_CACHE_SEC) {
            $cached = @json_decode(file_get_contents($cacheFile), true);
            if ($cached) {
                $topSeason = $cached['topSeason'] ?? [];
                $topAllTime = $cached['topAllTime'] ?? [];
                $useCache = true;
            }
        }
    }

    $topSeason = [];
    $topAllTime = [];
    if (!$useCache) {
        if ($season) {
            $stmt = $pdo->prepare(
                "SELECT s.user_id, s.xp_earned, s.matches_played, s.wins, s.losses,
                        u.username, COALESCE(x.level, 1) AS level
                 FROM knd_season_stats s
                 JOIN users u ON u.id = s.user_id
                 LEFT JOIN knd_user_xp x ON x.user_id = s.user_id
                 WHERE s.season_id = ?
                 ORDER BY s.xp_earned DESC
                 LIMIT ?"
            );
            $stmt->execute([$season['id'], LB_TOP_LIMIT]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $i => $r) {
                $w = (int) ($r['wins'] ?? 0);
                $l = (int) ($r['losses'] ?? 0);
                $wr = ($w + $l) > 0 ? round(100 * $w / ($w + $l), 1) : null;
                $topSeason[] = [
                    'rank'   => $i + 1,
                    'user_id'=> (int) $r['user_id'],
                    'username'=> $r['username'],
                    'level'  => (int) ($r['level'] ?? 1),
                    'xp'     => (int) $r['xp_earned'],
                    'wins'   => $w,
                    'losses' => $l,
                    'winrate'=> $wr,
                ];
            }
        }

        $stmt = $pdo->prepare(
            "SELECT x.user_id, x.xp, x.level, u.username
             FROM knd_user_xp x
             JOIN users u ON u.id = x.user_id
             WHERE x.xp > 0
             ORDER BY x.xp DESC
             LIMIT ?"
        );
        $stmt->execute([LB_TOP_LIMIT]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $allTimeStats = [];
        if ($season) {
            $stmt2 = $pdo->prepare(
                "SELECT user_id, wins, losses FROM knd_season_stats WHERE season_id = ?"
            );
            $stmt2->execute([$season['id']]);
            while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $allTimeStats[(int)$r['user_id']] = [(int)$r['wins'], (int)$r['losses']];
            }
        }
        foreach ($rows as $i => $r) {
            $uid = (int) $r['user_id'];
            $wl = $allTimeStats[$uid] ?? [0, 0];
            $wr = ($wl[0] + $wl[1]) > 0 ? round(100 * $wl[0] / ($wl[0] + $wl[1]), 1) : null;
            $topAllTime[] = [
                'rank'   => $i + 1,
                'user_id'=> $uid,
                'username'=> $r['username'],
                'level'  => (int) ($r['level'] ?? 1),
                'xp'     => (int) $r['xp'],
                'wins'   => $wl[0],
                'losses' => $wl[1],
                'winrate'=> $wr,
            ];
        }

        if ($cacheDir && is_writable($cacheDir)) {
            @file_put_contents($cacheFile, json_encode(['topSeason' => $topSeason, 'topAllTime' => $topAllTime]));
        }
    }

    $myRankSeason = null;
    $myRankAllTime = null;
    if ($userId && $season) {
        $stmt = $pdo->prepare(
            "SELECT xp_earned, wins, losses FROM knd_season_stats WHERE season_id = ? AND user_id = ?"
        );
        $stmt->execute([$season['id'], $userId]);
        $my = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($my) {
            $stmt = $pdo->prepare(
                "SELECT 1 + COUNT(*) AS r FROM knd_season_stats WHERE season_id = ? AND xp_earned > ?"
            );
            $stmt->execute([$season['id'], (int)$my['xp_earned']]);
            $myRankSeason = [
                'rank'   => (int) $stmt->fetchColumn(),
                'xp'     => (int) $my['xp_earned'],
                'wins'   => (int) $my['wins'],
                'losses' => (int) $my['losses'],
                'winrate'=> ($my['wins'] + $my['losses']) > 0
                    ? round(100 * $my['wins'] / ($my['wins'] + $my['losses']), 1) : null,
            ];
        }

        $stmt = $pdo->prepare("SELECT xp, level FROM knd_user_xp WHERE user_id = ?");
        $stmt->execute([$userId]);
        $my = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($my && (int)$my['xp'] > 0) {
            $stmt = $pdo->prepare("SELECT 1 + COUNT(*) AS r FROM knd_user_xp WHERE xp > ?");
            $stmt->execute([(int)$my['xp']]);
            $myRankAllTime = [
                'rank'  => (int) $stmt->fetchColumn(),
                'xp'    => (int) $my['xp'],
                'level' => (int) ($my['level'] ?? 1),
            ];
            if ($season) {
                $stmt = $pdo->prepare(
                    "SELECT wins, losses FROM knd_season_stats WHERE season_id = ? AND user_id = ?"
                );
                $stmt->execute([$season['id'], $userId]);
                $s = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($s) {
                    $myRankAllTime['wins'] = (int)$s['wins'];
                    $myRankAllTime['losses'] = (int)$s['losses'];
                    $myRankAllTime['winrate'] = ($s['wins'] + $s['losses']) > 0
                        ? round(100 * $s['wins'] / ($s['wins'] + $s['losses']), 1) : null;
                }
            }
        }
    }

    json_success([
        'season'       => $seasonInfo,
        'topSeason'    => $topSeason ?? [],
        'topAllTime'   => $topAllTime ?? [],
        'myRankSeason' => $myRankSeason,
        'myRankAllTime'=> $myRankAllTime,
    ]);
} catch (\Throwable $e) {
    error_log('leaderboard state error: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An error occurred.', 500);
}
