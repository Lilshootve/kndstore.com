<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../includes/comfyui.php';

$toolName = t('ai.upscale.title', 'Upscale');
$jobType = 'upscale';
$historyJobs = [];
if ($pdo) {
    try {
        $historyJobs = comfyui_get_user_jobs($pdo, current_user_id(), 10);
    } catch (\Throwable $e) {
        $historyJobs = [];
    }
}
$historyJobs = array_filter($historyJobs, fn($j) => ($j['tool'] ?? '') === 'upscale');

$aiCss = __DIR__ . '/../assets/css/ai-tools.css';
$labsCss = __DIR__ . '/../assets/css/knd-labs.css';
$extraCss = '<link rel="stylesheet" href="/assets/css/ai-tools.css?v=' . (file_exists($aiCss) ? filemtime($aiCss) : time()) . '">';
$extraCss .= '<link rel="stylesheet" href="/assets/css/knd-labs.css?v=' . (file_exists($labsCss) ? filemtime($labsCss) : time()) . '">';
echo generateHeader(t('labs.tool_page_title', '{tool} | KND Labs', ['tool' => $toolName]), t('labs.tool_page_desc', 'Create with AI'), $extraCss);
?>
<div id="particles-bg"></div>
<?php echo generateNavigation(); ?>

<section class="labs-tool-section py-5">
  <div class="container">
    <?php labs_breadcrumb($toolName); ?>

    <div class="row mt-4">
      <div class="col-lg-5 order-lg-2 mb-4">
        <div class="glass-card-neon p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-white mb-0"><?php echo htmlspecialchars($toolName); ?></h4>
            <span class="ai-balance-badge" id="labs-balance"><i class="fas fa-coins me-1"></i><?php echo number_format($balance); ?> KP</span>
          </div>
          <p class="text-white-50 small" id="labs-cost-label"><?php echo t('labs.cost_label', 'Cost: 5 KP'); ?></p>

          <form id="labs-comfy-form" class="labs-form" method="post" action="#" onsubmit="return false;">
            <input type="hidden" name="tool" value="upscale">
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('ai.upscale.scale'); ?></label>
              <select name="scale" id="labs-scale-select" class="form-select bg-dark text-white mb-2">
                <option value="2">2x (5 KP)</option>
                <option value="4" selected>4x (8 KP)</option>
              </select>
              <div class="form-text text-white-50 small"><?php echo t('labs.upscale_scale_help', 'Higher scale = more detail, higher cost'); ?></div>
            </div>
            <div class="mb-3 collapse" id="labs-upscale-advanced">
              <label class="form-label text-white-50 small"><?php echo t('labs.denoise', 'Denoise'); ?></label>
              <input type="number" name="upscale_denoise" class="form-control form-control-sm bg-dark text-white" value="0.10" min="0" max="0.35" step="0.05" placeholder="0.10">
              <div class="form-text text-white-50 small"><?php echo t('labs.upscale_denoise_help', '0–0.35. Lower = preserve detail'); ?></div>
              <label class="form-label text-white-50 small mt-2"><?php echo t('labs.upscaler_model', 'Upscaler model'); ?></label>
              <select name="upscale_model" class="form-select form-select-sm bg-dark text-white">
                <option value="4x-UltraSharp.pth" selected>4x-UltraSharp</option>
              </select>
            </div>
            <button type="button" class="btn btn-link btn-sm text-white-50 p-0 mb-2" data-bs-toggle="collapse" data-bs-target="#labs-upscale-advanced">
              <i class="fas fa-cog me-1"></i><?php echo t('labs.advanced', 'Advanced'); ?>
            </button>
            <div class="mb-3">
              <div class="ai-dropzone" id="labs-upscale-dropzone">
                <input type="file" name="image" id="labs-upscale-file" accept="image/jpeg,image/jpg,image/png,image/webp" hidden>
                <div id="labs-upscale-content">
                  <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-white-50"></i>
                  <p class="mb-0 text-white-50"><?php echo t('ai.upscale.upload'); ?></p>
                  <p class="small text-white-50">JPG, PNG, WebP. Max 10MB</p>
                </div>
                <div id="labs-upscale-preview" style="display:none;"><img id="labs-upscale-preview-img" src="" alt="" style="max-height:120px;"></div>
              </div>
            </div>
            <button type="submit" class="btn btn-neon-primary w-100" id="labs-submit-btn" disabled>
              <i class="fas fa-search-plus me-2"></i><?php echo t('ai.upscale.title'); ?>
            </button>
          </form>
        </div>
      </div>

      <div class="col-lg-7 order-lg-1">
        <div class="glass-card-neon p-4">
          <h5 class="text-white mb-3"><?php echo t('labs.result_area', 'Result'); ?></h5>
          <div id="labs-result-preview" class="labs-result-preview text-center py-5">
            <i class="fas fa-search-plus fa-3x text-white-50 mb-3"></i>
            <p class="text-white-50 mb-0"><?php echo t('labs.no_result_yet', 'Submit to generate'); ?></p>
          </div>
          <div id="labs-result-actions" class="mt-3" style="display:none;">
            <a href="#" id="labs-download-btn" class="btn btn-success me-2" download><i class="fas fa-download me-1"></i><?php echo t('ai.download'); ?></a>
            <button type="button" id="labs-retry-btn" class="btn btn-outline-primary"><i class="fas fa-redo me-1"></i><?php echo t('labs.generate_again', 'Generate again'); ?></button>
          </div>
          <div id="labs-status-panel" class="mt-3" style="display:none;">
            <div class="d-flex align-items-center"><div class="ai-spinner me-2"><i class="fas fa-cog fa-spin"></i></div><span id="labs-status-text"><?php echo t('ai.status.processing'); ?></span></div>
          </div>
          <div id="labs-error-msg" class="alert alert-danger mt-3" style="display:none;"></div>
          <?php require __DIR__ . '/partials/image_details_panel.php'; ?>
        </div>

        <?php if (!empty($historyJobs)): ?>
        <div class="glass-card-neon p-4 mt-4">
          <h6 class="text-white mb-3"><?php echo t('labs.tool_history', 'Recent'); ?></h6>
          <ul class="list-unstyled mb-0">
            <?php foreach (array_slice($historyJobs, 0, 5) as $j): ?>
            <li class="d-flex align-items-center justify-content-between py-2 border-bottom border-secondary flex-wrap gap-2">
              <span class="text-white-50 small"><?php echo date('M j, H:i', strtotime($j['created_at'])); ?></span>
              <span class="badge bg-<?php echo ($j['status'] ?? '') === 'done' ? 'success' : (($j['status'] ?? '') === 'failed' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($j['status'] ?? 'pending'); ?></span>
              <?php if (!empty($j['image_url'])): ?>
              <a href="<?php echo htmlspecialchars($j['image_url']); ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="fas fa-download"></i></a>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>
