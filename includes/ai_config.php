<?php
/**
 * AI tools configuration (text2img, upscale, character lab).
 * Reuses INSTANTMESH_* if AI_GPU_API_URL not set (unified GPU server).
 */

if (!defined('AI_GPU_API_URL') && !defined('AI_CALLBACK_SECRET')) {
    $secrets = __DIR__ . '/../config/ai_secrets.local.php';
    if (file_exists($secrets)) {
        require_once $secrets;
    }
}

if (!defined('AI_GPU_API_URL')) {
    define('AI_GPU_API_URL', defined('INSTANTMESH_API_URL') ? INSTANTMESH_API_URL : '');
}
if (!defined('AI_CALLBACK_SECRET')) {
    define('AI_CALLBACK_SECRET', defined('INSTANTMESH_CALLBACK_SECRET') ? INSTANTMESH_CALLBACK_SECRET : '');
}
if (!defined('AI_UPLOAD_DIR')) {
    define('AI_UPLOAD_DIR', 'ai/uploads');
}
if (!defined('AI_OUTPUT_DIR')) {
    define('AI_OUTPUT_DIR', 'ai/outputs');
}
