<?php
/**
 * TripoSR configuration loader.
 * Add to includes/config.php:
 *   require_once __DIR__ . '/triposr_config.php';
 * Or configure directly in config.php:
 *   define('TRIPOSR_API_URL', '...');
 *   define('TRIPOSR_CALLBACK_SECRET', '...');
 *   define('TRIPOSR_UPLOAD_DIR', 'storage/triposr/uploads');
 *   define('TRIPOSR_OUTPUT_DIR', 'storage/triposr/outputs');
 */

if (!defined('TRIPOSR_API_URL')) {
    $secrets = __DIR__ . '/../config/triposr_secrets.local.php';
    if (file_exists($secrets)) {
        require_once $secrets;
    }
}

if (!defined('TRIPOSR_API_URL')) {
    define('TRIPOSR_API_URL', '');
}
if (!defined('TRIPOSR_CALLBACK_SECRET')) {
    define('TRIPOSR_CALLBACK_SECRET', '');
}
if (!defined('TRIPOSR_UPLOAD_DIR')) {
    define('TRIPOSR_UPLOAD_DIR', 'triposr/uploads');
}
if (!defined('TRIPOSR_OUTPUT_DIR')) {
    define('TRIPOSR_OUTPUT_DIR', 'triposr/outputs');
}
