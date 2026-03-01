<?php
// KND Store - Auth helpers for Death Roll 1v1 user system

require_once __DIR__ . '/session.php';

function is_logged_in(): bool {
    return !empty($_SESSION['dr_user_id']);
}

function current_user_id(): ?int {
    return $_SESSION['dr_user_id'] ?? null;
}

function current_username(): ?string {
    return $_SESSION['dr_username'] ?? null;
}

function auth_login(int $userId, string $username): void {
    session_regenerate_id(true);
    $_SESSION['dr_user_id'] = $userId;
    $_SESSION['dr_username'] = $username;
}

function auth_logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/**
 * Redirect to login if not authenticated. Use at top of protected pages.
 */
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /auth.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * API guard: return JSON error if not authenticated.
 */
function api_require_login(): void {
    if (!is_logged_in()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode([
            'ok' => false,
            'error' => ['code' => 'AUTH_REQUIRED', 'message' => 'You must be logged in.']
        ]);
        exit;
    }
}
