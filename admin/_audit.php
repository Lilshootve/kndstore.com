<?php
/**
 * Admin audit log - registra acciones administrativas.
 * Requiere: session, config (getDBConnection).
 */
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/config.php';

function admin_log_action(string $action, array $meta = []): void {
    try {
        $pdo = getDBConnection();
        if (!$pdo) return;

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $adminUser = $_SESSION['admin_user'] ?? $_SESSION['admin_logged_in'] ?? null;
        $adminUserStr = (is_string($adminUser) && $adminUser !== '') ? $adminUser : 'admin';

        $metaJson = empty($meta) ? null : json_encode($meta);
        $stmt = $pdo->prepare(
            'INSERT INTO admin_audit_logs (admin_ip, admin_user, action, meta_json, created_at) VALUES (?, ?, ?, ?, NOW())'
        );
        $stmt->execute([$ip, $adminUserStr, $action, $metaJson]);
    } catch (\Throwable $e) {
        error_log('admin_log_action: ' . $e->getMessage());
    }
}
