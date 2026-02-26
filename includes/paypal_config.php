<?php
// PayPal configuration (LIVE).
// Credentials are loaded from paypal_secrets.local.php (non-versioned) or environment variables.
// Do NOT store real credentials in the repository.

$paypalSecrets = [];
$secretsFile = __DIR__ . '/paypal_secrets.local.php';
if (file_exists($secretsFile)) {
    $paypalSecrets = include $secretsFile;
    if (!is_array($paypalSecrets)) {
        $paypalSecrets = [];
    }
}

$clientId = $paypalSecrets['client_id'] ?? getenv('PAYPAL_CLIENT_ID');
$clientSecret = $paypalSecrets['client_secret'] ?? getenv('PAYPAL_CLIENT_SECRET');
$apiBase = $paypalSecrets['api_base'] ?? getenv('PAYPAL_API_BASE') ?: 'https://api-m.paypal.com';

if (empty($clientId) || empty($clientSecret)) {
    throw new \Exception(
        'PayPal credentials are missing. Create includes/paypal_secrets.local.php returning an array with client_id and client_secret, or set PAYPAL_CLIENT_ID and PAYPAL_CLIENT_SECRET environment variables.'
    );
}

if (!defined('PAYPAL_CLIENT_ID')) {
    define('PAYPAL_CLIENT_ID', $clientId);
}
if (!defined('PAYPAL_CLIENT_SECRET')) {
    define('PAYPAL_CLIENT_SECRET', $clientSecret);
}
if (!defined('PAYPAL_API_BASE')) {
    define('PAYPAL_API_BASE', $apiBase ?: 'https://api-m.paypal.com');
}

if (!function_exists('getPayPalAccessToken')) {
    function getPayPalAccessToken(): string {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => PAYPAL_API_BASE . '/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_USERPWD => PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Accept-Language: en_US',
            ],
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            curl_close($ch);
            return '';
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status < 200 || $status >= 300) {
            return '';
        }

        $payload = json_decode($response, true);
        return $payload['access_token'] ?? '';
    }
}

if (!function_exists('paypalApiRequest')) {
    function paypalApiRequest(string $method, string $path, string $accessToken, ?array $body = null): array {
        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ];

        curl_setopt_array($ch, [
            CURLOPT_URL => PAYPAL_API_BASE . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response ?: '', true);
        return [
            'status' => $status,
            'body' => $decoded,
            'raw' => $response,
        ];
    }
}
?>
