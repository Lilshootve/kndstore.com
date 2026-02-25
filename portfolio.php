<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$projects = [
    [
        'title' => 'Enviosand',
        'url' => 'https://enviosand.com/shipping-services',
        'goal' => __('portfolio.project.enviosand.goal', 'Increase shipping service inquiries with a clear booking flow.'),
        'role' => __('portfolio.project.enviosand.role', 'Landing page strategy, visual design, and conversion copy.'),
    ],
    [
        'title' => 'SolutionsEA',
        'url' => 'https://solutionseallc.com',
        'goal' => __('portfolio.project.solutionsea.goal', 'Drive qualified leads for enterprise consulting services.'),
        'role' => __('portfolio.project.solutionsea.role', 'Information architecture, UI direction, and CTA optimization.'),
    ],
    [
        'title' => 'Midwest Clean Solutions',
        'url' => 'https://midwestcleansol.com',
        'goal' => __('portfolio.project.midwest.goal', 'Increase service requests for commercial cleaning.'),
        'role' => __('portfolio.project.midwest.role', 'Conversion-focused layout, copy, and visual hierarchy.'),
    ],
    [
        'title' => 'JProd USA',
        'url' => 'https://jprodusa.com/services.html',
        'goal' => __('portfolio.project.jprod.goal', 'Position services with clarity and premium positioning.'),
        'role' => __('portfolio.project.jprod.role', 'Page redesign, messaging polish, and CTA strategy.'),
    ],
    [
        'title' => 'HK Jewelry',
        'url' => 'https://hkjewelrycorp.com',
        'goal' => __('portfolio.project.hk.goal', 'Showcase brand value and convert showroom inquiries.'),
        'role' => __('portfolio.project.hk.role', 'Landing page design, copy alignment, and user flow.'),
    ],
];
?>

<?php echo generateHeader(
    __('portfolio.meta.title', 'Portfolio'),
    __('portfolio.meta.description', 'Landing pages built to help businesses get clients and sales')
); ?>

<link rel="stylesheet" href="/assets/css/portfolio.css">

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<div class="portfolio-page">
    <!-- Hero Section -->
    <section class="hero-section portfolio-hero">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-8">
                    <div class="portfolio-hero-content">
                        <span class="portfolio-badge badge bg-primary">
                            <i class="fas fa-gem me-2"></i> <?php echo __('portfolio.hero.badge', 'Premium landing pages'); ?>
                        </span>
                        <h1 class="hero-title mt-4">
                            <span class="text-gradient">
                                <?php echo __('portfolio.hero.title', 'Landing pages built to help businesses get clients and sales'); ?>
                            </span>
                        </h1>
                        <p class="hero-subtitle">
                            <?php echo __('portfolio.hero.subtitle', 'I design conversion-focused landing pages that turn traffic into qualified leads and sales.'); ?>
                        </p>
                        <div class="hero-buttons">
                            <a href="/contact.php" class="btn btn-neon-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> <?php echo __('portfolio.hero.cta', 'Start a project'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="portfolio-section py-5 bg-dark-epic">
        <div class="container">
            <div class="row mb-4 align-items-end">
                <div class="col-lg-8">
                    <h2 class="section-title">
                        <?php echo __('portfolio.projects.title', 'Selected landing pages'); ?>
                    </h2>
                    <p class="portfolio-section-subtitle">
                        <?php echo __('portfolio.projects.subtitle', 'Five focused builds designed to convert traffic into booked calls and sales.'); ?>
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <?php foreach ($projects as $project): ?>
                    <div class="col-lg-4 col-md-6">
                        <article class="portfolio-card glass-card-neon h-100">
                            <div class="portfolio-card-body">
                                <h3 class="portfolio-card-title">
                                    <?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?>
                                </h3>
                                <div class="portfolio-card-meta">
                                    <div class="portfolio-meta-item">
                                        <span class="portfolio-meta-label">
                                            <?php echo __('portfolio.label.goal', 'Goal'); ?>
                                        </span>
                                        <span class="portfolio-meta-text">
                                            <?php echo $project['goal']; ?>
                                        </span>
                                    </div>
                                    <div class="portfolio-meta-item">
                                        <span class="portfolio-meta-label">
                                            <?php echo __('portfolio.label.role', 'Role'); ?>
                                        </span>
                                        <span class="portfolio-meta-text">
                                            <?php echo $project['role']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="portfolio-card-footer">
                                <a class="btn btn-outline-neon btn-sm portfolio-link"
                                   href="<?php echo htmlspecialchars($project['url'], ENT_QUOTES, 'UTF-8'); ?>"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    <?php echo __('portfolio.project.cta', 'View live'); ?>
                                    <i class="fas fa-arrow-up-right-from-square ms-2"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="section-title mb-4">
                        <?php echo __('portfolio.cta.title', 'Need a landing page for your business?'); ?>
                    </h2>
                    <a href="/contact.php" class="btn btn-neon-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i> <?php echo __('portfolio.cta.button', 'Request a quote'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/navigation-extend.js"></script>

<?php
echo generateFooter();
echo generateScripts();
?>
