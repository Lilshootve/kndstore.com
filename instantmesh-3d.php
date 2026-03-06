<?php
/**
 * KND Labs - InstantMesh Image → 3D tool page
 */
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
    $uid = current_user_id();
    release_available_points_if_due($pdo, $uid);
    expire_points_if_due($pdo, $uid);
    $balance = get_available_points($pdo, $uid);
}

$instantmeshCost = 15;
$labsCss = __DIR__ . '/assets/css/knd-labs.css';
$toolCss = __DIR__ . '/assets/css/labs/instantmesh-3d.css';

$extraHead = '';
$extraHead .= '<link rel="stylesheet" href="/assets/css/knd-labs.css?v=' . (file_exists($labsCss) ? filemtime($labsCss) : time()) . '">';
$extraHead .= '<link rel="stylesheet" href="/assets/css/labs/instantmesh-3d.css?v=' . (file_exists($toolCss) ? filemtime($toolCss) : time()) . '">';
$extraHead .= '<script type="module" src="https://cdn.jsdelivr.net/npm/@google/model-viewer/dist/model-viewer.min.js"></script>';

echo generateHeader('Image → 3D | KND Labs', 'Generate OBJ / GLB assets from a single image with InstantMesh.', $extraHead);
?>

<div id="particles-bg"></div>
<?php echo generateNavigation(); ?>

