<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/knd_profile.php';

require_login();

$pdo = getDBConnection();
if (!$pdo) {
    header('Location: /auth.php');
    exit;
}

$userId = (int) $_SESSION['dr_user_id'];
$data = profile_get_data($pdo, $userId);

$seoTitle = t('profile.title', 'My Profile') . ' | KND Arena';
$seoDesc = 'Your KND Arena identity: Level, XP, stats for LastRoll, Insight, Drops, and seasonal progress.';
$ogHead = '    <meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead .= '    <meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
$ogHead .= '    <meta property="og:type" content="website">' . "\n";
echo generateHeader($seoTitle, $seoDesc, $ogHead);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section profile-page" style="min-height:100vh; padding-top:110px; padding-bottom:60px;">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">

      <!-- Header -->
      <div class="text-center mb-4">
        <span class="badge bg-success px-3 py-1 mb-2" style="font-size:.75rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
        <h1 class="glow-text mb-2" style="font-size:2.2rem;">
          <i class="fas fa-user-shield me-2"></i><?php echo t('profile.title', 'My Profile'); ?>
        </h1>
        <p class="text-white-50 mb-0"><?php echo t('profile.subtitle', 'KND Arena Identity'); ?></p>
      </div>

      <!-- Profile Header Card -->
      <div class="glass-card-neon p-4 mb-4 profile-hud-card">
        <div class="row align-items-center">
          <div class="col-12 col-md-4 text-center text-md-start mb-3 mb-md-0">
            <div class="profile-level-badge">
              <span class="profile-level-num"><?php echo (int) $data['level']; ?></span>
              <span class="profile-level-label"><?php echo t('profile.level', 'Level'); ?></span>
            </div>
          </div>
          <div class="col-12 col-md-8">
            <h4 class="mb-2" style="font-family:'Orbitron',sans-serif; color:#00d4ff;">
              <?php echo htmlspecialchars($data['username'] ?? 'Player'); ?>
            </h4>
            <div class="profile-xp-row mb-2">
              <span class="text-white-50 me-2"><?php echo t('profile.xp_total', 'Total XP'); ?>:</span>
              <span class="fw-bold" style="font-family:'Orbitron',monospace; color:#00d4ff;"><?php echo number_format($data['xp']); ?></span>
            </div>
            <?php if ($data['progress']['isMaxLevel']): ?>
              <div class="profile-max-badge mb-2">
                <span class="badge px-3 py-2" style="background:linear-gradient(135deg,rgba(255,193,7,.2),rgba(255,152,0,.2)); border:1px solid rgba(255,193,7,.5); color:#ffc107; font-size:.9rem;">
                  <i class="fas fa-crown me-1"></i><?php echo t('profile.max', 'MAX LEVEL'); ?>
                </span>
              </div>
              <p class="text-white-50 small mb-0"><?php echo t('profile.max_hint', 'XP continues for leaderboard'); ?></p>
            <?php else: ?>
              <div class="profile-progress-wrap mb-2" role="progressbar" aria-valuenow="<?php echo (int)($data['progress']['progressPct'] * 100); ?>" aria-valuemin="0" aria-valuemax="100">
                <div class="profile-progress-fill" style="width: <?php echo round($data['progress']['progressPct'] * 100, 1); ?>%;"></div>
              </div>
              <p class="text-white-50 small mb-0">
                <?php echo t('profile.next', 'XP to next level'); ?>: <strong style="color:#00d4ff;"><?php echo number_format($data['progress']['xpToNext']); ?></strong>
              </p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="row g-4 mb-4">
        <!-- LastRoll -->
        <div class="col-12 col-md-4">
          <div class="glass-card-neon p-4 h-100 profile-stat-card">
            <div class="d-flex align-items-center mb-3">
              <div class="profile-stat-icon"><i class="fas fa-dice-d20"></i></div>
              <h5 class="mb-0 ms-2" style="font-size:1rem;"><?php echo t('profile.lastroll', 'KND LastRoll'); ?></h5>
            </div>
            <div class="profile-stat-grid">
              <div><span class="text-white-50 small"><?php echo t('profile.lastroll_matches', 'Matches'); ?></span><br><strong><?php echo $data['lastroll']['matches']; ?></strong></div>
              <div><span class="text-white-50 small"><?php echo t('profile.lastroll_wins', 'Wins'); ?></span><br><strong class="text-success"><?php echo $data['lastroll']['wins']; ?></strong></div>
              <div><span class="text-white-50 small"><?php echo t('profile.lastroll_losses', 'Losses'); ?></span><br><strong class="text-danger"><?php echo $data['lastroll']['losses']; ?></strong></div>
              <div><span class="text-white-50 small"><?php echo t('lb.winrate', 'Winrate'); ?></span><br><strong><?php echo $data['lastroll']['winrate'] !== null ? $data['lastroll']['winrate'] . '%' : '—'; ?></strong></div>
            </div>
          </div>
        </div>
        <!-- Above/Under -->
        <div class="col-12 col-md-4">
          <div class="glass-card-neon p-4 h-100 profile-stat-card">
            <div class="d-flex align-items-center mb-3">
              <div class="profile-stat-icon"><i class="fas fa-eye"></i></div>
              <h5 class="mb-0 ms-2" style="font-size:1rem;"><?php echo t('profile.aboveunder', 'KND Insight'); ?></h5>
            </div>
            <div class="profile-stat-grid">
              <div><span class="text-white-50 small"><?php echo t('profile.aboveunder_rolls', 'Rolls'); ?></span><br><strong><?php echo $data['above_under']['rolls']; ?></strong></div>
              <div><span class="text-white-50 small"><?php echo t('profile.lastroll_wins', 'Wins'); ?></span><br><strong class="text-success"><?php echo $data['above_under']['wins']; ?></strong></div>
              <div><span class="text-white-50 small"><?php echo t('lb.winrate', 'Winrate'); ?></span><br><strong><?php echo $data['above_under']['winrate'] !== null ? $data['above_under']['winrate'] . '%' : '—'; ?></strong></div>
            </div>
          </div>
        </div>
        <!-- Drops -->
        <div class="col-12 col-md-4">
          <div class="glass-card-neon p-4 h-100 profile-stat-card">
            <div class="d-flex align-items-center mb-3">
              <div class="profile-stat-icon"><i class="fas fa-box-open"></i></div>
              <h5 class="mb-0 ms-2" style="font-size:1rem;"><?php echo t('profile.drops', 'KND Drop Chamber'); ?></h5>
            </div>
            <div class="profile-stat-grid">
              <div><span class="text-white-50 small"><?php echo t('profile.drops_total', 'Drops'); ?></span><br><strong><?php echo $data['drops']['total']; ?></strong></div>
              <div><span class="text-white-50 small"><?php echo t('profile.drops_best', 'Best rarity'); ?></span><br><strong class="profile-rarity-<?php echo htmlspecialchars($data['drops']['best_rarity'] ?? 'common'); ?>"><?php echo ucfirst($data['drops']['best_rarity'] ?? '—'); ?></strong></div>
              <div><span class="text-white-50 small"><?php echo t('profile.drops_avg', 'Avg reward'); ?></span><br><strong><?php echo $data['drops']['avg_reward'] !== null ? number_format($data['drops']['avg_reward']) . ' KP' : '—'; ?></strong></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Seasonal Snapshot -->
      <?php if ($data['season']): ?>
      <div class="glass-card-neon p-4 mb-4">
        <h5 class="mb-3" style="font-size:1rem;"><i class="fas fa-calendar-alt me-2" style="color:#00d4ff;"></i><?php echo t('profile.seasonal', 'Seasonal Snapshot'); ?></h5>
        <div class="row g-3">
          <div class="col-6 col-md-3">
            <span class="text-white-50 small"><?php echo t('profile.season_xp', 'XP this season'); ?></span>
            <div class="fw-bold" style="color:#00d4ff;"><?php echo number_format($data['season']['xp_earned']); ?></div>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-white-50 small"><?php echo t('profile.season_rank', 'Rank'); ?></span>
            <div class="fw-bold">#<?php echo $data['season']['rank'] ?? '—'; ?></div>
          </div>
          <div class="col-12 col-md-6">
            <span class="text-white-50 small"><?php echo $data['season']['name']; ?> — <?php echo t('profile.season_ends', 'Ends'); ?> <?php echo date('M j, Y', strtotime($data['season']['ends_at'])); ?></span>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Inventory placeholders -->
      <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
          <div class="glass-card-neon p-4 profile-placeholder-card">
            <div class="d-flex align-items-center mb-2">
              <div class="profile-stat-icon" style="opacity:.6;"><i class="fas fa-award"></i></div>
              <h5 class="mb-0 ms-2 text-white-50" style="font-size:1rem;"><?php echo t('profile.badges', 'Badges'); ?></h5>
            </div>
            <p class="text-white-50 small mb-0"><?php echo t('profile.coming_soon', 'Coming Soon'); ?></p>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="glass-card-neon p-4 profile-placeholder-card">
            <div class="d-flex align-items-center mb-2">
              <div class="profile-stat-icon" style="opacity:.6;"><i class="fas fa-palette"></i></div>
              <h5 class="mb-0 ms-2 text-white-50" style="font-size:1rem;"><?php echo t('profile.cosmetics', 'Cosmetics'); ?></h5>
            </div>
            <p class="text-white-50 small mb-0"><?php echo t('profile.coming_soon', 'Coming Soon'); ?></p>
          </div>
        </div>
      </div>

      <div class="text-center">
        <a href="/knd-arena.php" class="text-white-50 small" style="text-decoration:underline;">
          <i class="fas fa-arrow-left me-1"></i><?php echo t('profile.back_arena', 'Back to Arena'); ?>
        </a>
      </div>

    </div>
  </div>
