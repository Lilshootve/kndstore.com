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

<?php echo generateHeader('FAQ', 'Preguntas Frecuentes - KND Store - Tu tienda galáctica de productos únicos'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient">Preguntas</span><br>
                    <span class="text-gradient">Frecuentes</span>
                </h1>
                <p class="hero-subtitle">
                    Encuentra respuestas a todas tus dudas sobre KND Store
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section py-5 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="faq-container">
                    
                    <!-- FAQ Item 1 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-brain me-3"></i>¿Qué es KND Store?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>KND Store es mucho más que una tienda. Es una estación de comando para gamers, techies, y aventureros digitales que buscan equiparse con tecnología premium, periféricos épicos y experiencias únicas. Fundada en 1995 (sí, ese es nuestro easter egg), fusionamos hardware, cultura gamer y visión del futuro en un solo nodo.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-rocket me-3"></i>¿Realizan envíos interplanetarios?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Aún no llegamos a Marte, pero hacemos envíos nacionales e internacionales dependiendo del producto. Si tu zona está fuera del radar, contáctanos por Discord o mediante el formulario de contacto para habilitar la ruta.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-credit-card me-3"></i>¿Qué métodos de pago aceptan?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Aceptamos:</p>
                            <ul>
                                <li>Tarjetas de crédito y débito</li>
                                <li>Pagos móviles (Zinli, Wally, Binance Pay)</li>
                                <li>Criptomonedas seleccionadas</li>
                                <li>PayPal, Apple Pay, Google Pay</li>
                                <li>Sistema de puntos KND (muy pronto)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-coins me-3"></i>¿Puedo pagar en criptomonedas?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Absolutamente. Estamos conectados con las principales cadenas y pasarelas de pago cripto. Solo debes seleccionar la opción al finalizar tu misión (compra).</p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-gamepad me-3"></i>¿Venden productos digitales?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Sí. Ofrecemos claves de juegos, software premium y contenido descargable. Los productos digitales se entregan directamente en tu hangar (email o panel de usuario) al completar el pago.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-clock me-3"></i>¿Cuánto tardan en entregar?</h3>
                        </div>
                        <div class="faq-answer">
                            <ul>
                                <li><strong>Productos digitales:</strong> instantáneo o máximo 15 minutos.</li>
                                <li><strong>Envíos físicos nacionales:</strong> 24 a 72 horas hábiles.</li>
                                <li><strong>Envíos internacionales:</strong> entre 5 y 15 días dependiendo del planeta.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Item 7 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-tools me-3"></i>¿Ofrecen garantía?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Sí, todos nuestros productos físicos incluyen garantía galáctica según el fabricante. Si algo falla, abrimos una brecha técnica para resolverlo lo más rápido posible.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-box me-3"></i>¿Puedo rastrear mi pedido?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Sí. Al completar tu compra, recibirás un código de rastreo o acceso a tu panel de piloto donde verás el estado de tu pedido en tiempo real.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 9 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-satellite me-3"></i>¿Cómo puedo contactar soporte?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Tienes varias frecuencias de contacto:</p>
                            <ul>
                                <li>Formulario de contacto en nuestra web</li>
                                <li>Email directo: support@kndstore.com</li>
                                <li>Discord: discord.gg/VXXYakrb7X</li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Item 10 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-robot me-3"></i>¿Quién o qué es Kael?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Kael es nuestra IA táctica interna. No es un chatbot más: es una entidad lógica entrenada para asistirte, responder con precisión y darte soluciones antes de que preguntes. Si Kael te responde... escucha.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 11 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-dice me-3"></i>¿Qué es el Death Roll Chain?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>Es un proyecto experimental basado en azar cósmico, blockchain y recompensas. Un minijuego en desarrollo donde puedes ganar tokens, ítems, o morir de risa. (Próximamente disponible en el sector /deathroll).</p>
                        </div>
                    </div>

                    <!-- FAQ Item 12 -->
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><i class="fas fa-crystal-ball me-3"></i>¿Qué hay en el futuro para KND?</h3>
                        </div>
                        <div class="faq-answer">
                            <p>No hay límites. Desde lanzar nuestra propia criptomoneda, hasta convertirse en una red de tiendas interestelares con IA integrada, productos NFT y periféricos con alma. Todo es posible.</p>
                        </div>
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
                    <i class="fas fa-question-circle me-3"></i>
                    ¿No encontraste tu respuesta?
                </h2>
                <p class="cta-text">
                    Si tienes alguna pregunta específica que no está en esta lista, no dudes en contactarnos directamente.
                </p>
                <div class="cta-buttons">
                    <a href="/contact.php" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-envelope"></i> Contactar
                    </a>
                    <a href="https://discord.gg/VXXYakrb7X" target="_blank" class="btn btn-primary btn-lg">
                        <i class="fab fa-discord"></i> Discord
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
echo generateFooter();
echo generateScripts();
?> 