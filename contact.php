<?php
// Configuración de sesión ANTES de cargar config.php
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
    session_start();
} else {
    // Si la sesión ya está activa, solo la iniciamos
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'includes/footer.php';

// Procesar formulario de contacto
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validación básica
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = t('contact.form.error.required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = t('contact.form.error.email');
    } else {
        // Aquí normalmente enviarías el email
        // Por ahora simulamos el envío exitoso
        $success_message = t('contact.form.success');
        
        // Limpiar campos después del envío exitoso
        $name = $email = $subject = $message = '';
    }
}
?>

<?php echo generateHeader(t('contact.meta.title'), t('contact.meta.description')); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section hero-contact-bg">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient"><?php echo t('contact.hero.title_line1'); ?></span><br>
                    <span class="text-gradient"><?php echo t('contact.hero.title_line2'); ?></span><br>
                    <span class="text-gradient"><?php echo t('contact.hero.title_line3'); ?></span>
                </h1>
                <p class="hero-subtitle">
                    <?php echo t('contact.hero.subtitle'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Formulario de Contacto -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form-container">
                    <h2 class="section-title text-center mb-5">
                        <i class="fas fa-satellite me-3"></i>
                        <?php echo t('contact.form.title'); ?>
                    </h2>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success galactic-alert" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger galactic-alert" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="contact.php" class="contact-form">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-2"></i>
                                        <?php echo t('contact.form.name_label_pilot'); ?>
                                    </label>
                                    <input type="text" 
                                           class="form-control galactic-input" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo htmlspecialchars($name ?? ''); ?>"
                                           placeholder="<?php echo t('contact.form.name_placeholder'); ?>"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>
                                        <?php echo t('contact.form.email_label_frequency'); ?>
                                    </label>
                                    <input type="email" 
                                           class="form-control galactic-input" 
                                           id="email" 
                                           name="email" 
                                           value="<?php echo htmlspecialchars($email ?? ''); ?>"
                                           placeholder="<?php echo t('contact.form.email_placeholder'); ?>"
                                           required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="subject" class="form-label">
                                    <i class="fas fa-bullseye me-2"></i>
                                    <?php echo t('contact.form.subject_label_galactic'); ?>
                                </label>
                                <input type="text" 
                                       class="form-control galactic-input" 
                                       id="subject" 
                                       name="subject" 
                                       value="<?php echo htmlspecialchars($subject ?? ''); ?>"
                                       placeholder="<?php echo t('contact.form.subject_placeholder'); ?>"
                                       required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comments me-2"></i>
                                    <?php echo t('contact.form.message_label_transmission'); ?>
                                </label>
                                <textarea class="form-control galactic-textarea" 
                                          id="message" 
                                          name="message" 
                                          rows="6" 
                                          placeholder="<?php echo t('contact.form.message_placeholder'); ?>"
                                          required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg galactic-btn">
                                <i class="fas fa-paper-plane me-2"></i>
                                <?php echo t('contact.form.submit'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Información de Contacto -->
<section class="contact-info-section py-5 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <i class="fas fa-broadcast-tower me-3"></i>
                    <?php echo t('contact.info.channels.title'); ?>
                </h2>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h4><?php echo t('contact.info.email_official'); ?></h4>
                            <p>support@kndstore.com</p>
                            <a href="mailto:support@kndstore.com" class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane me-2"></i>
                                <?php echo t('contact.info.send_message'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h4><?php echo t('contact.info.base_operative'); ?></h4>
                            <p>Maracaibo, Venezuela</p>
                            <span class="location-badge"><?php echo t('contact.info.location_badge'); ?></span>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="contact-card featured">
                            <div class="contact-icon">
                                <i class="fab fa-discord"></i>
                            </div>
                            <h4><?php echo t('contact.info.mothership'); ?></h4>
                            <p><?php echo t('contact.info.mothership'); ?></p>
                            <a href="https://discord.gg/VXXYakrb7X" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fab fa-discord me-2"></i>
                                <?php echo t('contact.info.join_discord'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Horarios de Operación -->
<section class="hours-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hours-container">
                    <h2 class="section-title text-center mb-5">
                        <i class="fas fa-clock me-3"></i>
                        <?php echo t('contact.hours.title'); ?>
                    </h2>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="hours-card">
                                <h4><i class="fas fa-headset me-2"></i><?php echo t('contact.info.technical_support'); ?></h4>
                                <p><?php echo t('contact.hours.support_247'); ?></p>
                                <span class="status-badge online"><?php echo t('contact.hours.online'); ?></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="hours-card">
                                <h4><i class="fas fa-shopping-cart me-2"></i><?php echo t('contact.info.sales'); ?></h4>
                                <p><?php echo t('contact.hours.mon_sun'); ?></p>
                                <span class="status-badge online"><?php echo t('contact.hours.hours_range'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="hours-note">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo t('contact.hours.note'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="section-title">
                    <i class="fas fa-rocket me-3"></i>
                    <?php echo t('contact.cta.immediate_assistance'); ?>
                </h2>
                <p class="cta-text">
                    <?php echo t('contact.cta.urgent_text'); ?>
                </p>
                <div class="cta-buttons">
                    <a href="https://discord.gg/VXXYakrb7X" target="_blank" class="btn btn-primary btn-lg me-3">
                        <i class="fab fa-discord"></i> <?php echo t('contact.cta.discord_immediate'); ?>
                    </a>
                    <a href="mailto:support@kndstore.com" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope"></i> <?php echo t('contact.cta.emergency_email'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?> 