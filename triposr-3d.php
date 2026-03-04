<?php
/**
 * Image to 3D (InstantMesh) - Upload image, generate downloadable 3D model.
 * URL /triposr-3d.php kept for backward compatibility.
 */
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

require_login();

$errorMsg = '';
if (isset($_GET['error'])) {
    $map = [
        'missing' => t('triposr.error.missing', 'Missing job ID'),
        'not_found' => t('triposr.error.not_found', 'Job not found'),
        'forbidden' => t('triposr.error.forbidden', 'Access denied'),
        'not_ready' => t('triposr.error.not_ready', 'Model not ready yet'),
        'file_missing' => t('triposr.error.file_missing', 'File not found'),
        'db' => t('triposr.error.db', 'Server error'),
        'server' => t('triposr.error.server', 'Server error'),
    ];
    $errorMsg = $map[$_GET['error']] ?? t('triposr.error.unknown', 'An error occurred');
}

$triposrCss = __DIR__ . '/assets/css/triposr-3d.css';
$extraCss = '<link rel="stylesheet" href="/assets/css/triposr-3d.css?v=' . (file_exists($triposrCss) ? filemtime($triposrCss) : time()) . '">';
echo generateHeader(t('triposr.meta.title'), t('triposr.meta.description'), $extraCss);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section triposr-hero">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8 mx-auto text-center">
                <div class="triposr-hero-badge mb-3">
                    <i class="fas fa-cube"></i>
                    <span><?php echo t('triposr.hero.subtitle', 'Imagen → 3D'); ?></span>
                </div>
                <h1 class="hero-title triposr-hero-title">
                    <span class="text-gradient"><?php echo t('triposr.hero.title', 'Imagen → 3D (InstantMesh)'); ?></span>
                </h1>
                <p class="hero-subtitle triposr-hero-desc">
                    <?php echo t('triposr.hero.desc', 'Upload an image and generate a downloadable 3D model (GLB/OBJ).'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Upload Section -->
<section class="py-5 triposr-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($errorMsg): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                    <span><?php echo htmlspecialchars($errorMsg); ?></span>
                </div>
                <?php endif; ?>

                <div class="glass-card-neon triposr-upload-card p-5">
                    <form id="triposr-form" class="triposr-upload-form">
                        <div class="triposr-dropzone" id="triposr-dropzone">
                            <input type="file" id="triposr-file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp" hidden>
                            <div class="triposr-dropzone-content" id="triposr-dropzone-content">
                                <div class="triposr-dropzone-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <p class="triposr-dropzone-label"><?php echo t('triposr.upload.label', 'Choose an image'); ?></p>
                                <p class="triposr-dropzone-hint text-white-50 small"><?php echo t('triposr.upload.hint', 'JPG, PNG or WebP. Max 10MB.'); ?></p>
                            </div>
                            <div class="triposr-preview" id="triposr-preview" style="display:none;">
                                <img id="triposr-preview-img" src="" alt="Preview">
                                <button type="button" class="triposr-preview-remove btn btn-sm btn-outline-danger" id="triposr-remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label"><?php echo t('triposr.quality.label', 'Quality'); ?></label>
                            <div class="triposr-quality-options">
                                <label class="triposr-quality-option">
                                    <input type="radio" name="quality" value="fast">
                                    <span class="option-label"><?php echo t('triposr.quality.fast', 'Fast'); ?> — <?php echo t('triposr.quality.fast_cost', '8 KP'); ?></span>
                                    <span class="option-desc text-white-50 small"><?php echo t('triposr.quality.fast_desc', 'Faster, lower quality'); ?></span>
                                </label>
                                <label class="triposr-quality-option triposr-quality-default">
                                    <input type="radio" name="quality" value="balanced" checked>
                                    <span class="option-label"><?php echo t('triposr.quality.balanced', 'Balanced'); ?> — <?php echo t('triposr.quality.balanced_cost', '15 KP'); ?></span>
                                    <span class="option-desc text-white-50 small"><?php echo t('triposr.quality.balanced_desc', 'Balanced'); ?></span>
                                </label>
                                <label class="triposr-quality-option">
                                    <input type="radio" name="quality" value="high">
                                    <span class="option-label"><?php echo t('triposr.quality.high', 'High'); ?> — <?php echo t('triposr.quality.high_cost', '30 KP'); ?></span>
                                    <span class="option-desc text-white-50 small"><?php echo t('triposr.quality.high_desc', 'Best quality, takes longer'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-neon-primary btn-lg" id="triposr-submit" disabled>
                                <i class="fas fa-magic me-2"></i><?php echo t('triposr.upload.btn', 'Generate 3D Model'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Status Panel -->
                <div class="glass-card-neon triposr-status-card p-4 mt-4" id="triposr-status-panel" style="display:none;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="triposr-status-spinner me-3" id="triposr-spinner">
                                <i class="fas fa-cog fa-spin"></i>
                            </div>
                            <div>
                                <strong id="triposr-status-text"><?php echo t('triposr.status.processing', 'Generating 3D...'); ?></strong>
                                <div class="small text-white-50" id="triposr-status-detail"></div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-warning btn-sm" id="triposr-cancel-btn" style="display:none;">
                                <i class="fas fa-times me-1"></i><?php echo t('triposr.cancel.btn', 'Cancel'); ?>
                            </button>
                            <div id="triposr-download-wrap" style="display:none;">
                                <a href="#" class="btn btn-success" id="triposr-download-btn">
                                    <i class="fas fa-download me-2"></i><?php echo t('triposr.download.btn', 'Download Model'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger mt-3" id="triposr-error-msg" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5 bg-dark-epic triposr-how-section">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-info-circle me-2"></i><?php echo t('triposr.how.title', 'How it works'); ?>
        </h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="glass-card-neon p-4 h-100 text-center triposr-step-card">
                    <div class="triposr-step-num mb-3">1</div>
                    <h5 class="text-white mb-2"><?php echo t('triposr.how.step1', 'Upload'); ?></h5>
                    <p class="text-white-50 small mb-0"><?php echo t('triposr.how.step1_desc', 'Select a clear image of an object'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card-neon p-4 h-100 text-center triposr-step-card">
                    <div class="triposr-step-num mb-3">2</div>
                    <h5 class="text-white mb-2"><?php echo t('triposr.how.step2', 'Process'); ?></h5>
                    <p class="text-white-50 small mb-0"><?php echo t('triposr.how.step2_desc', 'Our GPU server generates the 3D mesh'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card-neon p-4 h-100 text-center triposr-step-card">
                    <div class="triposr-step-num mb-3">3</div>
                    <h5 class="text-white mb-2"><?php echo t('triposr.how.step3', 'Download'); ?></h5>
                    <p class="text-white-50 small mb-0"><?php echo t('triposr.how.step3_desc', 'Get your OBJ or GLB file'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>
<script>
(function() {
    const dropzone = document.getElementById('triposr-dropzone');
    const dropzoneContent = document.getElementById('triposr-dropzone-content');
    const preview = document.getElementById('triposr-preview');
    const previewImg = document.getElementById('triposr-preview-img');
    const fileInput = document.getElementById('triposr-file');
    const form = document.getElementById('triposr-form');
    const submitBtn = document.getElementById('triposr-submit');
    const statusPanel = document.getElementById('triposr-status-panel');
    const statusText = document.getElementById('triposr-status-text');
    const statusDetail = document.getElementById('triposr-status-detail');
    const spinner = document.getElementById('triposr-spinner');
    const downloadWrap = document.getElementById('triposr-download-wrap');
    const downloadBtn = document.getElementById('triposr-download-btn');
    const errorMsg = document.getElementById('triposr-error-msg');
    const removeBtn = document.getElementById('triposr-remove');
    const cancelBtn = document.getElementById('triposr-cancel-btn');

    const statusLabels = {
        pending: '<?php echo addslashes(t('triposr.status.pending')); ?>',
        processing: '<?php echo addslashes(t('triposr.status.processing')); ?>',
        completed: '<?php echo addslashes(t('triposr.status.completed')); ?>',
        failed: '<?php echo addslashes(t('triposr.status.failed')); ?>'
    };

    const statusDetails = {
        pending: '<?php echo addslashes(t('triposr.status.pending_detail')); ?>',
        processing: '<?php echo addslashes(t('triposr.status.processing_detail')); ?>',
        completed: '<?php echo addslashes(t('triposr.status.completed_detail')); ?>',
        failed: '<?php echo addslashes(t('triposr.status.failed_detail')); ?>'
    };

    let selectedFile = null;
    let pollInterval = null;
    let currentJobId = null;

    function resetForm() {
        selectedFile = null;
        fileInput.value = '';
        preview.style.display = 'none';
        dropzoneContent.style.display = 'block';
        submitBtn.disabled = true;
    }

    function showStatus(jobId, initialStatus, quality) {
        currentJobId = jobId;
        statusPanel.style.display = 'block';
        statusText.textContent = statusLabels[initialStatus] || initialStatus;
        statusDetail.textContent = quality ? ('Quality: ' + quality) : '';
        spinner.style.display = initialStatus === 'pending' || initialStatus === 'processing' ? 'inline-block' : 'none';
        downloadWrap.style.display = 'none';
        errorMsg.style.display = 'none';
        cancelBtn.style.display = (initialStatus === 'pending') ? 'inline-block' : 'none';

        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(function() {
            fetch('/api/triposr/status.php?job_id=' + encodeURIComponent(jobId))
                .then(r => r.json())
                .then(d => {
                    if (!d.ok) return;
                    const st = d.data.status;
                    const qual = d.data.quality || '';
                    statusText.textContent = statusLabels[st] || st;
                    let detail = qual ? ('Quality: ' + qual) : '';
                    if (st === 'failed' && d.data.error_message) {
                        detail = d.data.error_message;
                        errorMsg.textContent = d.data.error_message;
                        errorMsg.style.display = 'block';
                    } else {
                        detail = detail || (statusDetails[st] || '');
                        statusDetail.textContent = detail;
                    }
                    cancelBtn.style.display = (st === 'pending') ? 'inline-block' : 'none';
                    if (st === 'completed') {
                        clearInterval(pollInterval);
                        pollInterval = null;
                        currentJobId = null;
                        spinner.style.display = 'none';
                        downloadWrap.style.display = 'block';
                        cancelBtn.style.display = 'none';
                        downloadBtn.href = '/api/triposr/download.php?job_id=' + encodeURIComponent(jobId);
                        downloadBtn.target = '_blank';
                        statusDetail.textContent = statusDetails.completed || '';
                    } else if (st === 'failed') {
                        clearInterval(pollInterval);
                        pollInterval = null;
                        currentJobId = null;
                        spinner.style.display = 'none';
                        cancelBtn.style.display = 'none';
                    }
                })
                .catch(() => {});
        }, 3000);
    }

    cancelBtn.addEventListener('click', function() {
        if (!currentJobId) return;
        if (!confirm('<?php echo addslashes(t('triposr.cancel.confirm', 'Cancel this job?')); ?>')) return;
        const fd = new FormData();
        fd.append('job_id', currentJobId);
        fetch('/api/triposr/cancel.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.ok) {
                    if (pollInterval) clearInterval(pollInterval);
                    pollInterval = null;
                    statusText.textContent = statusLabels.failed;
                    statusDetail.textContent = 'Cancelled by user';
                    errorMsg.textContent = 'Cancelled by user';
                    errorMsg.style.display = 'block';
                    spinner.style.display = 'none';
                    cancelBtn.style.display = 'none';
                    currentJobId = null;
                } else {
                    const msg = (d.error && d.error.message) ? d.error.message : 'Cancel failed';
                    if (typeof kndToast !== 'undefined') kndToast(msg, 'error');
                    else alert(msg);
                }
            });
    });

    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const f = e.dataTransfer.files[0];
        if (f && f.type.startsWith('image/')) {
            selectedFile = f;
            previewImg.src = URL.createObjectURL(f);
            preview.style.display = 'block';
            dropzoneContent.style.display = 'none';
            submitBtn.disabled = false;
        }
    });
    fileInput.addEventListener('change', function() {
        const f = this.files[0];
        if (f) {
            selectedFile = f;
            previewImg.src = URL.createObjectURL(f);
            preview.style.display = 'block';
            dropzoneContent.style.display = 'none';
            submitBtn.disabled = false;
        }
    });
    removeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        resetForm();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!selectedFile) return;
        const qualityEl = form.querySelector('input[name="quality"]:checked');
        const quality = qualityEl ? qualityEl.value : 'balanced';

        submitBtn.disabled = true;
        const fd = new FormData();
        fd.append('image', selectedFile);
        fd.append('quality', quality);

        fetch('/api/triposr/submit.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.ok && d.data.job_id) {
                    resetForm();
                    showStatus(d.data.job_id, d.data.status || 'pending', d.data.quality || quality);
                    statusPanel.scrollIntoView({ behavior: 'smooth' });
                } else {
                    submitBtn.disabled = false;
                    const msg = (d.error && d.error.message) ? d.error.message : 'Upload failed';
                    if (typeof kndToast !== 'undefined') kndToast(msg, 'error');
                    else alert(msg);
                }
            })
            .catch(() => {
                submitBtn.disabled = false;
                if (typeof kndToast !== 'undefined') kndToast('Network error', 'error');
                else alert('Network error');
            });
    });
})();
</script>

<?php
echo generateFooter();
echo generateScripts();
?>
