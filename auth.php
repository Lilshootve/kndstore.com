<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

if (is_logged_in()) {
    header('Location: /death-roll-lobby.php');
    exit;
}

$redirect = htmlspecialchars($_GET['redirect'] ?? '/death-roll-lobby.php', ENT_QUOTES);
$csrfToken = csrf_token();
?>

<?php
$seoTitle = 'KND LastRoll — Account';
$seoDesc  = 'Login or register to play KND LastRoll, a next-gen Death Roll 1v1 game.';
$ogHead   = '    <meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
$ogHead  .= '    <meta property="og:type" content="website">' . "\n";
$ogHead  .= '    <meta name="twitter:card" content="summary_large_image">' . "\n";
$ogHead  .= '    <meta name="twitter:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta name="twitter:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
echo generateHeader($seoTitle, $seoDesc, $ogHead);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height: 100vh; padding-top: 120px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="glass-card-neon p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="glow-text mb-2"><i class="fas fa-dice-d20 me-2"></i>KND LastRoll</h2>
                        <p class="text-white-50"><?php echo t('dr.auth.subtitle', 'Login or register to play'); ?></p>
                        <p class="text-white-50 small mb-0" style="opacity:0.5; font-style:italic;">Next-gen Death Roll 1v1</p>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-pills nav-fill mb-4" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-login" type="button"><?php echo t('dr.auth.login', 'Login'); ?></button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-register" type="button"><?php echo t('dr.auth.register', 'Register'); ?></button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Login -->
                        <div class="tab-pane fade show active" id="tab-login">
                            <form id="form-login" autocomplete="on">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo t('dr.auth.username', 'Username'); ?></label>
                                    <input type="text" name="username" class="form-control" required minlength="3" maxlength="24" pattern="[A-Za-z0-9_]+" autocomplete="username">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo t('dr.auth.password', 'Password'); ?></label>
                                    <input type="password" name="password" class="form-control" required minlength="8" autocomplete="current-password">
                                </div>
                                <button type="submit" class="btn btn-neon-primary w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i><?php echo t('dr.auth.login', 'Login'); ?>
                                </button>
                            </form>
                        </div>

                        <!-- Register -->
                        <div class="tab-pane fade" id="tab-register">
                            <form id="form-register" autocomplete="on">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                <div class="mb-3">
                                    <label class="form-label"><?php echo t('dr.auth.username', 'Username'); ?></label>
                                    <input type="text" name="username" class="form-control" required minlength="3" maxlength="24" pattern="[A-Za-z0-9_]+" autocomplete="username">
                                    <div class="form-text text-white-50"><?php echo t('dr.auth.username_hint', '3-24 chars: letters, numbers, underscore'); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo t('dr.auth.password', 'Password'); ?></label>
                                    <input type="password" name="password" class="form-control" required minlength="8" autocomplete="new-password">
                                    <div class="form-text text-white-50"><?php echo t('dr.auth.password_hint', 'Minimum 8 characters'); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo t('dr.auth.confirm_password', 'Confirm Password'); ?></label>
                                    <input type="password" name="password_confirm" class="form-control" required minlength="8" autocomplete="new-password">
                                </div>
                                <button type="submit" class="btn btn-outline-neon w-100">
                                    <i class="fas fa-user-plus me-2"></i><?php echo t('dr.auth.register', 'Register'); ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="auth-alert" class="mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>

<?php echo generateFooter(); ?>

<script>
const REDIRECT = <?php echo json_encode($redirect); ?>;

function showAlert(msg, type) {
    const el = document.getElementById('auth-alert');
    el.innerHTML = '<div class="alert alert-' + type + ' mb-0">' + msg + '</div>';
    el.style.display = 'block';
}

document.getElementById('form-login').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch('/api/auth/login.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            if (d.ok) {
                window.location.href = REDIRECT;
            } else {
                showAlert(d.error.message, 'danger');
            }
        })
        .catch(() => showAlert('Connection error.', 'danger'));
});

document.getElementById('form-register').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    if (fd.get('password') !== fd.get('password_confirm')) {
        showAlert('<?php echo t("dr.auth.password_mismatch", "Passwords do not match."); ?>', 'warning');
        return;
    }
    fetch('/api/auth/register.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            if (d.ok) {
                window.location.href = REDIRECT;
            } else {
                showAlert(d.error.message, 'danger');
            }
        })
        .catch(() => showAlert('Connection error.', 'danger'));
});
</script>

<?php echo generateScripts(); ?>
