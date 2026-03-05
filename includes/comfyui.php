<?php
/**
 * KND Labs - ComfyUI workflow injection and API helpers
 */

/**
 * Upload image to ComfyUI and return filename.
 */
function comfyui_upload_image(string $filePath): string {
    if (!file_exists(dirname(__DIR__) . '/config/comfyui.php')) {
        throw new \RuntimeException('ComfyUI config not found');
    }
    require_once dirname(__DIR__) . '/config/comfyui.php';
    $base = rtrim(COMFYUI_BASE_URL, '/');
    $url = $base . '/upload/image';
    $cfile = new \CURLFile($filePath, mime_content_type($filePath) ?: 'image/png', basename($filePath));
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['image' => $cfile],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
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
 * Load master workflow and inject parameters.
 * @param array $params prompt, negative_prompt, seed, steps, cfg, width, height, image_filename (for upscale)
 * @param string $tool text2img|upscale|character
 * @return array workflow for ComfyUI API
 */
function comfyui_inject_workflow(array $params, string $tool = 'text2img'): array {
    $baseDir = dirname(__DIR__);
    if ($tool === 'upscale') {
        $path = $baseDir . '/KND_MASTER_WORKFLOW_UPSCALE.json';
    } else {
        $path = $baseDir . '/KND_MASTER_WORKFLOW_API.json';
    }
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
    $cfg = (float) ($params['cfg'] ?? 7.5);
    $width = (int) ($params['width'] ?? 1024);
    $height = (int) ($params['height'] ?? 1024);
    $width = max(512, min(2048, $width - ($width % 8)));
    $height = max(512, min(2048, $height - ($height % 8)));

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
        }
        if ($ctype === 'EmptyLatentImage') {
            $inputs['width'] = $width;
            $inputs['height'] = $height;
        }
        if ($ctype === 'LoadImage' && !empty($params['image_filename'])) {
            $inputs['image'] = $params['image_filename'];
        }
    }
    return $wf;
}

/**
 * Send prompt to ComfyUI.
 * @return array ['prompt_id' => string] or throw
 */
function comfyui_run_prompt(array $workflow): array {
    if (!file_exists(dirname(__DIR__) . '/config/comfyui.php')) {
        throw new \RuntimeException('ComfyUI config not found');
    }
    require_once __DIR__ . '/../config/comfyui.php';

    $base = rtrim(COMFYUI_BASE_URL, '/');
    $url = $base . '/prompt';
    $payload = [
        'prompt' => $workflow,
        'client_id' => COMFYUI_CLIENT_ID,
    ];
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => (int) (COMFYUI_TIMEOUT ?? 30),
    ]);
    $body = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($err) {
        throw new \RuntimeException('ComfyUI unreachable: ' . $err);
    }
    $data = json_decode($body, true);
    if (!is_array($data) || empty($data['prompt_id'])) {
        $msg = is_array($data) && isset($data['error']) ? $data['error'] : ($body ?: 'Invalid response');
        throw new \RuntimeException('ComfyUI error: ' . (is_string($msg) ? $msg : json_encode($msg)));
    }
    return ['prompt_id' => $data['prompt_id']];
}

/**
 * Get ComfyUI history for a prompt.
 */
function comfyui_get_history(string $promptId): ?array {
    if (!file_exists(__DIR__ . '/../config/comfyui.php')) return null;
    require_once __DIR__ . '/../config/comfyui.php';
    $base = rtrim(COMFYUI_BASE_URL, '/');
    $url = $base . '/history/' . urlencode($promptId);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
    if (!$body) return null;
    $data = json_decode($body, true);
    return is_array($data) && isset($data[$promptId]) ? $data[$promptId] : null;
}

/**
 * Get recent ComfyUI jobs for user.
 */
function comfyui_get_user_jobs(PDO $pdo, int $userId, int $limit = 20): array {
    $limit = max(1, min(50, (int) $limit));
    $stmt = $pdo->prepare(
        "SELECT id, tool, prompt, status, image_url, created_at FROM knd_labs_jobs WHERE user_id = ? ORDER BY created_at DESC LIMIT {$limit}"
    );
    if (!$stmt || !$stmt->execute([$userId])) return [];
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
