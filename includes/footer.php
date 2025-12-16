<?php
// KND Store - Footer común

// Función para generar el footer completo
function generateFooter() {
    $footer = '    <!-- Footer -->' . "\n";
    $footer .= '    <footer class="footer py-5 position-relative">' . "\n";
    $footer .= '        <div class="container position-relative z-2">' . "\n";
    $footer .= '            <div class="row g-5">' . "\n";
    $footer .= '                <div class="col-lg-6">' . "\n";
    $footer .= '                    <h3 class="mb-4">' . "\n";
    $footer .= '                        <span class="glow-text">KND STORE</span>' . "\n";
    $footer .= '                    </h3>' . "\n";
    $footer .= '                    <p class="mb-4" style="opacity: 0.8;">' . "\n";
    $footer .= '                        Tu tienda galáctica de productos únicos y tecnología de vanguardia. Descubre un universo de posibilidades con nuestro catálogo exclusivo.' . "\n";
    $footer .= '                    </p>' . "\n";
    $footer .= '                    <div class="d-flex mt-4">' . "\n";
    $footer .= '                        <a href="#" class="btn btn-outline-neon btn-icon me-3">' . "\n";
    $footer .= '                            <i class="fab fa-discord"></i>' . "\n";
    $footer .= '                        </a>' . "\n";
    $footer .= '                        <a href="#" class="btn btn-outline-neon btn-icon me-3">' . "\n";
    $footer .= '                            <i class="fab fa-twitter"></i>' . "\n";
    $footer .= '                        </a>' . "\n";
    $footer .= '                        <a href="#" class="btn btn-outline-neon btn-icon me-3">' . "\n";
    $footer .= '                            <i class="fab fa-instagram"></i>' . "\n";
    $footer .= '                        </a>' . "\n";
    $footer .= '                        <a href="#" class="btn btn-outline-neon btn-icon">' . "\n";
    $footer .= '                            <i class="fab fa-youtube"></i>' . "\n";
    $footer .= '                        </a>' . "\n";
    $footer .= '                    </div>' . "\n";
    $footer .= '                </div>' . "\n";
    $footer .= '                ' . "\n";
    $footer .= '                <div class="col-lg-6">' . "\n";
    $footer .= '                    <div class="row">' . "\n";
    $footer .= '                        <div class="col-md-6">' . "\n";
    $footer .= '                            <h5 class="mb-4">NAVEGACIÓN</h5>' . "\n";
    $footer .= '                            <ul class="list-unstyled">' . "\n";
    $footer .= '                                <li class="mb-3"><a href="/faq.php" class="text-decoration-none" style="opacity: 0.8;">FAQ</a></li>' . "\n";
    $footer .= '                                <li class="mb-3"><a href="/contact.php" class="text-decoration-none" style="opacity: 0.8;">Contacto</a></li>' . "\n";
    $footer .= '                                <li class="mb-3"><a href="/privacy.php" class="text-decoration-none" style="opacity: 0.8;">Política de Privacidad</a></li>' . "\n";
    $footer .= '                                <li class="mb-3"><a href="/terms.php" class="text-decoration-none" style="opacity: 0.8;">Términos y Condiciones</a></li>' . "\n";
    $footer .= '                            </ul>' . "\n";
    $footer .= '                        </div>' . "\n";
    $footer .= '                        ' . "\n";
    $footer .= '                        <div class="col-md-6">' . "\n";
    $footer .= '                            <h5 class="mb-4">CONTACTO</h5>' . "\n";
    $footer .= '                            <ul class="list-unstyled">' . "\n";
    $footer .= '                                <li class="mb-3 d-flex align-items-center">' . "\n";
    $footer .= '                                    <i class="fas fa-envelope me-3" style="color: var(--knd-neon-blue);"></i>' . "\n";
    $footer .= '                                    <span style="opacity: 0.8;">info@kndstore.com</span>' . "\n";
    $footer .= '                                </li>' . "\n";
    $footer .= '                                <li class="mb-3 d-flex align-items-center">' . "\n";
    $footer .= '                                    <i class="fas fa-headset me-3" style="color: var(--knd-neon-blue);"></i>' . "\n";
    $footer .= '                                    <span style="opacity: 0.8;">Soporte 24/7</span>' . "\n";
    $footer .= '                                </li>' . "\n";
    $footer .= '                                <li class="mb-3 d-flex align-items-center">' . "\n";
    $footer .= '                                    <i class="fab fa-discord me-3" style="color: var(--knd-neon-blue);"></i>' . "\n";
    $footer .= '                                    <span style="opacity: 0.8;">Discord: KND_Store</span>' . "\n";
    $footer .= '                                </li>' . "\n";
    $footer .= '                            </ul>' . "\n";
    $footer .= '                            ' . "\n";
    $footer .= '                            <h6 class="mt-4 mb-3">PAGOS SEGUROS</h6>' . "\n";
    $footer .= '                            <div class="d-flex flex-wrap">' . "\n";
    $footer .= '                                <div class="me-3 mb-3">' . "\n";
    $footer .= '                                    <i class="fab fa-cc-paypal fa-2x" style="color: var(--knd-electric-purple);"></i>' . "\n";
    $footer .= '                                </div>' . "\n";
    $footer .= '                                <div class="me-3 mb-3">' . "\n";
    $footer .= '                                    <i class="fab fa-cc-stripe fa-2x" style="color: var(--knd-electric-purple);"></i>' . "\n";
    $footer .= '                                </div>' . "\n";
    $footer .= '                                <div class="me-3 mb-3">' . "\n";
    $footer .= '                                    <i class="fab fa-bitcoin fa-2x" style="color: var(--knd-electric-purple);"></i>' . "\n";
    $footer .= '                                </div>' . "\n";
    $footer .= '                                <div class="me-3 mb-3">' . "\n";
    $footer .= '                                    <i class="fab fa-cc-visa fa-2x" style="color: var(--knd-electric-purple);"></i>' . "\n";
    $footer .= '                                </div>' . "\n";
    $footer .= '                            </div>' . "\n";
    $footer .= '                        </div>' . "\n";
    $footer .= '                    </div>' . "\n";
    $footer .= '                </div>' . "\n";
    $footer .= '            </div>' . "\n";
    $footer .= '            ' . "\n";
    $footer .= '            <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.2);">' . "\n";
    $footer .= '            ' . "\n";
    $footer .= '            <div class="text-center pt-3">' . "\n";
    $footer .= '                <p class="mb-1" style="opacity: 0.7; font-size: 0.9rem;">' . "\n";
    $footer .= '                    ' . (defined('KND_MEANING_TEXT') ? KND_MEANING_TEXT : 'KND = <strong>Knowledge ‘N Development</strong> — conocimiento convertido en soluciones digitales rápidas, seguras e innovadoras.') . "\n";
    $footer .= '                </p>' . "\n";
    $footer .= '                <p class="mb-0" style="opacity: 0.7;">' . "\n";
    $footer .= '                    &copy; ' . date('Y') . ' KND STORE. Todos los derechos reservados.' . "\n";
    $footer .= '                </p>' . "\n";
    $footer .= '            </div>' . "\n";
    $footer .= '        </div>' . "\n";
    $footer .= '        ' . "\n";
    $footer .= '        <!-- Efecto de partículas para el footer -->' . "\n";
    $footer .= '        <div id="particles-footer" class="position-absolute top-0 left-0 w-100 h-100" style="z-index: 1;"></div>' . "\n";
    $footer .= '    </footer>' . "\n";
    
    return $footer;
}

