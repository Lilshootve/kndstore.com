<?php
/**
 * KND Home Demo - Propuesta visual premium
 * Vista aislada. No modifica producción.
 */
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

$extraCss = '<link rel="stylesheet" href="/assets/css/knd-demo-future.css?v=' . (file_exists(__DIR__ . '/assets/css/knd-demo-future.css') ? filemtime(__DIR__ . '/assets/css/knd-demo-future.css') : time()) . '">';
echo generateHeader('KND — Build, Create & Launch', 'AI tools, digital services and smart products in one ecosystem', $extraCss);
?>
<?php echo generateNavigation(); ?>

<div class="knd-demo-shell">
  <!-- Hero -->
  <section class="knd-demo-hero">
    <div class="container">
      <h1 class="knd-demo-hero__headline">Build, create and launch with KND</h1>
      <p class="knd-demo-hero__sub">AI tools, digital services and smart products in one ecosystem. Create visuals, scale assets and ship digital experiences.</p>
      <div class="knd-demo-hero__cta">
        <a href="/labs-workspace-demo.php" class="knd-btn-primary"><i class="fas fa-microscope me-2"></i>Explore Labs</a>
        <a href="/products.php" class="knd-btn-secondary">View Store</a>
      </div>
      <div class="knd-prompt-bar">
        <input type="text" placeholder="Describe what you want to create..." readonly>
        <button class="knd-btn-primary" style="padding: 0.5rem 1rem;">Go</button>
      </div>
    </div>
  </section>

  <!-- Ecosystem -->
  <section class="py-5">
    <div class="container">
      <h2 class="knd-section-title mb-4">The Ecosystem</h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <a href="/labs-workspace-demo.php" class="text-decoration-none">
            <div class="knd-eco-card">
              <div class="knd-eco-card__icon"><i class="fas fa-microscope"></i></div>
              <div class="knd-eco-card__title">KND Labs</div>
              <div class="knd-eco-card__desc">AI-powered creation. Text to image, upscale, consistency and more.</div>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-3">
          <a href="/products.php" class="text-decoration-none">
            <div class="knd-eco-card">
              <div class="knd-eco-card__icon"><i class="fas fa-store"></i></div>
              <div class="knd-eco-card__title">KND Store</div>
              <div class="knd-eco-card__desc">Digital services, apparel and premium products.</div>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-3">
          <a href="/knd-arena.php" class="text-decoration-none">
            <div class="knd-eco-card">
              <div class="knd-eco-card__icon"><i class="fas fa-gamepad"></i></div>
              <div class="knd-eco-card__title">KND Arena</div>
              <div class="knd-eco-card__desc">Compete, earn and level up in skill-based games.</div>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="knd-eco-card">
            <div class="knd-eco-card__icon"><i class="fas fa-headset"></i></div>
            <div class="knd-eco-card__title">Support & Custom</div>
            <div class="knd-eco-card__desc">Premium support and custom digital workflows.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Showcase -->
  <section class="py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="knd-section-title mb-0">Recent Creations</h2>
        <a href="/labs-text-to-image.php" class="knd-btn-secondary btn-sm">View All</a>
      </div>
      <div class="knd-card-grid">
        <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="knd-showcase-card">
          <div class="knd-showcase-card__img"><i class="fas fa-image"></i></div>
          <div class="knd-showcase-card__body">
            <div class="knd-showcase-card__title">Creation #<?php echo $i + 1; ?></div>
            <div class="knd-showcase-card__meta">Text → Image · 1024×1024</div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </section>

  <!-- How It Works -->
  <section class="py-5">
    <div class="container">
      <h2 class="knd-section-title mb-4">How It Works</h2>
      <div class="row g-4 text-center">
        <div class="col-md-4">
          <div class="knd-panel">
            <div class="mb-3"><span class="badge bg-secondary">1</span></div>
            <h5 class="text-white mb-2">Choose</h5>
            <p class="knd-muted mb-0 small">Pick a tool or service from the ecosystem.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="knd-panel">
            <div class="mb-3"><span class="badge bg-secondary">2</span></div>
            <h5 class="text-white mb-2">Create</h5>
            <p class="knd-muted mb-0 small">Generate, design and refine with AI and premium workflows.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="knd-panel">
            <div class="mb-3"><span class="badge bg-secondary">3</span></div>
            <h5 class="text-white mb-2">Launch</h5>
            <p class="knd-muted mb-0 small">Download, ship or integrate. Your work, ready to go.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Benefits -->
  <section class="py-5">
    <div class="container">
      <h2 class="knd-section-title mb-4">Why KND</h2>
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <div class="knd-panel text-center py-3">
            <i class="fas fa-bolt text-primary mb-2"></i>
            <p class="knd-muted small mb-0">Fast delivery</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="knd-panel text-center py-3">
            <i class="fas fa-headset text-primary mb-2"></i>
            <p class="knd-muted small mb-0">Premium support</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="knd-panel text-center py-3">
            <i class="fas fa-cogs text-primary mb-2"></i>
            <p class="knd-muted small mb-0">Smart workflows</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="knd-panel text-center py-3">
            <i class="fas fa-palette text-primary mb-2"></i>
            <p class="knd-muted small mb-0">Creative + technical</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Final -->
  <section class="py-5">
    <div class="container">
      <div class="knd-panel knd-panel--active text-center py-5">
        <h3 class="text-white mb-2">Start with KND</h3>
        <p class="knd-muted mb-4">Discover the ecosystem</p>
        <a href="/labs-workspace-demo.php" class="knd-btn-primary me-2">Explore Labs</a>
        <a href="/products.php" class="knd-btn-secondary">View Services</a>
      </div>
    </div>
  </section>

  <!-- Footer Demo -->
  <footer class="knd-demo-footer">
    <div class="container">
      <div class="knd-demo-footer__links">
        <a href="/">Home</a>
        <a href="/knd-labs.php">Labs</a>
        <a href="/products.php">Services</a>
        <a href="/contact.php">Contact</a>
      </div>
      <p class="knd-demo-footer__copy">© KND Store. Demo prototype.</p>
    </div>
  </footer>
</div>
<?php echo generateFooter(); echo generateScripts(); ?>
