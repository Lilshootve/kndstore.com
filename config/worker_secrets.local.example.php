<?php
// Copy to worker_secrets.local.php and set WORKER_TOKEN.
// worker_secrets.local.php is NOT in Git (config/*.local.php).
// Used by /api/labs/queue/lease.php, complete.php, fail.php for X-KND-WORKER-TOKEN auth.

return [
    'WORKER_TOKEN' => 'your_secure_random_token_here_min_32_chars',
];
