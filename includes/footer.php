<?php
// KND Store - Footer común

// Función para generar el footer completo
function generateFooter() {
    ob_start();
    ?>
    <!-- Footer -->
    <footer class="footer py-5 position-relative">
        <div class="container position-relative z-2">
            <div class="row g-5">

                <div class="col-lg-6">
                    <h3 class="mb-4">
                        <span class="glow-text">KND STORE</span>
                    </h3>

                    <p class="mb-4" style="opacity: 0.8;">
                        <?= t('footer.about_text') ?>
                    </p>

                    <div class="d-flex mt-4">
                        <a href="https://discord.gg/zjP3u5Yztx" target="_blank" rel="noopener" class="btn btn-outline-neon btn-icon me-3" title="Discord">
                            <i class="fab fa-discord"></i>
                        </a>
                        <a href="https://www.instagram.com/kndofficialstore" target="_blank" rel="noopener" class="btn btn-outline-neon btn-icon me-3" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://x.com/knd_store" target="_blank" rel="noopener" class="btn btn-outline-neon btn-icon me-3" title="X">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://www.tiktok.com/@kndstoreofficial" target="_blank" rel="noopener" class="btn btn-outline-neon btn-icon" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-4">

                        <!-- Navigation -->
                        <div class="col-md-4">
                            <h5 class="mb-4"><?= t('footer.navigation.title') ?></h5>
                            <ul class="list-unstyled">
                                <li class="mb-3"><a href="/faq.php" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.navigation.faq') ?></a></li>
                                <li class="mb-3"><a href="/contact.php" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.navigation.contact') ?></a></li>
                                <li class="mb-3"><a href="/privacy.php" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.navigation.privacy') ?></a></li>
                                <li class="mb-3"><a href="/game-fairness" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.navigation.game_fairness', 'Game Fairness') ?></a></li>
                                <li class="mb-3"><a href="/privacy.php#cookies-policy" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.navigation.cookies') ?></a></li>
                                <li class="mb-3"><a href="/track-order.php" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.navigation.track_order') ?></a></li>
                                <li class="mb-3"><a href="#" class="text-decoration-none knd-cookie-settings-link" style="opacity: 0.8;"><?= t('footer.navigation.cookie_settings') ?></a></li>
                            </ul>
                        </div>

                        <!-- Arena + Tools -->
                        <div class="col-md-4">
                            <h5 class="mb-4"><?= t('footer.arena.title', 'Arena') ?></h5>
                            <ul class="list-unstyled">
                                <li class="mb-3"><a href="/arena" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.arena.knd_arena', 'KND Arena') ?></a></li>
                                <li class="mb-3"><a href="/how-knd-arena-works" class="text-decoration-none" style="opacity: 0.8;"><?= t('footer.arena.how_it_works', 'How Arena Works') ?></a></li>
                            </ul>

                            <h5 class="mb-4 mt-4"><?= t('footer.tools.title', 'Tools') ?></h5>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <a href="/labs" class="text-decoration-none" style="opacity: 0.8;">
                                        <i class="fas fa-microscope me-2" style="color: var(--knd-neon-blue);"></i><?= t('footer.tools.knd_labs', 'KND Labs') ?>
                                    </a>
                                </li>
                                <li class="mb-3">
                                    <a href="/labs?tool=3d" class="text-decoration-none" style="opacity: 0.8;">
                                        <i class="fas fa-cube me-2" style="color: var(--knd-neon-blue);"></i>3D Lab
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Contact + Payments -->
                        <div class="col-md-4">
                            <h5 class="mb-4"><?= t('footer.contact.title') ?></h5>
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-envelope me-3" style="color: var(--knd-neon-blue);"></i>
                                    <span style="opacity: 0.8;">info@kndstore.com</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fas fa-headset me-3" style="color: var(--knd-neon-blue);"></i>
                                    <span style="opacity: 0.8;"><?= t('footer.contact.support_247') ?></span>
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="fab fa-discord me-3" style="color: var(--knd-neon-blue);"></i>
                                    <a href="https://discord.gg/zjP3u5Yztx" target="_blank" rel="noopener" class="text-decoration-none" style="opacity: 0.8;">Discord: KND Store</a>
                                </li>
                            </ul>

                            <h6 class="mt-4 mb-3"><?= t('footer.payments.title') ?></h6>
                            <div class="d-flex footer-payment-icons">
                                <div class="me-3 mb-3 footer-payment-icon"><i class="fab fa-cc-paypal fa-2x" style="color: var(--knd-electric-purple);" title="PayPal"></i></div>
                                <div class="me-3 mb-3 footer-payment-icon"><i class="fab fa-cc-visa fa-2x" style="color: var(--knd-electric-purple);" title="Visa"></i></div>
                                <div class="me-3 mb-3 footer-payment-icon"><i class="fas fa-wallet fa-2x" style="color: var(--knd-electric-purple);" title="Binance Pay"></i></div>
                                <div class="me-3 mb-3 footer-payment-icon"><i class="fas fa-university fa-2x" style="color: var(--knd-electric-purple);" title="Bank Transfer / ACH / Wire"></i></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.2);">

            <div class="text-center pt-3">
                <p class="mb-1" style="opacity: 0.7; font-size: 0.9rem;">
                    <?= defined('KND_MEANING_TEXT')
                        ? KND_MEANING_TEXT
                        : 'KND = <strong>Knowledge ‘N Development</strong> — knowledge turned into fast, secure, innovative digital solutions.' ?>
                </p>
                <p class="mb-0" style="opacity: 0.7;">
                    <?= t('footer.copyright', null, ['year' => date('Y')]) ?>
                </p>
            </div>
        </div>

        <!-- Efecto de partículas para el footer -->
        <div id="particles-footer" class="position-absolute top-0 left-0 w-100 h-100" style="z-index: 1;"></div>
    </footer>

    <!-- Cookie Consent Banner & Modal -->
    <div id="knd-cookie-banner" class="knd-cookie-banner">
        <div class="knd-cookie-banner-inner glass-card-neon">
            <div class="knd-cookie-banner-text">
                <h5 class="mb-2" data-knd-cookie-title><?= t('cookie.title') ?></h5>
                <p class="mb-0 small" data-knd-cookie-message><?= t('cookie.message') ?></p>
            </div>
            <div class="knd-cookie-banner-actions">
                <button type="button" id="knd-cookie-reject-all" class="btn btn-sm btn-outline-light me-2"><?= t('cookie.btn.reject_all') ?></button>
                <button type="button" id="knd-cookie-customize" class="btn btn-sm btn-outline-neon me-2"><?= t('cookie.btn.customize') ?></button>
                <button type="button" id="knd-cookie-accept-all" class="btn btn-sm btn-neon-primary"><?= t('cookie.btn.accept_all') ?></button>
            </div>
        </div>
    </div>

    <div id="knd-cookie-modal" class="knd-cookie-modal">
        <div class="knd-cookie-modal-backdrop"></div>
        <div class="knd-cookie-modal-dialog glass-card-neon">
            <div class="knd-cookie-modal-header d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0" data-knd-cookie-title><?= t('cookie.title') ?></h5>
                <button type="button" class="btn btn-sm btn-outline-light" data-knd-cookie-close>&times;</button>
            </div>
            <div class="knd-cookie-modal-body">
                <p class="small mb-3" data-knd-cookie-message><?= t('cookie.message') ?></p>

                <div class="knd-cookie-category mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= t('cookie.category.necessary') ?></strong>
                            <p class="small mb-0 text-white-50"><?= t('cookie.category.necessary_desc') ?></p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" checked disabled>
                        </div>
                    </div>
                </div>

                <div class="knd-cookie-category mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= t('cookie.category.preferences') ?></strong>
                            <p class="small mb-0 text-white-50"><?= t('cookie.category.preferences_desc') ?></p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="knd-consent-preferences">
                        </div>
                    </div>
                </div>

                <div class="knd-cookie-category mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= t('cookie.category.analytics') ?></strong>
                            <p class="small mb-0 text-white-50"><?= t('cookie.category.analytics_desc') ?></p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="knd-consent-analytics">
                        </div>
                    </div>
                </div>

                <div class="knd-cookie-category mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= t('cookie.category.marketing') ?></strong>
                            <p class="small mb-0 text-white-50"><?= t('cookie.category.marketing_desc') ?></p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="knd-consent-marketing">
                        </div>
                    </div>
                </div>

                <p class="small text-white-50 mt-3 mb-0"><?= t('cookie.note') ?></p>
            </div>

            <div class="knd-cookie-modal-footer mt-3 d-flex flex-column flex-md-row justify-content-between align-items-stretch">
                <button type="button" id="knd-cookie-modal-reject-all" class="btn btn-outline-light mb-2 mb-md-0 flex-fill me-md-2"><?= t('cookie.btn.reject_all') ?></button>
                <button type="button" id="knd-cookie-save-preferences" class="btn btn-neon-primary flex-fill"><?= t('cookie.btn.save_preferences') ?></button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Función para generar los scripts comunes
