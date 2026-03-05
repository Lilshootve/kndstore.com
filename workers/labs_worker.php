#!/usr/bin/env php
<?php
/**
 * KND Labs HTTP Worker - No MySQL needed. Uses API lease/complete/fail.
 * Usage:
 *   php workers/labs_worker.php              (single run: process 1 job)
 *   php workers/labs_worker.php --loop --sleep=2 --worker-id=PC1
 */
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
chdir($projectRoot);

require_once $projectRoot . '/includes/comfyui.php';
require_once $projectRoot . '/includes/storage.php';
if (file_exists($projectRoot . '/config/labs.php')) {
    require_once $projectRoot . '/config/labs.php';
}

$opts = getopt('', ['loop', 'sleep:', 'worker-id:']);
$loop = isset($opts['loop']);
$sleepSec = isset($opts['sleep']) ? max(1, (int) $opts['sleep']) : 2;
$workerId = $opts['worker-id'] ?? 'http-' . gethostname();

$cfg = load_worker_config();

function load_worker_config(): array {
    $path = __DIR__ . '/worker_config.local.php';
    if (is_readable($path)) {
        $c = (array) include $path;
        if (isset($c['COMFY_URL']) && !isset($c['COMFYUI_BASE'])) {
            $c['COMFYUI_BASE'] = $c['COMFY_URL'];
        }
        return $c;
    }
    return [
        'API_BASE'       => getenv('KND_API_BASE') ?: 'https://kndstore.com',
        'WORKER_TOKEN'   => getenv('KND_WORKER_TOKEN') ?: '',
        'COMFYUI_BASE'   => getenv('COMFYUI_BASE_URL') ?: 'https://comfy.kndstore.com',
        'COMFYUI_TOKEN'  => getenv('COMFYUI_TOKEN') ?: '',
        'COMFY_INPUT_DIR'  => getenv('COMFY_INPUT_DIR') ?: '',
        'COMFY_OUTPUT_DIR' => getenv('COMFY_OUTPUT_DIR') ?: '',
    ];
}

function logWorker(string $msg): void {
    $ts = date('Y-m-d H:i:s');
    echo "[$ts] $msg\n";
}

function httpPost(string $url, array $headers, array $post = []): array {
    $ch = curl_init($url);
    $allHeaders = ['Content-Type: application/x-www-form-urlencoded'];
    foreach ($headers as $h) $allHeaders[] = $h;
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($post),
        CURLOPT_HTTPHEADER => $allHeaders,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        return ['ok' => false, 'error' => $err];
    }
    $data = json_decode($body, true);
    return is_array($data) ? $data : ['ok' => false, 'error' => 'Invalid JSON'];
}

function workerDownloadFile(string $url, array $headers): ?string {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($body !== false && $code >= 200 && $code < 300) ? $body : null;
}

function workerUploadToComfyui(string $filePath, string $baseUrl, string $token = ''): string {
    $mime = mime_content_type($filePath) ?: 'image/png';
    $cfile = new \CURLFile($filePath, $mime, basename($filePath));
    $url = rtrim($baseUrl, '/') . '/upload/image';
    $headers = ['Content-Type: multipart/form-data'];
    if ($token !== '') $headers[] = 'X-KND-TOKEN: ' . $token;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['image' => $cfile],
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
    ]);
    $body = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) throw new \RuntimeException('ComfyUI upload failed: ' . $err);
    $data = json_decode($body, true);
    if (empty($data['name'])) throw new \RuntimeException('ComfyUI upload: no filename');
    return $data['name'];
}

function comfyui_get_history_standalone(string $promptId, string $baseUrl, string $token = ''): ?array {
    $base = rtrim($baseUrl, '/');
    $url = $base . '/history/' . urlencode($promptId);
    $headers = ['Accept: application/json'];
    if ($token !== '') $headers[] = 'X-KND-TOKEN: ' . $token;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $body = curl_exec($ch);
    $err = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($err) {
        logWorker("ComfyUI history curl error: $err (HTTP $code)");
        return null;
    }
    if (!$body) return null;
    $data = json_decode($body, true);
    $hist = is_array($data) && isset($data[$promptId]) ? $data[$promptId] : null;
    return $hist;
}

if (empty($cfg['WORKER_TOKEN']) || empty($cfg['API_BASE'])) {
    logWorker('ERROR: Set API_BASE and WORKER_TOKEN in workers/worker_config.local.php');
    exit(1);
}

