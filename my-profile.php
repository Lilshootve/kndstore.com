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
require_once __DIR__ . '/includes/knd_avatar.php';
require_once __DIR__ . '/includes/csrf.php';

require_login();

$csrfToken = csrf_token();

$pdo = getDBConnection();
if (!$pdo) {
    header('Location: /auth.php');
    exit;
}

$userId = (int) $_SESSION['dr_user_id'];
$data = profile_get_data($pdo, $userId);
$inventory = avatar_get_inventory($pdo, $userId);

$seoTitle = t('profile.title', 'My Profile') . ' | KND Arena';
$seoDesc = 'Your KND Arena identity: Level, XP, stats for LastRoll, Insight, Drops, and seasonal progress.';
$ogHead = '    <meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead .= '    <meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
$ogHead .= '    <meta property="og:type" content="website">' . "\n";
$ogHead .= '    <link rel="stylesheet" href="/assets/css/avatar.css?v=' . @filemtime(__DIR__ . '/assets/css/avatar.css') . '">' . "\n";
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
              <?php
                $pct = $data['progress']['progressPct'];
                $pctClamped = min(100, max(0, (float) $pct * 100));
              ?>
              <div class="profile-progress-wrap mb-2" role="progressbar" aria-valuenow="<?php echo (int) round($pctClamped); ?>" aria-valuemin="0" aria-valuemax="100">
                <div class="profile-progress-fill" style="width: <?php echo round($pctClamped, 1); ?>%;"></div>
              </div>
              <p class="text-white-50 small mb-0">
                <?php echo t('profile.next', 'XP to next level'); ?>: <strong style="color:#00d4ff;"><?php echo number_format($data['progress']['xpToNext']); ?></strong>
              </p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Your Rank -->
      <div class="glass-card-neon p-4 mb-4">
        <h5 class="mb-3" style="font-size:1rem;"><i class="fas fa-trophy me-2" style="color:#00d4ff;"></i><?php echo t('profile.rank', 'Your Rank'); ?></h5>
        <div class="row g-3 align-items-center">
          <div class="col-6 col-md-3">
            <span class="text-white-50 small"><?php echo t('profile.rank_season', 'Season Rank'); ?></span>
            <div class="fw-bold">#<?php echo !empty($data['season']) ? ($data['season']['rank'] ?? '—') : '—'; ?></div>
          </div>
          <div class="col-6 col-md-3">
            <span class="text-white-50 small"><?php echo t('profile.rank_alltime', 'All-time Rank'); ?></span>
            <div class="fw-bold">#<?php echo $data['all_time_rank'] ?? '—'; ?></div>
          </div>
          <div class="col-12 col-md-6 text-md-end">
            <a href="/leaderboard.php" class="btn btn-sm btn-neon-primary"><i class="fas fa-external-link-alt me-1"></i><?php echo t('profile.view_leaderboard', 'View Leaderboard'); ?></a>
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

      <!-- Avatar Section -->
      <div class="glass-card-neon p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="mb-0" style="font-size:1rem;"><i class="fas fa-user-astronaut me-2" style="color:#00d4ff;"></i><?php echo t('avatar.title', 'KND Avatar'); ?></h5>
          <div class="d-flex align-items-center gap-3">
            <span class="text-white-50 small"><i class="fas fa-gem me-1" style="color:#a78bfa;"></i><?php echo t('avatar.fragments', 'Fragments'); ?>: <strong id="profile-fragments" style="color:#a78bfa;">—</strong></span>
            <span class="text-white-50 small"><?php echo t('avatar.kp', 'KP'); ?>: <strong id="avatar-kp-balance" style="color:#00d4ff;">—</strong></span>
            <button type="button" id="avatar-btn-customize" class="btn btn-sm btn-neon-primary">
              <i class="fas fa-palette me-1"></i><?php echo t('profile.avatar_customize', 'Customize'); ?>
            </button>
          </div>
        </div>
        <?php if (!empty($data['favorite_avatar'])): ?>
  <div class="favorite-avatar-stage">
    <div class="favorite-avatar-frame">
      <img
        id="favorite-avatar-image"
        src="<?php echo htmlspecialchars($data['favorite_avatar']['asset_path']); ?>"
        alt="<?php echo htmlspecialchars($data['favorite_avatar']['name'] ?? 'KND Avatar'); ?>"
        class="favorite-avatar-image"
      >
    </div>

    <div class="favorite-avatar-meta">
      <div class="favorite-avatar-name">
        <?php echo htmlspecialchars($data['favorite_avatar']['name'] ?? 'Favorite Avatar'); ?>
      </div>
      <div class="favorite-avatar-subtitle text-white-50">
        Active favorite avatar
      </div>
    </div>
  </div>

  <div id="avatar-preview" class="avatar-stage d-none"></div>
