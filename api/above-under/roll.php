<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/rate_limit.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/support_credits.php';

define('AU_ENTRY_POINTS', 200);
define('AU_PAYOUT_POINTS', 340);
define('AU_XP_WIN', 10);
define('AU_XP_LOSE', 2);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
    }

    csrf_guard();
    api_require_verified_email();

    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);

    $userId = current_user_id();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    rate_limit_guard($pdo, "au_roll_user:{$userId}", 5, 30);
    rate_limit_guard($pdo, "au_roll_ip:{$ip}", 10, 60);

    if (has_risk_flag($pdo, $userId)) {
        json_error('ACCOUNT_FLAGGED', 'Your account has been flagged. Contact support.', 403);
    }

    $choice = strtolower(trim($_POST['choice'] ?? ''));
    if (!in_array($choice, ['under', 'above'], true)) {
        json_error('INVALID_CHOICE', 'Choice must be "under" or "above".');
    }

    $pdo->beginTransaction();
    try {
        $available = get_available_points($pdo, $userId);
        if ($available < AU_ENTRY_POINTS) {
            $pdo->rollBack();
            json_error('INSUFFICIENT_POINTS', 'Not enough KND Points. Need ' . AU_ENTRY_POINTS . ' KP.', 400);
        }

        $now = gmdate('Y-m-d H:i:s');

        // Debit entry cost
        $pdo->prepare(
            "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, created_at)
             VALUES (?, 'adjustment', 0, 'spend', 'spent', ?, ?)"
        )->execute([$userId, -AU_ENTRY_POINTS, $now]);

        $rolled = random_int(1, 10);
        $win = ($choice === 'under' && $rolled <= 5) || ($choice === 'above' && $rolled >= 6);
        $payout = 0;
        $xp = $win ? AU_XP_WIN : AU_XP_LOSE;

        if ($win) {
            $payout = AU_PAYOUT_POINTS;
            $expiresAt = gmdate('Y-m-d H:i:s', strtotime('+12 months'));
            $pdo->prepare(
                "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, available_at, expires_at, created_at)
                 VALUES (?, 'adjustment', 0, 'earn', 'available', ?, ?, ?, ?)"
            )->execute([$userId, $payout, $now, $expiresAt, $now]);
        }

        // Record roll
        $pdo->prepare(
            "INSERT INTO above_under_rolls (user_id, choice, rolled_value, is_win, entry_points, payout_points, xp_awarded, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        )->execute([$userId, $choice, $rolled, $win ? 1 : 0, AU_ENTRY_POINTS, $payout, $xp, $now]);

        // XP upsert
        $pdo->prepare(
            "INSERT INTO user_xp (user_id, xp, updated_at) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE xp = xp + VALUES(xp), updated_at = VALUES(updated_at)"
        )->execute([$userId, $xp, $now]);

        $pdo->commit();

        $newBalance = get_available_points($pdo, $userId);

        json_success([
            'rolled'         => $rolled,
            'win'            => $win,
            'choice'         => $choice,
            'entry'          => AU_ENTRY_POINTS,
            'payout'         => $payout,
            'points_balance' => $newBalance,
            'xp_awarded'     => $xp,
        ]);
    } catch (\Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
} catch (\Throwable $e) {
    error_log('above-under roll error: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An unexpected error occurred.', 500);
}
