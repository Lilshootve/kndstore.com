<?php
/**
 * KND Labs - ComfyUI configuration
 * Define COMFYUI_BASE_URL (e.g. Runpod), timeout, and client ID.
 */
if (!defined('COMFYUI_BASE_URL')) {
    define('COMFYUI_BASE_URL', getenv('COMFYUI_BASE_URL') ?: 'https://your-runpod-url.runpod.net');
}
if (!defined('COMFYUI_TIMEOUT')) {
    define('COMFYUI_TIMEOUT', 120);
}
if (!defined('COMFYUI_CLIENT_ID')) {
    define('COMFYUI_CLIENT_ID', 'knd-labs');
}
