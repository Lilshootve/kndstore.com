<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/support_credits.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

require_login();

$pdo = getDBConnection();
$balance = 0;
if ($pdo) {
    release_available_points_if_due($pdo, current_user_id());
    expire_points_if_due($pdo, current_user_id());
    $balance = get_available_points($pdo, current_user_id());
}

$errorMsg = '';
if (isset($_GET['error'])) {
    $map = [
        'missing' => t('ai.error.missing', 'Missing job ID'),
        'not_found' => t('ai.error.not_found', 'Job not found'),
        'forbidden' => t('ai.error.forbidden', 'Access denied'),
        'not_ready' => t('ai.error.not_ready', 'Result not ready yet'),
        'file_missing' => t('ai.error.file_missing', 'File not found'),
        'db' => 'Database error',
        'server' => 'Server error',
    ];
    $errorMsg = $map[$_GET['error']] ?? 'An error occurred';
}

$aiCss = __DIR__ . '/assets/css/ai-tools.css';
$extraCss = '<link rel="stylesheet" href="/assets/css/ai-tools.css?v=' . (file_exists($aiCss) ? filemtime($aiCss) : time()) . '">';
$seoTitle = t('labs.meta.title', 'KND Labs | KND Store');
$seoDesc = t('labs.meta.desc', 'AI-powered asset creation: Text to Image, Upscale, Character Lab, Texture Lab, Image→3D.');
echo generateHeader($seoTitle, $seoDesc, $extraCss);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height:auto; padding-top:110px; padding-bottom:50px;">
  <div class="container">

    <!-- Hero -->
    <div class="text-center mb-5">
      <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
        <span class="badge bg-warning text-dark fw-bold px-3 py-2" style="font-size:.85rem; letter-spacing:.05em;">
          <i class="fas fa-flask me-1"></i>BETA
        </span>
      </div>
      <h1 class="glow-text mb-3" style="font-size:2.8rem;">
        <i class="fas fa-microscope me-2"></i><?php echo t('labs.title', 'KND Labs'); ?>
      </h1>
      <p class="text-white-50 mx-auto mb-3" style="max-width:600px; font-size:1.1rem;">
        <?php echo t('labs.subtitle', 'AI-powered asset creation. Text to Image, Upscale, Character Lab, Texture Lab, and more.'); ?>
      </p>
      <div class="ai-balance-badge">
        <i class="fas fa-coins me-2"></i>
        <span id="ai-kp-balance"><?php echo t('ai.balance', 'Balance: {kp} KP', ['kp' => number_format($balance)]); ?></span>
      </div>
    </div>

    <?php if ($errorMsg): ?>
    <div class="alert alert-danger d-flex align-items-center mb-4">
      <i class="fas fa-exclamation-triangle me-3"></i>
      <span><?php echo htmlspecialchars($errorMsg); ?></span>
    </div>
    <?php endif; ?>

    <!-- Tool Cards -->
    <div class="row g-4 justify-content-center mb-5">

      <!-- Text → Image -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-font"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('ai.text2img.title'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('labs.card_text2img_desc', 'Generate images from text prompts. Standard (3 KP) or High quality (6 KP).'); ?></p>
          <a href="#section-text2img" class="btn btn-neon-primary w-100 mt-auto labs-scroll-link">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- Upscale -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-search-plus"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('ai.upscale.title'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('labs.card_upscale_desc', 'Upscale images 2x or 4x. JPG, PNG, WebP. 5 KP.'); ?></p>
          <a href="#section-upscale" class="btn btn-neon-primary w-100 mt-auto labs-scroll-link">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- Character Lab -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-user-astronaut"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('ai.character.title'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('labs.card_character_desc', 'Create game/anime/realistic characters from prompts. 15 KP.'); ?></p>
          <a href="#section-character" class="btn btn-neon-primary w-100 mt-auto labs-scroll-link">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- Texture Lab -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-border-all"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('ai.texture.title'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('labs.card_texture_desc', 'Generate seamless textures for 3D/games. 4 KP.'); ?></p>
          <a href="#section-texture" class="btn btn-neon-primary w-100 mt-auto labs-scroll-link">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

      <!-- Image → 3D -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="glass-card-neon p-4 h-100 d-flex flex-column arena-card">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="arena-card-icon"><i class="fas fa-cube"></i></div>
            <span class="badge bg-success px-2 py-1" style="font-size:.7rem;"><?php echo t('arena.live', 'LIVE'); ?></span>
          </div>
          <h3 class="mb-2" style="font-size:1.25rem;"><?php echo t('ai.img23d.link'); ?></h3>
          <p class="text-white-50 small flex-grow-1"><?php echo t('labs.card_img23d_desc', 'Upload an image and generate a 3D model (GLB/OBJ) with InstantMesh.'); ?></p>
          <a href="/triposr-3d.php" class="btn btn-neon-primary w-100 mt-auto">
            <i class="fas fa-play me-2"></i><?php echo t('arena.enter', 'Enter'); ?>
          </a>
        </div>
      </div>

    </div>

    <!-- Tool Sections -->
    <div class="labs-tool-sections mt-5 pt-4 border-top border-secondary">

      <h2 class="text-white mb-4" style="font-size:1.5rem;"><i class="fas fa-tools me-2" style="color:var(--knd-neon-blue);"></i><?php echo t('labs.tools_heading', 'Tools'); ?></h2>

      <!-- Text → Image -->
      <div class="glass-card-neon ai-tool-card p-4 mb-4" id="section-text2img">
        <h4 class="text-white mb-3"><i class="fas fa-font me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('ai.text2img.title'); ?></h4>
        <form id="ai-text2img-form" class="ai-form">
          <input type="hidden" name="type" value="text2img">
          <div class="mb-3">
            <label class="form-label text-white-50"><?php echo t('ai.text2img.prompt'); ?></label>
            <textarea name="prompt" class="form-control bg-dark text-white" rows="3" maxlength="500" placeholder="Describe the image..."></textarea>
            <div class="form-text text-white-50"><small>Max 500 chars</small></div>
          </div>
          <div class="mb-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="mode" id="t2i-standard" value="standard" checked>
              <label class="form-check-label text-white-50" for="t2i-standard"><?php echo t('ai.text2img.mode_standard'); ?></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="mode" id="t2i-high" value="high">
              <label class="form-check-label text-white-50" for="t2i-high"><?php echo t('ai.text2img.mode_high'); ?></label>
            </div>
          </div>
          <button type="submit" class="btn btn-neon-primary" id="ai-t2i-submit">
            <i class="fas fa-magic me-1"></i><?php echo t('ai.text2img.generate'); ?>
          </button>
        </form>
      </div>

      <!-- Upscale -->
      <div class="glass-card-neon ai-tool-card p-4 mb-4" id="section-upscale">
        <h4 class="text-white mb-3"><i class="fas fa-search-plus me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('ai.upscale.title'); ?></h4>
        <form id="ai-upscale-form" class="ai-form">
          <input type="hidden" name="type" value="upscale">
          <div class="mb-3">
            <div class="ai-dropzone" id="ai-upscale-dropzone">
              <input type="file" name="image" id="ai-upscale-file" accept="image/jpeg,image/jpg,image/png,image/webp" hidden>
              <div id="ai-upscale-content">
                <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-white-50"></i>
                <p class="mb-0 text-white-50"><?php echo t('ai.upscale.upload'); ?></p>
                <p class="small text-white-50">JPG, PNG, WebP. Max 10MB, 4096×4096</p>
              </div>
              <div id="ai-upscale-preview" style="display:none;">
                <img id="ai-upscale-preview-img" src="" alt="" style="max-height:120px;">
              </div>
            </div>
          </div>
          <div class="mb-3">
            <select name="scale" class="form-select bg-dark text-white w-auto">
              <option value="2">2x</option>
              <option value="4">4x</option>
            </select>
          </div>
          <button type="submit" class="btn btn-neon-primary" id="ai-upscale-submit" disabled>
            <i class="fas fa-search-plus me-1"></i>Upscale (5 KP)
          </button>
        </form>
      </div>

      <!-- Character Lab -->
      <div class="glass-card-neon ai-tool-card p-4 mb-4" id="section-character">
        <h4 class="text-white mb-3"><i class="fas fa-user-astronaut me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('ai.character.title'); ?></h4>
        <form id="ai-character-form" class="ai-form">
          <input type="hidden" name="type" value="character_create">
          <div class="mb-3">
            <label class="form-label text-white-50"><?php echo t('ai.text2img.prompt'); ?></label>
            <textarea name="prompt" class="form-control bg-dark text-white" rows="2" maxlength="500" placeholder="Describe your character..."></textarea>
          </div>
          <div class="mb-3">
            <select name="style" class="form-select bg-dark text-white w-auto">
              <option value="game">Game</option>
              <option value="anime">Anime</option>
              <option value="realistic">Realistic</option>
            </select>
          </div>
          <button type="submit" class="btn btn-neon-primary" id="ai-char-submit">
            <i class="fas fa-user-plus me-1"></i><?php echo t('ai.character.create'); ?>
          </button>
        </form>
      </div>

      <!-- Texture Lab -->
      <div class="glass-card-neon ai-tool-card p-4 mb-4" id="section-texture">
        <h4 class="text-white mb-3"><i class="fas fa-border-all me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('ai.texture.title'); ?></h4>
        <form id="ai-texture-form" class="ai-form">
          <input type="hidden" name="type" value="texture_seamless">
          <div class="mb-3">
            <label class="form-label text-white-50"><?php echo t('ai.text2img.prompt'); ?></label>
            <textarea name="prompt" class="form-control bg-dark text-white" rows="2" maxlength="500" placeholder="<?php echo t('ai.texture.prompt_placeholder', 'e.g. brick wall, wood grain, marble...'); ?>"></textarea>
          </div>
          <button type="submit" class="btn btn-neon-primary" id="ai-texture-submit">
            <i class="fas fa-border-all me-1"></i><?php echo t('ai.texture.generate'); ?>
          </button>
        </form>
      </div>

      <!-- Status panel -->
      <div class="glass-card-neon ai-status-card p-4 mt-4" id="ai-status-panel" style="display:none;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div class="d-flex align-items-center">
            <div class="ai-spinner me-3" id="ai-spinner"><i class="fas fa-cog fa-spin"></i></div>
            <div>
              <strong id="ai-status-text"><?php echo t('ai.status.processing'); ?></strong>
              <div class="small text-white-50" id="ai-status-detail"></div>
            </div>
          </div>
          <div id="ai-download-wrap" style="display:none;">
            <a href="#" class="btn btn-success" id="ai-download-btn">
              <i class="fas fa-download me-2"></i><?php echo t('ai.download'); ?>
            </a>
          </div>
        </div>
        <div class="alert alert-danger mt-3" id="ai-error-msg" style="display:none;"></div>
      </div>
    </div>

    <!-- Disclaimer -->
    <div class="text-center mt-5">
      <p class="text-white-50 small mb-1" style="opacity:.6;">
        <i class="fas fa-info-circle me-1"></i><?php echo t('labs.disclaimer', 'BETA: AI tools may have rate limits. Uses KND Points (KP).'); ?>
      </p>
    </div>

  </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>
