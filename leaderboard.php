<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$seoTitle = 'Leaderboard | KND Arena';
$seoDesc  = 'Global rankings and seasonal badges across all KND Arena games. Coming soon.';
echo generateHeader($seoTitle, $seoDesc);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height:100vh; padding-top:120px;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 text-center">
        <div class="glass-card-neon p-5">
          <span class="badge px-3 py-2 mb-3" style="font-size:.85rem; background:rgba(0,212,255,.15); color:var(--cyan, #00d4ff); border:1px solid rgba(0,212,255,.3);">
            <i class="fas fa-flask me-1"></i><?php echo t('arena.coming', 'COMING SOON'); ?>
          </span>
          <h2 class="glow-text mb-3"><i class="fas fa-trophy me-2"></i><?php echo t('arena.card_leaderboard', 'Leaderboard'); ?></h2>
          <p class="text-white-50 mb-4"><?php echo t('arena.card_leaderboard_desc', 'Global rankings, win streaks, and seasonal badges. Compete for the top spot across all KND Arena games.'); ?></p>
          <a href="/knd-arena.php" class="btn btn-outline-neon">
            <i class="fas fa-arrow-left me-2"></i><?php echo t('arena.back', 'Back to Arena'); ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>
<?php echo generateFooter(); ?>
<?php echo generateScripts(); ?>
