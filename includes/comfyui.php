<?php
/**
 * KND Labs - ComfyUI workflow injection and API helpers
 */

/**
 * Upload image to ComfyUI and return filename.
 * @param string $filePath Path to image file
 * @param string|null $baseUrl Override base URL (from provider)
 * @param string $token Optional X-KND-TOKEN for auth
 */
function comfyui_upload_image(string $filePath, ?string $baseUrl = null, string $token = ''): string {
    if ($baseUrl === null && file_exists(dirname(__DIR__) . '/config/comfyui.php')) {
        require_once dirname(__DIR__) . '/config/comfyui.php';
        $baseUrl = defined('COMFYUI_BASE_URL') ? rtrim(COMFYUI_BASE_URL, '/') : '';
    }
    $base = $baseUrl ? rtrim($baseUrl, '/') : '';
    if ($base === '') throw new \RuntimeException('ComfyUI config not found');

    $url = $base . '/upload/image';
    $cfile = new \CURLFile($filePath, mime_content_type($filePath) ?: 'image/png', basename($filePath));
    $headers = [];
    if ($token !== '') $headers[] = 'X-KND-TOKEN: ' . $token;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['image' => $cfile],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $body = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) throw new \RuntimeException('ComfyUI upload failed: ' . $err);
    $data = json_decode($body, true);
    if (empty($data['name'])) {
        throw new \RuntimeException('ComfyUI upload: no filename returned');
    }
    return $data['name'];
}

/**
 * Load workflow file path by tool.
 */
function comfyui_workflow_path(string $tool): string {
    $baseDir = defined('WORKFLOWS_DIR') ? rtrim(WORKFLOWS_DIR, '/\\') : (dirname(__DIR__) . '/workflows');
    if ($tool === 'upscale') {
        $path = $baseDir . '/upscale_api.json';
        if (!is_readable($path)) $path = dirname(__DIR__) . '/KND_MASTER_WORKFLOW_UPSCALE.json';
    } elseif ($tool === 'text2img' || $tool === 'character') {
        $path = $baseDir . '/knd-workflow-api.json';
        if (!is_readable($path)) $path = $baseDir . '/text2img_api.json';
        if (!is_readable($path)) $path = dirname(__DIR__) . '/KND_MASTER_WORKFLOW_API.json';
    } else {
        $path = dirname(__DIR__) . '/KND_MASTER_WORKFLOW_API.json';
    }
    return $path;
}

/**
 * Load master workflow and inject parameters.
 * @param array $params prompt, negative_prompt, seed, steps, cfg, width, height, image_filename (for upscale), scale, job_id (for upscale)
 * @param string $tool text2img|upscale|character
 * @return array workflow for ComfyUI API
 */