function generateScripts() {
    $scripts = '';
    
    // Level-up y badge (defer para no bloquear render)
    $scripts .= '<script src="/assets/js/knd-xp-fx.js" defer></script>' . "\n";
    $scripts .= '<script src="/assets/js/level-up.js" defer></script>' . "\n";
    
    // jQuery con preload
    $scripts .= '<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>' . "\n";
    
    // Bootstrap JS con preload
    $scripts .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>' . "\n";
    
    // Particles.js con preload
    $scripts .= '<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js" defer></script>' . "\n";
    
    // Header dinámico (scroll: ocultar/mostrar, estado scrolled)
    $scripts .= '<script src="/assets/js/header-dynamic.js" defer></script>' . "\n";
    // Custom JS optimizado
    $scripts .= '<script src="assets/js/main.js" defer></script>' . "\n";
    
    // Gestor de consentimiento de cookies
    $scripts .= '<script src="assets/js/cookies-consent.js" defer></script>' . "\n";
    
    // Mobile Optimization JS
    $scripts .= '<script src="assets/js/mobile-optimization.js" defer></script>' . "\n";
    
    // Scroll suave por bloques
    $scripts .= '<script src="assets/js/scroll-smooth.js" defer></script>' . "\n";

    // Confetti (Legendary) - toast/xp-fx/level-up loaded in header for early availability
    $scripts .= '<script src="assets/js/knd-confetti.js" defer></script>' . "\n";
    
    // Partículas footer (único canvas, deferred para no bloquear render)
    $scripts .= '<script>' . "\n";
    $scripts .= 'var _particlesInited = false;' . "\n";
    $scripts .= 'function initParticles() {' . "\n";
    $scripts .= '  if (_particlesInited) return;' . "\n";
    $scripts .= '  if (typeof particlesJS === "undefined") { setTimeout(initParticles, 150); return; }' . "\n";
    $scripts .= '  var el = document.getElementById("particles-footer");' . "\n";
    $scripts .= '  if (!el) return;' . "\n";
    $scripts .= '  _particlesInited = true;' . "\n";
    $scripts .= '  ' . "\n";
    $scripts .= '  particlesJS("particles-footer", {' . "\n";
    $scripts .= '  particles: {' . "\n";
    $scripts .= '    number: { value: 45, density: { enable: true, value_area: 900 } },' . "\n";
    $scripts .= '    color: { value: ["#35C2FF", "#8B5CFF", "#67D5FF"] },' . "\n";
    $scripts .= '    shape: {' . "\n";
    $scripts .= '      type: "circle",' . "\n";
    $scripts .= '      stroke: {' . "\n";
    $scripts .= '        width: 0,' . "\n";
    $scripts .= '        color: "#000000"' . "\n";
    $scripts .= '      },' . "\n";
    $scripts .= '      polygon: {' . "\n";
    $scripts .= '        nb_sides: 5' . "\n";
    $scripts .= '      }' . "\n";
    $scripts .= '    },' . "\n";
    $scripts .= '    opacity: { value: 0.65,' . "\n";
    $scripts .= '      random: false,' . "\n";
    $scripts .= '      anim: {' . "\n";
    $scripts .= '        enable: false,' . "\n";
    $scripts .= '        speed: 1,' . "\n";
    $scripts .= '        opacity_min: 0.1,' . "\n";
    $scripts .= '        sync: false' . "\n";
    $scripts .= '      }' . "\n";
    $scripts .= '    },' . "\n";
    $scripts .= '    size: {' . "\n";
    $scripts .= '      value: 3,' . "\n";
    $scripts .= '      random: true,' . "\n";
    $scripts .= '      anim: {' . "\n";
    $scripts .= '        enable: false,' . "\n";
    $scripts .= '        speed: 40,' . "\n";
    $scripts .= '        size_min: 0.1,' . "\n";
    $scripts .= '        sync: false' . "\n";
    $scripts .= '      }' . "\n";
    $scripts .= '    },' . "\n";
    $scripts .= '    line_linked: { enable: true, distance: 130, color: "#35C2FF", opacity: 0.6, width: 1 },' . "\n";
    $scripts .= '    move: { enable: true, speed: 4,' . "\n";
    $scripts .= '      direction: "none",' . "\n";
    $scripts .= '      random: false,' . "\n";
    $scripts .= '      straight: false,' . "\n";
    $scripts .= '      out_mode: "out",' . "\n";
    $scripts .= '      bounce: false,' . "\n";
    $scripts .= '      attract: {' . "\n";
    $scripts .= '        enable: false,' . "\n";
    $scripts .= '        rotateX: 600,' . "\n";
    $scripts .= '        rotateY: 1200' . "\n";
    $scripts .= '      }' . "\n";
    $scripts .= '    }' . "\n";
    $scripts .= '  },' . "\n";
    $scripts .= '  interactivity: { detect_on: "canvas", events: { resize: true } },' . "\n";
    $scripts .= '  retina_detect: true });' . "\n";
    $scripts .= '}' . "\n";
    $scripts .= 'function scheduleParticles() {' . "\n";
    $scripts .= '  var el = document.getElementById("particles-footer");' . "\n";
    $scripts .= '  if (!el) return;' . "\n";
    $scripts .= '  if ("IntersectionObserver" in window) {' . "\n";
    $scripts .= '    var io = new IntersectionObserver(function(entries) {' . "\n";
    $scripts .= '      if (entries[0].isIntersecting) { initParticles(); io.disconnect(); }' . "\n";
    $scripts .= '    }, { rootMargin: "100px", threshold: 0 });' . "\n";
    $scripts .= '    io.observe(el);' . "\n";
    $scripts .= '    setTimeout(function() { if (typeof initParticles === "function") initParticles(); io.disconnect(); }, 3000);' . "\n";
    $scripts .= '  } else {' . "\n";
    $scripts .= '    window.addEventListener("load", function() { setTimeout(initParticles, 400); });' . "\n";
    $scripts .= '  }' . "\n";
    $scripts .= '}' . "\n";
    $scripts .= 'if (document.readyState === "loading") { document.addEventListener("DOMContentLoaded", scheduleParticles); }' . "\n";
    $scripts .= 'else { scheduleParticles(); }' . "\n";
    $scripts .= '</script>' . "\n";
    
    // Panel de personalización de colores
    $scripts .= generateColorPanel();
    
    // Support AI Chat Widget
    $scripts .= '<link rel="stylesheet" href="/assets/css/support-chat.css">' . "\n";
    $scripts .= '<button id="knd-chat-btn" class="knd-chat-btn" title="Support" aria-label="Open support chat"><i class="fas fa-headset"></i></button>' . "\n";
    $scripts .= '<div id="knd-chat-panel" class="knd-chat-panel">' . "\n";
    $scripts .= '  <div class="knd-chat-header">' . "\n";
    $scripts .= '    <div class="knd-chat-header-info"><h4>KND Support</h4><span>AI assistant &bull; 24/7</span></div>' . "\n";
    $scripts .= '    <button class="knd-chat-close" aria-label="Close">&times;</button>' . "\n";
    $scripts .= '  </div>' . "\n";
    $scripts .= '  <div class="knd-chat-messages"></div>' . "\n";
    $scripts .= '  <div class="knd-chat-typing"><span></span><span></span><span></span></div>' . "\n";
    $scripts .= '  <div class="knd-chat-quick">' . "\n";
    $scripts .= '    <button class="knd-chat-quick-btn" data-msg="What payment methods do you accept?">Payment</button>' . "\n";
    $scripts .= '    <button class="knd-chat-quick-btn" data-msg="How long does delivery take?">Delivery</button>' . "\n";
    $scripts .= '    <button class="knd-chat-quick-btn" data-msg="How do I choose the right size?">Sizing</button>' . "\n";
    $scripts .= '    <button class="knd-chat-quick-btn" data-msg="What is your refund policy?">Refunds</button>' . "\n";
    $scripts .= '    <button class="knd-chat-quick-btn" data-msg="How can I reach human support?">Contact</button>' . "\n";
    $scripts .= '  </div>' . "\n";
    $scripts .= '  <div class="knd-chat-input-bar">' . "\n";
    $scripts .= '    <textarea class="knd-chat-input" placeholder="Type a message..." rows="1"></textarea>' . "\n";
    $scripts .= '    <button class="knd-chat-send" aria-label="Send"><i class="fas fa-paper-plane"></i></button>' . "\n";
    $scripts .= '  </div>' . "\n";
    $scripts .= '</div>' . "\n";
    $scripts .= '<script src="/assets/js/support-chat.js" defer></script>' . "\n";

    
    $scripts .= '</body>' . "\n";
    $scripts .= '</html>' . "\n";
    
    return $scripts;
}
?> 