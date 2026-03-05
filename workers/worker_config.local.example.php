<?php
// Copy to worker_config.local.php (NOT in Git).
// HTTP Worker runs on your PC - no MySQL needed.

return [
    'API_BASE'       => 'https://kndstore.com',           // Hostinger API base
    'WORKER_TOKEN'   => 'same_as_worker_secrets_on_server',
    'COMFYUI_BASE'   => 'https://comfy.kndstore.com',     // or http://127.0.0.1:8188
    'COMFYUI_TOKEN'  => '',                               // X-KND-TOKEN if ComfyUI uses it
];
