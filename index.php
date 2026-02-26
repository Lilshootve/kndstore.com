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
                    <span class="text-gradient">Build Smarter. Perform Better.</span>
                </h1>
                <p class="hero-subtitle">
                    Digital performance services and creative assets for modern builders worldwide.
                </p>
                <div class="hero-buttons">
                    <a href="#services" class="btn btn-primary btn-lg">
                        <i class="fas fa-bolt"></i> Explore Services
                    </a>
                    <a href="#creative" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-palette"></i> Explore Creative
                    </a>
                    <a href="/apparel.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-tshirt"></i> View Apparel
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

<!-- Services Pillar -->
<section class="knd-pillar-section py-5 bg-dark-epic" id="services">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge bg-primary mb-3">Digital Performance</span>
                <h2 class="section-title mb-3">Services built for precision and results.</h2>
                <p class="text-white-50 mb-4">
                    Make confident decisions before you spend. Remote optimization, consulting, and digital services designed to upgrade performance fast.
                </p>
                <a href="/products.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-right me-2"></i> Explore Services
                </a>
            </div>
            <div class="col-lg-6">
                <div class="knd-pillar-card">
                    <div class="knd-pillar-item">
                        <h4>Digital Consulting</h4>
                        <p class="text-white-50">Budget planning, compatibility checks, and build simulations.</p>
                    </div>
                    <div class="knd-pillar-item">
                        <h4>Remote Technical Services</h4>
                        <p class="text-white-50">Installations, performance tuning, and setup optimization.</p>
                    </div>
                    <div class="knd-pillar-item">
                        <h4>Digital Marketplace</h4>
                        <p class="text-white-50">Instant assets, guides, and digital drops.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Creative Pillar -->
<section class="knd-pillar-section py-5" id="creative">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 order-lg-2">
                <span class="badge bg-danger mb-3">Creative Identity</span>
                <h2 class="section-title mb-3">Creative assets with a galactic edge.</h2>
                <p class="text-white-50 mb-4">
                    Wallpapers, avatars, icons, and digital content built for creators who want a strong identity and a clean visual system.
                </p>
                <a href="/creative.php" class="btn btn-outline-neon btn-lg">
                    <i class="fas fa-layer-group me-2"></i> Explore Creative
                </a>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="knd-pillar-card">
                    <div class="knd-pillar-item">
                        <h4>Custom Wallpapers</h4>
                        <p class="text-white-50">AI-built visuals in multiple resolutions.</p>
                    </div>
                    <div class="knd-pillar-item">
                        <h4>Avatars & Icons</h4>
                        <p class="text-white-50">Social-ready assets with galactic styling.</p>
                    </div>
                    <div class="knd-pillar-item">
                        <h4>Mystery Drops</h4>
                        <p class="text-white-50">Limited digital loot and surprise packs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Apparel Callout -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="section-title mb-3">Apparel is the extension.</h2>
                <p class="text-white-50 mb-0">
                    CORE essentials and LIMITED drops built for creators who want the KND mindset in the real world.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="/apparel.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-tshirt me-2"></i> View Apparel
                </a>
            </div>
        </div>
    </div>
</section>

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