<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader('Death Roll', 'Death Roll - Controlled risk mini-game with digital rewards at KND Store'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5 deathroll-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    <span class="text-gradient">Death Roll</span><br>
                    <span class="hero-subtitle-mini">Controlled risk, luck, and digital loot</span>
                </h1>
                <p class="hero-subtitle">
                    A galactic mini-game where a controlled roll determines your reward: keys, avatars, wallpapers, and more.
                    Managed by <strong>KND (Knowledge 'N Development)</strong> with clear rules and transparent results.
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <a href="#crates" class="btn btn-primary btn-lg">
                        <i class="fas fa-dice me-2"></i> View crates
                    </a>
                    <a href="#rules" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-scroll me-2"></i> Rules
                    </a>
                    <button class="btn btn-outline-neon btn-lg" onclick="copyDiscordServer()">
                        <i class="fab fa-discord me-2"></i> Play on Discord
                    </button>
                </div>
                <p class="mt-3 small text-white" style="opacity: 0.7;">
                    * The game and rewards are managed manually via <strong>Discord / WhatsApp</strong>, with logged results and screenshots.
                </p>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="deathroll-card-glass">
                    <h3 class="mb-3">
                        <i class="fas fa-dice-d20 me-2"></i> How it works
                    </h3>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Choose your Death Roll Crate.</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> We run the roll on Discord.</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Results are logged and shared.</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> You receive your digital loot.</li>
                    </ul>
                    <p class="small text-warning mb-0">
                        Digital service with mini-game experience; no gambling on-site.
                        You purchase a digital service and the mini-game decides the reward tier.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What is Death Roll -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    What is <span class="text-gradient">Death Roll</span>?
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    Death Roll is a <strong>controlled-risk mini-game</strong> where the outcome translates into
                    <strong>digital rewards</strong> managed by KND Store: keys, exclusive content, avatars, wallpapers, discounts, and more.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="knd-feature-card h-100">
                    <h4><i class="fas fa-dice-six me-2"></i> Visible RNG</h4>
                    <p class="text-white">
                        Rolls happen live in Discord so you can see results in real time.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="knd-feature-card h-100">
                    <h4><i class="fas fa-gift me-2"></i> Guaranteed loot</h4>
                    <p class="text-white">
                        Every Death Roll Crate guarantees a digital reward. The rarity changes based on the roll.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="knd-feature-card h-100">
                    <h4><i class="fas fa-shield-alt me-2"></i> Transparent logs</h4>
                    <p class="text-white">
                        All rounds are documented to keep history and transparency.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How to play -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    <i class="fas fa-route me-2"></i> How to play
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    Simple, direct, and controlled by <strong>Knowledge 'N Development</strong>.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">1</span>
                    <h5>Buy your crate</h5>
                    <p class="text-white">Get the <strong>Death Roll Crate</strong> from the catalog.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">2</span>
                    <h5>Join the channel</h5>
                    <p class="text-white">Join the Discord channel or coordinate via WhatsApp.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">3</span>
                    <h5>We roll</h5>
                    <p class="text-white">The roll follows the rules and determines your reward tier.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">4</span>
                    <h5>Claim your loot</h5>
                    <p class="text-white">Receive your digital reward based on the result.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Crates -->
<section class="py-5 bg-dark-epic" id="crates">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">Crates</h2>
                <p class="text-white">Choose your crate and roll for your reward tier.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card bg-dark border-primary h-100 text-center">
                    <div class="card-body">
                        <h4 class="text-white mb-3">KND Digital Store</h4>
                        <p class="text-white-50">Browse our full catalog of digital services.</p>
                        <a href="/products.php" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i> View Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rules -->
<section class="py-5" id="rules">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">Rules</h2>
                <p class="text-white">Fair play and transparent results, always.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <ul class="list-unstyled text-white">
                    <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Rolls are visible and logged.</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Rewards are delivered after the roll.</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> One crate = one roll.</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> By participating, you accept KND Store terms.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">Ready to roll?</h2>
                <p class="text-white mb-4">Grab a crate and jump into the Death Roll session.</p>
                <a href="/products.php" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-shopping-cart me-2"></i> Visit shop
                </a>
                <a href="/order.php" class="btn btn-outline-neon btn-lg">
                    <i class="fas fa-clipboard-list me-2"></i> View order
                </a>
            </div>
        </div>
    </div>
</section>

<script>
function copyDiscordServer() {
    navigator.clipboard.writeText('discord.gg/zjP3u5Yztx').then(function() {
        alert('Discord invite copied!');
    }).catch(function() {
        alert('Discord invite: discord.gg/zjP3u5Yztx');
    });
}
</script>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>
