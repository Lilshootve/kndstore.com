<?php
/**
 * Admin guard - middleware común para páginas protegidas.
 * Proporciona: session, config, headers no-cache y helper admin_require_login().
 */
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/config.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
header('X-Robots-Tag: noindex, nofollow');

/**
 * Requiere sesión admin; redirige a login si no está autenticado.
 */
function admin_require_login(): void {
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /admin/index.php');
        exit;
    }
}
