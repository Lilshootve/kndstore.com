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

$opts = getopt('', ['loop', 'sleep:', 'worker-id:']);
$loop = isset($opts['loop']);
$sleepSec = isset($opts['sleep']) ? max(1, (int) $opts['sleep']) : 2;
$workerId = $opts['worker-id'] ?? 'http-' . gethostname();

$cfg = load_worker_config();

function load_worker_config(): array {
    $path = __DIR__ . '/worker_config.local.php';
    if (is_readable($path)) {
        return (array) include $path;
    }
    return [
        'API_BASE'      => getenv('KND_API_BASE') ?: 'https://kndstore.com',
        'WORKER_TOKEN'  => getenv('KND_WORKER_TOKEN') ?: '',
        'COMFYUI_BASE'  => getenv('COMFYUI_BASE_URL') ?: 'https://comfy.kndstore.com',
        'COMFYUI_TOKEN' => getenv('COMFYUI_TOKEN') ?: '',
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

function comfyui_get_history_standalone(string $promptId, string $baseUrl, string $token = ''): ?array {
    $base = rtrim($baseUrl, '/');
    $url = $base . '/history/' . urlencode($promptId);
    $headers = ['Accept: application/json'];
    if ($token !== '') $headers[] = 'X-KND-TOKEN: ' . $token;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPGET => true,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
    if (!$body) return null;
    $data = json_decode($body, true);
    return is_array($data) && isset($data[$promptId]) ? $data[$promptId] : null;
}

if (empty($cfg['WORKER_TOKEN']) || empty($cfg['API_BASE'])) {
    logWorker('ERROR: Set API_BASE and WORKER_TOKEN in workers/worker_config.local.php');
    exit(1);
}

$apiBase = rtrim($cfg['API_BASE'], '/');
$workerToken = $cfg['WORKER_TOKEN'];
$comfyBase = rtrim($cfg['COMFYUI_BASE'] ?? 'https://comfy.kndstore.com', '/');
$comfyToken = $cfg['COMFYUI_TOKEN'] ?? '';
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

    try {
        $workflow = comfyui_inject_workflow($payload, $tool);
        if ($tool !== 'upscale') {
            comfyui_apply_checkpoint($workflow, $model, $refinerEnabled, $overrideCkpt);
        }
        $result = comfyui_run_prompt($workflow, $comfyBase, $comfyToken);
        $promptId = $result['prompt_id'];
    } catch (\Throwable $e) {
        logWorker("Job $jobId ComfyUI send failed: " . $e->getMessage());
        httpPost($apiBase . '/api/labs/queue/fail.php', $headers, [
            'job_id' => $jobId,
            'error_message' => $e->getMessage(),
        ]);
        if (!$loop) break;
        sleep($sleepSec);
        continue;
    }

    logWorker("Job $jobId: ComfyUI prompt_id=$promptId, polling...");
    $maxPoll = 120;
    $pollInterval = 2;
    $imageUrl = null;
    $pollError = null;
    for ($i = 0; $i < $maxPoll; $i++) {
        sleep($pollInterval);
        $hist = comfyui_get_history_standalone($promptId, $comfyBase, $comfyToken);
        if (!$hist) continue;
        if (isset($hist['status']['status_str']) && $hist['status']['status_str'] === 'error') {
            $err = $hist['status']['messages'] ?? 'ComfyUI error';
            $pollError = is_string($err) ? $err : json_encode($err);
            break;
        }
        if (isset($hist['outputs']) && is_array($hist['outputs'])) {
            foreach ($hist['outputs'] as $nodeOut) {
                if (isset($nodeOut['images']) && is_array($nodeOut['images'])) {
                    foreach ($nodeOut['images'] as $img) {
                        if (!empty($img['filename'])) {
                            $imageUrl = '/api/labs/image.php?job_id=' . $jobId;
                            break 3;
                        }
                    }
                }
            }
        }
    }

    if ($pollError) {
        httpPost($apiBase . '/api/labs/queue/fail.php', $headers, [
            'job_id' => $jobId,
            'error_message' => $pollError,
        ]);
        logWorker("Job $jobId failed: $pollError");
    } elseif ($imageUrl) {
        $r = httpPost($apiBase . '/api/labs/queue/complete.php', $headers, [
            'job_id' => $jobId,
            'comfy_prompt_id' => $promptId,
            'image_url' => $imageUrl,
        ]);
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
