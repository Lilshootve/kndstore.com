<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$isLoggedIn = !empty($_SESSION['dr_user_id']);
$csrfToken = $isLoggedIn ? csrf_token() : '';

$seoTitle = 'KND Arena | KND Store';
$seoDesc  = 'KND Arena — next-gen death roll duels, promo drops, and seasonal badges. Play KND LastRoll 1v1, KND Insight, and more.';
$ogHead   = '    <meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
$ogHead  .= '    <meta property="og:type" content="website">' . "\n";
$ogHead  .= '    <meta property="og:url" content="https://kndstore.com/arena">' . "\n";
$ogHead  .= '    <meta name="twitter:card" content="summary_large_image">' . "\n";
$ogHead  .= '    <meta name="twitter:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta name="twitter:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
echo generateHeader($seoTitle, $seoDesc, $ogHead);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height:100vh; padding-top:110px; padding-bottom:60px;">
  <div class="container">

    <!-- Hero -->
    <div class="text-center mb-5">
      <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
        <span class="badge bg-warning text-dark fw-bold px-3 py-2" style="font-size:.85rem; letter-spacing:.05em;">
          <i class="fas fa-flask me-1"></i>BETA
        </span>
        <a href="/game-fairness" class="badge arena-fair-badge px-3 py-2 text-decoration-none" style="font-size:.75rem; letter-spacing:.04em;">
          <i class="fas fa-shield-alt me-1"></i>Fair &amp; Transparent System
        </a>
      </div>
      <h1 class="glow-text mb-3" style="font-size:2.8rem;">
        <i class="fas fa-gamepad me-2"></i><?php echo t('arena.title', 'KND Arena'); ?>
      </h1>
      <p class="text-white-50 mx-auto" style="max-width:600px; font-size:1.1rem;">
        <?php echo t('arena.subtitle', 'Next-gen RNG duels, promo drops, and seasonal badges.'); ?>
      </p>
    </div>

    <!-- Game Cards -->
    <div class="row g-4 justify-content-center mb-5">

      <!-- KND LastRoll -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-dice-d20"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('arena.card_lastroll', 'KND LastRoll'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('arena.card_lastroll_desc', '1v1 Death Roll — roll down from max to 1. The one who rolls 1 loses. Real-time rooms, 8s turn timer, rematch system.'); ?></p>
          <a href="/death-roll-lobby.php" class="btn btn-neon-primary w-100 mt-auto">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- KND Insight -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-eye"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('arena.card_aboveunder', 'KND Insight'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('arena.card_aboveunder_desc', 'Predict if the next number will be above or under the threshold. Fast rounds, pure probability.'); ?></p>
          <a href="/above-under.php" class="btn btn-neon-primary w-100 mt-auto">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- KND Drop Chamber -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-box-open"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('arena.card_drop', 'KND Drop Chamber'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('arena.card_drop_desc', 'Open capsules and discover rewards. Seasonal loot pools with Common, Rare, Epic, and Legendary drops.'); ?></p>
          <a href="/knd-drop.php" class="btn btn-neon-primary w-100 mt-auto">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- Leaderboard -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-trophy"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('arena.card_leaderboard', 'Leaderboard'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('arena.card_leaderboard_desc', 'Global rankings by XP — season standings and Hall of Fame. Compete for the top spot.'); ?></p>
          <a href="/leaderboard.php" class="btn btn-neon-primary w-100 mt-auto">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- Coming Soon placeholder -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card" style="opacity:.45; pointer-events:none;">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-rocket"></i></div>
            <span class="badge px-2 py-1" style="font-size:.7rem; background:rgba(255,255,255,.08); color:rgba(255,255,255,.4); border:1px solid rgba(255,255,255,.1);">TBA</span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('arena.comingsoon', 'More Games'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('arena.comingsoon_desc', 'New game modes and seasonal events are in development. Stay tuned.'); ?></p>
          <button class="btn btn-outline-neon w-100 mt-auto" disabled>
            <i class="fas fa-lock me-2"></i><?php echo t('arena.locked', 'Locked'); ?>
          </button>
        </div>
      </div>

    </div>

    <?php if ($isLoggedIn): ?>
    <!-- Daily Rewards & Missions -->
    <div class="row g-4 mb-5">

      <!-- Daily Streak -->
      <div class="col-12 col-lg-5">
        <div class="glass-card-neon p-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0" style="font-size:1.15rem;"><i class="fas fa-calendar-check me-2" style="color:var(--knd-neon-blue,#00d4ff);"></i>Daily Streak</h3>
            <span id="daily-streak-badge" class="badge" style="background:rgba(0,212,255,.15); color:#00d4ff; border:1px solid rgba(0,212,255,.3); font-size:.8rem;">Day —/7</span>
          </div>

          <div id="daily-dots" class="d-flex justify-content-between mb-3" style="gap:4px;">
            <?php for ($d = 1; $d <= 7; $d++): ?>
            <div class="text-center flex-fill">
              <div class="daily-dot" id="daily-dot-<?php echo $d; ?>" style="width:32px; height:32px; border-radius:50%; margin:0 auto 4px; display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:700; border:2px solid rgba(255,255,255,.15); color:rgba(255,255,255,.4); background:rgba(255,255,255,.03);">
                <?php echo $d; ?>
              </div>
              <div class="text-white-50" style="font-size:.6rem;" id="daily-kp-<?php echo $d; ?>">—</div>
            </div>
            <?php endfor; ?>
          </div>

          <div class="text-center">
            <button id="daily-claim-btn" class="btn btn-neon-primary px-4" disabled>
              <i class="fas fa-gift me-2"></i><span id="daily-claim-text">Loading…</span>
            </button>
            <div id="daily-msg" class="mt-2 small" style="display:none;"></div>
          </div>
        </div>
      </div>

      <!-- Daily Missions -->
      <div class="col-12 col-lg-7">
        <div class="glass-card-neon p-4 h-100">
          <h3 class="mb-3" style="font-size:1.15rem;"><i class="fas fa-tasks me-2" style="color:var(--knd-neon-blue,#00d4ff);"></i>Daily Missions</h3>
          <div id="missions-list">
            <div class="text-center text-white-50 py-3"><i class="fas fa-spinner fa-spin me-2"></i>Loading missions…</div>
          </div>
        </div>
      </div>

    </div>
    <?php endif; ?>

    <!-- Disclaimer -->
    <div class="text-center">
      <p class="text-white-50 small mb-1" style="opacity:.6;">
        <i class="fas fa-info-circle me-1"></i><?php echo t('arena.disclaimer', 'BETA: mechanics and balances may change while we stabilize the ecosystem.'); ?>
      </p>
      <p class="text-white-50 small" style="opacity:.4; font-style:italic;">
        <?php echo t('arena.inspired', 'Inspired by the classic Death Roll format. KND Arena is a next-gen death roll experience.'); ?>
      </p>
    </div>

  </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>

<?php if ($isLoggedIn): ?>
<script>
var ARENA_CSRF = <?php echo json_encode($csrfToken); ?>;
</script>
<script src="/assets/js/arena-daily.js?v=<?php echo @filemtime(__DIR__ . '/assets/js/arena-daily.js'); ?>"></script>
<?php endif; ?>

<?php echo generateFooter(); ?>
<?php echo generateScripts(); ?>
