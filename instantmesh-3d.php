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
            <div class="instantmesh-hero__badge">KND Labs</div>
            <h1 class="instantmesh-hero__title">Image → 3D</h1>
            <p class="instantmesh-hero__subtitle">Turn a single product image or character render into a downloadable 3D mesh.</p>
            <div class="instantmesh-chips">
                <span class="knd-chip">OBJ</span>
                <span class="knd-chip">GLB</span>
                <span class="knd-chip">Background Removal</span>
                <span class="knd-chip">GPU Processed</span>
            </div>
        </div>

        <div class="instantmesh-layout mt-4">
            <aside class="knd-panel instantmesh-input-panel">
                <h3 class="knd-section-title mb-3">Input</h3>

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

                    <p class="knd-muted small mt-3 mb-1">Cost: <strong id="instantmesh-cost"><?php echo (int) $instantmeshCost; ?></strong> credits</p>
                    <p class="knd-muted small mb-0">Use centered subjects with clean silhouettes for better geometry.</p>
                </form>
            </aside>

            <section class="knd-panel instantmesh-output-panel">
                <h3 class="knd-section-title mb-3">Output / Status</h3>

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
                    <div id="viewer-empty" class="knd-canvas__empty">
                        <i class="fas fa-cube fa-2x mb-2"></i>
                        <p class="mb-0">No generations yet. Drop an image and create your first 3D asset.</p>
                    </div>
                    <model-viewer id="instantmesh-model-viewer" camera-controls auto-rotate interaction-prompt="none" style="display:none;"></model-viewer>
                </div>

                <div class="instantmesh-downloads mt-3">
                    <a id="download-glb" href="#" class="btn btn-success me-2 disabled" aria-disabled="true">Download GLB</a>
                    <a id="download-obj" href="#" class="btn btn-outline-light disabled" aria-disabled="true">Download OBJ</a>
                </div>

                <div class="instantmesh-meta mt-3">
                    <div><span>Date:</span> <strong id="meta-date">—</strong></div>
                    <div><span>Seed:</span> <strong id="meta-seed">—</strong></div>
                    <div><span>Remove BG:</span> <strong id="meta-remove-bg">—</strong></div>
                    <div><span>Total time:</span> <strong id="meta-time">—</strong></div>
                </div>

                <div id="instantmesh-error" class="alert alert-danger mt-3 mb-0" style="display:none;"></div>
            </section>
        </div>

        <section class="knd-panel-soft mt-4">
            <h3 class="knd-section-title mb-3">Recent Generations</h3>
            <div id="instantmesh-history" class="instantmesh-history-grid">
                <p class="knd-muted small mb-0"><i class="fas fa-spinner fa-spin me-1"></i>Loading recent jobs...</p>
            </div>
        </section>

        <section class="knd-panel-soft mt-4 mb-2">
            <h3 class="knd-section-title mb-3">FAQ</h3>
            <div class="instantmesh-faq">
                <div>
                    <strong>What kind of images work best?</strong>
                    <p>Clear, centered subjects with simple backgrounds produce cleaner geometry.</p>
                </div>
                <div>
                    <strong>Can I generate OBJ and GLB together?</strong>
                    <p>Yes. Select <em>Both</em> in Output Format to request both exports.</p>
                </div>
                <div>
                    <strong>Can I close the page while generating?</strong>
                    <p>Yes. Jobs run in the queue. You can reopen this page and check Recent Generations.</p>
                </div>
                <div>
                    <strong>Why is GLB recommended for preview?</strong>
                    <p>GLB is easier to render directly in-browser and offers a smoother workflow.</p>
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