<?php else: ?>
  <div id="avatar-preview" class="avatar-stage"></div>
<?php endif; ?>
      </div>

      <!-- Collected Avatars Section -->
      <div class="glass-card-neon p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center">
            <div class="profile-stat-icon"><i class="fas fa-box-open"></i></div>
            <h5 class="mb-0 ms-2" style="font-size:1rem;"><?php echo t('profile.collected_avatars', 'Collected Avatars'); ?></h5>
          </div>
          <span class="text-white-50 small"><?php echo count($inventory); ?> items</span>
        </div>
        
        <?php if (empty($inventory)): ?>
          <div class="text-center text-white-50 py-3">
            <i class="fas fa-box-open me-2" style="opacity:.5;"></i>No avatars collected yet. Play KND Drop Chamber to earn avatars!
          </div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach ($inventory as $item):
              $isSelected = !empty($data['favorite_avatar']) && $data['favorite_avatar']['id'] == $item['id'];
            ?>
            <div class="col-4 col-md-3 col-lg-2">
              <div class="avatar-item-card<?php echo $isSelected ? ' avatar-item-card--selected' : ''; ?>" style="border-radius:8px; padding:10px; text-align:center; height:100%;">
                <?php if ($isSelected): ?>
                  <div class="avatar-item-selected-badge"><i class="fas fa-check-circle"></i></div>
                <?php endif; ?>
                <div style="height:60px; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
                  <img src="<?php echo htmlspecialchars($item['asset_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="max-height:100%; max-width:100%; object-fit:contain; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.5));">
                </div>
                <div style="font-size:.7rem; font-weight:600; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:4px;" title="<?php echo htmlspecialchars($item['name']); ?>">
                  <?php echo htmlspecialchars($item['name']); ?>
                </div>
                <?php
                  $rCls = 'profile-rarity-common';
                  if ($item['rarity'] === 'special') $rCls = 'profile-rarity-special';
                  elseif ($item['rarity'] === 'rare') $rCls = 'profile-rarity-rare';
                  elseif ($item['rarity'] === 'epic') $rCls = 'profile-rarity-epic';
                  elseif ($item['rarity'] === 'legendary') $rCls = 'profile-rarity-legendary';
                ?>
                <div style="font-size:.65rem; text-transform:uppercase;" class="<?php echo $rCls; ?>">
                  <?php echo htmlspecialchars($item['rarity']); ?>
                </div>
                <div class="mt-2">
                  <?php if ($isSelected): ?>
                    <?php if ($isSelected): ?>
  <button class="knd-btn-chip is-active" disabled>
    <i class="fas fa-check-circle me-1"></i>Active
  </button>
<?php else: ?>
  <button class="knd-btn-chip btn-set-favorite" data-id="<?php echo (int) $item['id']; ?>">
    <i class="fas fa-sparkles me-1"></i>Set Favorite
  </button>
