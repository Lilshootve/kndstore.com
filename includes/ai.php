<?php
/**
 * KND Store - AI tools helpers (text2img, upscale, character lab)
 * Uses triposr_jobs table with job_type, payload_json, result_json, cost_kp.
 */

require_once __DIR__ . '/triposr.php';

/** KP costs per job type/subtype */
const AI_COSTS = [
    'text2img' => 3,
    'text2img_standard' => 3,
    'text2img_high' => 6,
    'upscale' => 5,
    'character_create' => 15,
    'character_variation' => 6,
];

function ai_job_cost(string $type, ?string $subtype = null): int {
    $key = $subtype ? "{$type}_{$subtype}" : $type;
    return AI_COSTS[$key] ?? 0;
}

function ai_count_active_jobs(PDO $pdo, int $userId): int {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM triposr_jobs WHERE user_id = ? AND status IN ('pending','processing')");
    if (!$stmt || !$stmt->execute([$userId])) return 0;
    return (int) $stmt->fetchColumn();
}

function ai_count_jobs_last_hour(PDO $pdo, int $userId): int {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM triposr_jobs WHERE user_id = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    if (!$stmt || !$stmt->execute([$userId])) return 0;
    return (int) $stmt->fetchColumn();
}

function ai_count_jobs_today(PDO $pdo, int $userId): int {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM triposr_jobs WHERE user_id = ? AND created_at >= CURDATE()");
    if (!$stmt || !$stmt->execute([$userId])) return 0;
    return (int) $stmt->fetchColumn();
}

function ai_spend_points(PDO $pdo, int $userId, int $jobId, int $amount): bool {
    if ($amount <= 0) return false;
    $now = gmdate('Y-m-d H:i:s');
    $stmt = $pdo->prepare(
        "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, created_at)
         VALUES (?, 'ai_job_spend', ?, 'spend', 'spent', ?, ?)"
    );
    return $stmt && $stmt->execute([$userId, $jobId, -$amount, $now]);
}

function ai_refund_points(PDO $pdo, int $userId, int $jobId, int $amount): bool {
    if ($amount <= 0) return false;
    $now = gmdate('Y-m-d H:i:s');
    $stmt = $pdo->prepare(
        "INSERT INTO points_ledger (user_id, source_type, source_id, entry_type, status, points, created_at)
         VALUES (?, 'ai_job_refund', ?, 'reversal', 'available', ?, ?)"
    );
    return $stmt && $stmt->execute([$userId, $jobId, $amount, $now]);
}

function ai_generate_uuid(): string {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        random_int(0, 0xffff), random_int(0, 0xffff),
        random_int(0, 0xffff), random_int(0, 0x4fff) | 0x4000,
        random_int(0, 0xffff) | 0x8000, random_int(0, 0xffff),
        random_int(0, 0xffff), random_int(0, 0xffff)
    );
}

/**
 * Create an AI job. Uses triposr_jobs.
 * @param string $jobType text2img|upscale|character_create|character_variation
 * @param string $inputPath '' for text2img/character, path for upscale
 * @param string|null $jobUuid Optional; for upscale pass before saving file to match image URL
 */
function ai_create_job(PDO $pdo, int $userId, string $jobType, array $payload, int $costKp, string $inputPath = '', ?string $provider = null, ?string $jobUuid = null): ?array {
    $jobUuid = $jobUuid ?? ai_generate_uuid();
    $payloadJson = json_encode($payload);
    $inputPath = $inputPath ?: '';

    $stmt = $pdo->prepare(
        "INSERT INTO triposr_jobs (user_id, job_uuid, job_type, provider, cost_kp, payload_json, input_path, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')"
    );
    $ok = $stmt && $stmt->execute([$userId, $jobUuid, $jobType, $provider, $costKp, $payloadJson, $inputPath]);

    if (!$ok) return null;

    $id = (int) $pdo->lastInsertId();
    return [
        'id' => $id,
        'job_uuid' => $jobUuid,
        'job_type' => $jobType,
        'cost_kp' => $costKp,
        'payload' => $payload,
        'input_path' => $inputPath,
        'status' => 'pending',
    ];
}

function ai_get_job(PDO $pdo, string $jobUuid): ?array {
    $stmt = $pdo->prepare(
        "SELECT id, user_id, job_uuid, job_type, provider, cost_kp, payload_json, result_json,
                input_path, output_path, quality, status, error_message, created_at, completed_at
         FROM triposr_jobs WHERE job_uuid = ? LIMIT 1"
    );
    if (!$stmt || !$stmt->execute([$jobUuid])) return null;
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) return null;

    if (!isset($row['job_type'])) $row['job_type'] = 'img23d';
    if (!isset($row['cost_kp'])) $row['cost_kp'] = 0;
    if (!empty($row['payload_json'])) {
        $row['payload'] = json_decode($row['payload_json'], true);
        if (!is_array($row['payload'])) $row['payload'] = [];
    } else {
        $row['payload'] = [];
    }
    if (!empty($row['result_json'])) {
        $row['result'] = json_decode($row['result_json'], true);
        if (!is_array($row['result'])) $row['result'] = [];
    } else {
        $row['result'] = [];
    }
    if (!isset($row['quality'])) $row['quality'] = 'balanced';
    return $row;
}

function ai_update_job(PDO $pdo, string $jobUuid, array $updates): bool {
    $allowed = ['status', 'output_path', 'error_message', 'completed_at', 'result_json'];
    $set = [];
    $params = [];
    foreach ($updates as $key => $val) {
        if (!in_array($key, $allowed, true)) continue;
        $set[] = "`$key` = ?";
        $params[] = $val;
    }
    if (empty($set)) return false;
    $params[] = $jobUuid;
    $sql = 'UPDATE triposr_jobs SET ' . implode(', ', $set) . ' WHERE job_uuid = ?';
    $stmt = $pdo->prepare($sql);
    return $stmt && $stmt->execute($params);
}
