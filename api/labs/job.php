<?php
/**
 * KND Labs - Job details for "View details" modal
 * GET /api/labs/job.php?job_id=XXX
 */
header('Cache-Control: no-store, no-cache');
header('Content-Type: application/json');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        json_error('METHOD_NOT_ALLOWED', 'GET only.', 405);
    }
    api_require_login();

    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);

    $jobId = trim($_GET['job_id'] ?? '');
    if ($jobId === '') json_error('INVALID_INPUT', 'job_id is required.', 400);

    $stmt = $pdo->prepare("SELECT * FROM knd_labs_jobs WHERE id = ? AND user_id = ? LIMIT 1");
    if (!$stmt || !$stmt->execute([$jobId, current_user_id()])) {
        json_error('DB_ERROR', 'Could not fetch job.', 500);
    }
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$job) json_error('JOB_NOT_FOUND', 'Job not found.', 404);

    $payload = json_decode($job['payload_json'] ?? '{}', true) ?: [];

    $data = [
        'job_id' => (string) $job['id'],
        'tool' => $job['tool'] ?? '',
        'status' => $job['status'] ?? '',
        'prompt' => $job['prompt'] ?? '',
        'negative_prompt' => $job['negative_prompt'] ?? ($payload['negative_prompt'] ?? ''),
        'model' => $payload['model'] ?? 'juggernaut_v8',
        'seed' => $payload['seed'] ?? null,
        'steps' => $payload['steps'] ?? 20,
        'cfg' => $payload['cfg'] ?? 7.5,
        'width' => $payload['width'] ?? 1024,
        'height' => $payload['height'] ?? 1024,
        'sampler_name' => $payload['sampler_name'] ?? 'euler',
        'scheduler' => $payload['scheduler'] ?? 'normal',
        'cost_kp' => (int) ($job['cost_kp'] ?? 0),
        'provider' => $job['provider'] ?? null,
        'created_at' => $job['created_at'] ?? '',
        'finished_at' => $job['finished_at'] ?? null,
        'error_message' => $job['error_message'] ?? null,
        'image_url' => $job['image_url'] ?? null,
    ];

    json_success($data);
} catch (\Throwable $e) {
    error_log('api/labs/job: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An error occurred.', 500);
}
