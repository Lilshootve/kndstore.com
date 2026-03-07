<?php
// Copy to worker_secrets.local.php and set tokens.
// worker_secrets.local.php is NOT in Git (config/*.local.php).
// WORKER_TOKEN: used by Text2Img queue (lease.php, complete.php, fail.php, tmp_image.php) — X-KND-WORKER-TOKEN.
// WORKER_3D_UPLOAD_TOKEN: used only by 3D Lab upload endpoint (upload-output.php) — X-KND-3D-WORKER-TOKEN.

return [
    'WORKER_TOKEN' => 'your_secure_random_token_here_min_32_chars',
    'WORKER_3D_UPLOAD_TOKEN' => 'your_3d_upload_secure_token_here_min_32_chars',
];
