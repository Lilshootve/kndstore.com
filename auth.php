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
    $pdo = getDBConnection();
    if ($pdo) {
        $uid = current_user_id();
        $evStmt = $pdo->prepare('SELECT email, email_verified FROM users WHERE id = ? LIMIT 1');
        $evStmt->execute([$uid]);
        $evRow = $evStmt->fetch();
        if ($evRow && !empty($evRow['email']) && (int) $evRow['email_verified'] === 0) {
            $showVerify = true;
        } else {
            header('Location: /death-roll-lobby.php');
            exit;
        }
    } else {
        header('Location: /death-roll-lobby.php');
        exit;
    }
}
$showVerify = $showVerify ?? false;

$redirect = htmlspecialchars($_GET['redirect'] ?? '/death-roll-lobby.php', ENT_QUOTES);
$csrfToken = csrf_token();
?>

<?php
$seoTitle = 'KND Access — Sign In';
$seoDesc  = 'Sign in to your KND ecosystem. Access KND Arena, LastRoll, Support Credits, and more.';
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
                        <h2 class="glow-text mb-2"><i class="fas fa-shield-alt me-2"></i>KND Access</h2>
                        <p class="text-white-50"><?php echo t('dr.auth.subtitle', 'Sign in to your KND ecosystem'); ?></p>
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
                                <div class="text-center mt-3">
                                    <a href="#" id="link-forgot" class="text-white-50 small" style="text-decoration:underline;">
                                        <i class="fas fa-question-circle me-1"></i><?php echo t('dr.auth.forgot_link', 'Forgot your password or username?'); ?>
                                    </a>
                                </div>
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
                                    <label class="form-label"><?php echo t('dr.auth.email', 'Email'); ?></label>
                                    <input type="email" name="email" class="form-control" required maxlength="255" autocomplete="email">
                                    <div class="form-text text-white-50"><?php echo t('dr.auth.email_hint', 'We\'ll send a verification code'); ?></div>
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

                <!-- Forgot Password/Username Panel -->
                <div id="forgot-panel" class="glass-card-neon p-4 p-md-5 mt-4" style="display:none;">
                    <div class="text-center mb-4">
                        <h3 class="glow-text mb-2"><i class="fas fa-key me-2"></i><?php echo t('dr.auth.forgot_title', 'Account Recovery'); ?></h3>
                        <p class="text-white-50"><?php echo t('dr.auth.forgot_subtitle', 'Enter the email linked to your account'); ?></p>
                    </div>

                    <!-- Step 1: Enter email & choose action -->
                    <div id="forgot-step1">
                        <div class="mb-3">
                            <label class="form-label"><?php echo t('dr.auth.email', 'Email'); ?></label>
                            <input type="email" id="forgot-email" class="form-control" required maxlength="255" autocomplete="email">
                        </div>
                        <div class="d-grid gap-2">
                            <button id="btn-forgot-password" class="btn btn-neon-primary">
                                <i class="fas fa-lock me-2"></i><?php echo t('dr.auth.reset_password_btn', 'Reset Password'); ?>
                            </button>
                            <button id="btn-forgot-username" class="btn btn-outline-neon">
                                <i class="fas fa-user me-2"></i><?php echo t('dr.auth.send_username_btn', 'Send me my username'); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Enter code + new password (only for password reset) -->
                    <div id="forgot-step2" style="display:none;">
                        <form id="form-reset-password" autocomplete="off">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            <input type="hidden" name="email" id="reset-email-hidden">
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('dr.auth.verify_code', 'Verification Code'); ?></label>
                                <input type="text" name="code" class="form-control text-center"
                                       required pattern="\d{6}" maxlength="6" inputmode="numeric"
                                       placeholder="000000"
                                       style="font-size:1.8rem; letter-spacing:0.5em; font-family:'Orbitron',monospace;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('dr.auth.new_password', 'New Password'); ?></label>
                                <input type="password" name="password" class="form-control" required minlength="8" autocomplete="new-password">
                                <div class="form-text text-white-50"><?php echo t('dr.auth.password_hint', 'Minimum 8 characters'); ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('dr.auth.confirm_password', 'Confirm Password'); ?></label>
                                <input type="password" name="password_confirm" class="form-control" required minlength="8" autocomplete="new-password">
                            </div>
                            <button type="submit" class="btn btn-neon-primary w-100">
                                <i class="fas fa-save me-2"></i><?php echo t('dr.auth.set_new_password_btn', 'Set New Password'); ?>
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-3">
                        <a href="#" id="link-back-login" class="text-white-50 small" style="text-decoration:underline;">
                            <i class="fas fa-arrow-left me-1"></i><?php echo t('dr.auth.back_to_login', 'Back to login'); ?>
                        </a>
                    </div>
                    <div id="forgot-alert" class="mt-3" style="display:none;"></div>
                </div>

                <!-- Email Verification Panel (hidden by default) -->
                <div id="verify-panel" class="glass-card-neon p-4 p-md-5 mt-4" style="display:<?php echo $showVerify ? 'block' : 'none'; ?>;">
                    <div class="text-center mb-4">
                        <h3 class="glow-text mb-2"><i class="fas fa-envelope-open-text me-2"></i><?php echo t('dr.auth.verify_title', 'Verify Your Email'); ?></h3>
                        <p class="text-white-50"><?php echo t('dr.auth.verify_subtitle', 'Enter the 6-digit code we sent to your email'); ?></p>
                    </div>
                    <form id="form-verify" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <div class="mb-3">
                            <label class="form-label"><?php echo t('dr.auth.verify_code', 'Verification Code'); ?></label>
                            <input type="text" name="code" id="verify-code-input" class="form-control text-center"
                                   required pattern="\d{6}" maxlength="6" inputmode="numeric"
                                   placeholder="000000"
                                   style="font-size:1.8rem; letter-spacing:0.5em; font-family:'Orbitron',monospace;">
                        </div>
                        <button type="submit" class="btn btn-neon-primary w-100 mb-2">
                            <i class="fas fa-check-circle me-2"></i><?php echo t('dr.auth.verify_btn', 'Verify'); ?>
                        </button>
                    </form>
                    <div class="text-center mt-2">
                        <button id="btn-resend" class="btn btn-sm btn-link text-white-50" style="text-decoration:underline;">
                            <i class="fas fa-redo me-1"></i><?php echo t('dr.auth.resend_code', 'Resend code'); ?>
                        </button>
                        <div id="resend-cooldown" class="text-white-50 small mt-1" style="display:none;"></div>
                    </div>
                    <div id="verify-alert" class="mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>

