<?php
/**
 * Copy to labs.local.php and adjust paths for your environment.
 * labs.local.php is loaded after labs.php if it exists.
 */
// ComfyUI input directory (where job_X_input.png files go)
define('COMFY_INPUT_DIR', '/path/to/ComfyUI/input');

// ComfyUI output directory (where ComfyUI writes results)
define('COMFY_OUTPUT_DIR', '/path/to/ComfyUI/output');

// Workflows directory (optional, defaults to project/workflows)
// define('WORKFLOWS_DIR', dirname(__DIR__) . '/workflows');

// Labs upload dir relative to storage (for upscaled outputs)
// define('LABS_UPLOAD_DIR', 'uploads/labs');
