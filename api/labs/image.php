<?php
/**
 * GET /api/labs/image.php?job_id=XXX[&download=1]
 * Serves image via proxy (avoids CORS). download=1 forces attachment.
 */
header('Cache-Control: no-store, no-cache');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';

try {
    api_require_login();

    $jobId = trim($_GET['job_id'] ?? '');
    if ($jobId === '') json_error('INVALID_INPUT', 'job_id is required.', 400);

    $pdo = getDBConnection();
    if (!$pdo) json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);

    $stmt = $pdo->prepare("SELECT * FROM knd_labs_jobs WHERE id = ? AND user_id = ? LIMIT 1");
    $stmt->execute([$jobId, current_user_id()]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$job) json_error('JOB_NOT_FOUND', 'Job not found.', 404);

    $imageUrl = $job['image_url'] ?? '';
    if (!$imageUrl || ($job['status'] ?? '') !== 'done') {
        json_error('NO_IMAGE', 'Image not ready.', 409);
    }

    $ch = curl_init($imageUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $bin = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ct = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($bin === false || $code < 200 || $code >= 300) {
        json_error('FETCH_FAILED', 'Could not fetch image from ComfyUI.', 502);
    }

    $download = isset($_GET['download']) && $_GET['download'] !== '0';
    $filename = 'knd_labs_' . $jobId . '.png';

    header('Content-Type: ' . ($ct ?: 'image/png'));
    header('Content-Length: ' . strlen($bin));
    header('X-Content-Type-Options: nosniff');
    header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . $filename . '"');

    echo $bin;
    exit;
} catch (\Throwable $e) {
    error_log('api/labs/image: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An error occurred.', 500);
}
