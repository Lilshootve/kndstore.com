<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/config.php';

$secretsPath = __DIR__ . '/../config/admin_secrets.local.php';
if (!file_exists($secretsPath)) {
    header('Content-Type: text/html; charset=utf-8');
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>Admin - Configuration Error</title></head><body style="font-family:sans-serif;padding:2rem;background:#0a0a0a;color:#fff;">';
    echo '<h1>Configuration Error</h1><p>Admin secrets file not found at:<br><code>' . htmlspecialchars($secretsPath) . '</code></p>';
    echo '<p>Copy <code>config/admin_secrets.local.example.php</code> to that path and set your credentials.</p>';
    echo '</body></html>';
    exit;
}
$adminSecrets = require $secretsPath;
if (!is_array($adminSecrets)) {
    header('Content-Type: text/html; charset=utf-8');
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>Admin - Configuration Error</title></head><body style="font-family:sans-serif;padding:2rem;background:#0a0a0a;color:#fff;">';
    echo '<h1>Configuration Error</h1><p>Secrets file did not return an array.<br><code>' . htmlspecialchars($secretsPath) . '</code></p></body></html>';
    exit;
}
$adminUser = trim($adminSecrets['admin_user'] ?? $adminSecrets['username'] ?? '');
$adminPass = trim($adminSecrets['admin_pass'] ?? $adminSecrets['password'] ?? '');
if ($adminUser === '' || $adminPass === '') {
    header('Content-Type: text/html; charset=utf-8');
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>Admin - Configuration Error</title></head><body style="font-family:sans-serif;padding:2rem;background:#0a0a0a;color:#fff;">';
    echo '<h1>Configuration Error</h1><p>Username or password is empty in:<br><code>' . htmlspecialchars($secretsPath) . '</code></p>';
    echo '<p>Accepted keys: <code>admin_user/admin_pass</code> or <code>username/password</code>.</p></body></html>';
    exit;
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: /admin/orders.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_user'], $_POST['admin_pass'])) {
    if (hash_equals($adminUser, $_POST['admin_user']) && hash_equals($adminPass, $_POST['admin_pass'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: /admin/orders.php');
        exit;
    }
    $loginError = 'Invalid credentials.';
}

if (empty($_SESSION['admin_logged_in'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><title>Admin Login</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>';
    echo '<body class="bg-dark text-light min-vh-100 d-flex align-items-center"><div class="container" style="max-width:400px;">';
    echo '<h2 class="mb-4">KND Admin</h2>';
    if (!empty($loginError)) echo '<div class="alert alert-danger">' . htmlspecialchars($loginError) . '</div>';
    echo '<form method="post"><div class="mb-3"><label class="form-label">User</label><input type="text" name="admin_user" class="form-control bg-dark text-light border-secondary" required></div>';
    echo '<div class="mb-3"><label class="form-label">Password</label><input type="password" name="admin_pass" class="form-control bg-dark text-light border-secondary" required></div>';
    echo '<button type="submit" class="btn btn-primary w-100">Login</button></form></div></div></body></html>';
    exit;
}

require_once __DIR__ . '/../includes/storage.php';

ensure_storage_ready();

$ordersFile = storage_path('orders.json');
$bankFile   = storage_path('bank_transfer_requests.json');
$otherFile  = storage_path('other_payment_requests.json');

function loadJson($path) {
    return read_json_array($path);
}
function saveJson($path, $data) {
    $dir = dirname($path);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $fp = @fopen($path, 'c+');
    if (!$fp || !flock($fp, LOCK_EX)) return false;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

$validTabs = ['paypal', 'bank', 'whatsapp', 'test'];
$activeTab = in_array($_GET['tab'] ?? '', $validTabs) ? $_GET['tab'] : 'paypal';
$flashMsg = '';
$flashType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $orderId   = trim($_POST['order_id'] ?? '');
    $source    = $_POST['source'] ?? '';
    $newStatus = $_POST['new_status'] ?? '';
    $postTab   = in_array($_POST['tab'] ?? '', $validTabs) ? $_POST['tab'] : 'paypal';
    $allowed   = ['pending', 'awaiting_transfer', 'paid', 'delivered', 'cancelled'];

    $redirectParams = ['tab' => $postTab];
    foreach (['search_paypal','status_paypal','search_bank','status_bank','search_whatsapp','status_whatsapp','search_test','status_test'] as $k) {
        if (!empty($_POST[$k])) $redirectParams[$k] = $_POST[$k];
    }

    $result = 'not_found';

    if ($orderId && $source && in_array($newStatus, $allowed)) {
        $fileMap = [
            'paypal' => $ordersFile,
            'test'   => $ordersFile,
            'bank'   => $bankFile,
            'whatsapp' => $otherFile,
        ];
        $targetFile = $fileMap[$source] ?? null;

        if ($targetFile) {
            $data = loadJson($targetFile);
            $found = false;
            foreach ($data as $i => $r) {
                $refMatch = isset($r['order_ref']) && strcasecmp($r['order_ref'], $orderId) === 0;
                $idMatch  = isset($r['order_id']) && strcasecmp($r['order_id'], $orderId) === 0;
                if ($refMatch || $idMatch) {
                    $data[$i]['status'] = $newStatus;
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $result = saveJson($targetFile, $data) ? 'ok' : 'write_failed';
                if ($result === 'write_failed') {
                    storage_log('admin_status_update: write_failed', ['order_id' => $orderId, 'file' => $targetFile]);
                }
            }
        }
    }

    $redirectParams['update_result'] = $result;
    header('Location: /admin/orders.php?' . http_build_query($redirectParams));
    exit;
}

if (isset($_GET['update_result'])) {
    $ur = $_GET['update_result'];
    if ($ur === 'ok') { $flashMsg = 'Status updated.'; $flashType = 'success'; }
    elseif ($ur === 'not_found') { $flashMsg = 'Order not found in file.'; $flashType = 'warning'; }
    elseif ($ur === 'write_failed') { $flashMsg = 'Write failed — check server logs.'; $flashType = 'danger'; }
}

$allOrders    = loadJson($ordersFile);
$bankRequests = loadJson($bankFile);
$otherRequests = loadJson($otherFile);

$paypalOrders = [];
$testOrders   = [];
foreach ($allOrders as $i => $r) {
    if (empty($r['status'])) $r['status'] = 'paid';
    if (($r['source'] ?? 'paypal') === 'test') {
        $testOrders[] = $r;
    } else {
        $paypalOrders[] = $r;
    }
}
foreach ($bankRequests as $i => $r) {
    if (empty($r['status'])) $bankRequests[$i]['status'] = 'pending';
}
foreach ($otherRequests as $i => $r) {
    if (empty($r['status'])) $otherRequests[$i]['status'] = 'pending';
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';

echo generateHeader('Admin - Orders', 'KND Store order management');
?>
<style>
.admin-lux { --knd-cyan: #00d4ff; --knd-magenta: #ff00ff; }
.admin-lux body { background: #0a0a0f; color: #e8ecf0; }
.admin-lux .admin-card { background: rgba(12,15,22,0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; }
.admin-lux .nav-tabs .nav-link { border: none; color: rgba(255,255,255,0.6); }
.admin-lux .nav-tabs .nav-link.active { color: var(--knd-cyan); border-bottom: 2px solid var(--knd-cyan); }
.admin-lux .table { color: #e8ecf0; }
.admin-lux .table th { border-color: rgba(255,255,255,0.08); font-weight: 600; }
.admin-lux .table td { border-color: rgba(255,255,255,0.06); vertical-align: middle; }
.admin-lux .table tbody tr:hover { background: rgba(255,255,255,0.03); }
.admin-lux .order-row-expand { background: rgba(0,212,255,0.05); }
.admin-lux .btn-cyber { background: rgba(0,212,255,0.15); border: 1px solid rgba(0,212,255,0.4); color: #00d4ff; }
.admin-lux .btn-cyber:hover { background: rgba(0,212,255,0.25); color: #fff; }
.admin-lux .badge-status { font-size: 0.75rem; }
.admin-lux select.form-select { background: #0c0f16; color: #f0f4f8; border-color: rgba(255,255,255,0.12); }
</style>
<div id="particles-bg"></div>
<?php echo generateNavigation(); ?>

<div class="admin-lux">
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Order Management</h1>
            <div>
                <a href="/admin/test_order.php" class="btn btn-sm btn-cyber me-2">+ Test Order</a>
                <a href="/admin/debug_storage.php" class="btn btn-sm btn-cyber me-2">Debug</a>
                <a href="/admin/orders.php?logout=1" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>

        <?php if ($flashMsg): ?>
        <div class="alert alert-<?php echo $flashType; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($flashMsg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link <?php echo $activeTab === 'paypal' ? 'active' : ''; ?>" href="?tab=paypal">PayPal (<?php echo count($paypalOrders); ?>)</a></li>
            <li class="nav-item"><a class="nav-link <?php echo $activeTab === 'bank' ? 'active' : ''; ?>" href="?tab=bank">Bank Transfer (<?php echo count($bankRequests); ?>)</a></li>
            <li class="nav-item"><a class="nav-link <?php echo $activeTab === 'whatsapp' ? 'active' : ''; ?>" href="?tab=whatsapp">WhatsApp Other (<?php echo count($otherRequests); ?>)</a></li>
            <li class="nav-item"><a class="nav-link <?php echo $activeTab === 'test' ? 'active' : ''; ?>" href="?tab=test">Test (<?php echo count($testOrders); ?>)</a></li>
        </ul>

        <?php if ($activeTab === 'paypal'): ?>
            <?php echo renderOrderTable($paypalOrders, 'paypal', 'paypal'); ?>
        <?php elseif ($activeTab === 'bank'): ?>
            <?php echo renderOrderTable($bankRequests, 'bank', 'bank'); ?>
        <?php elseif ($activeTab === 'whatsapp'): ?>
            <?php echo renderOrderTable($otherRequests, 'whatsapp', 'whatsapp'); ?>
        <?php elseif ($activeTab === 'test'): ?>
            <?php echo renderOrderTable($testOrders, 'test', 'test'); ?>
        <?php endif; ?>
    </div>
</section>
</div>

<?php
function renderOrderTable($rows, $source, $tab) {
    $orderIdKey = ($source === 'paypal' || $source === 'test') ? 'order_ref' : 'order_id';
    $search = trim($_GET['search_' . $tab] ?? '');
    $filterStatus = trim($_GET['status_' . $tab] ?? '');
    if ($search) {
        $rows = array_filter($rows, fn($r) => stripos($r[$orderIdKey] ?? '', $search) !== false);
    }
    if ($filterStatus) {
        $rows = array_filter($rows, fn($r) => ($r['status'] ?? '') === $filterStatus);
    }
    $rows = array_values($rows);

    $paymentLabels = ['paypal' => 'PayPal', 'bank' => 'Bank Transfer', 'whatsapp' => 'WhatsApp Other', 'test' => 'Test'];
    $paymentLabel = $paymentLabels[$source] ?? ucfirst($source);

    $filterHidden = '';
    if ($search) $filterHidden .= '<input type="hidden" name="search_' . $tab . '" value="' . htmlspecialchars($search) . '">';
    if ($filterStatus) $filterHidden .= '<input type="hidden" name="status_' . $tab . '" value="' . htmlspecialchars($filterStatus) . '">';

    ob_start();
    ?>
    <div class="admin-card p-3 mb-3">
        <form method="get" class="row g-2 mb-3">
            <input type="hidden" name="tab" value="<?php echo htmlspecialchars($tab); ?>">
            <div class="col-md-4">
                <input type="text" name="search_<?php echo $tab; ?>" class="form-control bg-dark text-light border-secondary" placeholder="Search by Order ID" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="status_<?php echo $tab; ?>" class="form-select form-select-sm">
                    <option value="">All statuses</option>
                    <option value="pending" <?php echo $filterStatus === 'pending' ? 'selected' : ''; ?>>pending</option>
                    <option value="awaiting_transfer" <?php echo $filterStatus === 'awaiting_transfer' ? 'selected' : ''; ?>>awaiting_transfer</option>
                    <option value="paid" <?php echo $filterStatus === 'paid' ? 'selected' : ''; ?>>paid</option>
                    <option value="delivered" <?php echo $filterStatus === 'delivered' ? 'selected' : ''; ?>>delivered</option>
                    <option value="cancelled" <?php echo $filterStatus === 'cancelled' ? 'selected' : ''; ?>>cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-cyber">Apply</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>Payment</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach (array_reverse($rows) as $idx => $r):
                    $oid = $r[$orderIdKey] ?? '-';
                    $rowUid = $source . '-' . $idx;
                    $date = isset($r['created_at']) ? date('M j, Y H:i', strtotime($r['created_at'])) : '-';
                    $total = $r['totals']['total'] ?? 0;
                    $currency = $r['totals']['currency'] ?? 'USD';
                    $items = $r['items'] ?? [];
                    $cust = $r['customer'] ?? [];
                    if ($source === 'whatsapp') {
                        $custName = $r['customer_name'] ?? $cust['name'] ?? '-';
                        $custWhatsapp = $r['whatsapp'] ?? $cust['whatsapp'] ?? '';
                    } else {
                        $custName = $cust['name'] ?? '-';
                        $custWhatsapp = $cust['whatsapp'] ?? '';
                    }
                    $status = $r['status'] ?? (($source === 'paypal' || $source === 'test') ? 'paid' : 'pending');
                ?>
                    <tr class="order-row" data-row-uid="<?php echo htmlspecialchars($rowUid); ?>">
                        <td><?php echo htmlspecialchars($date); ?></td>
                        <td><code><?php echo htmlspecialchars($oid); ?></code></td>
                        <td><?php echo htmlspecialchars($paymentLabel); ?></td>
                        <td><?php echo htmlspecialchars($custName); ?><br><small class="text-muted"><?php echo htmlspecialchars($custWhatsapp); ?></small></td>
                        <td>$<?php echo number_format($total, 2); ?> <?php echo htmlspecialchars($currency); ?></td>
                        <td><span class="badge badge-status bg-secondary"><?php echo htmlspecialchars($status); ?></span></td>
                        <td><?php echo count($items); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-cyber expand-btn" data-target="<?php echo htmlspecialchars($rowUid); ?>">Expand</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary copy-wa-btn" data-order-id="<?php echo htmlspecialchars($oid); ?>" data-status="<?php echo htmlspecialchars($status); ?>" data-customer="<?php echo htmlspecialchars($custName); ?>">Copy WA</button>
                        </td>
                    </tr>
                    <tr class="order-details-row d-none" data-row-uid="<?php echo htmlspecialchars($rowUid); ?>-detail">
                        <td colspan="8" class="order-row-expand p-4">
                            <strong>Items:</strong>
                            <ul class="mb-2">
                            <?php foreach ($items as $it): ?>
                                <li><?php echo htmlspecialchars($it['name'] ?? 'Item'); ?> x<?php echo (int)($it['qty'] ?? 1); ?> — $<?php echo number_format($it['line_total'] ?? 0, 2); ?></li>
                            <?php endforeach; ?>
                            </ul>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="tab" value="<?php echo htmlspecialchars($tab); ?>">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($oid); ?>">
                                <input type="hidden" name="source" value="<?php echo htmlspecialchars($source); ?>">
                                <?php echo $filterHidden; ?>
                                <label class="me-2 text-muted">Current: <strong><?php echo htmlspecialchars($status); ?></strong></label>
                                <select name="new_status" class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>pending</option>
                                    <option value="awaiting_transfer" <?php echo $status === 'awaiting_transfer' ? 'selected' : ''; ?>>awaiting_transfer</option>
                                    <option value="paid" <?php echo $status === 'paid' ? 'selected' : ''; ?>>paid</option>
                                    <option value="delivered" <?php echo $status === 'delivered' ? 'selected' : ''; ?>>delivered</option>
                                    <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-cyber ms-2">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (empty($rows)): ?>
        <p class="text-muted mb-0">No orders found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.expand-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var uid = this.dataset.target;
            var details = document.querySelector('.order-details-row[data-row-uid="' + uid + '-detail"]');
            if (details) {
                details.classList.toggle('d-none');
                this.textContent = details.classList.contains('d-none') ? 'Expand' : 'Collapse';
            }
        });
    });
    document.querySelectorAll('.copy-wa-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var oid = this.dataset.orderId;
            var status = this.dataset.status;
            var customer = this.dataset.customer;
            var templates = {
                pending: 'Hi ' + customer + ', your order ' + oid + ' is being processed. We will confirm payment options shortly.',
                awaiting_transfer: 'Hi ' + customer + ', please complete the transfer for order ' + oid + '. Banking details were sent separately.',
                paid: 'Hi ' + customer + ', we received payment for order ' + oid + '. We will ship/deliver soon.',
                delivered: 'Hi ' + customer + ', your order ' + oid + ' has been delivered. Thank you!',
                cancelled: 'Hi ' + customer + ', order ' + oid + ' was cancelled. If you have questions, contact us.'
            };
            var msg = templates[status] || 'Order ' + oid + ' - Status: ' + status;
            navigator.clipboard.writeText(msg).then(function() { alert('Copied to clipboard'); }).catch(function() { prompt('Copy:', msg); });
        });
    });
});
</script>

<?php echo generateFooter(); echo generateScripts(); ?>
