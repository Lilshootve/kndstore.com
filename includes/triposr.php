<?php
/**
 * KND Store - TripoSR 3D model generation helpers
 * Manages jobs for image-to-3D conversion via external GPU server.
 */

if (!function_exists('create_triposr_job')) {
    /**
     * @param string|null $jobUuid Optional UUID; if null, one is generated.
     */
    function create_triposr_job(PDO $pdo, int $userId, string $inputPath, ?string $jobUuid = null): ?array {
        if ($jobUuid === null) {
            $jobUuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff), random_int(0, 0xffff),
            random_int(0, 0xffff), random_int(0, 0x4fff) | 0x4000,
            random_int(0, 0xffff) | 0x8000, random_int(0, 0xffff),
            random_int(0, 0xffff), random_int(0, 0xffff)
        );
        }

        $sql = 'INSERT INTO triposr_jobs (user_id, job_uuid, input_path, status) VALUES (?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        if (!$stmt || !$stmt->execute([$userId, $jobUuid, $inputPath, 'pending'])) {
            return null;
        }

        $id = (int) $pdo->lastInsertId();
        return ['id' => $id, 'job_uuid' => $jobUuid, 'input_path' => $inputPath, 'status' => 'pending'];
    }
}

if (!function_exists('get_triposr_job')) {
    function get_triposr_job(PDO $pdo, string $jobId): ?array {
        $stmt = $pdo->prepare('SELECT id, user_id, job_uuid, input_path, output_path, status, error_message, created_at, completed_at FROM triposr_jobs WHERE job_uuid = ? LIMIT 1');
        if (!$stmt || !$stmt->execute([$jobId])) {
            return null;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}

if (!function_exists('get_triposr_job_by_uuid')) {
    function get_triposr_job_by_uuid(PDO $pdo, string $jobUuid): ?array {
        return get_triposr_job($pdo, $jobUuid);
    }
}

if (!function_exists('update_triposr_job')) {
    function update_triposr_job(PDO $pdo, string $jobUuid, array $updates): bool {
        $allowed = ['status', 'output_path', 'error_message', 'completed_at'];
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
}