<?php echo generateFooter(); ?>

<script>
const REDIRECT = <?php echo json_encode($redirect); ?>;
const SHOW_VERIFY = <?php echo $showVerify ? 'true' : 'false'; ?>;

function showAlert(msg, type) {
    const el = document.getElementById('auth-alert');
    el.innerHTML = '<div class="alert alert-' + type + ' mb-0">' + msg + '</div>';
    el.style.display = 'block';
}

function showVerifyAlert(msg, type) {
    const el = document.getElementById('verify-alert');
    el.innerHTML = '<div class="alert alert-' + type + ' mb-0">' + msg + '</div>';
    el.style.display = 'block';
}

function switchToVerify() {
    document.querySelector('.tab-content').parentElement.style.display = 'none';
    document.querySelector('.nav-pills').style.display = 'none';
    document.getElementById('auth-alert').style.display = 'none';
    document.getElementById('verify-panel').style.display = 'block';
    document.getElementById('verify-code-input').focus();
}

if (SHOW_VERIFY) {
    switchToVerify();
}

/* ---- Login ---- */
document.getElementById('form-login').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch('/api/auth/login.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            if (d.ok) {
                if (d.data && d.data.email_pending) {
                    showAlert('<?php echo t("dr.auth.email_not_verified", "Please verify your email to continue."); ?>', 'warning');
                    setTimeout(switchToVerify, 800);
                } else {
                    window.location.href = REDIRECT;
                }
            } else {
                showAlert(d.error.message, 'danger');
            }
        })
        .catch(() => showAlert('Connection error.', 'danger'));
});

/* ---- Register ---- */
document.getElementById('form-register').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    if (fd.get('password') !== fd.get('password_confirm')) {
        showAlert('<?php echo t("dr.auth.password_mismatch", "Passwords do not match."); ?>', 'warning');
        return;
    }
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    fetch('/api/auth/register.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            btn.disabled = false;
            if (d.ok) {
                if (d.data && d.data.email_pending) {
                    showAlert('<?php echo t("dr.auth.check_email", "Account created! Check your email for the verification code."); ?>', 'success');
                    setTimeout(switchToVerify, 1200);
                } else {
                    window.location.href = REDIRECT;
                }
            } else {
                showAlert(d.error.message, 'danger');
            }
        })
        .catch(() => { btn.disabled = false; showAlert('Connection error.', 'danger'); });
});

/* ---- Verify Email ---- */
document.getElementById('form-verify').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    fetch('/api/auth/verify_email.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            btn.disabled = false;
            if (d.ok) {
                showVerifyAlert('<?php echo t("dr.auth.email_verified", "Email verified! Redirecting…"); ?>', 'success');
                setTimeout(() => { window.location.href = REDIRECT; }, 1000);
            } else {
                showVerifyAlert(d.error.message, 'danger');
            }
        })
        .catch(() => { btn.disabled = false; showVerifyAlert('Connection error.', 'danger'); });
});

/* ---- Resend Code ---- */
let resendCooldown = 0;
let resendTimer = null;

function startResendCooldown(sec) {
    resendCooldown = sec;
    const btn = document.getElementById('btn-resend');
    const cd = document.getElementById('resend-cooldown');
    btn.style.display = 'none';
    cd.style.display = 'block';
    function tick() {
        if (resendCooldown <= 0) {
            cd.style.display = 'none';
            btn.style.display = 'inline-block';
            clearInterval(resendTimer);
            return;
        }
        cd.textContent = '<?php echo t("dr.auth.resend_wait", "Resend available in"); ?> ' + resendCooldown + 's';
        resendCooldown--;
    }
    tick();
    resendTimer = setInterval(tick, 1000);
}

