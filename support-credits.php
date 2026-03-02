<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
ini_set('display_errors', '0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/support_credits.php';

require_login();

$csrfToken = csrf_token();
$userId = current_user_id();
$username = htmlspecialchars(current_username());
$ptsRate = defined('SUPPORT_POINTS_PER_USD') ? SUPPORT_POINTS_PER_USD : 100;

$pdo = getDBConnection();
$balance = ['pending' => 0, 'available' => 0, 'locked' => 0, 'spent_total' => 0, 'expiring_soon' => []];
try {
    if ($pdo) {
        release_available_points_if_due($pdo, $userId);
        expire_points_if_due($pdo, $userId);
        $balance = get_points_balance($pdo, $userId);
    }
} catch (\Throwable $e) {
    error_log('support-credits page balance error: ' . $e->getMessage());
}

$seoTitle = t('sc.page_title', 'KND Support Credits') . ' | KND Store';
$seoDesc  = t('sc.page_desc', 'Support KND and earn credits you can redeem for services and rewards.');
echo generateHeader($seoTitle, $seoDesc);
?>

<div id="particles-bg"></div>
<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height: 100vh; padding-top: 120px; padding-bottom: 60px;">
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="glow-text mb-2"><i class="fas fa-coins me-2"></i><?php echo t('sc.title', 'KND Support Credits'); ?></h1>
            <p class="text-white-50 mb-0"><?php echo t('sc.subtitle', 'Support KND, earn credits, redeem rewards.'); ?></p>
        </div>
    </div>

    <!-- Balance Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="glass-card-neon p-3 text-center">
                <div class="text-white-50 small mb-1"><?php echo t('sc.pending', 'Pending'); ?></div>
                <div class="fs-3 fw-bold" style="color: #ffc107;" id="bal-pending"><?php echo number_format($balance['pending']); ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card-neon p-3 text-center">
                <div class="text-white-50 small mb-1"><?php echo t('sc.available', 'Available'); ?></div>
                <div class="fs-3 fw-bold" style="color: var(--knd-neon-blue);" id="bal-available"><?php echo number_format($balance['available']); ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card-neon p-3 text-center">
                <div class="text-white-50 small mb-1"><?php echo t('sc.spent', 'Spent'); ?></div>
                <div class="fs-3 fw-bold text-white-50" id="bal-spent"><?php echo number_format($balance['spent_total']); ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card-neon p-3 text-center">
                <div class="text-white-50 small mb-1"><?php echo t('sc.expiring', 'Expiring Soon'); ?></div>
                <div class="fs-3 fw-bold" style="color: #fd7e14;" id="bal-expiring"><?php echo count($balance['expiring_soon']); ?></div>
            </div>
        </div>
    </div>

    <?php if (!empty($balance['expiring_soon'])): ?>
    <div class="alert" style="background: rgba(253,126,20,0.1); border: 1px solid rgba(253,126,20,0.3); color: #fd7e14;">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo t('sc.expiring_warning', 'Some credits will expire soon:'); ?>
        <?php foreach ($balance['expiring_soon'] as $exp): ?>
            <span class="badge bg-warning text-dark ms-1"><?php echo $exp['points']; ?> pts — <?php echo date('M d, Y', strtotime($exp['expires_at'])); ?></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Support KND -->
        <div class="col-lg-7">
            <div class="glass-card-neon p-4">
                <h3 class="mb-3"><i class="fas fa-microchip me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('sc.obtain_credits', 'Obtain Credits'); ?></h3>

                <div class="mb-3">
                    <label class="form-label text-white-50"><?php echo t('sc.select_amount', 'Select Amount (USD)'); ?></label>
                    <div class="d-flex flex-wrap gap-2 mb-2" id="sc-packs">
                        <button class="btn btn-outline-light sc-pack-btn" data-amount="5">$5</button>
                        <button class="btn btn-outline-light sc-pack-btn" data-amount="10">$10</button>
                        <button class="btn btn-outline-light sc-pack-btn active" data-amount="25">$25</button>
                        <button class="btn btn-outline-light sc-pack-btn" data-amount="50">$50</button>
                        <button class="btn btn-outline-light sc-pack-btn" data-amount="100">$100</button>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 200px;">
                        <span class="input-group-text bg-dark text-white border-secondary">$</span>
                        <input type="number" id="sc-amount" class="form-control bg-dark text-white border-secondary" value="25" min="1" max="500" step="0.01">
                    </div>
                    <div class="small text-white-50 mt-1">
                        = <strong id="sc-points-preview"><?php echo 25 * $ptsRate; ?></strong> <?php echo t('sc.credits_label', 'credits'); ?>
                        (<?php echo $ptsRate; ?> per $1)
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50"><?php echo t('sc.payment_method', 'Payment Method'); ?></label>
                    <div class="d-flex flex-wrap gap-2" id="sc-methods">
                        <button class="btn btn-outline-light sc-method-btn active" data-method="paypal"><i class="fab fa-paypal me-1"></i>PayPal</button>
                        <button class="btn btn-outline-light sc-method-btn" data-method="binance_pay"><i class="fas fa-coins me-1"></i>Binance Pay</button>
                        <button class="btn btn-outline-light sc-method-btn" data-method="zinli"><i class="fas fa-wallet me-1"></i>Zinli</button>
                        <button class="btn btn-outline-light sc-method-btn" data-method="pago_movil"><i class="fas fa-mobile-alt me-1"></i>Pago Móvil</button>
                        <button class="btn btn-outline-light sc-method-btn" data-method="ach"><i class="fas fa-university me-1"></i>ACH</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50"><?php echo t('sc.notes_label', 'Notes (optional)'); ?></label>
                    <input type="text" id="sc-notes" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="<?php echo t('sc.notes_placeholder', 'Transaction ID, reference, etc.'); ?>" maxlength="200">
                </div>

                <button id="sc-submit" class="btn btn-neon-primary btn-lg">
                    <i class="fas fa-paper-plane me-2"></i><?php echo t('sc.submit_support', 'Submit Support'); ?>
                </button>

                <div id="sc-result" class="mt-3" style="display:none;"></div>
            </div>
        </div>

        <!-- Info & Legal -->
        <div class="col-lg-5">
            <div class="glass-card-neon p-4 mb-3">
                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i><?php echo t('sc.how_it_works', 'How It Works'); ?></h5>
                <ol class="text-white-50 small ps-3">
                    <li class="mb-2"><?php echo t('sc.step1', 'Choose an amount and payment method.'); ?></li>
                    <li class="mb-2"><?php echo t('sc.step2', 'Submit your support. Credits appear as Pending.'); ?></li>
                    <li class="mb-2"><?php echo t('sc.step3', 'After admin confirms your payment, credits become Available after the hold period.'); ?></li>
                    <li class="mb-2"><?php echo t('sc.step4', 'Redeem available credits in the Rewards catalog.'); ?></li>
                </ol>
            </div>

            <div class="glass-card-neon p-4" style="border-color: rgba(253,126,20,0.3);">
                <h5 class="mb-3" style="color: #fd7e14;"><i class="fas fa-gavel me-2"></i><?php echo t('sc.legal_title', 'Important Notice'); ?></h5>
                <ul class="text-white-50 small ps-3 mb-0">
                    <li class="mb-1"><?php echo t('sc.legal_1', 'KND Support Credits are NOT money.'); ?></li>
                    <li class="mb-1"><?php echo t('sc.legal_2', 'Credits are non-transferable and non-refundable.'); ?></li>
                    <li class="mb-1"><?php echo t('sc.legal_3', 'Hold period: 7-10 business days (Mon-Fri).'); ?></li>
                    <li class="mb-1"><?php echo t('sc.legal_4', 'Credits expire 12 months after becoming available.'); ?></li>
                    <li class="mb-1"><?php echo t('sc.legal_5', 'Subject to availability. We may revoke credits in case of fraud or dispute.'); ?></li>
                    <li><?php echo t('sc.legal_6', 'This system is in BETA. Terms may change.'); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="row mt-4">
        <div class="col">
            <div class="glass-card-neon p-4">
                <h5 class="mb-3"><i class="fas fa-history me-2"></i><?php echo t('sc.my_payments', 'My Payments'); ?></h5>
                <div class="table-responsive">
                    <table class="table table-sm" style="color: #ccc;">
                        <thead><tr><th>#</th><th><?php echo t('sc.method', 'Method'); ?></th><th><?php echo t('sc.amount', 'Amount'); ?></th><th><?php echo t('sc.credits_label', 'Credits'); ?></th><th><?php echo t('sc.status', 'Status'); ?></th><th><?php echo t('sc.date', 'Date'); ?></th></tr></thead>
                        <tbody id="sc-payments-list">
                        <?php
                        $payments = [];
                        try {
                            if ($pdo) {
                                $stmt = $pdo->prepare(
                                    "SELECT sp.*, COALESCE(pl.points, 0) AS points FROM support_payments sp
                                     LEFT JOIN points_ledger pl ON pl.source_type='support_payment' AND pl.source_id=sp.id AND pl.entry_type='earn'
                                     WHERE sp.user_id = ? ORDER BY sp.created_at DESC LIMIT 20"
                                );
                                $stmt->execute([$userId]);
                                $payments = $stmt->fetchAll();
                            }
                        } catch (\Throwable $e) { $payments = []; }
                        foreach ($payments as $p):
                        ?>
                            <tr>
                                <td><?php echo $p['id']; ?></td>
                                <td><?php echo htmlspecialchars($p['method']); ?></td>
                                <td>$<?php echo number_format($p['amount_usd'], 2); ?></td>
                                <td><?php echo number_format($p['points']); ?></td>
                                <td>
                                    <?php
                                    $statusColors = ['pending'=>'warning','confirmed'=>'success','rejected'=>'danger','disputed'=>'warning','refunded'=>'secondary'];
                                    $sc = $statusColors[$p['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $sc; ?>"><?php echo $p['status']; ?></span>
                                </td>
                                <td class="small"><?php echo date('M d, Y H:i', strtotime($p['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<?php echo generateFooter(); ?>
<script src="/assets/js/navigation-extend.js"></script>
<?php echo generateScripts(); ?>

<script>
(function() {
    var CSRF = '<?php echo $csrfToken; ?>';
    var PTS_RATE = <?php echo $ptsRate; ?>;
    var selectedAmount = 25;
    var selectedMethod = 'paypal';

    var amountInput = document.getElementById('sc-amount');
    var preview = document.getElementById('sc-points-preview');
    var resultDiv = document.getElementById('sc-result');

    if (!amountInput || !preview || !resultDiv) return;

    document.querySelectorAll('.sc-pack-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.sc-pack-btn').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            selectedAmount = parseFloat(this.dataset.amount);
            amountInput.value = selectedAmount;
            preview.textContent = Math.round(selectedAmount * PTS_RATE).toLocaleString();
        });
    });

    amountInput.addEventListener('input', function() {
        selectedAmount = parseFloat(this.value) || 0;
        preview.textContent = Math.round(selectedAmount * PTS_RATE).toLocaleString();
        document.querySelectorAll('.sc-pack-btn').forEach(function(b) {
            b.classList.toggle('active', parseFloat(b.dataset.amount) === selectedAmount);
        });
    });

    document.querySelectorAll('.sc-method-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.sc-method-btn').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            selectedMethod = this.dataset.method;
        });
    });

    var submitBtn = document.getElementById('sc-submit');
    if (submitBtn) submitBtn.addEventListener('click', function() {
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        resultDiv.style.display = 'none';

        var fd = new FormData();
        fd.append('method', selectedMethod);
        fd.append('amount_usd', selectedAmount);
        fd.append('notes', document.getElementById('sc-notes').value);
        fd.append('csrf_token', CSRF);

        fetch('/api/support-credits/create_payment.php', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.ok) {
                    resultDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>'
                        + 'Payment submitted! <strong>' + data.data.pending_points + ' credits</strong> pending.'
                        + ' Available after: <strong>' + data.data.available_at + '</strong>'
                        + ' (hold: ' + data.data.hold_days + ' business days)</div>';
                    resultDiv.style.display = 'block';
                    setTimeout(function() { location.reload(); }, 3000);
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>'
                        + ((data.error && data.error.message) || 'Error') + '</div>';
                    resultDiv.style.display = 'block';
                }
            })
            .catch(function() {
                resultDiv.innerHTML = '<div class="alert alert-danger">Network error. Try again.</div>';
                resultDiv.style.display = 'block';
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Support';
            });
    });
})();
</script>
