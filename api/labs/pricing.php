<?php
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache');

require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/json.php';

api_require_login();

$pricing = [
    'text2img' => [
        'standard' => 3,
        'high' => 6,
    ],
    'upscale' => [
        '2x' => 5,
        '4x' => 8,
    ],
    'character' => [
        'base' => 15,
    ],
    'consistency' => [
        'base' => 5,
    ],
];

json_success($pricing);
