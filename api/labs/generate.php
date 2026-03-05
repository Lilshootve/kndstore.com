<?php
/**
 * KND Labs - ComfyUI generate
 * POST /api/labs/generate.php
 * tool, prompt, negative_prompt, seed, width, height, steps, cfg
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

    $seed = !empty($_POST['seed']) ? (int) $_POST['seed'] : random_int(0, 2147483647);
    $steps = (int) ($_POST['steps'] ?? 20);
    $steps = max(10, min(50, $steps));
    $cfg = (float) ($_POST['cfg'] ?? 7.5);
    $cfg = max(1, min(20, $cfg));
    $width = (int) ($_POST['width'] ?? 1024);
    $height = (int) ($_POST['height'] ?? 1024);

    $model = trim($_POST['model'] ?? 'dreamshaper');
    $allowedModels = ['juggernaut', 'realvis', 'dreamshaper'];
    if (!in_array($model, $allowedModels, true)) $model = 'dreamshaper';
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

    $userId = current_user_id();
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
    ];

    $imageFilename = null;
    if ($tool === 'upscale') {
        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            json_error('INVALID_IMAGE', 'Image upload required for upscale.');
        }
        $tmpPath = $_FILES['image']['tmp_name'];
        $imageFilename = comfyui_upload_image($tmpPath);
        $params['image_filename'] = $imageFilename;
    }

    $workflow = comfyui_inject_workflow($params, $tool);
    comfyui_apply_checkpoint($workflow, $model, $refinerEnabled);
    $result = comfyui_run_prompt($workflow);
    $promptId = $result['prompt_id'];

    $qualityCol = ($tool === 'text2img') ? $quality : (($tool === 'upscale') ? (string) $scale . 'x' : 'base');
    $stmt = $pdo->prepare(
        "INSERT INTO knd_labs_jobs (user_id, tool, prompt, negative_prompt, comfy_prompt_id, status, cost_kp, quality)
         VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)"
    );
    if (!$stmt || !$stmt->execute([$userId, $tool, $prompt, $negativePrompt, $promptId, $costKp, $qualityCol])) {
        json_error('DB_ERROR', 'Could not create job. Run sql/knd_labs_jobs_alter_cost.sql if needed.', 500);
    }
    $jobId = (int) $pdo->lastInsertId();

    ai_spend_points($pdo, $userId, $jobId, $costKp);

    json_success([
        'job_id' => (string) $jobId,
        'prompt_id' => $promptId,
        'cost' => $costKp,
        'available_after' => get_available_points($pdo, $userId),
    ]);
} catch (\Throwable $e) {
    error_log('api/labs/generate: ' . $e->getMessage());
    $code = (strpos($e->getMessage(), 'ComfyUI') !== false || strpos($e->getMessage(), 'invalid') !== false) ? 'COMFYUI_ERROR' : 'INTERNAL_ERROR';
    $status = ($code === 'COMFYUI_ERROR') ? 400 : 500;
    json_error($code, $e->getMessage(), $status);
}