// Función para generar los scripts comunes
function generateScripts() {
    $scripts = '';
    
    // jQuery con preload
    $scripts .= '<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>' . "\n";
    
    // Bootstrap JS con preload
    $scripts .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>' . "\n";
    
    // Particles.js con preload
    $scripts .= '<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js" defer></script>' . "\n";
    
    // Custom JS optimizado
    $scripts .= '<script src="assets/js/main.js" defer></script>' . "\n";
    
    // Mobile Optimization JS
    $scripts .= '<script src="assets/js/mobile-optimization.js" defer></script>' . "\n";
    
    // Scroll suave por bloques
    $scripts .= '<script src="assets/js/scroll-smooth.js" defer></script>' . "\n";
    
    // Configuración de partículas para el footer
    $scripts .= '<script>' . "\n";
    $scripts .= '// Configuración de partículas para el footer' . "\n";
    $scripts .= 'function initParticles() {' . "\n";
    $scripts .= '  if (typeof particlesJS === "undefined") {' . "\n";
    $scripts .= '    // Esperar a que particles.js se cargue' . "\n";
    $scripts .= '    setTimeout(initParticles, 100);' . "\n";
    $scripts .= '    return;' . "\n";
    $scripts .= '  }' . "\n";
    $scripts .= '  ' . "\n";
    $scripts .= '  const particlesContainer = document.getElementById("particles-footer");' . "\n";
    $scripts .= '  if (!particlesContainer) {' . "\n";
    $scripts .= '    return;' . "\n";
    $scripts .= '  }' . "\n";
    $scripts .= '  ' . "\n";
    $scripts .= '  particlesJS("particles-footer", {' . "\n";
    $scripts .= '  particles: {' . "\n";
    $scripts .= '    number: {' . "\n";
    $scripts .= '      value: 80,' . "\n";
    $scripts .= '      density: {' . "\n";
    $scripts .= '        enable: true,' . "\n";
    $scripts .= '        value_area: 800' . "\n";
    $scripts .= '      }' . "\n";
    $scripts .= '    },' . "\n";
    $scripts .= '    color: {' . "\n";
    $scripts .= '      value: ["#259cae", "#8a2be2", "#00d4ff"]' . "\n";
    $scripts .= '    },' . "\n";
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
    $scripts .= '    opacity: {' . "\n";
    $scripts .= '      value: 0.5,' . "\n";
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
    $scripts .= '    line_linked: {' . "\n";
    $scripts .= '      enable: true,' . "\n";
    $scripts .= '      distance: 150,' . "\n";
    $scripts .= '      color: "#259cae",' . "\n";
    $scripts .= '      opacity: 0.4,' . "\n";
    $scripts .= '      width: 1' . "\n";
    $scripts .= '    },' . "\n";
    $scripts .= '    move: {' . "\n";
    $scripts .= '      enable: true,' . "\n";
    $scripts .= '      speed: 6,' . "\n";
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
    $scripts .= '  interactivity: {' . "\n";
    $scripts .= '    detect_on: "canvas",' . "\n";
    $scripts .= '    events: {' . "\n";
    $scripts .= '      onhover: {' . "\n";
    $scripts .= '        enable: true,' . "\n";
    $scripts .= '        mode: "repulse"' . "\n";
    $scripts .= '      },' . "\n";
    $scripts .= '      onclick: {' . "\n";
    $scripts .= '        enable: true,' . "\n";
    $scripts .= '        mode: "push"' . "\n";
    $scripts .= '      },' . "\n";
    $scripts .= '      resize: true' . "\n";
    $scripts .= '    },' . "\n";
    $scripts .= '    modes: {' . "\n";
    $scripts .= '      grab: {' . "\n";
    $scripts .= '        distance: 400,' . "\n";
    $scripts .= '        line_linked: {' . "\n";
    $scripts .= '          opacity: 1' . "\n";
    $scripts .= '        }' . "\n";
    $scripts .= '      },' . "\n";
    $scripts .= '      bubble: {' . "\n";
    $scripts .= '        distance: 400,' . "\n";
    $scripts .= '        size: 40,' . "\n";
    $scripts .= '        duration: 2,' . "\n";
    $scripts .= '        opacity: 8,' . "\n";
    $scripts .= '        speed: 3' . "\n";
    $scripts .= '      },' . "\n";
    $scripts .= '      repulse: {' . "\n";
    $scripts .= '        distance: 200,' . "\n";
    $scripts .= '        duration: 0.4' . "\n";
    $scripts .= '      },' . "\n";
    $scripts .= '      push: {' . "\n";
    $scripts .= '        particles_nb: 4' . "\n";
    $scripts .= '      },' . "\n";
    $scripts .= '      remove: {' . "\n";
    $scripts .= '        particles_nb: 2' . "\n";
    $scripts .= '      }' . "\n";
    $scripts .= '    }' . "\n";
    $scripts .= '  },' . "\n";
    $scripts .= '  retina_detect: true' . "\n";
    $scripts .= '  });' . "\n";
    $scripts .= '}' . "\n";
    $scripts .= '' . "\n";
    $scripts .= '// Inicializar partículas cuando el DOM esté listo' . "\n";
    $scripts .= 'if (document.readyState === "loading") {' . "\n";
    $scripts .= '  document.addEventListener("DOMContentLoaded", initParticles);' . "\n";
    $scripts .= '} else {' . "\n";
    $scripts .= '  initParticles();' . "\n";
    $scripts .= '}' . "\n";
    $scripts .= '' . "\n";
    $scripts .= '// También intentar cuando la ventana se carga completamente' . "\n";
    $scripts .= 'window.addEventListener("load", initParticles);' . "\n";
    $scripts .= '</script>' . "\n";
    
    // Panel de personalización de colores
    $scripts .= generateColorPanel();
    
    // Botón flotante de Discord
    $scripts .= '<a href="https://discord.gg/VXXYakrb7X" target="_blank" class="discord-float-btn" title="Únete a nuestro Discord">' . "\n";
    $scripts .= '    <i class="fab fa-discord"></i>' . "\n";
    $scripts .= '</a>' . "\n";
    
    $scripts .= '</body>' . "\n";
    $scripts .= '</html>' . "\n";
    
    return $scripts;
}
?> 