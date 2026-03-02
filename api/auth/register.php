<?php
// KND Store - User registration endpoint (Death Roll 1v1)

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/rate_limit.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/deathroll_1v1.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('METHOD_NOT_ALLOWED', 'POST only.', 405);
}

csrf_guard();

$pdo = getDBConnection();
if (!$pdo) { json_error('DB_CONNECTION_FAILED', 'Database connection failed.', 500); }

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
rate_limit_guard($pdo, "register:{$ip}", 5, 300);

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!validate_username($username)) {
    json_error('INVALID_USERNAME', 'Username must be 3-24 characters (letters, numbers, underscore).');
}

if (strlen($password) < 8) {
    json_error('WEAK_PASSWORD', 'Password must be at least 8 characters.');
}

$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
if ($stmt->fetch()) {
    json_error('USERNAME_TAKEN', 'That username is already taken.');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$now = gmdate('Y-m-d H:i:s');

$stmt = $pdo->prepare(
    'INSERT INTO users (username, password_hash, created_at, updated_at) VALUES (?, ?, ?, ?)'
);
$stmt->execute([$username, $hash, $now, $now]);
$userId = (int) $pdo->lastInsertId();

auth_login($userId, $username);

json_success(['user_id' => $userId, 'username' => $username]);
