<?php
/**
 * KND Labs - Paths for upscale (filesystem mode)
 * Override in config/labs.local.php for your environment.
 */
$labsLocal = __DIR__ . '/labs.local.php';
if (file_exists($labsLocal)) {
    require_once $labsLocal;
}
if (!defined('COMFY_INPUT_DIR')) {
    define('COMFY_INPUT_DIR', getenv('COMFY_INPUT_DIR') ?: (dirname(__DIR__) . '/comfyui/ComfyUI/input'));
}
if (!defined('COMFY_OUTPUT_DIR')) {
    define('COMFY_OUTPUT_DIR', getenv('COMFY_OUTPUT_DIR') ?: (dirname(__DIR__) . '/comfyui/ComfyUI/output'));
}
if (!defined('WORKFLOWS_DIR')) {
    define('WORKFLOWS_DIR', getenv('WORKFLOWS_DIR') ?: (dirname(__DIR__) . '/workflows'));
}
if (!defined('LABS_UPLOAD_DIR')) {
    define('LABS_UPLOAD_DIR', 'uploads/labs');
}
