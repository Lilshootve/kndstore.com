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

<!-- KND Games Promo -->
<section class="py-5" id="lastroll-promo">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card-neon p-4 p-md-5 lastroll-promo-card position-relative overflow-hidden">
                    <span class="badge lastroll-beta-badge">BETA</span>

                    <div class="row align-items-center g-4">
                        <div class="col-md-7">
                            <h2 class="glow-text mb-2" style="font-size:2.2rem;">
                                <i class="fas fa-gamepad me-2"></i><?php echo t('home.games.title', 'KND Games'); ?>
                            </h2>
                            <p class="text-white-50 mb-3" style="font-size:1.05rem;">
                                <?php echo t('home.games.subtitle', 'Play KND LastRoll 1v1, KND Insight, and upcoming modes inside KND Arena.'); ?>
                            </p>
                            <ul class="lastroll-bullets mb-4">
                                <li><i class="fas fa-dice-d20 me-2"></i><?php echo t('home.games.bullet1', 'KND LastRoll — 1v1 Death Roll with real-time rooms'); ?></li>
                                <li><i class="fas fa-eye me-2"></i><?php echo t('home.games.bullet2', 'KND Insight — predict the number, win KND Points'); ?></li>
                                <li><i class="fas fa-trophy me-2"></i><?php echo t('home.games.bullet3', 'Earn XP, climb the leaderboard, collect seasonal badges'); ?></li>
                            </ul>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="/arena" class="btn btn-neon-primary btn-lg">
                                    <i class="fas fa-play me-2"></i><?php echo t('home.games.enter_arena', 'Enter KND Arena'); ?>
                                </a>
                                <a href="#knd-games-how" class="btn btn-outline-light">
                                    <i class="fas fa-info-circle me-2"></i><?php echo t('home.games.how', 'How it works'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-5 text-center">
                            <div class="lastroll-dice-hero">
                                <svg width="140" height="140" viewBox="0 0 120 120" aria-hidden="true">
                                    <rect x="10" y="10" width="100" height="100" rx="20" fill="rgba(10,15,30,0.6)" stroke="rgba(37,156,174,0.35)" stroke-width="2"/>
                                    <rect x="16" y="16" width="88" height="88" rx="16" fill="rgba(37,156,174,0.04)"/>
                                    <text x="60" y="72" text-anchor="middle" font-family="Orbitron, monospace" font-size="38" font-weight="900" fill="rgba(37,156,174,0.8)">KND</text>
                                    <circle cx="35" cy="35" r="3" fill="rgba(37,156,174,0.12)"/>
                                    <circle cx="85" cy="55" r="3" fill="rgba(37,156,174,0.12)"/>
                                    <circle cx="35" cy="85" r="3" fill="rgba(37,156,174,0.12)"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How KND Games Works -->
<section class="py-5" id="knd-games-how">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card-neon p-4 p-md-5">
                    <h3 class="glow-text mb-4 text-center" style="font-size:1.6rem;">
                        <i class="fas fa-scroll me-2"></i><?php echo t('home.games.how_title', 'How KND Games Works'); ?>
                    </h3>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="lastroll-step">
                                <span class="lastroll-step-num">1</span>
                                <p class="mb-0 small text-white-50"><?php echo t('home.games.how_b1', 'Create an account or log in to your KND ecosystem.'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="lastroll-step">
                                <span class="lastroll-step-num">2</span>
                                <p class="mb-0 small text-white-50"><?php echo t('home.games.how_b2', 'Earn KND Points (KP) from purchases or activity rewards.'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="lastroll-step">
                                <span class="lastroll-step-num">3</span>
                                <p class="mb-0 small text-white-50"><?php echo t('home.games.how_b3', 'Use KP to enter game modes — LastRoll 1v1, KND Insight, and more.'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="lastroll-step">
                                <span class="lastroll-step-num">4</span>
                                <p class="mb-0 small text-white-50"><?php echo t('home.games.how_b4', 'Climb the leaderboard, earn XP, and collect seasonal badges.'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="lastroll-step">
                                <span class="lastroll-step-num">5</span>
                                <p class="mb-0 small text-white-50"><?php echo t('home.games.how_b5', 'KND Points are internal and non-cash. No real-money gambling.'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="/arena" class="btn btn-neon-primary">
                            <i class="fas fa-gamepad me-2"></i><?php echo t('home.games.enter_arena', 'Enter KND Arena'); ?>
                        </a>
                    </div>
                    <p class="mt-3 mb-0 small text-center" style="color: rgba(255,255,255,0.3); font-style: italic;"><?php echo t('home.games.disclaimer', 'BETA: mechanics and balances may change while we stabilize the ecosystem.'); ?></p>
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