<?php
/**
 * KND Store — Storage helpers
 * Production-safe JSON persistence + forensic logging.
 */

function storage_path(string $relative = ''): string {
    static $base = null;
    if ($base === null) {
        $base = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage';
    }
    if ($relative === '') return $base;
    return $base . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative), DIRECTORY_SEPARATOR);
}

function ensure_storage_ready(): void {
    $dirs = [storage_path(), storage_path('logs')];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        @chmod($dir, 0750);
    }
    $jsonFiles = ['orders.json', 'bank_transfer_requests.json', 'other_payment_requests.json'];
    foreach ($jsonFiles as $f) {
        $path = storage_path($f);
        if (!file_exists($path)) {
            @file_put_contents($path, "[]");
            @chmod($path, 0640);
        }
    }
}

function read_json_array(string $path): array {
    if (!file_exists($path)) return [];
    $raw = @file_get_contents($path);
    if ($raw === false) {
        storage_log('read_json_array: failed to read file', ['path' => $path]);
        return [];
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        storage_log('read_json_array: invalid JSON, backing up', ['path' => $path, 'size' => strlen($raw)]);
        $backup = $path . '.bak.' . date('Ymd_His');
        @copy($path, $backup);
        @file_put_contents($path, "[]");
        @chmod($path, 0640);
        return [];
    }
    return $data;
}

/**
 * Append a record to a JSON array file atomically.
 * Uses flock for concurrency and writes to a temp file before renaming.
 */
function append_json_record(string $path, array $record): bool {
    ensure_storage_ready();
    $dir = dirname($path);
    $tmpFile = $dir . DIRECTORY_SEPARATOR . '.tmp_' . basename($path) . '.' . getmypid();

    $fp = @fopen($path, 'c+');
    if (!$fp) {
        storage_log('append_json_record: cannot open file', ['path' => $path]);
        return false;
    }
    if (!flock($fp, LOCK_EX)) {
        fclose($fp);
        storage_log('append_json_record: cannot lock file', ['path' => $path]);
        return false;
    }

    $raw = stream_get_contents($fp);
    $existing = json_decode($raw ?: '[]', true);
    if (!is_array($existing)) {
        storage_log('append_json_record: corrupt JSON, resetting with backup', ['path' => $path]);
        $backup = $path . '.bak.' . date('Ymd_His');
        @file_put_contents($backup, $raw);
        $existing = [];
    }

    $existing[] = $record;
    $json = json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $written = @file_put_contents($tmpFile, $json);
    if ($written === false) {
        flock($fp, LOCK_UN);
        fclose($fp);
        storage_log('append_json_record: tmp write failed', ['path' => $path, 'tmp' => $tmpFile]);
        return false;
    }

    if (!@rename($tmpFile, $path)) {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, $json);
    }

    @chmod($path, 0640);
    flock($fp, LOCK_UN);
    fclose($fp);
    @unlink($tmpFile);
    return true;
}

/**
 * Append a JSONL line to storage/logs/payments.log.
 */
function storage_log(string $message, array $context = []): void {
    $logPath = storage_path('logs/payments.log');
    $logDir = dirname($logPath);
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0750, true);
    }
    $entry = [
        'ts' => date('c'),
        'msg' => $message,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '-',
        'rid' => substr(bin2hex(random_bytes(4)), 0, 8),
    ];
    $entry = array_merge($entry, $context);
    @file_put_contents($logPath, json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX);
}
