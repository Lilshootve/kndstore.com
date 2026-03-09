<?php
/**
 * Character Lab - full integration inside Labs shell. Same workspace layout.
 * Uses components/character-lab/form.php and viewer.php. Expects $balance, $kpCostCharacter from knd-labs.
 */
$balance = isset($balance) ? (int) $balance : 0;
$kpCostCharacter = isset($kpCostCharacter) ? (int) $kpCostCharacter : 15;
?>
<div class="ln-t2i-workspace ln-tool-workspace">
  <header class="ln-t2i-header">
    <h1 class="ln-editor-title"><?php echo t('ai.character.title', 'Character Lab'); ?></h1>
    <p class="ln-editor-subtitle">Stylized game-ready character concepts and 3D. Single character, full body, clean silhouette.</p>
  </header>

  <div class="ln-t2i-grid">
    <div class="ln-t2i-main-col">
      <div class="ln-t2i-meta">
        <span class="ln-t2i-cost">Cost: <strong><?php echo (int) $kpCostCharacter; ?></strong> KP</span>
      </div>
      <div class="ln-t2i-canvas-zone">
        <div class="knd-canvas knd-panel-soft ln-t2i-preview-wrap">
          <div class="character-lab-status mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span id="cl-status-label" class="text-white-50">Idle</span>
              <span id="cl-status-badge" class="badge bg-secondary">waiting</span>
            </div>
            <div class="progress" role="progressbar"><div id="cl-progress-bar" class="progress-bar" style="width:0%"></div></div>
          </div>
          <div id="cl-concept-preview" class="mb-3 text-center" style="display:none;">
            <img id="cl-concept-img" src="" alt="Concept" class="img-fluid rounded" style="max-height:280px;">
          </div>
          <?php require __DIR__ . '/../../components/character-lab/viewer.php'; ?>
          <div id="cl-error" class="alert alert-danger ln-t2i-error mt-3 mb-0" style="display:none;"></div>
        </div>
      </div>
    </div>
    <aside class="ln-t2i-params-col">
      <?php require __DIR__ . '/credits-card.php'; ?>
      <div class="ln-t2i-params-panel">
        <?php require __DIR__ . '/../../components/character-lab/form.php'; ?>
      </div>
    </aside>
  </div>
</div>

<script>
window.KND_CHARACTER_LAB = {
  cost: <?php echo (int) $kpCostCharacter; ?>,
  balance: <?php echo (int) $balance; ?>,
  endpoints: {
    create: '/api/character-lab/create.php',
    status: '/api/character-lab/status.php',
    recentImages: '/api/character-lab/recent-images.php',
    download: '/api/character-lab/download.php'
  }
};
</script>
<script src="/assets/js/character-lab.js?v=<?php echo file_exists(__DIR__ . '/../../assets/js/character-lab.js') ? filemtime(__DIR__ . '/../../assets/js/character-lab.js') : time(); ?>"></script>