document.getElementById('btn-resend').addEventListener('click', function() {
    const fd = new FormData();
    fd.append('csrf_token', '<?php echo $csrfToken; ?>');
    this.disabled = true;
    fetch('/api/auth/resend_code.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            document.getElementById('btn-resend').disabled = false;
            if (d.ok) {
                showVerifyAlert('<?php echo t("dr.auth.code_resent", "New code sent! Check your inbox."); ?>', 'success');
                startResendCooldown(60);
            } else {
                showVerifyAlert(d.error.message, 'danger');
            }
        })
        .catch(() => { document.getElementById('btn-resend').disabled = false; showVerifyAlert('Connection error.', 'danger'); });
});

/* ---- Forgot Password/Username ---- */
const forgotPanel = document.getElementById('forgot-panel');
const forgotStep1 = document.getElementById('forgot-step1');
const forgotStep2 = document.getElementById('forgot-step2');
const authCard = document.querySelector('.glass-card-neon.p-4');

function showForgotAlert(msg, type) {
    const el = document.getElementById('forgot-alert');
    el.innerHTML = '<div class="alert alert-' + type + ' mb-0">' + msg + '</div>';
    el.style.display = 'block';
}

function switchToForgot() {
    authCard.style.display = 'none';
    document.getElementById('verify-panel').style.display = 'none';
    forgotPanel.style.display = 'block';
    forgotStep1.style.display = 'block';
    forgotStep2.style.display = 'none';
    document.getElementById('forgot-alert').style.display = 'none';
    document.getElementById('forgot-email').focus();
}

function switchToLogin() {
    forgotPanel.style.display = 'none';
    document.getElementById('verify-panel').style.display = 'none';
    authCard.style.display = 'block';
    document.getElementById('auth-alert').style.display = 'none';
}

document.getElementById('link-forgot').addEventListener('click', function(e) {
    e.preventDefault();
    switchToForgot();
});

document.getElementById('link-back-login').addEventListener('click', function(e) {
    e.preventDefault();
    switchToLogin();
});

document.getElementById('btn-forgot-username').addEventListener('click', function() {
    const email = document.getElementById('forgot-email').value.trim();
    if (!email) { showForgotAlert('<?php echo t("dr.auth.enter_email", "Please enter your email."); ?>', 'warning'); return; }
    this.disabled = true;
    const fd = new FormData();
    fd.append('csrf_token', '<?php echo $csrfToken; ?>');
    fd.append('email', email);
    fetch('/api/auth/forgot_username.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            document.getElementById('btn-forgot-username').disabled = false;
            if (d.ok) {
                showForgotAlert('<?php echo t("dr.auth.username_sent", "If an account exists with that email, the username was sent. Check your inbox."); ?>', 'success');
            } else {
                showForgotAlert(d.error.message, 'danger');
            }
        })
        .catch(() => { document.getElementById('btn-forgot-username').disabled = false; showForgotAlert('Connection error.', 'danger'); });
});

document.getElementById('btn-forgot-password').addEventListener('click', function() {
    const email = document.getElementById('forgot-email').value.trim();
    if (!email) { showForgotAlert('<?php echo t("dr.auth.enter_email", "Please enter your email."); ?>', 'warning'); return; }
    this.disabled = true;
    const fd = new FormData();
    fd.append('csrf_token', '<?php echo $csrfToken; ?>');
    fd.append('email', email);
    fetch('/api/auth/forgot_password.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            document.getElementById('btn-forgot-password').disabled = false;
            if (d.ok) {
                showForgotAlert('<?php echo t("dr.auth.reset_code_sent", "If an account exists with that email, a reset code was sent. Check your inbox."); ?>', 'success');
                document.getElementById('reset-email-hidden').value = email;
                setTimeout(function() {
                    forgotStep1.style.display = 'none';
                    forgotStep2.style.display = 'block';
                    document.getElementById('forgot-alert').style.display = 'none';
                }, 1500);
            } else {
                showForgotAlert(d.error.message, 'danger');
            }
        })
        .catch(() => { document.getElementById('btn-forgot-password').disabled = false; showForgotAlert('Connection error.', 'danger'); });
});

document.getElementById('form-reset-password').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    if (fd.get('password') !== fd.get('password_confirm')) {
        showForgotAlert('<?php echo t("dr.auth.password_mismatch", "Passwords do not match."); ?>', 'warning');
        return;
    }
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    fetch('/api/auth/reset_password.php', { method: 'POST', body: fd, credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            btn.disabled = false;
            if (d.ok) {
                showForgotAlert('<?php echo t("dr.auth.password_reset_success", "Password reset! You can now log in with your new password."); ?>', 'success');
                setTimeout(switchToLogin, 2000);
            } else {
                showForgotAlert(d.error.message, 'danger');
            }
        })
        .catch(() => { btn.disabled = false; showForgotAlert('Connection error.', 'danger'); });
});
</script>

<?php echo generateScripts(); ?>
