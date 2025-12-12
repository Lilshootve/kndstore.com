<?php
// Configuración de sesión ANTES de cargar config.php
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1); // Habilitado para HTTPS en producción
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

// Iniciar timer de rendimiento
$startTime = startPerformanceTimer();

// Configurar headers de cache para CSS y JS
setCacheHeaders('html');
?>

<?php echo generateHeader('Inicio', 'Tu tienda galáctica de productos únicos y tecnología de vanguardia'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <h1 class="hero-title">
                    <span class="text-gradient">Bienvenido a</span><br>
                    <span class="text-gradient">KND Store</span>
                </h1>
                <p class="hero-subtitle">
                    Tu tienda galáctica de productos únicos y tecnología de vanguardia. 
                    Descubre un universo de posibilidades con nuestro catálogo exclusivo.
                </p>
                <div class="hero-buttons">
                    <a href="/products.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket"></i> Explorar Productos
                    </a>
                    <a href="/about.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle"></i> Conoce Más
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <img src="/assets/images/knd-logo.png" alt="KND Store" class="img-fluid hero-logo">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Envío Galáctico</h3>
                    <p>Entregamos a cualquier parte del universo con nuestra tecnología de transporte espacial de última generación.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Seguridad Cósmica</h3>
                    <p>Protegemos tus datos con tecnología de encriptación cuántica y protocolos de seguridad intergalácticos.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Soporte 24/7</h3>
                    <p>Nuestro equipo de soporte está disponible las 24 horas del día, los 7 días de la semana, en todos los husos horarios.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products py-5">
    <div class="container">
        <h2 class="section-title">Productos Destacados</h2>
        <div class="row">
            <?php
            // Productos destacados reales de KND Store
            $featuredProducts = [
                [
                    'name' => 'Formateo y limpieza de PC (Remoto)',
                    'description' => 'Recupera el rendimiento de tu PC desde la comodidad de tu nave.',
                    'price' => 10.00,
                    'image' => 'assets/images/productos/formateo-limpieza-pc-remoto.png',
                    'url' => '/producto/formateo-limpieza-pc'
                ],
                [
                    'name' => 'Asesoría para PC Gamer (Presupuesto personalizado)',
                    'description' => '¿Tienes $300 o $3000? Te armamos la build perfecta.',
                    'price' => 5.00,
                    'image' => 'assets/images/productos/asesoria-pc-gamer-presupuesto.png',
                    'url' => '/producto/asesoria-pc-gamer'
                ],
                [
                    'name' => 'Avatar gamer personalizado',
                    'description' => 'Crea tu imagen digital con estilo galáctico.',
                    'price' => 6.00,
                    'image' => 'assets/images/productos/avatar-gamer-personalizado.png',
                    'url' => '/producto/avatar-personalizado'
                ],
                [
                    'name' => 'Wallpaper personalizado IA',
                    'description' => 'Tu fondo, tu nave. Generado a medida.',
                    'price' => 4.00,
                    'image' => 'assets/images/productos/wallpaper-personalizado-knd.png',
                    'url' => '/producto/wallpaper-personalizado'
                ]
            ];
            
            foreach ($featuredProducts as $product): ?>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="product-card">
                        <?php if (in_array($product['name'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                            <div class="product-offer-badge">Oferta</div>
                        <?php endif; ?>
                        <div class="product-image">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-price">
                                <?php if (in_array($product['name'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                                    <span class="product-price-original"><?php echo formatPrice($product['price']); ?></span>
                                    <span class="product-price-offer">$2.50</span>
                                <?php else: ?>
                                    <?php echo formatPrice($product['price']); ?>
                                <?php endif; ?>
                            </div>
                            <a href="/producto.php?slug=<?php echo str_replace(['/producto/', '/'], '', $product['url']); ?>" class="btn btn-primary">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/products.php" class="btn btn-outline-light btn-lg">Ver Todos los Productos</a>
        </div>
    </div>
</section>

<?php 
echo generateFooter();
echo generateScripts();

// Finalizar timer de rendimiento
$executionTime = endPerformanceTimer($startTime);

// Log de rendimiento en desarrollo
if (error_reporting() > 0) {
    echo "<!-- Page loaded in {$executionTime}ms -->";
}
?> 