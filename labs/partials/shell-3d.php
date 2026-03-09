<?php
/**
 * 3D Lab - full integration inside Labs shell. Same workspace layout.
 * Expects $balance, $kpCost3d from knd-labs; LABS_3D_CATEGORIES, LABS_3D_STYLES, LABS_3D_QUALITY from labs_3d_helpers.
 */
$balance = isset($balance) ? (int) $balance : 0;
$kpCost3d = isset($kpCost3d) ? (int) $kpCost3d : 30;
$categories = defined('LABS_3D_CATEGORIES') ? LABS_3D_CATEGORIES : ['Stylized Asset' => 'Stylized Asset'];
$styles = defined('LABS_3D_STYLES') ? LABS_3D_STYLES : ['Stylized' => 'Stylized'];
$qualities = defined('LABS_3D_QUALITY') ? LABS_3D_QUALITY : ['Standard' => 'Standard'];
?>
<div class="ln-t2i-workspace ln-tool-workspace">
  <header class="ln-t2i-header">
    <h1 class="ln-editor-title">3D Lab</h1>
    <p class="ln-editor-subtitle">Create optimized 3D models from text, images, or both. Clean GLB output.</p>
  </header>

  <form id="labs-3d-form" class="labs-form ln-t2i-form" method="post" action="#" onsubmit="return false;" enctype="multipart/form-data">
    <input type="hidden" name="mode" id="l3d-mode" value="image">
    <input type="hidden" name="source_recent_job_id" id="l3d-source-id" value="">
    <input type="hidden" name="source_recent_type" id="l3d-source-type" value="3d_lab">
    <div class="ln-t2i-grid">
      <div class="ln-t2i-main-col">
        <div class="ln-t2i-meta">
          <span class="ln-t2i-cost">Cost: <strong id="l3d-cost"><?php echo (int) $kpCost3d; ?></strong> KP</span>
        </div>
        <div class="ln-t2i-canvas-zone">
          <div class="knd-canvas knd-panel-soft ln-t2i-preview-wrap" id="l3d-result-wrapper">
            <div id="l3d-placeholder" class="labs-result-preview ln-t2i-preview" style="min-height:380px;">
              <div id="l3d-placeholder-empty" class="labs-placeholder-tips">
                <i class="fas fa-cube ln-t2i-placeholder-icon"></i>
                <p class="text-white-50 mb-1">3D Lab – Image or Text+Image</p>
                <p class="text-white-50 small mb-0">Generate a clean GLB model. Single subject works best.</p>
              </div>
              <div id="l3d-placeholder-loading" class="d-none">
                <div class="labs-stepper mb-2">
                  <span class="labs-stepper-dot" data-step="queued"></span><span class="labs-stepper-line"></span>
                  <span class="labs-stepper-dot" data-step="picked"></span><span class="labs-stepper-line"></span>
                  <span class="labs-stepper-dot" data-step="generating"></span><span class="labs-stepper-line"></span>
                  <span class="labs-stepper-dot" data-step="done"></span>
                </div>
                <p class="text-white-50 small mb-2">Generation is queued. You can leave this page.</p>
                <div class="ai-spinner mb-2"><i class="fas fa-cog fa-spin fa-2x"></i></div>
                <p class="text-white-50 mb-0" id="l3d-placeholder-status-text">Processing...</p>
              </div>
            </div>
            <div id="l3d-viewer-wrap" style="display:none; min-height:320px;">
              <div id="l3d-image-preview-wrap" class="d-none d-flex align-items-center justify-content-center" style="min-height:320px; background:#1a1a2e;">
                <img id="l3d-viewer-image" alt="Preview" class="img-fluid" style="max-height:320px; max-width:100%; object-fit:contain;">
              </div>
              <model-viewer id="l3d-model-viewer" camera-controls auto-rotate interaction-prompt="none" style="width:100%; height:320px;"></model-viewer>
              <div id="l3d-viewer-toolbar" class="d-flex flex-wrap gap-2 mt-2">
                <button type="button" id="l3d-wireframe" class="btn btn-sm btn-outline-secondary">Wireframe</button>
                <button type="button" id="l3d-textures" class="btn btn-sm btn-outline-secondary">Textures Off</button>
                <button type="button" id="l3d-fullscreen" class="btn btn-sm btn-outline-secondary">Fullscreen</button>
                <span id="l3d-stats" class="text-white-50 small align-self-center"></span>
              </div>
            </div>
          </div>
          <div class="ln-t2i-gen-area">
            <button type="submit" form="labs-3d-form" class="ln-t2i-cta" id="l3d-submit"><i class="fas fa-cube me-2"></i>Generate 3D</button>
          </div>
          <div id="l3d-result-actions" class="ln-t2i-actions" style="display:none;">
            <a href="#" id="l3d-download" class="labs-action labs-action--primary" download><i class="fas fa-download"></i>Download GLB</a>
          </div>
          <div id="l3d-status-panel" class="ln-t2i-status" style="display:none;">
            <div class="d-flex align-items-center"><div class="ai-spinner me-2"><i class="fas fa-cog fa-spin"></i></div><span id="l3d-status-text">Processing...</span></div>
          </div>
          <div id="l3d-error" class="alert alert-danger ln-t2i-error" style="display:none;"></div>
          <div id="l3d-history-placeholder" class="d-none" aria-hidden="true"></div>
          <ul id="l3d-history-list" class="list-unstyled mb-0 d-none" aria-hidden="true"></ul>
        </div>
      </div>
      <aside class="ln-t2i-params-col">
        <?php require __DIR__ . '/credits-card.php'; ?>
        <div class="ln-t2i-params-panel">
          <div class="ln-t2i-param-group">
            <label class="ln-t2i-param-label">Input mode</label>
            <select id="l3d-mode-select" class="form-select knd-select text-white">
              <option value="image" selected>Image</option>
              <option value="text_image">Text + Image</option>
              <option value="recent">Recent jobs</option>
              <option value="text" disabled>Text only (coming soon)</option>
            </select>
          </div>
          <div id="l3d-prompt-wrap" class="ln-t2i-param-group">
            <label class="ln-t2i-param-label">Prompt</label>
            <textarea name="prompt" id="l3d-prompt" class="knd-textarea form-control text-white" rows="3" maxlength="2000" placeholder="Describe your 3D model..."></textarea>
          </div>
          <div id="l3d-negative-wrap" class="ln-t2i-param-group">
            <label class="ln-t2i-param-label small">Negative prompt (optional)</label>
            <input type="text" name="negative_prompt" id="l3d-negative" class="knd-input form-control text-white" maxlength="1000" placeholder="low quality, blurry">
          </div>
          <div id="l3d-upload-wrap" class="ln-t2i-param-group" style="display:none;">
            <label class="ln-t2i-param-label">Image or GLB upload</label>
            <div id="l3d-dropzone" class="labs-3d-dropzone rounded border border-secondary p-4 text-center">
              <input type="file" id="l3d-file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp,.glb,model/gltf-binary" hidden>
              <div id="l3d-dropzone-content">
                <i class="fas fa-cloud-upload-alt fa-2x text-white-50 mb-2"></i>
                <p class="mb-1 text-white-50">Drop image or GLB, or click</p>
                <small class="text-white-50">Images: JPG, PNG, WEBP · 3D: GLB · max 10MB</small>
              </div>
              <div id="l3d-preview-wrap" style="display:none;">
                <img id="l3d-preview" alt="Preview" class="img-fluid rounded" style="max-height:120px;">
                <button type="button" id="l3d-remove-img" class="btn btn-sm btn-outline-danger mt-2"><i class="fas fa-times"></i> Remove</button>
              </div>
            </div>
          </div>
          <div id="l3d-recent-wrap" class="ln-t2i-param-group" style="display:none;">
            <label class="ln-t2i-param-label">Pick from your recent creations</label>
            <div id="l3d-recent-grid" class="row g-2"><p class="text-white-50 small mb-0"><i class="fas fa-spinner fa-spin me-1"></i>Loading...</p></div>
          </div>
          <div class="ln-t2i-param-group">
            <button type="button" class="btn btn-outline-light btn-sm w-100" id="l3d-view-image" style="display:none;"><i class="fas fa-eye me-2"></i>View in viewer</button>
          </div>
          <div class="ln-t2i-param-group">
            <label class="ln-t2i-param-label small">Category</label>
            <select name="category" id="l3d-category" class="knd-select form-select form-select-sm text-white">
              <?php foreach ($categories as $k => $v): ?><option value="<?php echo htmlspecialchars($k); ?>" <?php echo $k === 'Stylized Asset' ? 'selected' : ''; ?>><?php echo htmlspecialchars($v); ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="ln-t2i-param-group">
            <label class="ln-t2i-param-label small">Style</label>
            <select name="style" id="l3d-style" class="knd-select form-select form-select-sm text-white">
              <?php foreach ($styles as $k => $v): ?><option value="<?php echo htmlspecialchars($k); ?>" <?php echo $k === 'Stylized' ? 'selected' : ''; ?>><?php echo htmlspecialchars($v); ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="ln-t2i-param-group">
            <label class="ln-t2i-param-label small">Quality</label>
            <select name="quality" id="l3d-quality" class="knd-select form-select form-select-sm text-white">
              <?php foreach ($qualities as $k => $v): ?><option value="<?php echo htmlspecialchars($k); ?>" <?php echo $k === 'Standard' ? 'selected' : ''; ?>><?php echo htmlspecialchars($v); ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="ln-t2i-param-group">
            <button type="button" class="btn btn-link btn-sm text-white-50 p-0" id="l3d-advanced-toggle" data-bs-toggle="collapse" data-bs-target="#l3d-advanced"><i class="fas fa-chevron-down me-1"></i>Advanced</button>
            <div class="collapse mt-2" id="l3d-advanced">
              <div class="row g-2 mb-2">
                <div class="col-6"><label class="form-label text-white-50 small">Seed</label><input type="number" name="seed" id="l3d-seed" class="knd-input form-control form-control-sm text-white" placeholder="Random"></div>
                <div class="col-6"><label class="form-label text-white-50 small">Guidance</label><input type="number" name="guidance" id="l3d-guidance" class="knd-input form-control form-control-sm text-white" value="7.5" min="1" max="20" step="0.5"></div>
                <div class="col-6"><label class="form-label text-white-50 small">Image Influence</label><input type="number" name="image_influence" id="l3d-img-influence" class="knd-input form-control form-control-sm text-white" value="0.7" min="0" max="1" step="0.05"></div>
              </div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </form>
  <div id="l3d-recent-creations-grid" class="d-none" aria-hidden="true"></div>
</div>

<script>
window.KND_3D_LAB = { cost: <?php echo (int) $kpCost3d; ?>, balance: <?php echo (int) $balance; ?>, endpoints: { create: '/api/labs/3d-lab/create.php', status: '/api/labs/3d-lab/status.php', history: '/api/labs/3d-lab/history.php', download: '/api/labs/3d-lab/download.php' } };
</script>
<script src="/assets/js/labs/3d-lab.js?v=<?php echo file_exists(__DIR__ . '/../../assets/js/labs/3d-lab.js') ? filemtime(__DIR__ . '/../../assets/js/labs/3d-lab.js') : time(); ?>"></script>
