<?php
/**
 * Texture Lab - full integration inside Labs shell. Same workspace layout.
 * Form IDs/names as labs/texture-lab.php; uses KNDLabs.init jobType texture_seamless.
 */
$balance = isset($balance) ? (int) $balance : 0;
$cost = 4;
?>
<span id="labs-balance" class="d-none"><?php echo number_format($balance); ?></span>

<div class="ln-t2i-workspace ln-tool-workspace">
  <header class="ln-t2i-header">
    <h1 class="ln-editor-title"><?php echo t('ai.texture.title', 'Texture Lab'); ?></h1>
    <p class="ln-editor-subtitle"><?php echo t('ai.texture.subtitle', 'Generate seamless textures for 3D and games.'); ?></p>
  </header>

  <form id="labs-t2i-form" class="labs-form ln-t2i-form" method="post" action="#" onsubmit="return false;">
    <input type="hidden" name="type" value="texture_seamless">
    <div class="ln-t2i-grid">
      <div class="ln-t2i-main-col">
        <div class="ln-t2i-meta">
          <span class="ln-t2i-cost"><?php echo t('labs.cost_fixed', '{cost} KP', ['cost' => $cost]); ?></span>
          <span class="ln-t2i-balance-after"><?php echo t('labs.balance', 'Balance'); ?>: <?php echo number_format($balance); ?> KP</span>
        </div>
        <div class="ln-t2i-prompt-block ln-tool-block">
          <label class="form-label ln-t2i-label"><?php echo t('ai.text2img.prompt'); ?></label>
          <textarea name="prompt" id="labs-texture-prompt" class="knd-textarea form-control text-white ln-t2i-prompt-input" rows="3" maxlength="500" placeholder="<?php echo t('ai.texture.prompt_placeholder', 'e.g. brick wall, wood grain...'); ?>"></textarea>
          <div class="form-text text-white-50 small mt-1"><?php echo t('labs.presets'); ?></div>
          <div class="d-flex flex-wrap gap-2 mt-2">
            <button type="button" class="knd-chip preset-btn" data-prompt="Seamless stone texture, rough surface"><?php echo t('labs.preset_stone', 'Stone'); ?></button>
            <button type="button" class="knd-chip preset-btn" data-prompt="Seamless wood grain texture"><?php echo t('labs.preset_wood', 'Wood'); ?></button>
            <button type="button" class="knd-chip preset-btn" data-prompt="Seamless brick wall texture"><?php echo t('labs.preset_brick', 'Brick'); ?></button>
          </div>
        </div>
        <div class="ln-t2i-canvas-zone">
          <div class="knd-canvas knd-panel-soft ln-t2i-preview-wrap" id="labs-result-wrapper">
            <div id="labs-result-preview" class="labs-result-preview ln-t2i-preview" style="min-height:380px;">
              <div class="labs-placeholder-tips">
                <i class="fas fa-border-all ln-t2i-placeholder-icon"></i>
                <p class="text-white-50 mb-1 small"><?php echo t('labs.no_result_yet', 'Submit to generate'); ?></p>
              </div>
            </div>
          </div>
          <div class="ln-t2i-gen-area">
            <button type="submit" form="labs-t2i-form" class="ln-t2i-cta" id="labs-submit-btn">
              <i class="fas fa-border-all me-2"></i><?php echo t('ai.texture.generate'); ?>
            </button>
          </div>
          <div id="labs-result-actions" class="labs-result-actions-panel ln-t2i-actions" style="display:none;">
            <a href="#" id="labs-download-btn" class="labs-action labs-action--primary" download><i class="fas fa-download"></i><?php echo t('ai.download'); ?></a>
            <button type="button" id="labs-retry-btn" class="labs-action labs-action--secondary"><i class="fas fa-redo"></i><?php echo t('labs.generate_again', 'Generate again'); ?></button>
          </div>
          <div id="labs-status-panel" class="ln-t2i-status" style="display:none;">
            <div class="d-flex align-items-center"><div class="ai-spinner me-2"><i class="fas fa-cog fa-spin"></i></div><span id="labs-status-text"><?php echo t('ai.status.processing'); ?></span></div>
          </div>
          <div id="labs-error-msg" class="alert alert-danger ln-t2i-error" style="display:none;"></div>
        </div>
      </div>
      <aside class="ln-t2i-params-col">
        <?php require __DIR__ . '/credits-card.php'; ?>
        <div class="ln-t2i-params-panel">
          <div class="ln-t2i-param-group">
            <p class="text-white-50 small mb-0"><?php echo t('labs.cost_fixed', '{cost} KP', ['cost' => $cost]); ?> per generation.</p>
          </div>
        </div>
      </aside>
    </div>
  </form>
</div>

<script src="/assets/js/labs-lazy-history.js" defer></script>
<script src="/assets/js/kndlabs.js?v=<?php echo file_exists(__DIR__ . '/../../assets/js/kndlabs.js') ? filemtime(__DIR__ . '/../../assets/js/kndlabs.js') : time(); ?>"></script>
<script>
(function() {
  var form = document.getElementById('labs-t2i-form');
  if (form) form.querySelectorAll('.preset-btn').forEach(function(btn) {
    btn.addEventListener('click', function() { var p = document.getElementById('labs-texture-prompt'); if (p && btn.dataset.prompt) p.value = btn.dataset.prompt; });
  });
  function run() {
    if (typeof KNDLabs !== 'undefined') KNDLabs.init({ formId: 'labs-t2i-form', jobType: 'texture_seamless', balanceEl: '#labs-balance' });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
  function scheduleLazyHistory() {
    var fn = function() {
      if (window.LabsLazyHistory && window.LabsLazyHistory.load) window.LabsLazyHistory.load({ tool: 'texture', limit: 5, toolLabel: 'Texture Lab', hasProviderFilter: false });
    };
    if (window.requestIdleCallback) requestIdleCallback(fn, { timeout: 1500 }); else setTimeout(fn, 100);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scheduleLazyHistory); else scheduleLazyHistory();
})();
</script>