function comfyui_inject_workflow(array $params, string $tool = 'text2img'): array {
    $path = comfyui_workflow_path($tool);
    if (!is_readable($path)) {
        throw new \RuntimeException('Workflow file not found: ' . basename($path));
    }
    $wf = json_decode(file_get_contents($path), true);
    if (!is_array($wf)) {
        throw new \RuntimeException('Invalid workflow JSON');
    }
    $prompt = $params['prompt'] ?? '';
    $negative = $params['negative_prompt'] ?? 'ugly, blurry, low quality';
    $seed = isset($params['seed']) ? (int) $params['seed'] : random_int(0, 2147483647);
    $steps = (int) ($params['steps'] ?? 20);
    $steps = max(1, min(100, $steps));
    $cfg = (float) ($params['cfg'] ?? 7.5);
    $cfg = max(1, min(30, $cfg));
    $width = (int) ($params['width'] ?? 1024);
    $height = (int) ($params['height'] ?? 1024);
    $width = max(256, min(2048, $width - ($width % 8)));
    $height = max(256, min(2048, $height - ($height % 8)));
    $batchSize = isset($params['batch_size']) ? max(1, min(4, (int) $params['batch_size'])) : 1;
    $samplerName = $params['sampler_name'] ?? 'euler';
    $scheduler = $params['scheduler'] ?? 'normal';
    $denoise = isset($params['denoise']) ? (float) $params['denoise'] : 1.0;
    $denoise = max(0.01, min(1.0, $denoise));

    $allowedSamplers = ['euler', 'euler_ancestral', 'heun', 'dpm_2', 'dpm_2_ancestral', 'lms', 'dpm_fast', 'dpm_adaptive', 'dpmpp_2s_ancestral', 'dpmpp_sde', 'dpmpp_2m', 'dpmpp_2m_sde', 'dpmpp_3m_sde', 'ddpm', 'lcm', 'ddim', 'uni_pc', 'uni_pc_bh2'];
    if (!in_array($samplerName, $allowedSamplers, true)) $samplerName = 'euler';
    $allowedSchedulers = ['normal', 'karras', 'exponential', 'sgm_uniform', 'simple'];
    if (!in_array($scheduler, $allowedSchedulers, true)) $scheduler = 'normal';

    $injectedPositive = false;
    foreach ($wf as $nid => $node) {
        if (!is_array($node) || empty($node['class_type'])) continue;
        $ctype = $node['class_type'];
        $inputs = &$wf[$nid]['inputs'];
        if (!is_array($inputs)) $inputs = [];

        if ($ctype === 'CLIPTextEncode' && isset($inputs['text'])) {
            if (!$injectedPositive) {
                $inputs['text'] = $prompt;
                $injectedPositive = true;
            } else {
                $inputs['text'] = $negative;
            }
        }
        if (in_array($ctype, ['KSampler', 'KSamplerAdvanced'], true)) {
            $inputs['seed'] = $seed;
            $inputs['steps'] = $steps;
            $inputs['cfg'] = $cfg;
            $inputs['denoise'] = $denoise;
            if (isset($inputs['sampler_name'])) $inputs['sampler_name'] = $samplerName;
            if (isset($inputs['scheduler'])) $inputs['scheduler'] = $scheduler;
        }
        if ($ctype === 'EmptyLatentImage') {
            $inputs['width'] = $width;
            $inputs['height'] = $height;
            $inputs['batch_size'] = $batchSize;
        }
        if ($ctype === 'LoadImage' && !empty($params['image_filename'])) {
            $inputs['image'] = $params['image_filename'];
        }
        if ($ctype === 'UpscaleModelLoader' && $tool === 'upscale') {
            $inputs['model_name'] = '4x-UltraSharp.pth';
        }
        if ($ctype === 'SaveImage' && $tool === 'upscale' && isset($params['job_id'])) {
            $inputs['filename_prefix'] = 'knd_upscale/job_' . (int) $params['job_id'];
        }
    }
    return $wf;
}

/**
 * Fetch image bytes from a completed labs job (for upscale source_type=recent).
 * @return string|null raw image bytes or null on failure
 */
function comfyui_fetch_job_image_bytes(PDO $pdo, int $jobId, int $userId): ?string {
    $stmt = $pdo->prepare("SELECT comfy_prompt_id, provider FROM knd_labs_jobs WHERE id = ? AND user_id = ? AND status = 'done' LIMIT 1");
    if (!$stmt || !$stmt->execute([$jobId, $userId])) return null;
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$job || empty($job['comfy_prompt_id'])) return null;
    require_once __DIR__ . '/comfyui_provider.php';
    $baseUrl = ($job['provider'] ?? '') === 'runpod'
        ? comfyui_get_base_url_runpod($pdo) : comfyui_get_base_url_local($pdo);
    if (!$baseUrl) $baseUrl = comfyui_get_base_url($pdo, null);
    $token = comfyui_get_token($pdo);
    $hist = comfyui_get_history($job['comfy_prompt_id'], $baseUrl, $token);
    if (!$hist || !isset($hist['outputs'])) return null;
    $filename = null;
    $subfolder = '';
    $imgType = 'output';
    foreach ($hist['outputs'] as $nodeOut) {
        if (isset($nodeOut['images']) && is_array($nodeOut['images'])) {
            foreach ($nodeOut['images'] as $img) {
                if (!empty($img['filename'])) {
                    $filename = $img['filename'];
                    $subfolder = $img['subfolder'] ?? '';
                    $imgType = $img['type'] ?? 'output';
                    break 2;
                }
            }
        }
    }
    if (!$filename) return null;
    $params = ['filename' => $filename, 'type' => $imgType];
    if ($subfolder !== '') $params['subfolder'] = $subfolder;
    $url = rtrim($baseUrl, '/') . '/view?' . http_build_query($params);
    $headers = ['Accept: image/*'];
    if ($token !== '') $headers[] = 'X-KND-TOKEN: ' . $token;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $bin = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($bin !== false && $code >= 200 && $code < 300) ? $bin : null;
}

