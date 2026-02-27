<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$ref = isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : '';
?>
<?php echo generateHeader('Thank you - KND Store', 'Your order has been received.'); ?>

<div id="particles-bg"></div>
<?php echo generateNavigation(); ?>

<section class="order-section py-5">
    <div class="container text-center py-5">
        <div class="card knd-card mx-auto" style="max-width: 500px;">
            <div class="card-body py-5">
                <i class="fas fa-check-circle text-success mb-3" style="font-size: 4rem;"></i>
                <h2 class="mb-3">Payment approved</h2>
                <p class="text-muted mb-4">Your order has been received and payment was successful.</p>
                <?php if ($ref): ?>
                <p class="mb-2"><span class="text-muted">Order ID:</span> <strong class="fs-5"><?php echo $ref; ?></strong></p>
                <p class="small text-muted mb-4">You can track your order anytime at <a href="/track-order.php?id=<?php echo urlencode($ref); ?>" class="text-decoration-underline" style="color: var(--knd-accent-cyan);">/track-order</a></p>
                <?php endif; ?>
                <a href="/" class="btn btn-outline-neon mt-3">Back to Home</a>
            </div>
        </div>
    </div>
</section>

<?php echo generateFooter(); echo generateScripts(); ?>
