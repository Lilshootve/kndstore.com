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
        $error_message = 'Todos los campos son requeridos para establecer contacto intergaláctico.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Frecuencia de respuesta inválida. Verifica tu señal de email.';
    } else {
        // Aquí normalmente enviarías el email
        // Por ahora simulamos el envío exitoso
        $success_message = '🛰️ Transmisión recibida. El Consejo Técnico evaluará tu solicitud y te responderá en una frecuencia cercana.';
        
        // Limpiar campos después del envío exitoso
        $name = $email = $subject = $message = '';
    }
}
?>

<?php echo generateHeader('Contacto', 'Contacto - KND Store. Digital Goods • Apparel • Custom Design Services. Establece contacto intergaláctico con nosotros'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient">¿LISTO PARA</span><br>
                    <span class="text-gradient">ESTABLECER CONTACTO</span><br>
                    <span class="text-gradient">INTERGALÁCTICO?</span>
                </h1>
                <p class="hero-subtitle">
                    Ya seas un guerrero del gaming, un explorador de tecnología o una civilización alienígena con problemas de hardware... estamos aquí para ti. 💬 Mándanos una señal.
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
                        Transmisión de Señal
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
                                        Nombre del Piloto
                                    </label>
                                    <input type="text" 
                                           class="form-control galactic-input" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo htmlspecialchars($name ?? ''); ?>"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>
                                        Frecuencia de Respuesta
                                    </label>
                                    <input type="email" 
                                           class="form-control galactic-input" 
                                           id="email" 
                                           name="email" 
                                           value="<?php echo htmlspecialchars($email ?? ''); ?>"
                                           required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="subject" class="form-label">
                                    <i class="fas fa-bullseye me-2"></i>
                                    Asunto Galáctico
                                </label>
                                <input type="text" 
                                       class="form-control galactic-input" 
                                       id="subject" 
                                       name="subject" 
                                       value="<?php echo htmlspecialchars($subject ?? ''); ?>"
                                       required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comments me-2"></i>
                                    Transmisión / Mensaje
                                </label>
                                <textarea class="form-control galactic-textarea" 
                                          id="message" 
                                          name="message" 
                                          rows="6" 
                                          required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg galactic-btn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Transmitir Señal
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
                    Canales de Comunicación
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
                            <h4>Email Oficial</h4>
                            <p>support@kndstore.com</p>
                            <a href="mailto:support@kndstore.com" class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane me-2"></i>
                                Enviar Mensaje
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="contact-card">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h4>Base Operativa</h4>
                            <p>Maracaibo, Venezuela</p>
                            <span class="location-badge">Nodo Tierra 01</span>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="contact-card featured">
                            <div class="contact-icon">
                                <i class="fab fa-discord"></i>
                            </div>
                            <h4>Nave Nodriza</h4>
                            <p>Únete a nuestra nave nodriza</p>
                            <a href="https://discord.gg/VXXYakrb7X" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fab fa-discord me-2"></i>
                                Unirse al Discord
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
                        Horarios de Operación
                    </h2>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="hours-card">
                                <h4><i class="fas fa-headset me-2"></i>Soporte Técnico</h4>
                                <p>24/7 - Siempre activo</p>
                                <span class="status-badge online">En Línea</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="hours-card">
                                <h4><i class="fas fa-shopping-cart me-2"></i>Ventas</h4>
                                <p>Lunes a Domingo</p>
                                <span class="status-badge online">9:00 AM - 10:00 PM</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="hours-note">
                            <i class="fas fa-info-circle me-2"></i>
                            El Consejo Técnico está disponible para emergencias galácticas en cualquier momento del día.
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
                    ¿Necesitas Asistencia Inmediata?
                </h2>
                <p class="cta-text">
                    Para emergencias técnicas o consultas urgentes, nuestro equipo está listo para responder en tiempo real.
                </p>
                <div class="cta-buttons">
                                            <a href="https://discord.gg/VXXYakrb7X" target="_blank" class="btn btn-primary btn-lg me-3">
                        <i class="fab fa-discord"></i> Discord Inmediato
                    </a>
                    <a href="mailto:support@kndstore.com" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope"></i> Email de Emergencia
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