/** Checkpoint models available in ComfyUI */
const COMFYUI_CHECKPOINT_MAP = [
    'cyberrealistic_final' => 'cyberrealistic_final.safetensors',
    'flux_dev' => 'flux_dev.safetensors',
    'iniverseMixSFWNSFW' => 'iniverseMixSFWNSFW_ponyRealGuofengV51.safetensors',
    'juggernaut_ragnarok' => 'juggernautXL_ragnarokBy.safetensors',
    'juggernaut_v8' => 'juggernautXL_v8Rundiffusion.safetensors',
    'NSFW_master' => 'NSFW_master.safetensors',
    'pornmaster_asian' => 'pornmaster_asianSdxlV1VAE.safetensors',
    'realisticVision' => 'realisticVisionV60B1_v51HyperVAE.safetensors',
    'sd_xl_base' => 'sd_xl_base_1.0.safetensors',
    'sd_xl_refiner' => 'sd_xl_refiner_1.0.safetensors',
    'v1_5' => 'v1-5-pruned-emaonly.safetensors',
    'waiANINSFWPONY' => 'waiANINSFWPONYXL_v130.safetensors',
    'waiNSFW_v120' => 'waiNSFWIllustrious_v120.safetensors',
    'waiNSFW_v150' => 'waiNSFWIllustrious_v150.safetensors',
];
const COMFYUI_REFINER = 'sd_xl_refiner_1.0.safetensors';

/** If $model ends with .safetensors, use as ckpt directly; else lookup in map */
function comfyui_resolve_checkpoint(string $model, ?string $overrideCkpt): string {
    if ($overrideCkpt !== null && $overrideCkpt !== '') return $overrideCkpt;
    if (substr(strtolower($model), -13) === '.safetensors') return $model;
    return COMFYUI_CHECKPOINT_MAP[$model] ?? COMFYUI_CHECKPOINT_MAP['v1_5'];
}

/**
 * Override CheckpointLoaderSimple nodes with valid model.
 * @param array $workflow Workflow by reference
 * @param string $model Key from map or full .safetensors filename
 * @param bool $refinerEnabled Use refiner for second checkpoint node
 * @param string|null $overrideCkpt If set (from settings), use this checkpoint for base model
 */
function comfyui_apply_checkpoint(array &$workflow, string $model = 'v1_5', bool $refinerEnabled = false, ?string $overrideCkpt = null): void {
    $baseCkpt = comfyui_resolve_checkpoint($model, $overrideCkpt);
    $checkpointNodes = 0;
    foreach ($workflow as $nid => &$node) {
        if (!is_array($node) || ($node['class_type'] ?? '') !== 'CheckpointLoaderSimple') continue;
        $inputs = &$node['inputs'];
        if (!isset($inputs['ckpt_name'])) continue;
        $checkpointNodes++;
        if ($checkpointNodes === 1) {
            $inputs['ckpt_name'] = $baseCkpt;
        } elseif ($refinerEnabled) {
            $inputs['ckpt_name'] = COMFYUI_REFINER;
        } else {
            $inputs['ckpt_name'] = $baseCkpt;
        }
    }
}

