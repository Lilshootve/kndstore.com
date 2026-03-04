<?php
/**
 * AI tools configuration - Copy to ai_secrets.local.php and fill in values.
 * Covers text2img, upscale, character lab. Image→3D uses triposr config.
 */

// Base URL of AI GPU server /generate endpoint (can be same as INSTANTMESH_API_URL if unified)
define('AI_GPU_API_URL', 'https://your-ai-gpu-server.com/generate');

// Secret shared with the GPU server for callback validation
define('AI_CALLBACK_SECRET', 'generate-with-openssl-rand-hex-32');

// Directories relative to project storage/
define('AI_UPLOAD_DIR', 'ai/uploads');
define('AI_OUTPUT_DIR', 'ai/outputs');
