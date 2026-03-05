<?php
/**
 * POST /api/labs_upscale_create.php
 * Create upscale job. source_type: upload | recent
 * Uploads image to ComfyUI via HTTP (no shared filesystem).
 */
header('Cache-Control: no-store, no-cache');
header('Content-Type: application/json');
ini_set('display_errors', '0');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/support_credits.php';
require_once __DIR__ . '/../includes/ai.php';
require_once __DIR__ . '/../includes/json.php';
require_once __DIR__ . '/../includes/comfyui.php';
require_once __DIR__ . '/../includes/comfyui_provider.php';
require_once __DIR__ . '/../includes/settings.php';
require_once __DIR__ . '/../includes/labs_image_helper.php';

const MAX_UPLOAD_BYTES = 5 * 1024 * 1024; // 5MB
const MAX_DIMENSION = 2048;
const UPSCALE_COST_KP = 5;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
    }

    api_require_login();

    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);

    $userId = current_user_id();

    $sourceType = trim($_POST['source_type'] ?? '');
    if (!in_array($sourceType, ['upload', 'recent'], true)) {
        json_error('INVALID_INPUT', 'source_type must be upload or recent.', 400);
    }

    release_available_points_if_due($pdo, $userId);
    expire_points_if_due($pdo, $userId);
    $available = get_available_points($pdo, $userId);
    if ($available < UPSCALE_COST_KP) {
        json_error('INSUFFICIENT_POINTS', 'Insufficient KP. Need ' . UPSCALE_COST_KP . '.', 400);
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM knd_labs_jobs WHERE user_id = ? AND status IN ('queued','processing')");
    if ($stmt && $stmt->execute([$userId])) {
        if ((int) $stmt->fetchColumn() >= 2) {
            json_error('RATE_LIMIT', 'Too many active jobs.', 429);
        }
    }

    $tmpPath = null;
    if ($sourceType === 'upload') {
        if (empty($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
            json_error('INVALID_IMAGE', 'image_file required for upload.', 400);
        }
        $tmpPath = $_FILES['image_file']['tmp_name'];
        $valid = labs_validate_image($tmpPath, MAX_UPLOAD_BYTES, MAX_DIMENSION);
        if (!$valid['ok']) {
            json_error('INVALID_IMAGE', $valid['error'] ?? 'Invalid image.', 400);
        }
    } else {
        $sourceJobId = (int) ($_POST['source_job_id'] ?? 0);
        if ($sourceJobId <= 0) json_error('INVALID_INPUT', 'source_job_id required for recent.', 400);
        $bytes = comfyui_fetch_job_image_bytes($pdo, $sourceJobId, $userId);
        if (!$bytes) json_error('INVALID_SOURCE', 'Could not fetch source image.', 400);
        $tmpPath = tempnam(sys_get_temp_dir(), 'knd_up');
        if (!$tmpPath || file_put_contents($tmpPath, $bytes) === false) {
            if ($tmpPath) @unlink($tmpPath);
            json_error('INTERNAL_ERROR', 'Temp file error.', 500);
        }
        $valid = labs_validate_image($tmpPath, MAX_UPLOAD_BYTES, MAX_DIMENSION);
        if (!$valid['ok']) {
            if ($tmpPath) @unlink($tmpPath);
            json_error('INVALID_IMAGE', $valid['error'] ?? 'Invalid image.', 400);
        }
    }

    $runpodUrl = comfyui_get_base_url_runpod($pdo);
    $baseUrl = comfyui_get_base_url($pdo, null);
    $token = comfyui_get_token($pdo);
    $providerUsed = ($runpodUrl !== '' && rtrim($baseUrl, '/') === rtrim($runpodUrl, '/')) ? 'runpod' : 'local';

    try {
        $imageFilename = comfyui_upload_image($tmpPath, $baseUrl, $token);
    } finally {
        if ($tmpPath && $sourceType === 'recent') @unlink($tmpPath);
    }

    $pdo->beginTransaction();
    $stmt = $pdo->prepare(
        "INSERT INTO knd_labs_jobs (user_id, tool, prompt, negative_prompt, status, cost_kp, quality, provider, priority, payload_json)
         VALUES (?, 'upscale', '', '', 'queued', ?, '4x', ?, 100, ?)"
    );
    $jobId = 0;
    if (!$stmt || !$stmt->execute([$userId, UPSCALE_COST_KP, $providerUsed, '{}'])) {
        $pdo->rollBack();
        json_error('DB_ERROR', 'Could not create job.', 500);
    }
    $jobId = (int) $pdo->lastInsertId();

    $payload = [
        'tool' => 'upscale',
        'image_filename' => $imageFilename,
        'job_id' => $jobId,
    ];
    $stmt = $pdo->prepare("UPDATE knd_labs_jobs SET payload_json = ? WHERE id = ?");
    $stmt->execute([json_encode($payload), $jobId]);
    $pdo->commit();

    ai_spend_points($pdo, $userId, $jobId, UPSCALE_COST_KP);

    json_success(['job_id' => (string) $jobId]);
} catch (\Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log('api/labs_upscale_create: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', $e->getMessage(), 500);
}