/** Map legacy RealESRGAN models to 4x-UltraSharp (only model installed). */
const COMFYUI_UPSCALE_MODEL_MAP = [
    'RealESRGAN_x4plus.pth' => '4x-UltraSharp.pth',
    'RealESRGAN_x2plus.pth' => '4x-UltraSharp.pth',
];

/**
 * Strip _meta, normalize UpscaleModelLoader model_name (RealESRGAN -> 4x-UltraSharp).
 */
function comfyui_strip_meta(array $workflow): array {
    $out = [];
    foreach ($workflow as $nid => $node) {
        if (!is_array($node)) {
            $out[$nid] = $node;
            continue;
        }
        $ctype = $node['class_type'] ?? '';
        $inputs = $node['inputs'] ?? [];
        if ($ctype === 'UpscaleModelLoader' && isset($inputs['model_name'])) {
            $m = $inputs['model_name'];
            $inputs['model_name'] = COMFYUI_UPSCALE_MODEL_MAP[$m] ?? $m;
        }
        $out[$nid] = ['class_type' => $ctype, 'inputs' => $inputs];
    }
    return $out;
}

/**
 * Send prompt to ComfyUI.
 * POST {COMFY_BASE}/prompt with { "prompt": workflow_json, "client_id": "knd-labs" }
 * @param array $workflow Workflow JSON
 * @param string|null $baseUrl Override base URL (from provider)
 * @param string $token Optional X-KND-TOKEN for auth
 * @return array ['prompt_id' => string] or throw
 */
function comfyui_run_prompt(array $workflow, ?string $baseUrl = null, string $token = ''): array {
    if (!defined('COMFYUI_CLIENT_ID') && file_exists(dirname(__DIR__) . '/config/comfyui.php')) {
        require_once dirname(__DIR__) . '/config/comfyui.php';
    }
    if ($baseUrl === null) {
        $baseUrl = defined('COMFYUI_BASE_URL') ? COMFYUI_BASE_URL : '';
    }
    $base = $baseUrl ? rtrim($baseUrl, '/') : '';
    if ($base === '') throw new \RuntimeException('ComfyUI config not found');

    $url = $base . '/prompt';
    $workflowClean = comfyui_strip_meta($workflow);
    $payload = [
        'prompt' => $workflowClean,
        'client_id' => defined('COMFYUI_CLIENT_ID') ? COMFYUI_CLIENT_ID : 'knd-labs',
    ];
    $jsonBody = json_encode($payload);
    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if ($token !== '') $headers[] = 'X-KND-TOKEN: ' . $token;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, (int) (defined('COMFYUI_TIMEOUT') ? COMFYUI_TIMEOUT : 120));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    $body = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $bodyPreview = is_string($body) ? substr($body, 0, 500) : '';
    error_log('ComfyUI /prompt: HTTP ' . $code . ' | body: ' . $bodyPreview);

    if ($err) {
        throw new \RuntimeException('ComfyUI unreachable: ' . $err);
    }
    if ($code < 200 || $code >= 300) {
        $errMsg = 'ComfyUI HTTP ' . $code;
        $data = json_decode($body, true);
        if (is_array($data)) {
            if (!empty($data['message'])) {
                $errMsg = $data['message'];
                if (!empty($data['details'])) $errMsg .= ' | ' . (is_string($data['details']) ? $data['details'] : json_encode($data['details']));
            } elseif (!empty($data['error'])) $errMsg = is_string($data['error']) ? $data['error'] : json_encode($data['error']);
            elseif (!empty($data['node_errors'])) $errMsg = is_string($data['node_errors']) ? $data['node_errors'] : json_encode($data['node_errors']);
            else $errMsg .= ': ' . $bodyPreview;
        } else {
            $errMsg .= ': ' . $bodyPreview;
        }
        error_log('ComfyUI /prompt error: ' . $errMsg);
        throw new \RuntimeException($errMsg);
    }
    if (preg_match('/^\s*</', trim($body ?? ''))) {
        throw new \RuntimeException('ComfyUI returned HTML instead of JSON. Check COMFYUI_BASE_URL - wrong endpoint or base URL.');
    }
    $data = json_decode($body, true);
    if (!is_array($data) || empty($data['prompt_id'])) {
        $msg = 'Invalid response';
        if (is_array($data)) {
            if (isset($data['error'])) {
                $err = $data['error'];
                $msg = is_string($err) ? $err : (isset($err['message']) ? $err['message'] : json_encode($err));
            } elseif (isset($data['node_errors'])) {
                $msg = is_string($data['node_errors']) ? $data['node_errors'] : json_encode($data['node_errors']);
            }
        } elseif ($body) {
            $msg = substr($body, 0, 300);
        }
        error_log('ComfyUI validation error: ' . $msg);
        throw new \RuntimeException($msg);
    }
    return ['prompt_id' => $data['prompt_id']];
}

