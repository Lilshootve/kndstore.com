<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
header('X-Robots-Tag: noindex, nofollow');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/config.php';

$secretsPath = __DIR__ . '/../config/admin_secrets.local.php';
if (!file_exists($secretsPath)) {
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>Admin - Configuration Error</title></head><body style="font-family:sans-serif;padding:2rem;background:#0a0a0a;color:#fff;">';
    echo '<h1>Configuration Error</h1><p>Admin secrets file not found at:<br><code>' . htmlspecialchars($secretsPath) . '</code></p></body></html>';
    exit;
}
$adminSecrets = require $secretsPath;
if (!is_array($adminSecrets)) { http_response_code(500); echo 'Bad auth config'; exit; }
$adminUser = trim($adminSecrets['admin_user'] ?? $adminSecrets['username'] ?? '');
$adminPass = trim($adminSecrets['admin_pass'] ?? $adminSecrets['password'] ?? '');
if ($adminUser === '' || $adminPass === '') { http_response_code(500); echo 'Admin credentials not configured.'; exit; }

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_user'], $_POST['admin_pass'])) {
    if (hash_equals($adminUser, $_POST['admin_user']) && hash_equals($adminPass, $_POST['admin_pass'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: /admin/');
        exit;
    }
    $loginError = 'Invalid credentials.';
}

if (empty($_SESSION['admin_logged_in'])) {
    echo '<!DOCTYPE html><html><head><title>KND Admin</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>';
    echo '<body class="bg-dark text-light min-vh-100 d-flex align-items-center"><div class="container" style="max-width:400px;">';
    echo '<h2 class="mb-4">KND Admin</h2>';
    if (!empty($loginError)) echo '<div class="alert alert-danger">' . htmlspecialchars($loginError) . '</div>';
    echo '<form method="post"><div class="mb-3"><label class="form-label">User</label><input type="text" name="admin_user" class="form-control bg-dark text-light border-secondary" required></div>';
    echo '<div class="mb-3"><label class="form-label">Password</label><input type="password" name="admin_pass" class="form-control bg-dark text-light border-secondary" required></div>';
    echo '<button type="submit" class="btn btn-primary w-100">Login</button></form></div></body></html>';
    exit;
}

require_once __DIR__ . '/../includes/storage.php';
ensure_storage_ready();

$ordersFile = storage_path('orders.json');
$bankFile   = storage_path('bank_transfer_requests.json');
$otherFile  = storage_path('other_payment_requests.json');

clearstatcache(true);
$allOrders     = read_json_array($ordersFile);
$bankRequests  = read_json_array($bankFile);
$otherRequests = read_json_array($otherFile);

$totalOrders = count($allOrders) + count($bankRequests) + count($otherRequests);

$pendingCount = 0;
$paidCount = 0;
$lastTimestamp = null;

foreach (array_merge($allOrders, $bankRequests, $otherRequests) as $r) {
    $st = $r['status'] ?? 'pending';
    if ($st === 'pending') $pendingCount++;
    if (in_array($st, ['paid', 'completed', 'delivered'])) $paidCount++;
    $ts = $r['created_at'] ?? null;
    if ($ts && ($lastTimestamp === null || strtotime($ts) > strtotime($lastTimestamp))) {
        $lastTimestamp = $ts;
    }
}

$storagePath = storage_path();
$storageWritable = is_writable($storagePath);
$logFile = storage_path('logs/payments.log');
$lastLogTime = null;
if (file_exists($logFile) && filesize($logFile) > 0) {
    $fh = @fopen($logFile, 'r');
    if ($fh) {
        fseek($fh, max(0, filesize($logFile) - 512));
        $tail = fread($fh, 512);
        fclose($fh);
        if (preg_match_all('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $tail, $m)) {
            $lastLogTime = end($m[1]);
        }
    }
}

$navCards = [
    ['title' => 'Order Management', 'desc' => 'View, filter and update all orders', 'href' => '/admin/orders.php', 'icon' => 'fa-clipboard-list', 'show' => true],
    ['title' => 'Create Test Order', 'desc' => 'Generate a synthetic order for testing', 'href' => '/admin/test_order.php', 'icon' => 'fa-flask', 'show' => true],
    ['title' => 'Storage Diagnostics', 'desc' => 'Inspect JSON files, permissions and sizes', 'href' => '/admin/debug_storage.php', 'icon' => 'fa-database', 'show' => file_exists(__DIR__ . '/debug_storage.php')],
    ['title' => 'Email Test', 'desc' => 'Send a test confirmation email', 'href' => '/admin/email-test.php', 'icon' => 'fa-envelope', 'show' => file_exists(__DIR__ . '/email-test.php')],
];

require_once __DIR__ . '/../includes/header.php';
echo generateHeader('KND Admin', 'Admin dashboard');
?>
<style>
.admin-dash { --cyan: #00d4ff; min-height: 100vh; background: #0a0a0f; color: #e8ecf0; padding-top: 100px; }
.kpi-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.kpi-card { background: rgba(12,15,22,.85); border: 1px solid rgba(255,255,255,.08); border-radius: 12px; padding: 1.25rem 1.5rem; }
.kpi-value { font-size: 2rem; font-weight: 700; color: var(--cyan); line-height: 1.1; }
.kpi-label { font-size: .8rem; text-transform: uppercase; letter-spacing: .8px; color: rgba(255,255,255,.5); margin-top: .35rem; }
.nav-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
.nav-card { display: block; background: rgba(12,15,22,.85); border: 1px solid rgba(255,255,255,.08); border-radius: 14px; padding: 1.75rem; text-decoration: none !important; color: #e8ecf0 !important; transition: border-color .2s, box-shadow .2s, transform .2s; }
.nav-card:hover { border-color: rgba(0,212,255,.35); box-shadow: 0 0 24px rgba(0,212,255,.12); transform: translateY(-3px); }
.nav-card .card-icon { font-size: 1.6rem; color: var(--cyan); margin-bottom: .75rem; }
.nav-card h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: .4rem; }
.nav-card p { font-size: .85rem; color: rgba(255,255,255,.5); margin: 0; }
.sys-box { background: rgba(12,15,22,.85); border: 1px solid rgba(255,255,255,.08); border-radius: 12px; padding: 1.25rem 1.5rem; font-size: .85rem; }
.sys-box .label { color: rgba(255,255,255,.5); }
.sys-ok { color: #4ade80; }
.sys-fail { color: #f87171; }
.admin-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
.admin-topbar h1 { font-size: 1.75rem; font-weight: 700; margin: 0; }
</style>

<div class="admin-dash">
<div class="container">
    <div class="admin-topbar">
        <h1><i class="fas fa-terminal me-2" style="color:var(--cyan)"></i>KND Admin</h1>
        <a href="/admin/?logout=1" class="btn btn-outline-light btn-sm">Logout</a>
    </div>

    <div class="kpi-strip">
        <div class="kpi-card">
            <div class="kpi-value"><?php echo $totalOrders; ?></div>
            <div class="kpi-label">Total Orders</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-value"><?php echo $pendingCount; ?></div>
            <div class="kpi-label">Pending</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-value"><?php echo $paidCount; ?></div>
            <div class="kpi-label">Paid / Completed</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-value" style="font-size:1.1rem;"><?php echo $lastTimestamp ? date('M j, H:i', strtotime($lastTimestamp)) : '—'; ?></div>
            <div class="kpi-label">Last Order</div>
        </div>
    </div>

    <div class="nav-cards">
        <?php foreach ($navCards as $card): if (!$card['show']) continue; ?>
        <a href="<?php echo $card['href']; ?>" class="nav-card">
            <div class="card-icon"><i class="fas <?php echo $card['icon']; ?>"></i></div>
            <h3><?php echo htmlspecialchars($card['title']); ?></h3>
            <p><?php echo htmlspecialchars($card['desc']); ?></p>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="sys-box">
        <strong style="color:var(--cyan)">System Status</strong>
        <div class="d-flex flex-wrap gap-4 mt-2">
            <div>
                <span class="label">Storage writable:</span>
                <span class="<?php echo $storageWritable ? 'sys-ok' : 'sys-fail'; ?>"><?php echo $storageWritable ? 'Yes' : 'No'; ?></span>
            </div>
            <div>
                <span class="label">Last log entry:</span>
                <span><?php echo $lastLogTime ?? '—'; ?></span>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
