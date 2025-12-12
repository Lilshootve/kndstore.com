<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/products-data.php';

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
            // Productos destacados - slugs de productos destacados
            $featuredSlugs = [
                'formateo-limpieza-pc',
                'asesoria-pc-gamer',
                'avatar-personalizado',
                'wallpaper-personalizado'
            ];
            
            foreach ($featuredSlugs as $slug):
                if (!isset($PRODUCTS[$slug])) continue;
                $product = $PRODUCTS[$slug];
                // Determinar precio real (oferta para Avatar y Wallpaper)
                if (in_array($product['nombre'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])) {
                    $precio_real = 2.50;
                } else {
                    $precio_real = $product['precio'];
                }
            ?>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="product-card">
                        <?php if (in_array($product['nombre'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                            <div class="product-offer-badge">Oferta</div>
                        <?php endif; ?>
                        <div class="product-image">
                            <img src="<?php echo $product['imagen']; ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
                            <p><?php echo htmlspecialchars(strip_tags($product['descripcion'])); ?></p>
                            <div class="product-footer">
                                <?php if (in_array($product['nombre'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                                    <span class="product-price">
                                        <span class="product-price-original">$<?php echo number_format($product['precio'], 2); ?></span>
                                        <span class="product-price-offer">$2.50</span>
                                    </span>
                                <?php else: ?>
                                    <span class="product-price">$<?php echo number_format($product['precio'], 2); ?></span>
                                <?php endif; ?>
                                <div class="d-flex gap-2">
                                    <a href="/producto.php?slug=<?php echo $product['slug']; ?>" class="btn btn-outline-neon btn-sm btn-details">
                                        Ver detalles
                                    </a>
                                    <button 
                                        type="button"
                                        class="btn btn-primary btn-sm add-to-order"
                                        data-id="<?php echo (int)$product['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo number_format($precio_real, 2, '.', ''); ?>"
                                    >
                                        Añadir al pedido
                                    </button>
                                </div>
                            </div>
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