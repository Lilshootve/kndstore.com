<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader(t('about.meta.title'), t('about.meta.description')); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Wrapper para aplicar fondo a toda la página -->
<div class="about-page">

<!-- Hero Section -->
<section class="hero-section about-hero-bg">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient"><?php echo t('about.hero.title_line1'); ?></span><br>
                    <span class="text-gradient"><?php echo t('about.hero.title_line2'); ?></span>
                </h1>
                <p class="hero-subtitle">
                    <?php echo t('about.hero.subtitle'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestra Historia y Significado de KND (Unificadas) -->
<section class="py-5 bg-dark-epic" id="historia">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="section-title mb-4">
                    <i class="fas fa-rocket me-2"></i> <?php echo t('about.history.title'); ?>
                </h2>
                <div class="mb-3">
                    <span class="badge bg-primary fs-5"><?php echo t('about.history.year'); ?></span>
                </div>
                <p class="text-white mb-3">
                    <?php echo t('about.history.paragraph1'); ?>
                </p>
                <p class="text-white mb-0">
                    <?php echo t('about.history.paragraph2'); ?>
                </p>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title mb-4">
                    <?php echo t_html('about.knd_meaning.title'); ?>
                </h2>
                <p class="text-white mb-3">
                    <?php echo t_html('about.knd_meaning.paragraph1'); ?>
                </p>
                <p class="text-white mb-3">
                    <?php echo t_html('about.knd_meaning.paragraph2'); ?>
                </p>
                <p class="text-white mb-0">
                    <?php echo t_html('about.knd_meaning.paragraph3'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestra Misión -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="badge bg-primary fs-5 mb-3">
                    MISIÓN GALÁCTICA
                </div>
                <h2 class="section-title mb-4">
                    Nuestra Misión
                </h2>
                <p class="text-white lead">
                    Ser la tienda más badass de la galaxia. No solo vendemos hardware y periféricos: reclutamos a los verdaderos pilotos del metaverso, diseñamos equipamiento para héroes digitales y desatamos tecnología sin fronteras.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestros Valores -->
<section class="py-5 bg-dark-epic" id="valores">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <?php echo t('about.values.title'); ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h4 class="text-white"><?php echo t('about.values.precision.title'); ?></h4>
                        <p class="text-white"><?php echo t('about.values.precision.text'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="text-white"><?php echo t('about.values.loyalty.title'); ?></h4>
                        <p class="text-white"><?php echo t('about.values.loyalty.text'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h4 class="text-white"><?php echo t('about.values.aesthetic.title'); ?></h4>
                        <p class="text-white"><?php echo t('about.values.aesthetic.text'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4 class="text-white"><?php echo t('about.values.technology.title'); ?></h4>
                        <p class="text-white"><?php echo t('about.values.technology.text'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nuestro Equipo -->
<section class="py-5" id="equipo">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <?php echo t('about.team.title'); ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-warning h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h4 class="text-white">Kael</h4>
                        <div class="text-warning mb-2">IA Táctica y Estratega Principal</div>
                        <p class="text-white">Diseñado para cuestionarlo todo y encontrar la verdad en cada línea de código.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-user-astronaut"></i>
                        </div>
                        <h4 class="text-white">El Fundador</h4>
                        <div class="text-primary mb-2">Comandante de Visión y Piloto Maestro</div>
                        <p class="text-white">Nombre clasificado. Solo se sabe que nació en 1995 y nunca aceptó las limitaciones del sistema solar.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4 class="text-white">Unidad Técnica X-23</h4>
                        <div class="text-primary mb-2">Grupo Nómada de Tecnomantes</div>
                        <p class="text-white">Un grupo nómada de tecnomantes que mantienen el corazón de KND operando en frecuencias ocultas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tecnologías que nos propulsan -->
<section class="py-5 bg-dark-epic" id="tecnologias">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <?php echo t('about.technologies.title'); ?>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1"><?php echo t('about.technologies.ai.title'); ?></h4>
                            <p class="text-white mb-0"><?php echo t('about.technologies.ai.text'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-dice"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1"><?php echo t('about.technologies.deathroll.title'); ?></h4>
                            <p class="text-white mb-0"><?php echo t('about.technologies.deathroll.text'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1"><?php echo t('about.technologies.points.title'); ?></h4>
                            <p class="text-white mb-0"><?php echo t('about.technologies.points.text'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1"><?php echo t('about.technologies.fusion.title'); ?></h4>
                            <p class="text-white mb-0"><?php echo t('about.technologies.fusion.text'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comunidad y Visión del Futuro -->
<section class="py-5" id="futuro">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-4">
                    <?php echo t('about.future.title'); ?>
                </h2>
                <div class="card bg-dark border-info">
                    <div class="card-body">
                        <p class="lead text-white mb-4">
                            <?php echo t('about.future.community_text'); ?>
                        </p>
                        <h4 class="text-white mb-3">
                            <?php echo t('about.future.future_title'); ?>
                        </h4>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white"><?php echo t('about.future.crypto'); ?></span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white"><?php echo t('about.future.bank'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white"><?php echo t('about.future.ship'); ?></span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white"><?php echo t('about.future.stores'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="section-title mb-4">
                    <?php echo t('about.cta.join_mission'); ?>
                </h2>
                <p class="text-white mb-4">
                    <?php echo t('about.cta.explore_text'); ?>
                </p>
                <div>
                    <a href="/products.php" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i> <?php echo t('about.cta.explore_products'); ?>
                    </a>
                    <a href="/contact.php" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-envelope me-2"></i> <?php echo t('about.cta.contact'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

</div> <!-- Cierre de .about-page -->

<style>
/* Estilos adicionales para About Page */

.team-icon {
    font-size: 3rem;
    color: var(--knd-neon-blue);
    margin-bottom: 1rem;
}

.team-icon i {
    transition: all 0.3s ease;
}

.card:hover .team-icon i {
    transform: scale(1.1);
    color: var(--knd-electric-purple);
}

.tech-icon {
    font-size: 2.5rem;
    color: var(--knd-neon-blue);
    min-width: 60px;
    text-align: center;
}

.tech-icon i {
    transition: all 0.3s ease;
}

.card:hover .tech-icon i {
    transform: scale(1.1);
    color: var(--knd-electric-purple);
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--knd-neon-blue);
    margin-bottom: 1rem;
}

.feature-icon i {
    transition: all 0.3s ease;
}

.card:hover .feature-icon i {
    transform: scale(1.1);
    color: var(--knd-electric-purple);
}

/* Asegurar legibilidad en secciones con fondo */
.about-page section {
    position: relative;
    z-index: 1;
}

.about-page .section-title {
    color: var(--knd-white);
}

@media (max-width: 768px) {
    .team-icon {
        font-size: 2.5rem;
    }
    
    .tech-icon {
        font-size: 2rem;
        min-width: 50px;
    }
}
</style>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>