<?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Badges Section -->
      <div class="glass-card-neon p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex align-items-center">
            <div class="profile-stat-icon"><i class="fas fa-award"></i></div>
            <h5 class="mb-0 ms-2" style="font-size:1rem;"><?php echo t('profile.badges', 'Badges'); ?></h5>
          </div>
          <span id="badge-count" class="text-white-50 small">—</span>
        </div>
        <div id="badges-container">
          <div class="text-center text-white-50 py-3">
            <i class="fas fa-spinner fa-spin me-2"></i>Loading badges...
          </div>
        </div>
      </div>

      <!-- Avatar Customize Modal (KND HUD) -->
      <div id="avatar-customize-modal" class="avatar-modal-overlay" tabindex="-1" role="dialog" aria-labelledby="avatar-modal-title">
        <div class="avatar-modal-scroll">
          <div class="avatar-modal-hud">
            <div class="avatar-modal-header">
              <h4 id="avatar-modal-title"><?php echo t('avatar.customize', 'Customize Avatar'); ?></h4>
              <button type="button" id="avatar-customize-close" class="avatar-modal-close" aria-label="Close">&times;</button>
            </div>
            <div class="avatar-modal-grid">
              <div class="avatar-modal-preview-panel">
                <div class="avatar-preview-frame">
                  <span class="avatar-kp-pill"><i class="fas fa-coins"></i> <strong id="avatar-kp-balance-modal">—</strong> KP</span>
                  <div id="avatar-customize-preview" class="avatar-stage avatar-stage-modal"></div>
                </div>
              </div>
              <div class="avatar-modal-controls">
                <div id="avatar-slot-tabs" class="avatar-slot-seg" data-active="hair"></div>
                <div class="avatar-owned-shop-seg nav nav-tabs" role="tablist">
                  <a class="nav-link active" data-bs-toggle="tab" href="#avatar-owned-tab"><?php echo t('avatar.owned', 'Owned'); ?></a>
                  <a class="nav-link" data-bs-toggle="tab" href="#avatar-shop-tab"><?php echo t('avatar.shop', 'Shop'); ?></a>
                </div>
                <div class="tab-content avatar-tab-content">
                  <div id="avatar-owned-tab" class="tab-pane show active">
                    <div id="avatar-owned-pane" class="avatar-items-grid"></div>
                  </div>
                  <div id="avatar-shop-tab" class="tab-pane">
                    <div id="avatar-shop-pane" class="avatar-items-grid"></div>
                  </div>
                </div>
              </div>
            </div>
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
  .favorite-avatar-stage {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
  padding: 18px 0 8px;
}

.favorite-avatar-frame {
  width: min(100%, 320px);
  min-height: 320px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 18px;
  border-radius: 22px;
  background:
    radial-gradient(circle at top, rgba(0,212,255,.10), transparent 55%),
    linear-gradient(180deg, rgba(8,18,40,.92), rgba(10,16,34,.96));
  border: 1px solid rgba(0,212,255,.20);
  box-shadow:
    inset 0 0 25px rgba(0,212,255,.06),
    0 0 30px rgba(0,212,255,.10);
}

.favorite-avatar-image {
  max-width: 100%;
  max-height: 280px;
  object-fit: contain;
  filter: drop-shadow(0 10px 24px rgba(0,0,0,.45));
}

.favorite-avatar-meta {
  text-align: center;
}

.favorite-avatar-name {
  font-family: 'Orbitron', sans-serif;
  font-size: .95rem;
  font-weight: 700;
  color: #eaf6ff;
  letter-spacing: .04em;
}

.knd-btn-chip {
  width: 100%;
  border: 1px solid rgba(0,212,255,.22);
  background:
    linear-gradient(135deg, rgba(0,212,255,.14), rgba(167,139,250,.14));
  color: #dff7ff;
  border-radius: 12px;
  min-height: 38px;
  font-size: .76rem;
  font-weight: 700;
  letter-spacing: .03em;
  transition: .2s ease;
  box-shadow: 0 8px 18px rgba(0,0,0,.22);
}

