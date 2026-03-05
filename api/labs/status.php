<?php
/**
 * KND Labs - ComfyUI job status
 * GET /api/labs/status.php?job_id=XXX
 */
header('Cache-Control: no-store, no-cache');
header('Content-Type: application/json');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/comfyui.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        json_error('METHOD_NOT_ALLOWED', 'GET only.', 405);
    }

    api_require_login();

    $pdo = getDBConnection();
    if (!$pdo) {
        json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);
    }

    $jobId = trim($_GET['job_id'] ?? '');
    if ($jobId === '') {
        json_error('INVALID_INPUT', 'job_id is required.');
    }

    $stmt = $pdo->prepare("SELECT * FROM knd_labs_jobs WHERE id = ? AND user_id = ? LIMIT 1");
    if (!$stmt || !$stmt->execute([$jobId, current_user_id()])) {
        json_error('DB_ERROR', 'Could not fetch job.', 500);
    }
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$job) {
        json_error('JOB_NOT_FOUND', 'Job not found.', 404);
    }

    if ($job['status'] === 'pending' || $job['status'] === 'processing') {
        $history = comfyui_get_history($job['comfy_prompt_id']);
        if ($history && isset($history['outputs'])) {
            $filename = null;
            foreach ($history['outputs'] as $nodeOutputs) {
                if (isset($nodeOutputs['images']) && is_array($nodeOutputs['images'])) {
                    foreach ($nodeOutputs['images'] as $img) {
                        if (!empty($img['filename'])) {
                            $filename = $img['filename'];
                            break 2;
                        }
                    }
                }
            }
            if ($filename) {
                $base = '';
                if (file_exists(__DIR__ . '/../../config/comfyui.php')) {
                    require_once __DIR__ . '/../../config/comfyui.php';
                    $base = rtrim(COMFYUI_BASE_URL, '/');
                }
                $imageUrl = $base ? ($base . '/view?filename=' . urlencode($filename) . '&type=output') : null;
                $stmt2 = $pdo->prepare("UPDATE knd_labs_jobs SET status = 'done', image_url = ?, updated_at = NOW() WHERE id = ?");
                if ($stmt2) $stmt2->execute([$imageUrl, $jobId]);
                $job['status'] = 'done';
                $job['image_url'] = $imageUrl;
            }
        } elseif ($history && isset($history['status'])) {
            $status = $history['status'];
            if (isset($status['status_str']) && $status['status_str'] === 'error') {
                $errMsg = $status['messages'] ?? 'ComfyUI error';
                $stmt2 = $pdo->prepare("UPDATE knd_labs_jobs SET status = 'failed', error_message = ?, updated_at = NOW() WHERE id = ?");
                if ($stmt2) $stmt2->execute([is_string($errMsg) ? $errMsg : json_encode($errMsg), $jobId]);
                $job['status'] = 'failed';
                $job['error_message'] = is_string($errMsg) ? $errMsg : 'Unknown error';
            } else {
                $stmt2 = $pdo->prepare("UPDATE knd_labs_jobs SET status = 'processing', updated_at = NOW() WHERE id = ?");
                if ($stmt2) $stmt2->execute([$jobId]);
                $job['status'] = 'processing';
            }
        }
    }

    json_success([
        'status' => $job['status'],
        'image_url' => $job['image_url'] ?? null,
        'error_message' => $job['error_message'] ?? null,
    ]);
} catch (\Throwable $e) {
    error_log('api/labs/status: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An error occurred.', 500);
}