<section class="labs-tool-section instantmesh-page py-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/labs">KND Labs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Image → 3D</li>
            </ol>
        </nav>

        <div class="instantmesh-hero knd-panel-soft mt-4">
            <div class="instantmesh-hero__content">
                <div class="instantmesh-hero__badge">KND Labs</div>
                <h1 class="instantmesh-hero__title">Image → 3D Mesh Generator</h1>
                <p class="instantmesh-hero__subtitle">Convert a single image into a production-ready 3D asset for prototyping, previews or concept work.</p>
                <div class="instantmesh-chips">
                    <span class="knd-chip">OBJ</span>
                    <span class="knd-chip">GLB</span>
                    <span class="knd-chip">Background Removal</span>
                    <span class="knd-chip">GPU Processed</span>
                </div>
            </div>
            <aside class="instantmesh-hero__stats knd-panel-soft">
                <div class="instantmesh-stat-card">
                    <span class="instantmesh-stat-card__label">Generation Cost</span>
                    <strong class="instantmesh-stat-card__value"><?php echo (int) $instantmeshCost; ?> credits</strong>
                </div>
                <div class="instantmesh-stat-card">
                    <span class="instantmesh-stat-card__label">Output Formats</span>
                    <strong class="instantmesh-stat-card__value">GLB / OBJ</strong>
                </div>
                <div class="instantmesh-stat-card">
                    <span class="instantmesh-stat-card__label">Processing</span>
                    <strong class="instantmesh-stat-card__value">Dedicated GPU Queue</strong>
                </div>
                <div class="instantmesh-stat-card">
                    <span class="instantmesh-stat-card__label">Average Time</span>
                    <strong class="instantmesh-stat-card__value">~2-4 min</strong>
                </div>
            </aside>
        </div>

        <div class="instantmesh-layout mt-4">
            <aside class="knd-panel instantmesh-input-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="knd-section-title mb-0">Input</h3>
                    <span class="instantmesh-panel-tag">Single Image</span>
                </div>

                <form id="instantmesh-form" enctype="multipart/form-data" onsubmit="return false;">
                    <div id="instantmesh-dropzone" class="instantmesh-dropzone">
                        <input type="file" id="instantmesh-file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp" hidden>
                        <div id="instantmesh-dropzone-content" class="instantmesh-dropzone__content">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p class="mb-1">Drop image or click to upload</p>
                            <small>JPG, PNG, WEBP · max 10MB</small>
                        </div>
                        <div id="instantmesh-preview-wrap" class="instantmesh-preview-wrap" style="display:none;">
                            <img id="instantmesh-preview" alt="Uploaded image preview">
                            <button type="button" id="instantmesh-remove" class="btn btn-sm btn-outline-danger instantmesh-preview-remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="remove-bg" name="remove_bg" checked>
                        <label class="form-check-label text-white-50" for="remove-bg">Remove Background</label>
                    </div>

                    <div class="mt-3">
                        <label class="form-label text-white-50">Seed</label>
                        <input type="number" class="knd-input form-control text-white" id="seed" name="seed" value="42" min="0" max="2147483647">
                    </div>

                    <div class="mt-3">
                        <label class="form-label text-white-50">Output Format</label>
                        <select class="knd-select form-select text-white" id="output-format" name="output_format">
                            <option value="glb" selected>GLB</option>
                            <option value="obj">OBJ</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <button type="submit" id="instantmesh-submit" class="labs-gen-btn w-100 mt-4" disabled>
                        <i class="fas fa-cube me-2"></i>Generate 3D
                    </button>

                    <div class="instantmesh-input-foot mt-3">
                        <p class="knd-muted small mb-1">Cost: <strong id="instantmesh-cost"><?php echo (int) $instantmeshCost; ?></strong> credits</p>
                        <p class="knd-muted small mb-0">Use centered subjects with clean silhouettes for better geometry.</p>
                    </div>
                </form>
            </aside>

            <section class="knd-panel instantmesh-output-panel">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h3 class="knd-section-title mb-0">Output / Status</h3>
                    <span class="instantmesh-panel-tag">Live Job Monitor</span>
                </div>

                <div class="instantmesh-status">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="job-status-label" class="instantmesh-status__label">Idle</span>
                        <span id="job-status-badge" class="badge text-bg-secondary">waiting</span>
                    </div>
                    <div class="progress instantmesh-progress" role="progressbar" aria-label="Generation progress" aria-valuemin="0" aria-valuemax="100">
                        <div id="job-progress-bar" class="progress-bar" style="width:0%"></div>
                    </div>
                </div>

                <div id="viewer-wrap" class="instantmesh-viewer mt-3">
                    <div id="viewer-empty" class="instantmesh-viewer-empty">
                        <div class="instantmesh-viewer-empty__icon"><i class="fas fa-cube"></i></div>
                        <h4>No 3D preview yet</h4>
                        <p class="mb-0">Start a generation to preview your GLB directly in this panel.</p>
                    </div>
                    <model-viewer id="instantmesh-model-viewer" camera-controls auto-rotate interaction-prompt="none" style="display:none;"></model-viewer>
                </div>

                <div class="instantmesh-downloads mt-3">
                    <a id="download-glb" href="#" class="btn btn-success me-2 disabled" aria-disabled="true">Download GLB</a>
                    <a id="download-obj" href="#" class="btn btn-outline-light disabled" aria-disabled="true">Download OBJ</a>
                </div>

                <div class="instantmesh-meta-grid mt-3">
                    <div class="instantmesh-meta-item"><span>Date</span><strong id="meta-date">—</strong></div>
                    <div class="instantmesh-meta-item"><span>Seed</span><strong id="meta-seed">—</strong></div>
                    <div class="instantmesh-meta-item"><span>Remove BG</span><strong id="meta-remove-bg">—</strong></div>
                    <div class="instantmesh-meta-item"><span>Total Time</span><strong id="meta-time">—</strong></div>
                </div>

                <div id="instantmesh-error" class="alert alert-danger mt-3 mb-0" style="display:none;"></div>
            </section>
        </div>

        <section class="knd-panel-soft mt-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h3 class="knd-section-title mb-0">Recent Generations</h3>
                <span class="knd-muted small">Your latest InstantMesh jobs</span>
            </div>
            <div id="instantmesh-history" class="instantmesh-history-grid">
                <p class="knd-muted small mb-0"><i class="fas fa-spinner fa-spin me-1"></i>Loading recent jobs...</p>
            </div>
        </section>

        <section class="knd-panel-soft mt-4 mb-2">
            <h3 class="knd-section-title mb-3">FAQ</h3>
            <div class="accordion instantmesh-faq-accordion" id="instantmeshFaq">
                <div class="accordion-item instantmesh-faq-item">
                    <h2 class="accordion-header" id="faq-heading-one">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-one" aria-expanded="true" aria-controls="faq-collapse-one">
                            What kind of images work best?
                        </button>
                    </h2>
                    <div id="faq-collapse-one" class="accordion-collapse collapse show" aria-labelledby="faq-heading-one" data-bs-parent="#instantmeshFaq">
                        <div class="accordion-body">Clear, centered subjects with simple backgrounds produce cleaner geometry and more stable silhouettes.</div>
                    </div>
                </div>
                <div class="accordion-item instantmesh-faq-item">
                    <h2 class="accordion-header" id="faq-heading-two">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-two" aria-expanded="false" aria-controls="faq-collapse-two">
                            Can I generate OBJ and GLB together?
                        </button>
                    </h2>
                    <div id="faq-collapse-two" class="accordion-collapse collapse" aria-labelledby="faq-heading-two" data-bs-parent="#instantmeshFaq">
                        <div class="accordion-body">Yes. Select <em>Both</em> in Output Format to request simultaneous exports in a single generation.</div>
                    </div>
                </div>
                <div class="accordion-item instantmesh-faq-item">
                    <h2 class="accordion-header" id="faq-heading-three">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-three" aria-expanded="false" aria-controls="faq-collapse-three">
                            Can I close the page while generating?
                        </button>
                    </h2>
                    <div id="faq-collapse-three" class="accordion-collapse collapse" aria-labelledby="faq-heading-three" data-bs-parent="#instantmeshFaq">
                        <div class="accordion-body">Yes. Jobs run in queue and remain associated to your account. You can come back later and open them from Recent Generations.</div>
                    </div>
                </div>
                <div class="accordion-item instantmesh-faq-item">
                    <h2 class="accordion-header" id="faq-heading-four">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-four" aria-expanded="false" aria-controls="faq-collapse-four">
                            Why is GLB recommended for preview?
                        </button>
                    </h2>
                    <div id="faq-collapse-four" class="accordion-collapse collapse" aria-labelledby="faq-heading-four" data-bs-parent="#instantmeshFaq">
                        <div class="accordion-body">GLB is browser-friendly and enables native visual inspection directly inside this page using model-viewer.</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>

<script>
window.KND_INSTANTMESH = {
    cost: <?php echo (int) $instantmeshCost; ?>,
    balance: <?php echo (int) $balance; ?>,
    endpoints: {
        create: '/api/labs/instantmesh/create.php',
        status: '/api/labs/instantmesh/status.php',
        history: '/api/labs/instantmesh/history.php',
        download: '/api/labs/instantmesh/download.php'
    }
};
</script>
<script src="/assets/js/navigation-extend.js"></script>
<script src="/assets/js/labs/instantmesh-3d.js?v=<?php echo file_exists(__DIR__ . '/assets/js/labs/instantmesh-3d.js') ? filemtime(__DIR__ . '/assets/js/labs/instantmesh-3d.js') : time(); ?>"></script>

<?php echo generateFooter(); echo generateScripts(); ?>