</div>
</section>

<style>
.profile-page .profile-hud-card { border: 1px solid rgba(0,212,255,.2); }
.profile-level-badge {
  display: inline-flex; flex-direction: column; align-items: center; justify-content: center;
  width: 90px; height: 90px; border-radius: 12px;
  background: linear-gradient(135deg, rgba(0,212,255,.15), rgba(37,156,174,.08));
  border: 2px solid rgba(0,212,255,.4);
  box-shadow: 0 0 20px rgba(0,212,255,.2);
}
.profile-level-num { font-family: 'Orbitron', monospace; font-size: 2.2rem; font-weight: 700; color: #00d4ff; line-height: 1; }
.profile-level-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.6); }
.profile-progress-wrap { position: relative; height: 10px; background: rgba(255,255,255,.12); border-radius: 5px; overflow: hidden; }
.profile-progress-fill { position: absolute; left: 0; top: 0; bottom: 0; min-width: 4px; background: linear-gradient(90deg, #00d4ff, #259cae); border-radius: 5px; transition: width .5s ease; }
.profile-stat-card { border: 1px solid rgba(255,255,255,.06); transition: transform .2s, box-shadow .2s; }
.profile-stat-card:hover { transform: translateY(-2px); box-shadow: 0 0 16px rgba(0,212,255,.12); }
.profile-stat-icon { width: 36px; height: 36px; border-radius: 8px; background: rgba(0,212,255,.15); display: flex; align-items: center; justify-content: center; color: #00d4ff; font-size: 1rem; }
.profile-stat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
.profile-placeholder-card { opacity: .7; border: 1px dashed rgba(255,255,255,.15); }
.profile-rarity-common { color: #9ca3af; }
.profile-rarity-rare { color: #60a5fa; }
.profile-rarity-epic { color: #a78bfa; }
.profile-rarity-legendary { color: #fbbf24; }
</style>

<script src="/assets/js/navigation-extend.js"></script>
<?php echo generateFooter(); ?>
<?php echo generateScripts(); ?>
