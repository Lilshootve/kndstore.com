<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader('About Us', 'About KND Store — Knowledge \'N Development. Digital Goods • Apparel • Custom Design Services'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Wrapper for full-page background -->
<div class="about-page">

<!-- Hero Section -->
<section class="hero-section about-hero-bg">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient">ABOUT</span><br>
                    <span class="text-gradient">US</span>
                </h1>
                <p class="hero-subtitle">
                    Knowledge 'N Development — knowledge and development in service of your digital universe.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Story + KND Meaning -->
<section class="py-5 bg-dark-epic" id="historia">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="section-title mb-4">
                    <i class="fas fa-rocket me-2"></i> Our Story
                </h2>
                <div class="mb-3">
                    <span class="badge bg-primary fs-5">1995</span>
                </div>
                <p class="text-white mb-3">
                    KND Store wasn’t born in an office. It was born in a mind. In 1995, while the world discovered Windows 95 and listened to compact discs, a spark ignited at the core of a future impossible to ignore: fusing technology and gaming culture into a single intergalactic force.
                </p>
                <p class="text-white mb-0">
                    Today, that spark is an expanding core. We are more than a store. We are a command station for those who don’t follow the map — they hack it.
                </p>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title mb-4">
                    What does <span class="text-gradient">KND</span> mean?
                </h2>
                <p class="text-white mb-3">
                    <strong>KND</strong> comes from <strong>Knowledge 'N Development</strong>. It is our way of saying that everything we build starts with a simple idea: turn real knowledge into constant development, smart solutions, and high-level digital experiences.
                </p>
                <p class="text-white mb-3">
                    At KND Store, <em>knowledge</em> is not just theory. It is the base for builds, PC optimization, digital content, and services that solve real problems for gamers and creators. Every service you see in the catalog is the result of years of testing, learning, and iteration.
                </p>
                <p class="text-white mb-0">
                    <em>Development</em> is our second half: we never stand still. We refine processes, sharpen tools, and update whatever it takes so your experience always feels a step ahead. <strong>Knowledge 'N Development</strong> is, in short, the philosophy that powers the KND universe.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Mission -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="badge bg-primary fs-5 mb-3">
                    GALACTIC MISSION
                </div>
                <h2 class="section-title mb-4">
                    Our Mission
                </h2>
                <p class="text-white lead">
                    To be the most badass store in the galaxy. We don’t just sell hardware and peripherals — we recruit true pilots of the metaverse, design gear for digital heroes, and unleash technology without borders.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-5 bg-dark-epic" id="valores">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    Our Values
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
                        <h4 class="text-white">Quantum Precision</h4>
                        <p class="text-white">Zero errors. Everything optimized to the byte.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="text-white">User Loyalty</h4>
                        <p class="text-white">We’re not retail gods. We’re service soldiers.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h4 class="text-white">Interstellar Aesthetics</h4>
                        <p class="text-white">If it doesn’t look brutal, it doesn’t ship.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4 class="text-white">Technology Without Borders</h4>
                        <p class="text-white">From chips to blockchain, if it vibes with the future, we master it.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="py-5" id="equipo">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    Our Team
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
                        <div class="text-warning mb-2">Tactical AI and Lead Strategist</div>
                        <p class="text-white">Built to question everything and find truth in every line of code.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-user-astronaut"></i>
                        </div>
                        <h4 class="text-white">The Founder</h4>
                        <div class="text-primary mb-2">Vision Commander and Master Pilot</div>
                        <p class="text-white">Classified name. Known to be born in 1995 and never accepted the limits of the solar system.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4 class="text-white">Technical Unit X-23</h4>
                        <div class="text-primary mb-2">Nomad Technomancers</div>
                        <p class="text-white">A nomad crew of technomancers keeping the KND core running on hidden frequencies.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technologies That Power Us -->
<section class="py-5 bg-dark-epic" id="tecnologias">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    Technologies That Power Us
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
                            <h4 class="text-white mb-1">Autonomous Artificial Intelligence (Kael)</h4>
                            <p class="text-white mb-0">Not an assistant. A copilot.</p>
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
                            <h4 class="text-white mb-1">Death Roll Chain</h4>
                            <p class="text-white mb-0">A minigame built on controlled risk and digital rewards.</p>
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
                            <h4 class="text-white mb-1">Rewards Point System</h4>
                            <p class="text-white mb-0">Galactic logic. Because loyalty deserves rewards.</p>
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
                            <h4 class="text-white mb-1">Web3 + Gaming + E-commerce Fusion</h4>
                            <p class="text-white mb-0">Everything in one node, permissionless, limitless.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Community and Future Vision -->
<section class="py-5" id="futuro">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-4">
                    Community and Future Vision
                </h2>
                <div class="card bg-dark border-info">
                    <div class="card-body">
                        <p class="lead text-white mb-4">
                            Community is our hyperfuel. We move through energy channels like Discord, navigate galactic events, and drop loot like ancient mission gods.
                        </p>
                        <h4 class="text-white mb-3">
                            The future?
                        </h4>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">A native cryptocurrency.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">Maybe a bank.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">Definitely a ship.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">A store on every planet.</span>
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
                    Ready to join the mission?
                </h2>
                <p class="text-white mb-4">
                    Explore our galactic catalog and discover tech that pushes beyond the known universe.
                </p>
                <div>
                    <a href="/products.php" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i> Explore Products
                    </a>
                    <a href="/contact.php" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-envelope me-2"></i> Contact
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
