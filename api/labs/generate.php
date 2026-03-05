<?php
/**
 * KND Labs - Queue job (instant response)
 * POST /api/labs/generate.php
 * Validates, charges KP, creates job status=queued. Worker processes it.
 */
header('Cache-Control: no-store, no-cache');
header('Content-Type: application/json');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/support_credits.php';
require_once __DIR__ . '/../../includes/ai.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/comfyui.php';
require_once __DIR__ . '/../../includes/comfyui_provider.php';
require_once __DIR__ . '/../../includes/settings.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
    }

    api_require_login();

    $pdo = getDBConnection();
    if (!$pdo) {
        json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);
    }

    $tool = trim($_POST['tool'] ?? '');
    $allowed = ['text2img', 'upscale', 'character'];
    if (!in_array($tool, $allowed, true)) {
        json_error('INVALID_TOOL', 'tool must be one of: ' . implode(', ', $allowed));
    }

    $prompt = trim($_POST['prompt'] ?? '');
    if (strlen($prompt) < 1 && $tool !== 'upscale') {
        json_error('INVALID_INPUT', 'prompt is required.');
    }
    if (strlen($prompt) > 500) {
        json_error('INVALID_INPUT', 'Prompt max 500 characters.');
    }

    $negativePrompt = trim($_POST['negative_prompt'] ?? 'ugly, blurry, low quality');
    if (strlen($negativePrompt) > 500) $negativePrompt = substr($negativePrompt, 0, 500);

    $userId = current_user_id();

    // Rate limit: max 2 active (queued + processing) per user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM knd_labs_jobs WHERE user_id = ? AND status IN ('queued','processing')");
    if ($stmt && $stmt->execute([$userId])) {
        $active = (int) $stmt->fetchColumn();
        if ($active >= 2) {
            json_error('RATE_LIMIT', 'Too many active jobs. Wait for current ones to finish.', 429);
        }
    }

    $seed = !empty($_POST['seed']) ? (int) $_POST['seed'] : random_int(0, 2147483647);
    $steps = (int) ($_POST['steps'] ?? 30);
    $steps = max(1, min(100, $steps));
    $cfg = (float) ($_POST['cfg'] ?? 6);
    $cfg = max(1, min(30, $cfg));
    $width = (int) ($_POST['width'] ?? 512);
    $height = (int) ($_POST['height'] ?? 512);

    $model = trim($_POST['model'] ?? 'v1_5');
    $modelKeys = array_keys(COMFYUI_CHECKPOINT_MAP);
    if (!in_array($model, $modelKeys, true) && substr(strtolower($model), -13) !== '.safetensors') {
        $model = 'v1_5';
    }
    $refinerEnabled = !empty($_POST['refiner_enabled']);

    $quality = trim($_POST['quality'] ?? 'standard');
    if (!in_array($quality, ['standard', 'high'], true)) $quality = 'standard';
    $scale = (int) ($_POST['scale'] ?? 2);
    if (!in_array($scale, [2, 4], true)) $scale = 2;

    $costKp = 0;
    if ($tool === 'text2img') {
        $costKp = $quality === 'high' ? 6 : 3;
    } elseif ($tool === 'upscale') {
        $costKp = $scale === 4 ? 8 : 5;
    } else {
        $costKp = 15;
    }

    release_available_points_if_due($pdo, $userId);
    expire_points_if_due($pdo, $userId);
    $available = get_available_points($pdo, $userId);
    if ($available < $costKp) {
        json_error('INSUFFICIENT_POINTS', 'Insufficient KP. You have ' . $available . ', need ' . $costKp . '.', 400);
    }

    $params = [
        'prompt' => $prompt ?: 'high quality image',
        'negative_prompt' => $negativePrompt,
        'seed' => $seed,
        'steps' => $steps,
        'cfg' => $cfg,
        'width' => $width,
        'height' => $height,
        'batch_size' => max(1, min(4, (int) ($_POST['batch_size'] ?? 1))),
        'sampler_name' => trim($_POST['sampler_name'] ?? 'dpmpp_2m'),
        'scheduler' => trim($_POST['scheduler'] ?? 'karras'),
        'denoise' => isset($_POST['denoise']) ? (float) $_POST['denoise'] : 1.0,
    ];

    $baseUrl = comfyui_get_base_url($pdo, null);
    $token = comfyui_get_token($pdo);
    $runpodUrl = comfyui_get_base_url_runpod($pdo);
    $providerUsed = ($runpodUrl !== '' && rtrim($baseUrl, '/') === rtrim($runpodUrl, '/')) ? 'runpod' : 'local';

    $imageFilename = null;
    if ($tool === 'upscale') {
        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            json_error('INVALID_IMAGE', 'Image upload required for upscale.');
        }
        $tmpPath = $_FILES['image']['tmp_name'];
        $imageFilename = comfyui_upload_image($tmpPath, $baseUrl, $token);
        $params['image_filename'] = $imageFilename;
    }

    $overrideCkpt = settings_get($pdo, 'comfyui_default_ckpt', '');
    $overrideCkpt = trim($overrideCkpt) !== '' ? trim($overrideCkpt) : null;

    $payload = array_merge($params, [
        'tool' => $tool,
        'model' => $model,
        'refiner_enabled' => $refinerEnabled,
        'override_ckpt' => $overrideCkpt,
    ]);
    $payloadJson = json_encode($payload);

    $qualityCol = ($tool === 'text2img') ? $quality : (($tool === 'upscale') ? (string) $scale . 'x' : 'base');
    $priority = 100;

    $stmt = $pdo->prepare(
        "INSERT INTO knd_labs_jobs (user_id, tool, prompt, negative_prompt, status, cost_kp, quality, provider, priority, payload_json)
         VALUES (?, ?, ?, ?, 'queued', ?, ?, ?, ?, ?)"
    );
    if (!$stmt || !$stmt->execute([$userId, $tool, $prompt, $negativePrompt, $costKp, $qualityCol, $providerUsed, $priority, $payloadJson])) {
        json_error('DB_ERROR', 'Could not create job. Run sql/knd_labs_jobs_alter_queue.sql if needed.', 500);
    }
    $jobId = (int) $pdo->lastInsertId();

    ai_spend_points($pdo, $userId, $jobId, $costKp);

    $avgSeconds = ['text2img' => 60, 'upscale' => 40, 'character' => 45];
    $avgSec = $avgSeconds[$tool] ?? 60;

    $stmtQ = $pdo->query("SELECT COUNT(*) FROM knd_labs_jobs WHERE status = 'queued'");
    $queuePosition = $stmtQ ? (int) $stmtQ->fetchColumn() : 1;
    $etaSeconds = $queuePosition * $avgSec;

    json_success([
        'job_id' => (string) $jobId,
        'status' => 'queued',
        'provider_used' => $providerUsed,
        'queue_position' => $queuePosition,
        'eta_seconds' => $etaSeconds,
        'cost' => $costKp,
        'available_after' => get_available_points($pdo, $userId),
    ]);
} catch (\Throwable $e) {
    error_log('api/labs/generate: ' . $e->getMessage());
    $code = (strpos($e->getMessage(), 'ComfyUI') !== false || strpos($e->getMessage(), 'invalid') !== false) ? 'COMFYUI_ERROR' : 'INTERNAL_ERROR';
    $status = ($code === 'COMFYUI_ERROR') ? 400 : 500;
    if (strpos($e->getMessage(), 'RATE_LIMIT') !== false) $status = 429;
    json_error($code, $e->getMessage(), $status);
}
