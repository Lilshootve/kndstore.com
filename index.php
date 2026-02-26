<?php
// Habilitar errores para diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Capturar errores fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<!DOCTYPE html><html><head><title>Error</title></head><body>";
        echo "<h1 style='color:red'>ERROR FATAL</h1>";
        echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($error['message']) . "</p>";
        echo "<p><strong>Archivo:</strong> " . htmlspecialchars($error['file']) . "</p>";
        echo "<p><strong>Línea:</strong> " . $error['line'] . "</p>";
        echo "</body></html>";
    }
});

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

<?php echo generateHeader(t('nav.home'), t('meta.default_description')); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section hero-home-bg">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <h1 class="hero-title">
                    <span class="text-gradient"><?php echo t('home.hero.welcome'); ?></span><br>
                    <span class="text-gradient"><?php echo t('home.hero.store_name'); ?></span>
                </h1>
                <p class="hero-subtitle">
                    <?php echo t('home.hero.subtitle_line1'); ?><br>
                    <?php echo t('home.hero.subtitle_line2'); ?>
                </p>
                <div class="hero-buttons">
                    <a href="/products.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket"></i> <?php echo t('home.cta.explore_products'); ?>
                    </a>
                    <a href="/apparel.php" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-tshirt"></i> <?php echo t('home.cta.knd_apparel'); ?>
                    </a>
                    <a href="/about.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle"></i> <?php echo t('home.cta.learn_more'); ?>
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
                    <h3><?php echo t('home.features.title.shipping'); ?></h3>
                    <p><?php echo t('home.features.body.shipping'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3><?php echo t('home.features.title.security'); ?></h3>
                    <p><?php echo t('home.features.body.security'); ?></p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3><?php echo t('home.features.title.support'); ?></h3>
                    <p><?php echo t('home.features.body.support'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products py-5">
    <div class="container">
        <h2 class="section-title"><?php echo t('home.featured_products.title'); ?></h2>
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
                if (in_array($product['nombre'], ['Custom Gamer Avatar', 'AI Custom Wallpaper'])) {
                    $precio_real = 2.50;
                } else {
                    $precio_real = $product['precio'];
                }
            ?>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="product-card">
                        <?php if (in_array($product['nombre'], ['Custom Gamer Avatar', 'AI Custom Wallpaper'])): ?>
                            <div class="product-offer-badge"><?php echo t('product.badge.offer'); ?></div>
                        <?php endif; ?>
                        <div class="product-image">
                            <img src="<?php echo $product['imagen']; ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
                            <p><?php echo strip_tags($product['descripcion']); ?></p>
                            <div class="product-footer">
                                <?php if (in_array($product['nombre'], ['Custom Gamer Avatar', 'AI Custom Wallpaper'])): ?>
                                    <span class="product-price">
                                        <span class="product-price-original">$<?php echo number_format($product['precio'], 2); ?></span>
                                        <span class="product-price-offer">$2.50</span>
                                    </span>
                                <?php else: ?>
                                    <span class="product-price">$<?php echo number_format($product['precio'], 2); ?></span>
                                <?php endif; ?>
                                <div class="d-flex gap-2">
                                    <a href="/producto.php?slug=<?php echo $product['slug']; ?>" class="btn btn-outline-neon btn-sm btn-details">
                                        <?php echo t('btn.view_details'); ?>
                                    </a>
                                    <button 
                                        type="button"
                                        class="btn btn-primary btn-sm add-to-order"
                                        data-id="<?php echo (int)$product['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo number_format($precio_real, 2, '.', ''); ?>"
                                    >
                                        <?php echo t('btn.add_to_order'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/products.php" class="btn btn-outline-light btn-lg"><?php echo t('home.featured_products.view_all'); ?></a>
        </div>
    </div>
</section>

<!-- KND Apparel Showcase -->
<section class="apparel-showcase py-5 bg-dark-epic position-relative" style="overflow: hidden;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="apparel-hero-content">
                    <span class="badge bg-danger mb-3" style="font-size: 0.9rem; padding: 8px 16px;">
                        <i class="fas fa-gem me-2"></i> <?php echo t('apparel.badge.limited_drops'); ?>
                    </span>
                    <h2 class="section-title mb-4" style="font-size: 2.5rem; line-height: 1.2;">
                        <span class="text-gradient"><?php echo t('apparel.section.title'); ?></span><br>
                        <span style="font-size: 1.8rem;"><?php echo t('apparel.section.subtitle'); ?></span>
                    </h2>
                    <p class="text-white mb-4" style="font-size: 1.1rem; line-height: 1.8; opacity: 0.9;">
                        <?php echo t('apparel.section.body'); ?>
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="/apparel.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-tshirt me-2"></i> <?php echo t('apparel.cta.view_full_collection'); ?>
                        </a>
                        <a href="/custom-design.php" class="btn btn-outline-neon btn-lg">
                            <i class="fas fa-palette me-2"></i> <?php echo t('apparel.cta.custom_design'); ?>
                        </a>
                    </div>
                    <div class="apparel-features">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-primary me-2" style="font-size: 1.2rem;"></i>
                                    <span class="text-white"><?php echo t('apparel.feature.material'); ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-primary me-2" style="font-size: 1.2rem;"></i>
                                    <span class="text-white"><?php echo t('apparel.feature.exclusive_designs'); ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-primary me-2" style="font-size: 1.2rem;"></i>
                                    <span class="text-white"><?php echo t('apparel.feature.limited_editions'); ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-primary me-2" style="font-size: 1.2rem;"></i>
                                    <span class="text-white"><?php echo t('apparel.feature.coordinated_delivery'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="apparel-gallery-showcase">
                    <?php
                    // Obtener productos apparel limitados para mostrar
                    $limitedApparel = array_filter($PRODUCTS, function($p) {
                        return isset($p['tipo']) && $p['tipo'] === 'apparel' && isset($p['limited']) && $p['limited'];
                    });
                    $limitedApparel = array_slice($limitedApparel, 0, 2, true);
                    ?>
                    <div class="row g-3">
                        <?php foreach ($limitedApparel as $slug => $product): ?>
                            <div class="col-6">
                                <div class="apparel-card-featured position-relative" style="border-radius: 15px; overflow: hidden; border: 2px solid var(--knd-electric-purple); box-shadow: 0 10px 30px rgba(138, 43, 226, 0.3);">
                                    <?php if (isset($product['limited']) && $product['limited']): ?>
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-danger" style="font-size: 0.75rem; padding: 6px 12px;">
                                                <i class="fas fa-gem me-1"></i> LIMITED
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php 
                                    $displayImage = $product['imagen'];
                                    if (isset($product['gallery']['front'])) {
                                        $displayImage = $product['gallery']['front'];
                                    }
                                    ?>
                                    <img src="/<?php echo htmlspecialchars($displayImage); ?>" 
                                         alt="<?php echo htmlspecialchars($product['nombre']); ?>"
                                         class="w-100" 
                                         style="height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                                    <div class="p-3" style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(20, 20, 40, 0.95) 100%);">
                                        <h5 class="text-white mb-2" style="font-size: 1rem;"><?php echo htmlspecialchars($product['nombre']); ?></h5>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-bold">$<?php echo number_format($product['precio'], 2); ?></span>
                                            <a href="/producto.php?slug=<?php echo $slug; ?>" class="btn btn-sm btn-outline-neon">
                                                <?php echo t('btn.view'); ?> <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Efecto de fondo -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1; opacity: 0.1;">
        <div style="background: radial-gradient(circle at 30% 50%, var(--knd-neon-blue) 0%, transparent 50%), radial-gradient(circle at 70% 50%, var(--knd-electric-purple) 0%, transparent 50%); height: 100%;"></div>
    </div>
</section>

<style>
.apparel-card-featured:hover img {
    transform: scale(1.05);
}
.apparel-card-featured {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.apparel-card-featured:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(138, 43, 226, 0.5) !important;
}
</style>

<script src="/assets/js/navigation-extend.js"></script>

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