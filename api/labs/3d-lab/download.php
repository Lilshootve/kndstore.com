<?php
/**
 * 3D Lab - Download GLB or preview
 * GET /api/labs/3d-lab/download.php?id={job_id}&format=glb|preview&inline=1
 */
header('Cache-Control: no-store, no-cache');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../../includes/session.php';
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
require_once __DIR__ . '/../../../includes/storage.php';
require_once __DIR__ . '/../../../includes/labs_3d_helpers.php';

function labs_3d_fail(): void {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'File not available.';
    exit;
}

/**
 * Normalize path from DB: may be relative (labs/3d-lab/...) or absolute/weird from worker.
 * Always return a relative path with forward slashes for storage_path().
 */
function labs_3d_normalize_storage_path(string $path, string $publicId, string $format): string {
    $path = trim(str_replace('\\', '/', $path));
    if ($path === '') {
        return $format === 'glb'
            ? (LABS_3D_STORAGE_OUTPUT . '/' . $publicId . '.glb')
            : (LABS_3D_STORAGE_PREVIEW . '/' . $publicId . '.webp');
    }
    // If path already looks like our relative storage path, use it
    if (preg_match('#^labs/3d-lab/(output|preview)/[^\s\\\\/]+\.(glb|webp|png|jpe?g)$#i', $path)) {
        return $path;
    }
    // Extract relative part if DB stored absolute or path with prefix
    if (preg_match('#(labs/3d-lab/(?:output|preview)/[^\s\\\\/]+\.(?:glb|webp|png|jpe?g))$#i', $path, $m)) {
        return $m[1];
    }
    // Fallback: only filename or uuid — build canonical path
    $base = basename($path);
    if ($format === 'glb') {
        return LABS_3D_STORAGE_OUTPUT . '/' . (pathinfo($base, PATHINFO_EXTENSION) === 'glb' ? $base : $publicId . '.glb');
    }
    return LABS_3D_STORAGE_PREVIEW . '/' . (pathinfo($base, PATHINFO_EXTENSION) ? $base : $publicId . '.webp');
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        labs_3d_fail();
    }

    require_login();
    $userId = (int) current_user_id();

    $jobId = trim((string) ($_GET['id'] ?? $_GET['job_id'] ?? ''));
    $format = strtolower(trim((string) ($_GET['format'] ?? 'glb')));
    if (!in_array($format, ['glb', 'preview'], true)) {
        labs_3d_fail();
    }

    $pdo = getDBConnection();
    if (!$pdo) {
        labs_3d_fail();
    }

    $stmt = $pdo->prepare(
        "SELECT user_id, public_id, status, glb_path, preview_path
         FROM knd_labs_3d_jobs
         WHERE public_id = ? OR id = ? LIMIT 1"
    );
    $stmt->execute([$jobId, is_numeric($jobId) ? (int) $jobId : 0]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job || (int) $job['user_id'] !== $userId) {
        labs_3d_fail();
    }

    if ($format === 'glb' && $job['status'] !== 'completed') {
        labs_3d_fail();
    }

    $rawPath = $format === 'glb' ? ($job['glb_path'] ?? null) : ($job['preview_path'] ?? $job['glb_path'] ?? null);
    if (!$rawPath && $format === 'preview') {
        $rawPath = null;
    }
    $path = labs_3d_normalize_storage_path($rawPath ?? '', $job['public_id'], $format);
    $abs = storage_path($path);
    if (!is_file($abs) || !is_readable($abs)) {
        error_log('api/labs/3d-lab/download: file not found path=' . $path . ' abs=' . $abs . ' job_id=' . $jobId);
        labs_3d_fail();
    }

    $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
    $mime = $format === 'glb' ? 'model/gltf-binary' : ($ext === 'png' ? 'image/png' : ($ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : 'image/webp'));
    $name = '3d-lab-' . ($job['public_id'] ?? $jobId) . ($format === 'glb' ? '.glb' : ('.' . ($ext ?: 'webp')));

    $inline = !empty($_GET['inline']);

    header('Content-Type: ' . $mime);
    header('Content-Length: ' . (string) filesize($abs));
    header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . basename($name) . '"');

    readfile($abs);
    exit;
} catch (\Throwable $e) {
    error_log('api/labs/3d-lab/download: ' . $e->getMessage());
    labs_3d_fail();
}
