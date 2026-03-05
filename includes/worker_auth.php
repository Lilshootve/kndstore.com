<?php
/**
 * Worker token for HTTP worker auth (X-KND-WORKER-TOKEN).
 * Load from config/worker_secrets.local.php
 */
function get_worker_token(): string {
    static $token = null;
    if ($token !== null) return $token;
    $path = dirname(__DIR__) . '/config/worker_secrets.local.php';
    if (!is_readable($path)) return '';
    $cfg = include $path;
    $token = isset($cfg['WORKER_TOKEN']) ? trim((string) $cfg['WORKER_TOKEN']) : '';
    return $token;
}