.knd-btn-chip:hover {
  transform: translateY(-1px);
  border-color: rgba(0,212,255,.45);
  color: #fff;
  box-shadow: 0 0 0 1px rgba(0,212,255,.10), 0 10px 20px rgba(0,212,255,.14);
}

.knd-btn-chip:disabled {
  opacity: .7;
  cursor: not-allowed;
}

.knd-btn-chip.is-active {
  background: linear-gradient(135deg, rgba(0,212,255,.22), rgba(89,214,255,.18));
  border-color: rgba(0,212,255,.55);
  color: #00d4ff;
  box-shadow: inset 0 0 18px rgba(0,212,255,.08), 0 0 16px rgba(0,212,255,.10);
}
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
.profile-rarity-special { color: #8b5cf6; }
.profile-rarity-legendary { color: #fbbf24; }
.avatar-item-card {
  position: relative;
  background: rgba(255,255,255,.02);
  border: 1px solid rgba(255,255,255,.05);
  transition: transform 0.2s, box-shadow 0.2s;
}
.avatar-item-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.3); background: rgba(255,255,255,.05) !important; }
.avatar-item-card--selected {
  background: rgba(0,212,255,.06) !important;
  border: 1px solid rgba(0,212,255,.4) !important;
  box-shadow: 0 0 12px rgba(0,212,255,.15);
}
.avatar-item-selected-badge {
  position: absolute;
  top: 6px; right: 6px;
  font-size: .75rem;
  color: #00d4ff;
  line-height: 1;
}

</style>

<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
<script>var CSRF = '<?php echo addslashes($csrfToken); ?>';</script>
<script src="/assets/js/navigation-extend.js"></script>
<script src="/assets/js/avatar.js" defer></script>
<script>
document.querySelectorAll('.btn-set-favorite').forEach(btn => {
  btn.addEventListener('click', async function () {
    const itemId = parseInt(this.dataset.id, 10);
    const originalHtml = this.innerHTML;

    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

    try {
      const res = await fetch('/api/avatar/set_favorite.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          item_id: itemId,
          csrf_token: CSRF
        })
      });

      const data = await res.json();

      if (!res.ok || !data.ok) {
        throw new Error(data.error || 'Error saving avatar');
      }

      window.location.reload();
    } catch (e) {
      alert(e.message || 'Error saving avatar');
      this.disabled = false;
      this.innerHTML = originalHtml;
    }
  });
});

// Load fragments
fetch('/api/avatar/fragments.php', {credentials: 'same-origin'})
  .then(r => r.json())
  .then(d => {
    if (d.ok && d.data) {
      document.getElementById('profile-fragments').textContent = (d.data.fragments || 0).toLocaleString();
    }
  })
  .catch(() => {});

