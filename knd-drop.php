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
require_once __DIR__ . '/includes/knd_drop.php';

require_login();
require_verified_email();

$csrfToken = csrf_token();
$userId = current_user_id();

$balance = 0;
$season = null;
$configs = [];
$history = [];

try {
    $pdo = getDBConnection();
    if ($pdo) {
        release_available_points_if_due($pdo, $userId);
        expire_points_if_due($pdo, $userId);
        $balance = get_available_points($pdo, $userId);

        $season = get_active_drop_season($pdo);
        if ($season) {
            $configs = get_drop_configs_for_display($pdo, (int)$season['id']);
        }

        $stmt = $pdo->prepare(
            'SELECT rarity, reward_kp, entry_kp, xp_awarded, created_at
             FROM knd_drops WHERE user_id = ? ORDER BY id DESC LIMIT 10'
        );
        $stmt->execute([$userId]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (\Throwable $e) {
    error_log('knd-drop page error: ' . $e->getMessage());
}

$seoTitle = 'KND Drop Chamber | KND Arena';
$seoDesc  = 'KND Drop Chamber — open capsules and discover rewards. A next-gen drop experience in KND Arena.';
$ogHead   = '    <meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
$ogHead  .= '    <meta property="og:type" content="website">' . "\n";
$ogHead  .= '    <meta name="twitter:card" content="summary_large_image">' . "\n";
echo generateHeader($seoTitle, $seoDesc, $ogHead);

$entryKp = defined('DROP_ENTRY_KP') ? DROP_ENTRY_KP : 420;

$rarityColors = [
    'common'    => ['bg' => 'rgba(160,174,192,.15)', 'border' => 'rgba(160,174,192,.4)', 'text' => '#a0aec0'],
    'rare'      => ['bg' => 'rgba(66,153,225,.15)',   'border' => 'rgba(66,153,225,.4)',   'text' => '#4299e1'],
    'epic'      => ['bg' => 'rgba(159,122,234,.15)',  'border' => 'rgba(159,122,234,.4)',  'text' => '#9f7aea'],
    'legendary' => ['bg' => 'rgba(236,201,75,.15)',   'border' => 'rgba(236,201,75,.4)',   'text' => '#ecc94b'],
];
?>

<div id="particles-bg"></div>
<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height:100vh; padding-top:110px; padding-bottom:60px;">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">

      <!-- Header -->
      <div class="text-center mb-4">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 mb-2" style="font-size:.75rem;">BETA</span>
        <h1 class="glow-text mb-2" style="font-size:2.2rem;">
          <i class="fas fa-box-open me-2"></i>KND Drop Chamber
        </h1>
        <?php if ($season): ?>
        <p class="mb-1" style="color:var(--knd-neon-blue,#00d4ff); font-size:1rem; font-weight:600;">
          <?php echo htmlspecialchars($season['name']); ?> (Season I)
        </p>
        <p class="text-white-50 small mb-0">
          Ends in: <span id="drop-countdown" style="font-family:'Orbitron',monospace; color:#fb923c;">—</span>
        </p>
        <?php else: ?>
        <p class="text-white-50">No active season right now. Check back soon.</p>
        <?php endif; ?>
      </div>

      <?php if ($season): ?>

      <!-- Balance + Entry + Fragments -->
      <div class="glass-card-neon p-3 mb-4 text-center">
        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
          <div>
            <span class="text-white-50 small">Your KP</span><br>
            <span id="drop-balance" style="font-size:1.6rem; font-weight:900; font-family:'Orbitron',monospace; color:var(--knd-neon-blue,#00d4ff);"><?php echo number_format($balance); ?></span>
          </div>
          <div style="border-left:1px solid rgba(255,255,255,.1); padding-left:16px;">
            <span class="text-white-50 small">Entry Cost</span><br>
            <span style="font-size:1.4rem; font-weight:700; color:#fb923c;"><?php echo number_format($entryKp); ?> KP</span>
          </div>
          <div style="border-left:1px solid rgba(255,255,255,.1); padding-left:16px;">
            <span class="text-white-50 small"><i class="fas fa-gem me-1"></i>Fragments</span><br>
            <span id="drop-fragments" style="font-size:1.4rem; font-weight:700; color:#a78bfa;">—</span>
          </div>
        </div>
      </div>

      <!-- Reward Table -->
      <div class="glass-card-neon p-3 mb-4">
        <h5 class="mb-3 text-center" style="font-size:.95rem;"><i class="fas fa-gem me-2" style="color:var(--knd-neon-blue,#00d4ff);"></i>Possible Rewards</h5>
        <div class="row g-2 text-center">
          <?php foreach ($rarityColors as $rarity => $colors):
            $rewards = $configs[$rarity] ?? [];
          ?>
          <div class="col-6 col-md-3">
            <div class="p-2 rounded" style="background:<?php echo $colors['bg']; ?>; border:1px solid <?php echo $colors['border']; ?>;">
              <div class="fw-bold text-uppercase" style="font-size:.7rem; color:<?php echo $colors['text']; ?>; letter-spacing:.05em;"><?php echo $rarity; ?></div>
              <div class="mt-1" style="font-size:.8rem;">
                <?php if (empty($rewards)): ?>—<?php else: ?>
                <?php echo implode(' / ', array_map(function($r) { return $r === 0 ? '<span class="text-white-50">0</span>' : number_format($r); }, $rewards)); ?> KP
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Capsules Grid -->
      <div class="glass-card-neon p-4 mb-4">
        <div class="text-center mb-3">
          <span class="text-white-50 small">Select a capsule to open</span>
        </div>
        <div id="drop-capsules" class="d-flex flex-wrap justify-content-center gap-3">
          <?php for ($i = 1; $i <= 12; $i++): ?>
          <div class="drop-capsule" data-idx="<?php echo $i; ?>">
            <div class="drop-capsule-inner">
              <i class="fas fa-cube"></i>
            </div>
          </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- Result -->
      <div id="drop-result" class="mb-4" style="display:none;"></div>

      <!-- History -->
      <div class="glass-card-neon p-3">
        <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Drops</h5>
        <div class="table-responsive">
          <table class="table table-sm table-dark mb-0" style="--bs-table-bg:transparent; font-size:.8rem;">
            <thead><tr><th>Rarity</th><th>Reward</th><th>XP</th><th>Date</th></tr></thead>
            <tbody id="drop-history">
              <?php foreach ($history as $h):
                $rc = $rarityColors[$h['rarity']] ?? $rarityColors['common'];
              ?>
              <tr>
                <td><span class="badge" style="background:<?php echo $rc['bg']; ?>; color:<?php echo $rc['text']; ?>; border:1px solid <?php echo $rc['border']; ?>;"><?php echo ucfirst($h['rarity']); ?></span></td>
                <td><?php echo (int)$h['reward_kp'] > 0 ? '+' . number_format((int)$h['reward_kp']) . ' KP' : '<span class="text-white-50">No drop</span>'; ?></td>
                <td>+<?php echo (int)$h['xp_awarded']; ?> XP</td>
                <td class="text-white-50"><?php echo date('M j H:i', strtotime($h['created_at'])); ?></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($history)): ?>
              <tr><td colspan="4" class="text-center text-white-50">No drops yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="text-center mt-4">
        <a href="/knd-arena.php" class="text-white-50 small" style="text-decoration:underline;">
          <i class="fas fa-arrow-left me-1"></i>Back to Arena
        </a>
      </div>

      <?php endif; ?>

    </div>
  </div>
</div>
</section>

<style>
.drop-capsule {
  width: 72px; height: 72px; border-radius: 14px; cursor: pointer;
  background: rgba(12,15,22,.7); border: 2px solid rgba(0,212,255,.2);
  display: flex; align-items: center; justify-content: center;
  transition: all .25s ease; position: relative;
}
.drop-capsule:hover {
  border-color: rgba(0,212,255,.6); box-shadow: 0 0 18px rgba(0,212,255,.2);
  transform: translateY(-3px);
}
.drop-capsule.disabled { pointer-events: none; opacity: .4; }
.drop-capsule.active {
  border-color: #00d4ff; box-shadow: 0 0 30px rgba(0,212,255,.5);
  transform: scale(1.15); z-index: 2;
}
.drop-capsule-inner {
  font-size: 1.6rem; color: rgba(0,212,255,.5); transition: all .3s;
}
.drop-capsule:hover .drop-capsule-inner { color: #00d4ff; }
.drop-capsule.active .drop-capsule-inner { color: #fff; }
@keyframes drop-scan {
  0%   { box-shadow: 0 0 10px rgba(0,212,255,.3); }
  50%  { box-shadow: 0 0 40px rgba(0,212,255,.7); }
  100% { box-shadow: 0 0 10px rgba(0,212,255,.3); }
}
.drop-capsule.scanning { animation: drop-scan .6s ease-in-out infinite; }
.drop-rarity-common    { border-color: rgba(160,174,192,.6) !important; box-shadow: 0 0 20px rgba(160,174,192,.3) !important; }
.drop-rarity-rare      { border-color: rgba(66,153,225,.6) !important;  box-shadow: 0 0 20px rgba(66,153,225,.3) !important; }
.drop-rarity-epic      { border-color: rgba(159,122,234,.6) !important; box-shadow: 0 0 25px rgba(159,122,234,.4) !important; }
.drop-rarity-legendary { border-color: rgba(236,201,75,.7) !important;  box-shadow: 0 0 35px rgba(236,201,75,.5) !important; }
</style>

<script src="/assets/js/navigation-extend.js"></script>
<?php echo generateFooter(); ?>

<script>
var DROP_CSRF = <?php echo json_encode($csrfToken); ?>;
var DROP_ENDS_AT = <?php echo $season ? json_encode($season['ends_at']) : 'null'; ?>;
var DROP_ENTRY = <?php echo $entryKp; ?>;
</script>
<script src="/assets/js/knd-drop.js?v=<?php echo @filemtime(__DIR__ . '/assets/js/knd-drop.js'); ?>"></script>

<?php echo generateScripts(); ?>
