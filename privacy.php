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

<?php echo generateHeader('Política de Privacidad', 'Política de Privacidad de KND Store - Protección de datos y privacidad de usuarios'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>
<?php echo renderAnnouncementBar(); ?>

<!-- Hero Section -->
<section class="privacy-hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="privacy-hero-title">
                    <span class="text-gradient">Política de Privacidad</span>
                </h1>
                <p class="privacy-hero-subtitle">
                    Protección de datos y privacidad de usuarios en KND Store
                </p>
                <div class="privacy-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Última actualización: Julio 2025</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="privacy-content-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                
                <!-- Introducción -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-info-circle"></i> 1. Introducción</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>En KND Store valoramos tu privacidad y estamos comprometidos con proteger la información que compartes con nosotros. Este documento explica cómo recopilamos, utilizamos y resguardamos tus datos cuando navegas en nuestro sitio web o utilizas nuestros servicios digitales.</p>
                        <p><strong>Al usar kndstore.com, aceptas los términos de esta política.</strong></p>
                    </div>
                </div>

                <!-- Datos que recopilamos -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-database"></i> 2. Datos que recopilamos</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Recopilamos solo la información necesaria para operar nuestros servicios:</p>
                        <ul>
                            <li><strong>Datos de contacto:</strong> nombre, correo electrónico, teléfono (si los proporcionas).</li>
                            <li><strong>Datos de navegación:</strong> cookies, dirección IP, ubicación aproximada, navegador y dispositivo utilizado.</li>
                            <li><strong>Datos de compra:</strong> historial de servicios solicitados, transacciones y preferencias.</li>
                        </ul>
                        <div class="privacy-note">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p><strong>Importante:</strong> No solicitamos ni almacenamos información financiera directamente. Los pagos se procesan mediante plataformas externas (WhatsApp, Binance Pay, Zinli, PayPal, etc.).</p>
                        </div>
                    </div>
                </div>

                <!-- Uso de la información -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-cogs"></i> 3. Uso de la información</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Usamos tus datos para:</p>
                        <ul>
                            <li>Procesar pedidos y solicitudes.</li>
                            <li>Personalizar tu experiencia en la web.</li>
                            <li>Mejorar nuestros servicios y soporte.</li>
                            <li>Mantener comunicación contigo respecto a tus compras o consultas.</li>
                        </ul>
                        <div class="privacy-guarantee">
                            <i class="fas fa-lock"></i>
                            <p><strong>Garantía:</strong> Nunca vendemos tu información a terceros.</p>
                        </div>
                    </div>
                </div>

                <!-- Cookies -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-cookie-bite"></i> 4. Cookies y tecnologías similares</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Utilizamos cookies para:</p>
                        <ul>
                            <li>Recordar tus preferencias.</li>
                            <li>Analizar el tráfico del sitio.</li>
                            <li>Mejorar el rendimiento de la tienda.</li>
                        </ul>
                        <p><strong>Nota:</strong> Puedes desactivar las cookies desde tu navegador, pero algunas funciones podrían no funcionar correctamente.</p>
                    </div>
                </div>

                <!-- Compartición -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-share-alt"></i> 5. Compartición de información</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Solo compartimos datos con proveedores necesarios para operar:</p>
                        <ul>
                            <li>Servicios de hosting y seguridad.</li>
                            <li>Plataformas de pago.</li>
                            <li>Herramientas de análisis web.</li>
                        </ul>
                        <div class="privacy-guarantee">
                            <i class="fas fa-shield-alt"></i>
                            <p><strong>Protección:</strong> Nunca cedemos tu información a terceros con fines comerciales.</p>
                        </div>
                    </div>
                </div>

                <!-- Seguridad -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-user-shield"></i> 6. Seguridad de la información</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Tomamos medidas técnicas y organizativas para proteger tus datos. Sin embargo, ningún sistema es 100% seguro. Te recomendamos mantener tu información de acceso en privado.</p>
                    </div>
                </div>

                <!-- Derechos -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-user-check"></i> 7. Tus derechos</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Puedes solicitar en cualquier momento:</p>
                        <ul>
                            <li>Acceso a los datos que tenemos sobre ti.</li>
                            <li>Corrección o eliminación de tu información.</li>
                            <li>Retirar tu consentimiento para el uso de tus datos.</li>
                        </ul>
                        <div class="privacy-contact-info">
                            <i class="fas fa-envelope"></i>
                            <p><strong>Contacto:</strong> Envíanos un correo a <a href="mailto:support@kndstore.com">support@kndstore.com</a> para ejercer estos derechos.</p>
                        </div>
                    </div>
                </div>

                <!-- Cambios -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-edit"></i> 8. Cambios a esta política</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Podemos actualizar esta política en cualquier momento. Si lo hacemos, publicaremos la versión más reciente en nuestro sitio con la fecha de actualización.</p>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="privacy-section">
                    <div class="privacy-section-header">
                        <h2><i class="fas fa-phone"></i> 9. Contacto</h2>
                    </div>
                    <div class="privacy-section-content">
                        <p>Si tienes dudas sobre esta política, contáctanos:</p>
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

                <!-- Footer de la política -->
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