<script>
(function() {
    const balanceEl = document.getElementById('ai-kp-balance');
    const statusPanel = document.getElementById('ai-status-panel');
    const statusText = document.getElementById('ai-status-text');
    const statusDetail = document.getElementById('ai-status-detail');
    const spinner = document.getElementById('ai-spinner');
    const downloadWrap = document.getElementById('ai-download-wrap');
    const downloadBtn = document.getElementById('ai-download-btn');
    const errorMsg = document.getElementById('ai-error-msg');
    let pollInterval = null;
    let currentJobId = null;

    function updateBalance(kp) {
        if (balanceEl) balanceEl.textContent = 'Balance: ' + parseInt(kp, 10).toLocaleString() + ' KP';
    }

    document.querySelectorAll('.labs-scroll-link').forEach(function(a) {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    function showStatus(jobId) {
        currentJobId = jobId;
        statusPanel.style.display = 'block';
        statusText.textContent = 'Processing...';
        statusDetail.textContent = '';
        spinner.style.display = 'inline-block';
        downloadWrap.style.display = 'none';
        errorMsg.style.display = 'none';
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(function() {
            fetch('/api/ai/status.php?job_id=' + encodeURIComponent(jobId))
                .then(r => r.json())
                .then(d => {
                    if (!d.ok) return;
                    const st = d.data.status;
                    statusText.textContent = st.charAt(0).toUpperCase() + st.slice(1);
                    if (st === 'failed') {
                        statusDetail.textContent = d.data.error_message || '';
                        errorMsg.textContent = d.data.error_message || 'Failed';
                        errorMsg.style.display = 'block';
                        spinner.style.display = 'none';
                        clearInterval(pollInterval);
                        pollInterval = null;
                        currentJobId = null;
                    } else if (st === 'completed') {
                        clearInterval(pollInterval);
                        pollInterval = null;
                        currentJobId = null;
                        spinner.style.display = 'none';
                        downloadWrap.style.display = 'block';
                        downloadBtn.href = '/api/ai/download.php?job_id=' + encodeURIComponent(jobId);
                        downloadBtn.target = '_blank';
                    }
                    if (d.data.available_after !== undefined) updateBalance(d.data.available_after);
                })
                .catch(function() {});
        }, 3000);
    }

    function submitForm(formEl, formData) {
        const submitBtn = formEl.querySelector('[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;
        fetch('/api/ai/submit.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(d => {
                if (submitBtn) submitBtn.disabled = false;
                if (d.ok && d.data.job_id) {
                    if (d.data.available_after !== undefined) updateBalance(d.data.available_after);
                    formEl.reset();
                    var upscalePreview = document.getElementById('ai-upscale-preview');
                    var upscaleContent = document.getElementById('ai-upscale-content');
                    if (upscalePreview) upscalePreview.style.display = 'none';
                    if (upscaleContent) upscaleContent.style.display = 'block';
                    showStatus(d.data.job_id);
                    statusPanel.scrollIntoView({ behavior: 'smooth' });
                } else {
                    const msg = (d.error && d.error.message) ? d.error.message : 'Error';
                    if (typeof kndToast !== 'undefined') kndToast(msg, 'error');
                    else alert(msg);
                }
            })
            .catch(function() {
                if (submitBtn) submitBtn.disabled = false;
                if (typeof kndToast !== 'undefined') kndToast('Network error', 'error');
                else alert('Network error');
            });
    }

    document.getElementById('ai-text2img-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        if (!fd.get('prompt') || fd.get('prompt').trim().length === 0) {
            if (typeof kndToast !== 'undefined') kndToast('Prompt is required', 'error');
            return;
        }
        submitForm(this, fd);
    });

    const upscaleDz = document.getElementById('ai-upscale-dropzone');
    const upscaleFile = document.getElementById('ai-upscale-file');
    const upscaleContent = document.getElementById('ai-upscale-content');
    const upscalePreview = document.getElementById('ai-upscale-preview');
    const upscalePreviewImg = document.getElementById('ai-upscale-preview-img');
    if (upscaleDz) upscaleDz.addEventListener('click', function() { upscaleFile && upscaleFile.click(); });
    if (upscaleFile) upscaleFile.addEventListener('change', function() {
        const f = this.files[0];
        if (f && f.type.startsWith('image/')) {
            if (upscalePreviewImg) upscalePreviewImg.src = URL.createObjectURL(f);
            if (upscalePreview) upscalePreview.style.display = 'block';
            if (upscaleContent) upscaleContent.style.display = 'none';
            var btn = document.getElementById('ai-upscale-submit');
            if (btn) btn.disabled = false;
        }
    });

    document.getElementById('ai-upscale-form').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!upscaleFile || !upscaleFile.files.length) return;
        const fd = new FormData(this);
        fd.append('image', upscaleFile.files[0]);
        submitForm(this, fd);
    });

    document.getElementById('ai-character-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        if (!fd.get('prompt') || fd.get('prompt').trim().length === 0) {
            if (typeof kndToast !== 'undefined') kndToast('Prompt is required', 'error');
            return;
        }
        submitForm(this, fd);
    });

    document.getElementById('ai-texture-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        if (!fd.get('prompt') || fd.get('prompt').trim().length === 0) {
            if (typeof kndToast !== 'undefined') kndToast('Prompt is required', 'error');
            return;
        }
        submitForm(this, fd);
    });
})();
</script>

<?php echo generateFooter(); ?>
<?php echo generateScripts(); ?>
