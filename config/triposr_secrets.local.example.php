<?php
/**
 * TripoSR configuration - Copy to triposr_secrets.local.php and fill in values.
 * Include this file from includes/config.php if TripoSR is enabled.
 */

// Base URL of your TripoSR GPU server (e.g. https://gpu.kndstore.com or http://192.168.1.100:8080)
define('TRIPOSR_API_URL', 'https://your-triposr-server.com/api/submit');

// Secret shared with the GPU server for callback validation
define('TRIPOSR_CALLBACK_SECRET', 'generate-with-openssl-rand-hex-32');

// Directories relative to project root storage/
define('TRIPOSR_UPLOAD_DIR', 'triposr/uploads');
define('TRIPOSR_OUTPUT_DIR', 'triposr/outputs');
