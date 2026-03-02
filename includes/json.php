<?php
// KND Store - Centralized JSON response helpers

if (!function_exists('json_success')) {
    function json_success(array $data = []): void {
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (!function_exists('json_error')) {
    function json_error(string $code, string $message, int $httpStatus = 400): void {
        http_response_code($httpStatus);
        header('Content-Type: application/json');
        echo json_encode([
            'ok' => false,
            'error' => ['code' => $code, 'message' => $message]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
