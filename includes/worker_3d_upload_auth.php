<?php
/**
 * 3D Lab worker upload token only (X-KND-3D-WORKER-TOKEN).
 * Used exclusively by api/labs/3d-lab/upload-output.php.
 * Does not affect Text2Img queue auth (worker_auth.php / WORKER_TOKEN).
 */
function get_worker_3d_upload_token(): string {
    static $token = null;
    if ($token !== null) return $token;
    $path = dirname(__DIR__) . '/config/worker_secrets.local.php';
    if (!is_readable($path)) return '';
    $cfg = include $path;
    $token = isset($cfg['WORKER_3D_UPLOAD_TOKEN']) ? trim((string) $cfg['WORKER_3D_UPLOAD_TOKEN']) : '';
    return $token;
}