$apiBase = rtrim($cfg['API_BASE'], '/');
$workerToken = $cfg['WORKER_TOKEN'];
// Prefer COMFYUI_LOCAL cuando el worker corre en el mismo PC que ComfyUI (evita túnel)
$comfyBase = rtrim($cfg['COMFYUI_LOCAL'] ?? $cfg['COMFYUI_BASE'] ?? $cfg['COMFY_URL'] ?? 'https://comfy.kndstore.com', '/');
$comfyToken = $cfg['COMFYUI_TOKEN'] ?? '';
$comfyOutputDir = $cfg['COMFY_OUTPUT_DIR'] ?? (defined('COMFY_OUTPUT_DIR') ? COMFY_OUTPUT_DIR : '');
$headers = ['X-KND-WORKER-TOKEN: ' . $workerToken];

do {
    $resp = httpPost($apiBase . '/api/labs/queue/lease.php', $headers, ['worker_id' => $workerId]);
    if (!$resp['ok']) {
        logWorker('lease failed: ' . ($resp['error'] ?? 'unknown'));
        if ($loop) sleep($sleepSec);
        continue;
    }
    $job = $resp['job'] ?? null;
    if (!$job) {
        if ($loop) {
            logWorker('No jobs in queue');
            sleep($sleepSec);
            continue;
        }
        exit(0);
    }

    $jobId = (int) $job['id'];
    $payload = $job['payload'] ?? [];
    $tool = $payload['tool'] ?? 'text2img';
    $attempts = (int) ($job['attempts'] ?? 1);

    if (empty($payload)) {
        httpPost($apiBase . '/api/labs/queue/fail.php', $headers, [
            'job_id' => $jobId,
            'error_message' => 'Invalid payload',
        ]);
        logWorker("Job $jobId: invalid payload");
        if (!$loop) break;
        continue;
    }

    $model = $payload['model'] ?? 'v1_5';
    $refinerEnabled = !empty($payload['refiner_enabled']);
    $overrideCkpt = $payload['override_ckpt'] ?? null;

    if ($tool === 'upscale' && !empty($payload['image_url'])) {
        $imgData = workerDownloadFile($payload['image_url'], $headers);
        if (!$imgData) {
            httpPost($apiBase . '/api/labs/queue/fail.php', $headers, [
                'job_id' => $jobId,
                'error_message' => 'Could not download source image',
                'no_retry' => '1',
            ]);
            logWorker("Job $jobId: download failed");
            if (!$loop) break;
            sleep($sleepSec);
            continue;
        }
        $tmpFile = tempnam(sys_get_temp_dir(), 'knd_up');
        if (!$tmpFile || file_put_contents($tmpFile, $imgData) === false) {
            if ($tmpFile) @unlink($tmpFile);
            httpPost($apiBase . '/api/labs/queue/fail.php', $headers, [
                'job_id' => $jobId,
                'error_message' => 'Could not save temp image',
                'no_retry' => '1',
            ]);
            logWorker("Job $jobId: temp save failed");
            if (!$loop) break;
            sleep($sleepSec);
            continue;
        }
        try {
            $payload['image_filename'] = workerUploadToComfyui($tmpFile, $comfyBase, $comfyToken);
        } finally {
            @unlink($tmpFile);
        }
        $payload['job_id'] = $jobId;
    }
    if ($tool === 'upscale' && !isset($payload['job_id'])) {
        $payload['job_id'] = $jobId;
    }

    try {
        $workflow = comfyui_inject_workflow($payload, $tool);
        if ($tool !== 'upscale') {
            comfyui_apply_checkpoint($workflow, $model, $refinerEnabled, $overrideCkpt);
        }
        $result = comfyui_run_prompt($workflow, $comfyBase, $comfyToken);
        $promptId = $result['prompt_id'];
    } catch (\Throwable $e) {
        $msg = $e->getMessage();
        logWorker("Job $jobId ComfyUI send failed: " . $msg);
        $failData = ['job_id' => $jobId, 'error_message' => $msg];
        $isValidation = (stripos($msg, 'prompt_outputs_failed_validation') !== false || stripos($msg, '400') !== false || stripos($msg, 'Value not in list') !== false);
        if ($isValidation) {
            $failData['no_retry'] = '1';
        }
        httpPost($apiBase . '/api/labs/queue/fail.php', $headers, $failData);
        if (!$loop) break;
        sleep($sleepSec);
        continue;
    }

    logWorker("Job $jobId: ComfyUI prompt_id=$promptId, polling $comfyBase/history/...");
    $maxPoll = 120;
    $pollInterval = 1;
    $imageUrl = null;
    $outputFilename = null;
    $outputSubfolder = '';
    $pollError = null;
    $lastHistDebug = null;
    for ($i = 0; $i < $maxPoll; $i++) {
        sleep($pollInterval);
        $hist = comfyui_get_history_standalone($promptId, $comfyBase, $comfyToken);
        if (!$hist) continue;
        if (isset($hist['status']['status_str']) && $hist['status']['status_str'] === 'error') {
            $err = $hist['status']['messages'] ?? 'ComfyUI error';
            $pollError = is_string($err) ? $err : json_encode($err);
            break;
        }
        $outputs = $hist['outputs'] ?? [];
        if (!is_array($outputs)) $outputs = [];
        foreach ($outputs as $nodeOut) {
            if (!is_array($nodeOut)) continue;
            foreach (['images', 'gifs'] as $key) {
                if (!isset($nodeOut[$key]) || !is_array($nodeOut[$key])) continue;
                foreach ($nodeOut[$key] as $img) {
                    if (!empty($img['filename'])) {
                        $outputFilename = $img['filename'];
                        $outputSubfolder = $img['subfolder'] ?? '';
                        $imageUrl = '/api/labs/image.php?job_id=' . $jobId;
                        break 4;
                    }
                }
            }
        }
        if ($imageUrl !== null) break;
        $lastHistDebug = isset($hist['outputs']) ? 'outputs=' . json_encode($hist['outputs'], JSON_UNESCAPED_UNICODE) : 'no outputs';
    }
    if ($imageUrl === null && $lastHistDebug !== null && !$pollError) {
        $preview = strlen($lastHistDebug) > 600 ? substr($lastHistDebug, 0, 600) . '...' : $lastHistDebug;
        logWorker("Job $jobId: history debug: $preview");
    }

    $outputPathRel = null;
    if ($tool === 'upscale' && $comfyOutputDir !== '' && $outputFilename !== null) {
        $comfyOut = rtrim($comfyOutputDir, '/\\');
        $srcPath = $outputSubfolder !== '' ? $comfyOut . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $outputSubfolder) . DIRECTORY_SEPARATOR . $outputFilename : $comfyOut . DIRECTORY_SEPARATOR . $outputFilename;
        $labsDir = defined('LABS_UPLOAD_DIR') ? LABS_UPLOAD_DIR : 'uploads/labs';
        $destRel = $labsDir . '/' . $jobId . '_upscaled.png';
        $destFull = storage_path($destRel);
        $destDir = dirname($destFull);
        if (is_file($srcPath) && is_readable($srcPath)) {
            if (!is_dir($destDir)) @mkdir($destDir, 0755, true);
            if (is_dir($destDir) && @copy($srcPath, $destFull)) {
                $outputPathRel = $destRel;
                logWorker("Job $jobId: copied output to $destRel");
            }
        } else {
            logWorker("Job $jobId: output file not found at $srcPath, using proxy");
        }
    }

    if ($pollError) {
        httpPost($apiBase . '/api/labs/queue/fail.php', $headers, [
            'job_id' => $jobId,
            'error_message' => $pollError,
        ]);
        logWorker("Job $jobId failed: $pollError");
    } elseif ($imageUrl) {
        $postData = [
            'job_id' => $jobId,
            'comfy_prompt_id' => $promptId,
            'image_url' => $imageUrl,
        ];
        if ($outputPathRel !== null) $postData['output_path'] = $outputPathRel;
        $r = httpPost($apiBase . '/api/labs/queue/complete.php', $headers, $postData);
        if ($r['ok']) {
            logWorker("Job $jobId: done");
        } else {
            logWorker("Job $jobId complete failed: " . ($r['error'] ?? ''));
        }
    } elseif (!$pollError) {
        $errMsg = 'ComfyUI timeout: no image after ' . ($maxPoll * $pollInterval) . 's';
        httpPost($apiBase . '/api/labs/queue/fail.php', $headers, [
            'job_id' => $jobId,
            'error_message' => $errMsg,
        ]);
        logWorker("Job $jobId: $errMsg");
    }

    if (!$loop) break;
    sleep($sleepSec);

} while ($loop);
