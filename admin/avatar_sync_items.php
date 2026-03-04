<?php
/**
 * Avatar Sync - Scan assets/avatars/{slot}/*.svg and INSERT missing items into knd_avatar_items.
 * Admin only. Run via browser: /admin/avatar_sync_items.php
 */
require_once __DIR__ . '/_guard.php';
admin_require_login();

$pdo = getDBConnection();
if (!$pdo) {
    die('DB connection failed.');
}

$baseDir = realpath(__DIR__ . '/../assets/avatars');
$slots = ['bg', 'top', 'bottom', 'shoes', 'hair', 'accessory', 'frame'];
$inserted = [];
$skipped = [];
$errors = [];

$stmt = $pdo->query('SELECT asset_path, code FROM knd_avatar_items');
$existing = [];
$existingCodes = [];
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $existing[$r['asset_path']] = true;
    $existingCodes[$r['code']] = true;
}

foreach ($slots as $slot) {
    $dir = $baseDir . '/' . $slot;
    if (!is_dir($dir)) continue;
    $files = glob($dir . '/*.svg');
    foreach ($files as $f) {
        $name = basename($f);
        $path = '/assets/avatars/' . $slot . '/' . $name;
        if (isset($existing[$path])) {
            $skipped[] = $path;
            continue;
        }
        $base = pathinfo($name, PATHINFO_FILENAME);
        $base = preg_replace('/\.+$/', '', $base);
        $base = preg_replace('/[^a-z0-9_]/i', '_', $base);
        $code = $slot . '_' . ($base ?: substr(md5($path), 0, 6));
        $n = 0;
        while (isset($existingCodes[$code])) {
            $n++;
            $code = $slot . '_' . $base . '_' . $n;
        }
        $existingCodes[$code] = true;
        $displayName = ucwords(str_replace(['_', '-'], ' ', $base));
        $price = 50;
        try {
            $pdo->prepare(
                "INSERT INTO knd_avatar_items (code, slot, name, rarity, price_kp, asset_path, is_active) VALUES (?, ?, ?, 'common', ?, ?, 1)"
            )->execute([$code, $slot, $displayName, $price, $path]);
            $inserted[] = $path;
            $existing[$path] = true;
        } catch (\Throwable $e) {
            $errors[] = $path . ': ' . $e->getMessage();
        }
    }
}

header('Content-Type: application/json');
echo json_encode([
    'ok' => true,
    'inserted' => $inserted,
    'skipped_count' => count($skipped),
    'errors' => $errors,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
