<?php
/**
 * Shared init for KND Labs tool pages.
 */
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/../includes/labs_perf.php';
labs_perf_start();

require_once __DIR__ . '/../includes/session.php';
labs_perf_checkpoint('init_after_session');

require_once __DIR__ . '/../includes/config.php';
labs_perf_checkpoint('init_after_config');

require_once __DIR__ . '/../includes/auth.php';
labs_perf_checkpoint('init_after_auth');

require_once __DIR__ . '/../includes/support_credits.php';
require_once __DIR__ . '/../includes/ai.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';
labs_perf_checkpoint('init_after_includes');

require_login();
labs_perf_checkpoint('init_after_require_login');

$pdo = getDBConnection();
labs_perf_checkpoint('init_after_db_connect');

$balance = 0;
if ($pdo) {
    $uid = current_user_id();
    $t0 = microtime(true);
    release_available_points_if_due($pdo, $uid);
    expire_points_if_due($pdo, $uid);
    $balance = get_available_points($pdo, $uid);
    labs_perf_checkpoint('init_after_credits');
}

function labs_breadcrumb(string $toolName): void {
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    echo '<li class="breadcrumb-item"><a href="/">' . htmlspecialchars(t('nav.home', 'Home')) . '</a></li>';
    echo '<li class="breadcrumb-item"><a href="/labs">' . htmlspecialchars(t('labs.title', 'KND Labs')) . '</a></li>';
    echo '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($toolName) . '</li>';
    echo '</ol></nav>';
}
