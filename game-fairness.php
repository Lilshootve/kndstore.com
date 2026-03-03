<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader('Game Fairness & Transparency | KND Arena', 'How KND Arena ensures fair play: server-side RNG, integrity protections, and transparent rewards policy.'); ?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="game-fairness-hero py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="game-fairness-title mb-3">Game Fairness & Transparency</h1>
                <p class="game-fairness-subtitle">How we ensure fair play and transparent outcomes in KND Arena.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-dark-epic transparency-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto game-fairness-content transparency-content">

                <div class="transparency-card game-fairness-section mb-4">
                    <h2 class="game-fairness-h2"><i class="fas fa-random me-2"></i>How Randomness Works</h2>
                    <p class="game-fairness-text mb-3">
                        All random outcomes in KND Arena — including dice rolls, drop results, and game-critical decisions — are generated <strong>on the server</strong> using PHP’s <code>random_int()</code>. This is a cryptographically secure random number generator designed for fairness and unpredictability.
                    </p>
                    <p class="game-fairness-text mb-3">
                        Results are <strong>never</strong> determined by the client or your browser. When you roll, claim, or open a drop, the server computes the outcome and sends it to you. This design prevents manipulation and ensures every player has the same odds.
                    </p>
                </div>

                <div class="transparency-card game-fairness-section mb-4">
                    <h2 class="game-fairness-h2"><i class="fas fa-shield-alt me-2"></i>Integrity Protections</h2>
                    <p class="game-fairness-text mb-3">
                        We protect game integrity through server-side validation, rate limiting, and anti-abuse measures. Actions such as rolls, claims, and drops are verified and recorded on our systems. We do not rely on client-side data for outcomes.
                    </p>
                    <p class="game-fairness-text mb-0">
                        Our commitment is simple: the same rules for everyone, enforced consistently by the platform.
                    </p>
                </div>

                <div class="transparency-card game-fairness-section mb-4">
                    <h2 class="game-fairness-h2"><i class="fas fa-coins me-2"></i>Points & Rewards Policy</h2>
                    <p class="game-fairness-text mb-3">
                        <strong>KND Points (KP)</strong> and rewards earned in KND Arena are internal credits used within our ecosystem. They have <strong>no monetary value</strong> and cannot be exchanged for cash or other external currencies.
                    </p>
                    <p class="game-fairness-text mb-3">
                        Points and rewards are <strong>non-transferable</strong> between accounts and are tied to the account that earned them. There is no cashout option — KP is for in-platform use only (e.g. redemption for store credits, rewards, or participation in Arena features).
                    </p>
                    <p class="game-fairness-text mb-0">
                        We treat this as a transparent system: you play, you earn, you use within KND. No hidden economics.
                    </p>
                </div>

                <div class="transparency-card game-fairness-section mb-0">
                    <h2 class="game-fairness-h2"><i class="fas fa-eye me-2"></i>Transparency Commitment</h2>
                    <p class="game-fairness-text mb-3">
                        We believe players deserve to know how their games work. This page exists so you can see our approach: server-side randomness, clear rules, and honest communication about what points mean and how they can be used.
                    </p>
                    <p class="game-fairness-text mb-0">
                        Questions? Reach out via <a href="/contact.php" class="game-fairness-link">Contact</a> or our <a href="https://discord.gg/zjP3u5Yztx" target="_blank" rel="noopener" class="game-fairness-link">Discord</a>. We’re here to help.
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
/* Game Fairness - institutional, high readability */
.game-fairness-hero { padding-top: 140px; }
.game-fairness-title { font-size: 1.9rem; font-weight: 700; color: #fff; letter-spacing: .02em; }
.game-fairness-subtitle { font-size: 1rem; color: rgba(255,255,255,.7); }
.game-fairness-content { max-width: 72ch; }
.game-fairness-h2 { font-size: 1.25rem; font-weight: 600; color: rgba(0,212,255,.95); margin-bottom: 1rem; }
.game-fairness-text {
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    font-size: 1.05rem;
    line-height: 1.72;
    color: rgba(235, 245, 255, 0.93);
    text-shadow: 0 1px 2px rgba(0,0,0,.4);
}
.game-fairness-section code { background: rgba(0,0,0,.5); padding: 2px 6px; border-radius: 4px; font-size: .9em; color: #00d4ff; }
.game-fairness-link { color: rgba(0,212,255,.9); text-decoration: underline; text-underline-offset: 3px; }
.game-fairness-link:hover { color: #00d4ff; }
@media (max-width: 576px) {
    .game-fairness-content { max-width: 100%; }
    .game-fairness-text { font-size: 1rem; }
}
</style>

<?php echo generateFooter(); ?>
<?php echo generateScripts(); ?>
