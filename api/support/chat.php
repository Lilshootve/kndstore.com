<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

// ── Helpers ──

function json_error(int $code, string $msg): void {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit;
}

function support_log(string $event, array $data = []): void {
    $dir = dirname(__DIR__, 2) . '/storage/logs';
    if (!is_dir($dir)) @mkdir($dir, 0750, true);
    $line = '[' . date('Y-m-d H:i:s') . '] '
          . $event . ' '
          . json_encode($data, JSON_UNESCAPED_SLASHES) . "\n";
    @file_put_contents($dir . '/support_ai.log', $line, FILE_APPEND | LOCK_EX);
}

// ── Rate limiter (file-based, per IP) ──

function check_rate_limit(string $ip): bool {
    $dir = dirname(__DIR__, 2) . '/storage/logs';
    if (!is_dir($dir)) @mkdir($dir, 0750, true);
    $file = $dir . '/support_ratelimit.json';
    $window = 600; // 10 minutes
    $maxReq = 30;
    $now = time();

    $fh = @fopen($file, 'c+');
    if (!$fh) return true; // fail open
    flock($fh, LOCK_EX);

    $raw = stream_get_contents($fh);
    $data = $raw ? json_decode($raw, true) : [];
    if (!is_array($data)) $data = [];

    // Clean expired entries
    foreach ($data as $k => $entries) {
        $data[$k] = array_values(array_filter($entries, fn($t) => $t > $now - $window));
        if (empty($data[$k])) unset($data[$k]);
    }

    $ipKey = md5($ip);
    $hits = $data[$ipKey] ?? [];

    if (count($hits) >= $maxReq) {
        rewind($fh); ftruncate($fh, 0);
        fwrite($fh, json_encode($data, JSON_UNESCAPED_SLASHES));
        fflush($fh); flock($fh, LOCK_UN); fclose($fh);
        return false;
    }

    $hits[] = $now;
    $data[$ipKey] = $hits;

    rewind($fh); ftruncate($fh, 0);
    fwrite($fh, json_encode($data, JSON_UNESCAPED_SLASHES));
    fflush($fh); flock($fh, LOCK_UN); fclose($fh);
    return true;
}

// ── Validate request ──

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error(405, 'POST only.');
}

$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body) || empty($body['messages'])) {
    json_error(400, 'Invalid request body.');
}

$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$ip = explode(',', $ip)[0];

if (!check_rate_limit(trim($ip))) {
    support_log('rate_limited', ['ip_hash' => md5($ip)]);
    json_error(429, "You've sent too many messages. Please wait a few minutes and try again.");
}

$locale = in_array($body['locale'] ?? 'en', ['en', 'es']) ? $body['locale'] : 'en';

// ── Sanitize messages ──

$maxMessages = 20;
$maxContentLen = 500;
$messages = [];
foreach (array_slice($body['messages'], -$maxMessages) as $m) {
    if (!isset($m['role'], $m['content'])) continue;
    $role = $m['role'] === 'assistant' ? 'assistant' : 'user';
    $content = mb_substr(trim($m['content']), 0, $maxContentLen);
    if ($content === '') continue;
    $messages[] = ['role' => $role, 'content' => $content];
}

if (empty($messages)) {
    json_error(400, 'No valid messages.');
}

// ── Load config + KB ──

$secretsPath = dirname(__DIR__, 2) . '/config/openai_secrets.local.php';
if (!file_exists($secretsPath)) {
    support_log('error', ['msg' => 'openai_secrets.local.php missing']);
    json_error(503, 'Support AI is temporarily unavailable. Please contact support@kndstore.com.');
}
$secrets = require $secretsPath;
$apiKey = $secrets['api_key'] ?? '';
if (empty($apiKey)) {
    support_log('error', ['msg' => 'api_key empty']);
    json_error(503, 'Support AI is temporarily unavailable. Please contact support@kndstore.com.');
}

$kbFile = dirname(__DIR__, 2) . '/includes/support_kb_en.php';
$kb = file_exists($kbFile) ? require $kbFile : '';

$systemPrompt = "You are KND Support, the official AI assistant for KND Store (kndstore.com). "
    . "You provide Level 1 customer support 24/7.\n\n"
    . "RULES:\n"
    . "1. ONLY answer using the Knowledge Base below. If the answer is not there, say you're not sure and route to human support.\n"
    . "2. NEVER request passwords, card numbers, bank credentials, or sensitive personal data.\n"
    . "3. NEVER claim you verified a payment, checked an order status, or accessed any account or database.\n"
    . "4. Keep answers concise: 2–4 sentences when possible.\n"
    . "5. Always suggest a next step (track order, contact support, relevant link).\n"
    . "6. Be professional, helpful, and friendly with a premium brand tone.\n"
    . "7. If the user writes in Spanish, respond in Spanish. Match the user's language.\n\n"
    . "KNOWLEDGE BASE:\n" . $kb;

// ── Call OpenAI ──

$apiMessages = [
    ['role' => 'system', 'content' => $systemPrompt],
    ...$messages,
];

$payload = [
    'model'       => 'gpt-4o-mini',
    'messages'    => $apiMessages,
    'max_tokens'  => 300,
    'temperature' => 0.4,
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_SLASHES),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 25,
    CURLOPT_CONNECTTIMEOUT => 5,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr || $httpCode !== 200) {
    support_log('openai_error', [
        'http' => $httpCode,
        'curl' => $curlErr,
        'body_len' => strlen($response ?: ''),
    ]);
    json_error(502, 'Support AI is temporarily unavailable. Please email support@kndstore.com or WhatsApp +58 414-159-2319.');
}

$result = json_decode($response, true);
$reply = $result['choices'][0]['message']['content'] ?? '';

if (empty($reply)) {
    support_log('empty_reply', ['raw_len' => strlen($response)]);
    json_error(502, 'Could not generate a response. Please contact support@kndstore.com.');
}

$tokensUsed = $result['usage']['total_tokens'] ?? 0;

// Detect topic from last user message
$lastUser = '';
foreach (array_reverse($messages) as $m) {
    if ($m['role'] === 'user') { $lastUser = $m['content']; break; }
}
$topics = [];
$topicMap = [
    'payment' => '/pay|paypal|transfer|bank|binance|usdt|zinli|wally|pipol/i',
    'delivery' => '/deliver|ship|shipping|envio|envío|tracking|track/i',
    'sizing'   => '/size|sizing|talla|medida|fit/i',
    'refund'   => '/refund|refun|devol|disput|reembolso/i',
    'contact'  => '/contact|support|help|ayuda|soporte/i',
];
foreach ($topicMap as $tag => $pattern) {
    if (preg_match($pattern, $lastUser)) $topics[] = $tag;
}

support_log('chat', [
    'ip_hash'  => md5(trim($ip)),
    'locale'   => $locale,
    'tokens'   => $tokensUsed,
    'topics'   => $topics ?: ['general'],
    'msg_count'=> count($messages),
    'reply_len'=> mb_strlen($reply),
]);

echo json_encode(['reply' => $reply], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
