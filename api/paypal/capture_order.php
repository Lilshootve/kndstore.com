<?php
require_once __DIR__ . '/../../includes/pricing.php';
require_once __DIR__ . '/../../includes/paypal_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload']);
    exit;
}

$orderID = isset($payload['orderID']) ? trim((string) $payload['orderID']) : '';
$items = $payload['items'] ?? [];
$deliveryType = isset($payload['deliveryType']) ? (string) $payload['deliveryType'] : '';
$customer = isset($payload['customer']) && is_array($payload['customer']) ? $payload['customer'] : [];

if (!$orderID || !is_array($items) || empty($items)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing order data']);
    exit;
}

$itemsDetailed = [];
$subtotal = 0.0;

foreach ($items as $item) {
    $id = isset($item['id']) ? (int) $item['id'] : 0;
    $qty = isset($item['qty']) ? (int) $item['qty'] : 0;
    $variants = isset($item['variants']) && is_array($item['variants']) ? $item['variants'] : null;

    if ($id <= 0 || $qty < 1 || $qty > 10) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid item input']);
        exit;
    }

    $pricingItem = getPricingItemById($id);
    if (!$pricingItem) {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown product id']);
        exit;
    }

    if (!validateItemVariants($pricingItem, $variants)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid variants']);
        exit;
    }

    $unitPrice = (float) $pricingItem['base_price_usd'];
    $lineTotal = $unitPrice * $qty;
    $subtotal += $lineTotal;

    $itemsDetailed[] = [
        'id' => $id,
        'name' => $pricingItem['name'],
        'type' => $pricingItem['type'],
        'qty' => $qty,
        'unit_price' => round($unitPrice, 2),
        'line_total' => round($lineTotal, 2),
        'variants' => $variants,
    ];
}

$shipping = 0.0;
$total = $subtotal + $shipping;

$storageDir = __DIR__ . '/../../storage';
$ordersFile = $storageDir . '/orders.json';
if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0755, true);
}
if (!file_exists($ordersFile)) {
    @file_put_contents($ordersFile, '[]');
}

$existing = json_decode(file_get_contents($ordersFile), true);
if (!is_array($existing)) {
    $existing = [];
}
foreach ($existing as $record) {
    if (!empty($record['paypal_order_id']) && $record['paypal_order_id'] === $orderID) {
        http_response_code(409);
        echo json_encode(['error' => 'Order already captured']);
        exit;
    }
}

$accessToken = getPayPalAccessToken();
if (!$accessToken) {
    http_response_code(500);
    echo json_encode(['error' => 'PayPal auth failed']);
    exit;
}

$captureResponse = paypalApiRequest('POST', '/v2/checkout/orders/' . rawurlencode($orderID) . '/capture', $accessToken, null);
if ($captureResponse['status'] < 200 || $captureResponse['status'] >= 300) {
    http_response_code(500);
    echo json_encode(['error' => 'PayPal capture failed']);
    exit;
}

$body = $captureResponse['body'] ?? [];
$status = $body['status'] ?? '';
$capture = $body['purchase_units'][0]['payments']['captures'][0] ?? [];
$captureStatus = $capture['status'] ?? '';
$amountValue = isset($capture['amount']['value']) ? (float) $capture['amount']['value'] : 0.0;
$currencyCode = $capture['amount']['currency_code'] ?? '';

if ($status !== 'COMPLETED' && $captureStatus !== 'COMPLETED') {
    http_response_code(400);
    echo json_encode(['error' => 'Capture not completed']);
    exit;
}

if (round($amountValue, 2) !== round($total, 2) || $currencyCode !== 'USD') {
    http_response_code(400);
    echo json_encode(['error' => 'Amount mismatch']);
    exit;
}

$orderRef = 'KND-' . strtoupper(bin2hex(random_bytes(2)));
$record = [
    'created_at' => date('c'),
    'order_ref' => $orderRef,
    'paypal_order_id' => $orderID,
    'payer_email' => $body['payer']['email_address'] ?? '',
    'items' => $itemsDetailed,
    'totals' => [
        'subtotal' => round($subtotal, 2),
        'shipping' => round($shipping, 2),
        'total' => round($total, 2),
        'currency' => 'USD',
    ],
    'deliveryType' => $deliveryType,
    'customer' => [
        'name' => $customer['name'] ?? '',
        'whatsapp' => $customer['whatsapp'] ?? '',
        'notes' => $customer['notes'] ?? '',
    ],
];

$existing[] = $record;
@file_put_contents($ordersFile, json_encode($existing, JSON_PRETTY_PRINT), LOCK_EX);

echo json_encode([
    'ok' => true,
    'order_ref' => $orderRef,
    'redirect' => '/thank-you.php?ref=' . urlencode($orderRef),
]);
