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
?>

<?php echo generateHeader('Términos y Condiciones', 'Términos y Condiciones de KND Store - Servicios digitales y uso del sitio web'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="privacy-hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="privacy-hero-title">
                    <span class="text-gradient">Términos y Condiciones</span>
                </h1>
                <p class="privacy-hero-subtitle">
                    Servicios digitales y uso del sitio web de KND Store
                </p>
                <div class="privacy-badge">
                    <i class="fas fa-file-contract"></i>
                    <span>Última actualización: Julio 2025</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="privacy-content-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                
                <!-- Aceptación de los términos -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-check-circle"></i> 1. Aceptación de los términos</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Al acceder y usar el sitio web kndstore.com, aceptas los presentes términos y condiciones. Si no estás de acuerdo, te recomendamos no utilizar nuestros servicios.</p>
                        <p><strong>KND Store se reserva el derecho de modificar estos términos en cualquier momento y sin previo aviso.</strong></p>
                    </div>
                </div>

                <!-- Nuestros servicios -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-cogs"></i> 2. Nuestros servicios</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>KND Store ofrece productos y servicios 100% digitales que incluyen:</p>
                        <ul>
                            <li><strong>Servicios técnicos remotos.</strong></li>
                            <li><strong>Activación de juegos y gift cards.</strong></li>
                            <li><strong>Personalización digital</strong> (arte, wallpapers, iconos, etc.).</li>
                            <li><strong>Asesorías técnicas y de hardware.</strong></li>
                        </ul>
                        <div class="privacy-note">
                            <i class="fas fa-info-circle"></i>
                            <p><strong>Nota:</strong> No vendemos productos físicos por el momento.</p>
                        </div>
                    </div>
                </div>

                <!-- Uso del sitio web -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-globe"></i> 3. Uso del sitio web</h2>
                    </div>
                    <div class="privacy-section-content">
                        <ul>
                            <li>Debes ser mayor de 18 años o contar con autorización de tus padres/tutores.</li>
                            <li>No puedes usar el sitio para actividades ilegales o que afecten el funcionamiento de la web.</li>
                            <li>Está prohibido intentar hackear, copiar o modificar el contenido sin autorización.</li>
                        </ul>
                    </div>
                </div>

                <!-- Pedidos y pagos -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-credit-card"></i> 4. Pedidos y pagos</h2>
                    </div>
                    <div class="privacy-section-content">
                        <ul>
                            <li>Los pedidos se realizan a través de nuestra web o mediante contacto directo (WhatsApp o Discord).</li>
                            <li>Los pagos se procesan mediante plataformas externas (Zinli, Binance Pay, PayPal, etc.).</li>
                            <li>Una vez confirmado el pago, procederemos a entregar el producto o servicio contratado.</li>
                        </ul>
                        <div class="privacy-guarantee">
                            <i class="fas fa-shield-alt"></i>
                            <p><strong>Garantía:</strong> Los precios pueden cambiar sin previo aviso, pero respetaremos los precios confirmados al momento de tu compra.</p>
                        </div>
                    </div>
                </div>

                <!-- Entrega de productos -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-paper-plane"></i> 5. Entrega de productos</h2>
                    </div>
                    <div class="privacy-section-content">
                        <ul>
                            <li>Los productos digitales se entregan por correo, Discord, WhatsApp u otra plataforma acordada.</li>
                            <li>En el caso de servicios técnicos remotos, se coordinará contigo la fecha y hora.</li>
                        </ul>
                        <div class="privacy-note">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p><strong>Importante:</strong> No existen envíos físicos por el momento.</p>
                        </div>
                    </div>
                </div>

                <!-- Reembolsos -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-undo"></i> 6. Reembolsos</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Debido a la naturaleza digital de nuestros productos, no ofrecemos reembolsos una vez entregado el servicio o el contenido.</p>
                        <div class="privacy-guarantee">
                            <i class="fas fa-clock"></i>
                            <p><strong>Política de errores:</strong> Si existe un error de nuestra parte, contáctanos en un plazo máximo de 48 horas para evaluar tu caso.</p>
                        </div>
                    </div>
                </div>

                <!-- Propiedad intelectual -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-copyright"></i> 7. Propiedad intelectual</h2>
                    </div>
                    <div class="privacy-section-content">
                        <ul>
                            <li>Todo el contenido del sitio (logos, imágenes, textos, ilustraciones y recursos digitales) es propiedad de KND Store.</li>
                            <li>Está prohibido copiar, reproducir o distribuir dicho contenido sin autorización expresa.</li>
                        </ul>
                    </div>
                </div>

                <!-- Limitación de responsabilidad -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-exclamation-triangle"></i> 8. Limitación de responsabilidad</h2>
                    </div>
                    <div class="privacy-section-content">
                        <ul>
                            <li>KND Store no se hace responsable de daños ocasionados por el uso indebido de nuestros productos o servicios.</li>
                            <li>No garantizamos que la web esté libre de errores o caídas, aunque trabajamos para que siempre esté disponible.</li>
                        </ul>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-phone"></i> 9. Contacto</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Para consultas sobre estos términos puedes escribirnos a:</p>
                        <div class="contact-methods">
                            <div class="contact-method">
                                <i class="fas fa-envelope"></i>
                                <span><strong>Correo:</strong> support@kndstore.com</span>
                            </div>
                            <div class="contact-method">
                                <i class="fab fa-discord"></i>
                                <span><strong>Discord:</strong> knd_store</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer de los términos -->
                <div class="privacy-footer">
                    <div class="privacy-footer-content">
                        <i class="fas fa-rocket"></i>
                        <p><strong>Fecha de última actualización:</strong> Julio 2025</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php 
echo generateFooter();
echo generateScripts();
?> 