<script src="/assets/js/kndlabs.js"></script>
<script>
(function(){
  var dz = document.getElementById('labs-upscale-dropzone');
  var fileInput = document.getElementById('labs-upscale-file');
  var content = document.getElementById('labs-upscale-content');
  var preview = document.getElementById('labs-upscale-preview');
  var previewImg = document.getElementById('labs-upscale-preview-img');
  var params = new URLSearchParams(window.location.search);
  var sourceJobId = params.get('source_job_id');
  if (sourceJobId) {
    fetch('/api/labs/image.php?job_id=' + encodeURIComponent(sourceJobId) + '&t=' + Date.now(), { credentials: 'same-origin' })
      .then(function(r) { return r.blob(); })
      .then(function(blob) {
        var f = new File([blob], 'source.png', { type: 'image/png' });
        var dt = new DataTransfer();
        dt.items.add(f);
        if (fileInput) fileInput.files = dt.files;
        if (previewImg) previewImg.src = URL.createObjectURL(blob);
        if (preview) preview.style.display = 'block';
        if (content) content.style.display = 'none';
        var btn = document.getElementById('labs-submit-btn');
        if (btn) btn.disabled = false;
      })
      .catch(function() {});
  }
  if (dz) dz.addEventListener('click', function(){ fileInput && fileInput.click(); });
  if (fileInput) fileInput.addEventListener('change', function(){
    var f = this.files[0];
    if (f && f.type.startsWith('image/')) {
      if (previewImg) previewImg.src = URL.createObjectURL(f);
      if (preview) preview.style.display = 'block';
      if (content) content.style.display = 'none';
      var btn = document.getElementById('labs-submit-btn');
      if (btn) btn.disabled = false;
    }
  });
  KNDLabs.init({ formId: 'labs-comfy-form', jobType: 'upscale', costLabelId: 'labs-cost-label', pricingKey: 'upscale', scaleSelectId: 'labs-scale-select', balanceEl: '#labs-balance' });
})();
</script>
<?php echo generateFooter(); echo generateScripts(); ?>
