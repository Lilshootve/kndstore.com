<?php
require_once __DIR__ . '/../../includes/pricing.php';

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

$items = $payload['items'] ?? [];
$deliveryType = isset($payload['deliveryType']) ? (string) $payload['deliveryType'] : '';
$customer = isset($payload['customer']) && is_array($payload['customer']) ? $payload['customer'] : [];
$altMethod = isset($payload['alt_method']) ? trim((string) $payload['alt_method']) : '';

$allowedAltMethods = [
    'binance pay' => 'Binance Pay',
    'usdt trc20' => 'USDT (TRC20)',
    'usdt bep20' => 'USDT (BEP20)',
    'zinli' => 'Zinli',
    'wally' => 'Wally',
    'pipol pay' => 'Pipol Pay',
];
$altMethodNormalized = $allowedAltMethods[strtolower($altMethod)] ?? $altMethod;

if (!is_array($items) || empty($items)) {
    http_response_code(400);
    echo json_encode(['error' => 'No items provided']);
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
$total = round($subtotal + $shipping, 2);

$orderId = 'KND-' . date('Y') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

$customerName = trim($customer['name'] ?? '');
$customerWhatsapp = trim($customer['whatsapp'] ?? '');
$customerEmail = trim($customer['email'] ?? '');
$customerNotes = trim($customer['notes'] ?? '');

$record = [
    'order_id' => $orderId,
    'created_at' => date('c'),
    'customer_name' => $customerName,
    'whatsapp' => $customerWhatsapp,
    'email' => $customerEmail ?: null,
    'items' => $itemsDetailed,
    'totals' => [
        'subtotal' => round($subtotal, 2),
        'shipping' => round($shipping, 2),
        'total' => $total,
        'currency' => 'USD',
    ],
    'deliveryType' => $deliveryType,
    'notes' => $customerNotes ?: null,
    'alt_method' => $altMethodNormalized ?: null,
    'status' => 'pending',
    'payment_method' => 'WhatsApp Other',
];

$storageDir = __DIR__ . '/../../storage';
$requestsFile = $storageDir . '/other_payment_requests.json';

if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0755, true);
}
if (!file_exists($requestsFile)) {
    @file_put_contents($requestsFile, '[]');
}

$fp = fopen($requestsFile, 'r+b');
if (!$fp) {
    http_response_code(500);
    echo json_encode(['error' => 'Storage error']);
    exit;
}
if (!flock($fp, LOCK_EX)) {
    fclose($fp);
    http_response_code(500);
    echo json_encode(['error' => 'Storage lock error']);
    exit;
}

$existing = json_decode(stream_get_contents($fp), true);
if (!is_array($existing)) {
    $existing = [];
}
$existing[] = $record;
ftruncate($fp, 0);
rewind($fp);
fwrite($fp, json_encode($existing, JSON_PRETTY_PRINT));
flock($fp, LOCK_UN);
fclose($fp);

$phone = '584141592319';
$msg = '🛰 *New order from KND Store*%0A%0A';
$msg .= '*Order ID:* ' . $orderId . '%0A';
$msg .= '*Name:* ' . rawurlencode($customerName) . '%0A';
$msg .= '*Customer WhatsApp:* ' . rawurlencode($customerWhatsapp) . '%0A';
$msg .= '%0A*Requested items:*%0A';
foreach ($itemsDetailed as $it) {
    $msg .= '- ' . rawurlencode($it['name']) . ' (x' . $it['qty'] . ') - $' . number_format($it['line_total'], 2) . '%0A';
    if (!empty($it['variants'])) {
        if (!empty($it['variants']['size'])) $msg .= '  Size: ' . rawurlencode($it['variants']['size']) . '%0A';
        if (!empty($it['variants']['color'])) $msg .= '  Color: ' . rawurlencode($it['variants']['color']) . '%0A';
    }
}
$msg .= '%0A*Total:* $' . number_format($total, 2) . '%0A';
$msg .= '*Payment method:* WhatsApp (Other)%0A';
if ($altMethodNormalized) {
    $msg .= '*Alternative method:* ' . rawurlencode($altMethodNormalized) . '%0A';
}
if ($deliveryType) {
    $msg .= '*Delivery type:* ' . rawurlencode($deliveryType) . '%0A';
}
if ($customerNotes) {
    $msg .= '%0A*Notes:* ' . rawurlencode($customerNotes) . '%0A';
}
$msg .= '%0ASend the payment receipt here when you have it ready.';

$whatsappUrl = 'https://wa.me/' . $phone . '?text=' . $msg;

echo json_encode([
    'ok' => true,
    'order_id' => $orderId,
    'whatsapp_url' => $whatsappUrl,
]);
