#!/usr/bin/env php
<?php
/**
 * KND Labs Worker - Process queued jobs, send to ComfyUI
 * Usage:
 *   php workers/labs_worker.php              (single run: process 1 job)
 *   php workers/labs_worker.php --loop --sleep=2 --worker-id=PC1
 */
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
chdir($projectRoot);

require_once $projectRoot . '/includes/config.php';
require_once $projectRoot . '/includes/settings.php';
require_once $projectRoot . '/includes/comfyui.php';
require_once $projectRoot . '/includes/comfyui_provider.php';

$opts = getopt('', ['loop', 'sleep:', 'worker-id:']);
$loop = isset($opts['loop']);
$sleepSec = isset($opts['sleep']) ? max(1, (int) $opts['sleep']) : 2;
$workerId = $opts['worker-id'] ?? 'worker-' . gethostname();

function logWorker(string $msg): void {
    $ts = date('Y-m-d H:i:s');
    echo "[$ts] $msg\n";
}

$pdo = getDBConnection();
if (!$pdo) {
    logWorker('ERROR: No database connection');
    exit(1);
}

do {
    // Max 1 job processing at a time - check if current one is done (ComfyUI finished)
    $stmt = $pdo->query("SELECT id, comfy_prompt_id, provider FROM knd_labs_jobs WHERE status = 'processing' AND finished_at IS NULL LIMIT 1");
    $proc = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    if ($proc) {
        $promptId = $proc['comfy_prompt_id'] ?? '';
        if ($promptId) {
            $baseUrl = ($proc['provider'] ?? '') === 'runpod'
                ? comfyui_get_base_url_runpod($pdo) : comfyui_get_base_url_local($pdo);
            if (!$baseUrl) $baseUrl = comfyui_get_base_url($pdo, null);
            $hist = comfyui_get_history($promptId, $baseUrl, comfyui_get_token($pdo));
            $filename = null;
            if (is_array($hist['outputs'] ?? null)) {
                foreach ($hist['outputs'] as $nodeOut) {
                    if (isset($nodeOut['images']) && is_array($nodeOut['images'])) {
                        foreach ($nodeOut['images'] as $img) {
                            if (!empty($img['filename'])) {
                                $filename = $img['filename'];
                                break 2;
                            }
                        }
                    }
                }
            }
            if ($filename) {
                $pid = (int) $proc['id'];
                $pdo->prepare("UPDATE knd_labs_jobs SET status = 'done', image_url = ?, finished_at = NOW(), updated_at = NOW() WHERE id = ?")
                    ->execute(['/api/labs/image.php?job_id=' . $pid, $pid]);
                logWorker("Job $pid: marked done (ComfyUI finished)");
                continue;
            }
        }
        logWorker('Another job is processing, waiting...');
        if ($loop) {
            sleep($sleepSec);
            continue;
        }
        exit(0);
    }

    $job = null;
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->query(
            "SELECT id, user_id, tool, prompt, negative_prompt, payload_json, provider, attempts
             FROM knd_labs_jobs
             WHERE status = 'queued'
             ORDER BY priority ASC, created_at ASC
             LIMIT 1
             FOR UPDATE SKIP LOCKED"
        );
        $job = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        if (!$job) {
            $pdo->rollBack();
            if ($loop) {
                logWorker('No jobs in queue');
                sleep($sleepSec);
                continue;
            }
            exit(0);
        }

        $jobId = (int) $job['id'];
        $attempts = (int) ($job['attempts'] ?? 0) + 1;

        $pdo->prepare(
            "UPDATE knd_labs_jobs SET
               status = 'processing',
               locked_at = NOW(),
               locked_by = ?,
               started_at = IFNULL(started_at, NOW()),
               attempts = ?,
               updated_at = NOW()
             WHERE id = ?"
        )->execute([$workerId, $attempts, $jobId]);

        $pdo->commit();
    } catch (\Throwable $e) {
        $pdo->rollBack();
        logWorker('ERROR picking job: ' . $e->getMessage());
        if ($loop) sleep($sleepSec);
        continue;
    }

    $payload = json_decode($job['payload_json'] ?? '{}', true);
    if (!is_array($payload)) {
        $pdo->prepare("UPDATE knd_labs_jobs SET status = 'failed', error_message = ?, finished_at = NOW(), updated_at = NOW() WHERE id = ?")
            ->execute(['Invalid payload', $jobId]);
        logWorker("Job $jobId: invalid payload");
        if ($loop) continue;
        exit(0);
    }

    $baseUrl = null;
    $provider = $job['provider'] ?? null;
    if ($provider === 'runpod') {
        $baseUrl = comfyui_get_base_url_runpod($pdo);
    } else {
        $baseUrl = comfyui_get_base_url_local($pdo);
    }
    if (!$baseUrl) {
        $baseUrl = comfyui_get_base_url($pdo, null);
    }
    $token = comfyui_get_token($pdo);

    $tool = $payload['tool'] ?? 'text2img';
    $model = $payload['model'] ?? 'v1_5';
    $refinerEnabled = !empty($payload['refiner_enabled']);
    $overrideCkpt = $payload['override_ckpt'] ?? null;

    try {
        $workflow = comfyui_inject_workflow($payload, $tool);
        comfyui_apply_checkpoint($workflow, $model, $refinerEnabled, $overrideCkpt);
        $result = comfyui_run_prompt($workflow, $baseUrl, $token);
        $promptId = $result['prompt_id'];

        $pdo->prepare("UPDATE knd_labs_jobs SET comfy_prompt_id = ?, updated_at = NOW() WHERE id = ?")
            ->execute([$promptId, $jobId]);

        logWorker("Job $jobId: enqueued to ComfyUI, prompt_id=$promptId");
    } catch (\Throwable $e) {
        $errMsg = $e->getMessage();
        logWorker("Job $jobId failed: $errMsg");

        if ($attempts >= 3) {
            $pdo->prepare("UPDATE knd_labs_jobs SET status = 'failed', error_message = ?, finished_at = NOW(), updated_at = NOW() WHERE id = ?")
                ->execute([$errMsg, $jobId]);
        } else {
            $pdo->prepare("UPDATE knd_labs_jobs SET status = 'queued', locked_at = NULL, locked_by = NULL, updated_at = NOW() WHERE id = ?")
                ->execute([$jobId]);
        }
    }

    if (!$loop) break;
    sleep($sleepSec);

} while ($loop);
