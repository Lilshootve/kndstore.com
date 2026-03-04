<?php
/**
 * InstantMesh 3D configuration - Copy to triposr_secrets.local.php and fill in values.
 * Endpoints /api/triposr/* are kept for backward compatibility.
 */

// Base URL of your InstantMesh GPU server /generate endpoint
define('INSTANTMESH_API_URL', 'https://your-gpu-server.com/generate');

// Secret shared with the GPU server for callback validation
define('INSTANTMESH_CALLBACK_SECRET', 'generate-with-openssl-rand-hex-32');

// Directories relative to project storage/
define('TRIPOSR_UPLOAD_DIR', 'triposr/uploads');
define('TRIPOSR_OUTPUT_DIR', 'triposr/outputs');