/**
 * Get ComfyUI history for a prompt.
 * GET {COMFY_BASE}/history/{prompt_id}
 * @param string $promptId ComfyUI prompt ID
 * @param string|null $baseUrl Override base URL (from provider)
 * @param string $token Optional X-KND-TOKEN for auth
 */
function comfyui_get_history(string $promptId, ?string $baseUrl = null, string $token = ''): ?array {
    if ($baseUrl === null && file_exists(dirname(__DIR__) . '/config/comfyui.php')) {
        require_once dirname(__DIR__) . '/config/comfyui.php';
        $baseUrl = defined('COMFYUI_BASE_URL') ? COMFYUI_BASE_URL : '';
    }
    $base = $baseUrl ? rtrim($baseUrl, '/') : '';
    if ($base === '') return null;

    $url = $base . '/history/' . urlencode($promptId);
    $headers = ['Accept: application/json'];
    if ($token !== '') $headers[] = 'X-KND-TOKEN: ' . $token;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $bodyPreview = is_string($body) ? substr($body, 0, 500) : '';
    error_log('ComfyUI /history/' . $promptId . ': HTTP ' . $code . ' | body: ' . $bodyPreview);
    if (!$body) return null;
    if (preg_match('/^\s*</', trim($body))) return null;
    $data = json_decode($body, true);
    return is_array($data) && isset($data[$promptId]) ? $data[$promptId] : null;
}

/**
 * Get recent ComfyUI jobs for user.
 */
function comfyui_get_user_jobs(PDO $pdo, int $userId, int $limit = 20): array {
    $limit = max(1, min(50, (int) $limit));
    $stmt = $pdo->prepare(
        "SELECT id, tool, prompt, status, image_url, cost_kp, provider, created_at FROM knd_labs_jobs WHERE user_id = ? ORDER BY created_at DESC LIMIT {$limit}"
    );
    if (!$stmt || !$stmt->execute([$userId])) return [];
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get user's labs_recent_private preference (0=public, 1=private).
 * Returns false (public) if column missing or error.
 */
function comfyui_user_prefers_private_recent(PDO $pdo, int $userId): bool {
    try {
        $stmt = $pdo->prepare("SELECT COALESCE(labs_recent_private, 0) FROM users WHERE id = ? LIMIT 1");
        if (!$stmt || !$stmt->execute([$userId])) return false;
        return (int) $stmt->fetchColumn() === 1;
    } catch (\Throwable $e) {
        return false;
    }
}

/**
 * Get recent ComfyUI jobs from all users (public catalog).
 * Only includes done jobs from users with labs_recent_private = 0.
 */
function comfyui_get_recent_jobs_public(PDO $pdo, int $limit = 24): array {
    try {
        $limit = max(1, min(50, (int) $limit));
        $stmt = $pdo->prepare(
            "SELECT j.id, j.tool, j.prompt, j.status, j.image_url, j.cost_kp, j.created_at, j.user_id
             FROM knd_labs_jobs j
             INNER JOIN users u ON j.user_id = u.id
             WHERE j.status = 'done' AND COALESCE(u.labs_recent_private, 0) = 0
             ORDER BY j.created_at DESC LIMIT {$limit}"
        );
        if (!$stmt || !$stmt->execute()) return [];
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
        return [];
    }
}
