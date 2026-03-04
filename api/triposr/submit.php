<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
ini_set('display_errors', '0');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/triposr_config.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/storage.php';
require_once __DIR__ . '/../../includes/triposr.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
    }

    api_require_login();

    $pdo = getDBConnection();
    if (!$pdo) {
        json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500);
    }

    $userId = current_user_id();
    if (!$userId) {
        json_error('AUTH_REQUIRED', 'You must be logged in.', 401);
    }

    // Validate uploaded image
    if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $msg = 'No image uploaded or upload error.';
        if (!empty($_FILES['image']['error'])) {
            switch ($_FILES['image']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $msg = 'File too large.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $msg = 'File only partially uploaded.';
                    break;
                default:
                    $msg = 'Upload failed.';
            }
        }
        json_error('INVALID_IMAGE', $msg);
    }

    $file = $_FILES['image'];
    $maxSize = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $maxSize) {
        json_error('FILE_TOO_LARGE', 'Image must be 10MB or smaller.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowedMimes, true)) {
        json_error('INVALID_IMAGE', 'Only JPG, PNG and WebP images are allowed.');
    }

    // Ensure directories exist
    $uploadDir = storage_path(TRIPOSR_UPLOAD_DIR);
    $outputDir = storage_path(TRIPOSR_OUTPUT_DIR);
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0750, true);
    }
    if (!is_dir($outputDir)) {
        @mkdir($outputDir, 0750, true);
    }
    if (!is_writable($uploadDir)) {
        json_error('STORAGE_ERROR', 'Upload directory not writable.', 500);
    }

    $jobUuid = sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        random_int(0, 0xffff), random_int(0, 0xffff),
        random_int(0, 0xffff), random_int(0, 0x4fff) | 0x4000,
        random_int(0, 0xffff) | 0x8000, random_int(0, 0xffff),
        random_int(0, 0xffff), random_int(0, 0xffff)
    );

    $ext = (strpos($mime, 'png') !== false) ? 'png' : ((strpos($mime, 'webp') !== false) ? 'webp' : 'jpg');
    $fileName = $jobUuid . '.' . $ext;
    $destPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        json_error('STORAGE_ERROR', 'Could not save uploaded image.', 500);
    }

    $inputRelPath = TRIPOSR_UPLOAD_DIR . '/' . $fileName;
    $job = create_triposr_job($pdo, $userId, $inputRelPath, $jobUuid);
    if (!$job) {
        @unlink($destPath);
        json_error('JOB_CREATE_FAILED', 'Could not create job.', 500);
    }

    $siteUrl = rtrim(defined('SITE_URL') ? SITE_URL : ('https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')), '/');
    $imageUrl = $siteUrl . '/api/triposr/image.php?t=' . urlencode($job['job_uuid']);
    $callbackUrl = $siteUrl . '/api/triposr/callback.php';

    if (!empty(TRIPOSR_API_URL)) {
        $payload = json_encode([
            'job_id' => $job['job_uuid'],
            'image_url' => $imageUrl,
            'callback_url' => $callbackUrl,
            'secret' => TRIPOSR_CALLBACK_SECRET,
        ]);
        $ch = curl_init(TRIPOSR_API_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
        ]);
        curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            update_triposr_job($pdo, $job['job_uuid'], [
                'status' => 'failed',
                'error_message' => 'GPU server unreachable: ' . $err,
                'completed_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            update_triposr_job($pdo, $job['job_uuid'], ['status' => 'processing']);
        }
    }

    json_success([
        'job_id' => $job['job_uuid'],
        'status' => $job['status'],
    ]);
} catch (\Throwable $e) {
    error_log('triposr/submit: ' . $e->getMessage());
    json_error('INTERNAL_ERROR', 'An unexpected error occurred.', 500);
}
