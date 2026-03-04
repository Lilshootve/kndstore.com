<?php
/**
 * InstantMesh 3D configuration loader.
 * Endpoints remain /api/triposr/* for backward compatibility.
 *
 * Preferred constants: INSTANTMESH_API_URL, INSTANTMESH_CALLBACK_SECRET.
 * Fallback: TRIPOSR_API_URL, TRIPOSR_CALLBACK_SECRET (legacy).
 *
 * Add to includes/config.php:
 *   require_once __DIR__ . '/triposr_config.php';
 */

if (!defined('INSTANTMESH_API_URL') && !defined('TRIPOSR_API_URL')) {
    $secrets = __DIR__ . '/../config/triposr_secrets.local.php';
    if (file_exists($secrets)) {
        require_once $secrets;
    }
}

// INSTANTMESH_* preferred; fallback to TRIPOSR_* for backward compatibility
if (!defined('INSTANTMESH_API_URL')) {
    define('INSTANTMESH_API_URL', defined('TRIPOSR_API_URL') ? TRIPOSR_API_URL : '');
}
if (!defined('INSTANTMESH_CALLBACK_SECRET')) {
    define('INSTANTMESH_CALLBACK_SECRET', defined('TRIPOSR_CALLBACK_SECRET') ? TRIPOSR_CALLBACK_SECRET : '');
}
if (!defined('TRIPOSR_API_URL')) {
    define('TRIPOSR_API_URL', INSTANTMESH_API_URL);
}
if (!defined('TRIPOSR_CALLBACK_SECRET')) {
    define('TRIPOSR_CALLBACK_SECRET', INSTANTMESH_CALLBACK_SECRET);
}
if (!defined('TRIPOSR_UPLOAD_DIR')) {
    define('TRIPOSR_UPLOAD_DIR', 'triposr/uploads');
}
if (!defined('TRIPOSR_OUTPUT_DIR')) {
    define('TRIPOSR_OUTPUT_DIR', 'triposr/outputs');
}
