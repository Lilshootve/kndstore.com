<?php
/**
 * Character Lab config - copy to character_lab.local.php
 * Load this from config.php or at app bootstrap if needed.
 */

// KP cost per generation (default 25)
define('CHARACTER_LAB_KP_COST', 25);

// Engine config (for worker / ComfyUI integration)
// define('IMAGE_ENGINE_PROVIDER', 'local_comfyui'); // local_comfyui | runpod
// define('MODEL3D_ENGINE', 'hunyuan3d');           // hunyuan3d | triposr | instantmesh
// define('CHARACTER_LAB_SAFE_ONLY', true);         // Safe mode only for now