// Load badges
fetch('/api/badges/user_badges.php', {credentials: 'same-origin'})
  .then(r => r.json())
  .then(d => {
    if (d.ok && d.data) {
      const unlocked = d.data.unlocked_badges || [];
      const progress = d.data.progress || [];
      const milestones = d.data.milestones || {};
      
      const countEl = document.getElementById('badge-count');
      const container = document.getElementById('badges-container');
      
      if (countEl) {
        countEl.textContent = unlocked.length + ' / ' + progress.length + ' unlocked';
      }
      
      if (container) {
        if (progress.length === 0) {
          container.innerHTML = '<div class="text-center text-white-50 py-3"><i class="fas fa-award me-2" style="opacity:.5;"></i>No badges available.</div>';
        } else {
          let html = '<div class="row g-3 mb-3">';
          progress.forEach(badge => {
            const isUnlocked = badge.unlocked;
            const rarityColors = {
              generator: '#60a5fa',
              drop: '#a78bfa',
              collector: '#34d399',
              legendary_pull: '#fbbf24',
              level: '#f472b6'
            };
            const color = isUnlocked ? (rarityColors[badge.unlock_type] || '#00d4ff') : 'rgba(255,255,255,0.2)';
            const bgStr = isUnlocked ? 'background:rgba(0,212,255,.05); border:1px solid rgba(0,212,255,.2);' : 'background:rgba(255,255,255,.02); border:1px dashed rgba(255,255,255,.1);';
            const nameColor = isUnlocked ? '#fff' : 'rgba(255,255,255,0.5)';
            const descColor = isUnlocked ? 'rgba(255,255,255,.5)' : 'rgba(255,255,255,0.3)';
            
            // Get unlocked date if unlocked
            let unlockedDateHtml = '';
            if (isUnlocked) {
               const uBadge = unlocked.find(u => u.code === badge.code);
               if (uBadge) {
                   unlockedDateHtml = '<div style="font-size:.65rem; color:rgba(255,255,255,.4); margin-top:6px;">' + new Date(uBadge.unlocked_at).toLocaleDateString() + '</div>';
               }
            } else {
               unlockedDateHtml = '<div style="font-size:.65rem; color:rgba(255,255,255,.4); margin-top:6px;">' + badge.current + ' / ' + badge.threshold + '</div>';
               unlockedDateHtml += '<div style="width:100%; height:4px; background:rgba(255,255,255,0.1); border-radius:2px; margin-top:4px;"><div style="height:100%; background:' + (rarityColors[badge.unlock_type] || '#fff') + '; border-radius:2px; width:' + badge.progress_percent + '%;"></div></div>';
            }
            
            html += '<div class="col-6 col-md-4 col-lg-3">';
            html += '<div class="badge-card" style="' + bgStr + ' border-radius:8px; padding:12px; text-align:center; height:100%; position:relative;">';
            if (!isUnlocked) {
               html += '<div style="position:absolute; top:8px; right:8px; font-size:.6rem; color:rgba(255,255,255,.3);"><i class="fas fa-lock"></i></div>';
            }
            html += '<div style="font-size:2rem; margin-bottom:8px; color:' + color + '; transition: color 0.3s;"><i class="fas fa-award"></i></div>';
            html += '<div style="font-size:.85rem; font-weight:600; color:' + nameColor + '; margin-bottom:4px;">' + badge.name + '</div>';
            html += '<div style="font-size:.7rem; color:' + descColor + ';">' + badge.description + '</div>';
            html += unlockedDateHtml;
            html += '</div></div>';
          });
          html += '</div>';
          
          // Show milestones
          html += '<div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.1);">';
          html += '<div class="text-white-50 small mb-2"><i class="fas fa-chart-line me-1"></i>Your Progress</div>';
          html += '<div class="row g-2 text-center">';
          const milestoneLabels = {
            generator: 'Images Generated',
            drop: 'Drops Opened',
            collector: 'Items Collected',
            legendary_pull: 'Legendary Pulls',
            level: 'Current Level'
          };
          Object.keys(milestones).forEach(key => {
            html += '<div class="col-6 col-md-4">';
            html += '<div style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); border-radius:6px; padding:8px;">';
            html += '<div style="font-size:.7rem; color:rgba(255,255,255,.5); margin-bottom:4px;">' + (milestoneLabels[key] || key) + '</div>';
            html += '<div style="font-size:1.2rem; font-weight:700; color:#00d4ff;">' + milestones[key] + '</div>';
            html += '</div></div>';
          });
          html += '</div></div>';
          
          container.innerHTML = html;
        }
      }
    }
  })
  .catch(() => {
    const container = document.getElementById('badges-container');
    if (container) {
      container.innerHTML = '<div class="text-center text-danger py-3"><i class="fas fa-exclamation-triangle me-2"></i>Failed to load badges</div>';
    }
  });
</script>
<?php echo generateFooter(); ?>
<?php echo generateScripts(); ?>
