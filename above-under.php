<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/support_credits.php';

require_login();
require_verified_email();

$csrfToken = csrf_token();
$userId = current_user_id();

$balance = 0;
$history = [];
try {
    $pdo = getDBConnection();
    if ($pdo) {
        release_available_points_if_due($pdo, $userId);
        expire_points_if_due($pdo, $userId);
        $balance = get_available_points($pdo, $userId);

        $stmt = $pdo->prepare(
            'SELECT choice, rolled_value, is_win, entry_points, payout_points, xp_awarded, created_at
             FROM above_under_rolls WHERE user_id = ? ORDER BY id DESC LIMIT 10'
        );
        $stmt->execute([$userId]);
        $history = $stmt->fetchAll();
    }
} catch (\Throwable $e) {
    error_log('above-under page error: ' . $e->getMessage());
}

$seoTitle = t('au.page_title', 'KND Insight') . ' | KND Arena';
$seoDesc  = t('au.page_desc', 'KND Insight — predict if the number is above or under, and win KND Points. A next-gen prediction game in KND Arena.');
$ogHead   = '    <meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
$ogHead  .= '    <meta property="og:type" content="website">' . "\n";
$ogHead  .= '    <meta name="twitter:card" content="summary_large_image">' . "\n";
echo generateHeader($seoTitle, $seoDesc, $ogHead);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height:100vh; padding-top:110px; padding-bottom:60px;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">

        <!-- Header -->
        <div class="text-center mb-4">
          <span class="badge bg-warning text-dark fw-bold px-3 py-1 mb-2" style="font-size:.75rem;">BETA</span>
          <h1 class="glow-text mb-2" style="font-size:2.2rem;">
            <i class="fas fa-eye me-2"></i><?php echo t('au.title', 'KND Insight'); ?>
          </h1>
          <p class="text-white-50 mb-0"><?php echo t('au.subtitle', 'Pick a side. Roll the number. 1–5 = Under, 6–10 = Above.'); ?></p>
        </div>

        <!-- Balance Card -->
        <div class="glass-card-neon p-3 mb-4 text-center">
          <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
            <div>
              <span class="text-white-50 small"><?php echo t('au.your_balance', 'Your KND Points'); ?></span><br>
              <span id="au-balance" style="font-size:1.8rem; font-weight:900; font-family:'Orbitron',monospace; color:var(--knd-neon-blue, #00d4ff);"><?php echo number_format($balance); ?></span>
              <span class="text-white-50 small">KP</span>
            </div>
            <div style="border-left:1px solid rgba(255,255,255,.1); padding-left:16px;">
              <label class="text-white-50 small d-block mb-1"><?php echo t('au.entry_label', 'Entry'); ?></label>
              <select id="au-entry-select" class="form-select form-select-sm" style="width:auto; display:inline-block; background:#111; color:#fff; border-color:rgba(0,212,255,.3); font-weight:700; min-width:110px;">
                <option value="10">10 KP</option>
                <option value="25">25 KP</option>
                <option value="50">50 KP</option>
                <option value="100">100 KP</option>
                <option value="200" selected>200 KP</option>
                <option value="500">500 KP</option>
                <option value="1000">1,000 KP</option>
                <option value="2500">2,500 KP</option>
                <option value="5000">5,000 KP</option>
              </select>
              <div class="mt-1">
                <span class="text-white-50 small"><?php echo t('au.win_payout', 'Win'); ?>: </span>
                <strong id="au-payout-preview" style="color:#4ade80;">340 KP</strong>
              </div>
            </div>
          </div>
        </div>

        <!-- SVG HUD Dice -->
        <div class="text-center mb-4">
          <div id="au-dice-wrap" class="dr-hud-card" style="display:inline-flex;">
            <svg id="au-dice-svg" width="120" height="120" viewBox="0 0 120 120" aria-label="dice">
              <rect x="14" y="14" width="92" height="92" rx="18" class="dr-dice-plate"/>
              <rect x="20" y="20" width="80" height="80" rx="14" class="dr-dice-glow"/>
              <text id="au-dice-num" x="60" y="72" text-anchor="middle" class="dr-dice-text">&mdash;</text>
              <circle cx="40" cy="40" r="3" class="dr-dice-pip"/>
              <circle cx="80" cy="60" r="3" class="dr-dice-pip"/>
              <circle cx="40" cy="80" r="3" class="dr-dice-pip"/>
            </svg>
            <div id="au-dice-status" class="dr-dice-status">Ready</div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 justify-content-center mb-3">
          <button id="btn-under" class="btn btn-lg au-btn-choice au-btn-under">
            <i class="fas fa-arrow-down me-2"></i>UNDER<br><span class="small fw-normal" style="opacity:.6;">1 – 5</span>
          </button>
          <button id="btn-above" class="btn btn-lg au-btn-choice au-btn-above">
            <i class="fas fa-arrow-up me-2"></i>ABOVE<br><span class="small fw-normal" style="opacity:.6;">6 – 10</span>
          </button>
        </div>

        <!-- Result Banner -->
        <div id="au-result-banner" class="mb-4" style="display:none;"></div>

        <!-- History -->
        <div class="glass-card-neon p-3">
          <h5 class="mb-3"><i class="fas fa-history me-2"></i><?php echo t('au.history', 'Recent Rolls'); ?></h5>
          <div class="table-responsive">
            <table class="table table-sm table-dark mb-0" style="--bs-table-bg:transparent;">
              <thead><tr>
                <th><?php echo t('au.choice_col', 'Choice'); ?></th>
                <th><?php echo t('au.rolled_col', 'Rolled'); ?></th>
                <th><?php echo t('au.result_col', 'Result'); ?></th>
                <th>KP</th>
                <th>XP</th>
              </tr></thead>
              <tbody id="au-history-body">
                <?php foreach ($history as $h): ?>
                <tr>
                  <td><?php echo strtoupper($h['choice']); ?></td>
                  <td style="font-family:Orbitron,monospace;font-weight:700;"><?php echo (int)$h['rolled_value']; ?></td>
                  <td><?php echo (int)$h['is_win']
                        ? '<span class="badge bg-success">WIN</span>'
                        : '<span class="badge bg-danger">LOSE</span>'; ?></td>
                  <td><?php echo (int)$h['is_win'] ? '+' . (int)$h['payout_points'] : '−' . (int)$h['entry_points']; ?> KP</td>
                  <td>+<?php echo (int)$h['xp_awarded']; ?> XP</td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php if (empty($history)): ?>
          <p class="text-white-50 text-center small mt-2 mb-0"><?php echo t('au.no_history', 'No rolls yet. Make your first prediction!'); ?></p>
          <?php endif; ?>
        </div>

        <!-- Back link -->
        <div class="text-center mt-4">
          <a href="/knd-arena.php" class="text-white-50 small" style="text-decoration:underline;">
            <i class="fas fa-arrow-left me-1"></i><?php echo t('arena.back', 'Back to Arena'); ?>
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>
<?php echo generateFooter(); ?>

<script>
var AU_CSRF = <?php echo json_encode($csrfToken); ?>;
</script>
<script src="/assets/js/above-under.js?v=<?php echo @filemtime(__DIR__ . '/assets/js/above-under.js'); ?>"></script>

<?php echo generateScripts(); ?>
