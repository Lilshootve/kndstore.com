// KND Store - Mobile Optimization JavaScript

// ===== DETECCIÓN DE DISPOSITIVOS MÓVILES =====
const isMobile = () => {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
           window.innerWidth <= 768;
};

const isTablet = () => {
    return window.innerWidth > 768 && window.innerWidth <= 1024;
};

const isSmallMobile = () => {
    return window.innerWidth <= 480;
};

// ===== OPTIMIZACIONES DE RENDIMIENTO MÓVIL =====

// Reducir partículas en dispositivos móviles
function optimizeParticlesForMobile() {
    if (isMobile() && typeof particlesJS !== 'undefined') {
        // Configuración reducida para móviles
        const mobileParticleConfig = {
            particles: {
                number: {
                    value: 30, // Reducido de 80
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: ["#259cae", "#8a2be2"]
                },
                shape: {
                    type: "circle"
                },
                opacity: {
                    value: 0.3, // Reducido de 0.5
                    random: false
                },
                size: {
                    value: 2, // Reducido de 3
                    random: true
                },
                line_linked: {
                    enable: true,
                    distance: 120, // Reducido de 150
                    color: "#259cae",
                    opacity: 0.2, // Reducido de 0.4
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 3, // Reducido de 6
                    direction: "none",
                    random: false,
                    straight: false,
                    out_mode: "out",
                    bounce: false
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: {
                        enable: false, // Deshabilitado en móviles
                        mode: "repulse"
                    },
                    onclick: {
                        enable: false, // Deshabilitado en móviles
                        mode: "push"
                    },
                    resize: true
                }
            },
            retina_detect: true
        };

        // Aplicar configuración móvil
        particlesJS("particles-bg", mobileParticleConfig);
    }
}

// ===== OPTIMIZACIONES DE TOUCH =====

// Mejorar experiencia táctil
function enhanceTouchExperience() {
    if (isMobile()) {
        // Agregar feedback táctil a botones
        const touchElements = document.querySelectorAll('.btn, .product-card, .category-card, .nav-link');
        
        touchElements.forEach(element => {
            element.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
                this.style.transition = 'transform 0.1s ease';
            });
            
            element.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Mejorar scroll en navegación móvil
        const navbarCollapse = document.querySelector('.navbar-collapse');
        if (navbarCollapse) {
            navbarCollapse.style.webkitOverflowScrolling = 'touch';
        }
    }
}

// ===== OPTIMIZACIONES DE NAVEGACIÓN MÓVIL =====

// Mejorar navegación móvil
function enhanceMobileNavigation() {
    if (isMobile()) {
        // Cerrar menú al hacer clic en un enlace
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const navbarToggler = document.querySelector('.navbar-toggler');
        
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (navbarCollapse.classList.contains('show')) {
                    navbarToggler.click();
                }
            });
        });

        // Mejorar accesibilidad del menú hamburguesa
        if (navbarToggler) {
            navbarToggler.setAttribute('aria-label', 'Abrir menú de navegación');
            navbarToggler.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                this.setAttribute('aria-label', isExpanded ? 'Abrir menú de navegación' : 'Cerrar menú de navegación');
            });
        }
    }
}

// ===== OPTIMIZACIONES DE FORMULARIOS MÓVILES =====

// Mejorar formularios en móviles
function enhanceMobileForms() {
    if (isMobile()) {
        // Prevenir zoom en campos de entrada
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], textarea');
        inputs.forEach(input => {
            input.setAttribute('autocomplete', 'off');
            input.style.fontSize = '16px'; // Previene zoom en iOS
        });

        // Mejorar experiencia de formularios
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Mostrar indicador de carga
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
                }
            });
        });
    }
}

// ===== OPTIMIZACIONES DE IMÁGENES MÓVILES =====

// Optimizar carga de imágenes
function optimizeImagesForMobile() {
    if (isMobile()) {
        // Lazy loading para imágenes
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback para navegadores sin IntersectionObserver
            images.forEach(img => {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            });
        }
    }
}

// ===== OPTIMIZACIONES DE ORIENTACIÓN =====

// Manejar cambios de orientación
function handleOrientationChange() {
    let orientation = window.innerWidth > window.innerHeight ? 'landscape' : 'portrait';
    
    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            const newOrientation = window.innerWidth > window.innerHeight ? 'landscape' : 'portrait';
            if (orientation !== newOrientation) {
                orientation = newOrientation;
                adjustLayoutForOrientation(orientation);
            }
        }, 100);
    });
}

function adjustLayoutForOrientation(orientation) {
    if (isMobile()) {
        const heroSection = document.querySelector('.hero-section');
        const navbar = document.querySelector('.navbar');
        
        if (orientation === 'landscape') {
            // Ajustes para landscape
            if (heroSection) {
                heroSection.style.minHeight = '40vh';
                heroSection.style.paddingTop = '80px';
            }
            if (navbar) {
                navbar.style.height = '70px';
            }
        } else {
            // Ajustes para portrait
            if (heroSection) {
                heroSection.style.minHeight = '60vh';
                heroSection.style.paddingTop = '100px';
            }
            if (navbar) {
                navbar.style.height = '80px';
            }
        }
    }
}

// ===== OPTIMIZACIONES DE RENDIMIENTO =====

// Reducir animaciones en dispositivos de bajo rendimiento
function optimizeAnimationsForPerformance() {
    if (isMobile()) {
        // Detectar preferencias de movimiento reducido
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.body.classList.add('reduced-motion');
        }
        
        // Reducir efectos visuales en dispositivos de bajo rendimiento
        const isLowEndDevice = navigator.hardwareConcurrency <= 4 || 
                              navigator.deviceMemory <= 4;
        
        if (isLowEndDevice) {
            document.body.classList.add('low-performance');
        }
    }
}

// ===== OPTIMIZACIONES DE ACCESIBILIDAD MÓVIL =====

// Mejorar accesibilidad en móviles
function enhanceMobileAccessibility() {
    if (isMobile()) {
        // Mejorar focus visible
        const focusableElements = document.querySelectorAll('a, button, input, textarea, select, [tabindex]');
        
        focusableElements.forEach(element => {
            element.addEventListener('focus', function() {
                this.style.outline = '2px solid var(--knd-neon-blue)';
                this.style.outlineOffset = '2px';
            });
            
            element.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
            });
        });

        // Mejorar navegación por teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Cerrar menús abiertos
                const openMenus = document.querySelectorAll('.navbar-collapse.show, .color-panel-sidebar.open');
                openMenus.forEach(menu => {
                    if (menu.classList.contains('navbar-collapse')) {
                        const toggler = document.querySelector('.navbar-toggler');
                        if (toggler) toggler.click();
                    } else if (menu.classList.contains('color-panel-sidebar')) {
                        menu.classList.remove('open');
                        document.getElementById('colorPanelOverlay').classList.remove('active');
                    }
                });
            }
        });
    }
}

// ===== OPTIMIZACIONES DE CARGA =====

// Optimizar carga inicial
function optimizeInitialLoad() {
    if (isMobile()) {
        // Cargar recursos críticos primero
        const criticalResources = [
            'assets/css/style.css',
            'assets/css/mobile-optimization.css'
        ];
        
        // Precargar recursos importantes
        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = resource;
            link.as = 'style';
            document.head.appendChild(link);
        });
    }
}

// ===== OPTIMIZACIONES DE INTERACCIÓN =====

// Mejorar interacciones táctiles
function enhanceTouchInteractions() {
    if (isMobile()) {
        // Mejorar feedback táctil para cards
        const cards = document.querySelectorAll('.product-card, .category-card, .feature-card');
        
        cards.forEach(card => {
            let touchStartY = 0;
            let touchEndY = 0;
            
            card.addEventListener('touchstart', function(e) {
                touchStartY = e.touches[0].clientY;
                this.style.transform = 'scale(0.98)';
            });
            
            card.addEventListener('touchmove', function(e) {
                touchEndY = e.touches[0].clientY;
                const diff = touchStartY - touchEndY;
                
                // Prevenir scroll si el usuario está haciendo swipe horizontal
                if (Math.abs(diff) < 10) {
                    e.preventDefault();
                }
            });
            
            card.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }
}

// ===== OPTIMIZACIONES DE RED =====

// Optimizar para conexiones lentas
function optimizeForSlowConnections() {
    if (isMobile()) {
        // Detectar conexión lenta
        if ('connection' in navigator) {
            const connection = navigator.connection;
            
            if (connection.effectiveType === 'slow-2g' || 
                connection.effectiveType === '2g' || 
                connection.effectiveType === '3g') {
                
                document.body.classList.add('slow-connection');
                
                // Reducir aún más las partículas
                if (typeof particlesJS !== 'undefined') {
                    const slowConnectionConfig = {
                        particles: {
                            number: { value: 15 },
                            opacity: { value: 0.2 },
                            move: { speed: 2 }
                        }
                    };
                    particlesJS("particles-bg", slowConnectionConfig);
                }
            }
        }
    }
}

// ===== INICIALIZACIÓN =====

// Función principal de inicialización
function initMobileOptimizations() {
    console.log('🚀 Inicializando optimizaciones móviles...');
    
    // Aplicar optimizaciones según el dispositivo
    if (isMobile()) {
        console.log('📱 Dispositivo móvil detectado');
        
        // Optimizaciones de rendimiento
        optimizeParticlesForMobile();
        optimizeAnimationsForPerformance();
        optimizeForSlowConnections();
        
        // Optimizaciones de interacción
        enhanceTouchExperience();
        enhanceMobileNavigation();
        enhanceMobileForms();
        enhanceTouchInteractions();
        
        // Optimizaciones de accesibilidad
        enhanceMobileAccessibility();
        
        // Optimizaciones de carga
        optimizeImagesForMobile();
        optimizeInitialLoad();
        
        // Manejo de orientación
        handleOrientationChange();
        
        console.log('✅ Optimizaciones móviles aplicadas');
    } else if (isTablet()) {
        console.log('📱 Tablet detectada');
        // Optimizaciones específicas para tablets
        optimizeParticlesForMobile(); // Usar configuración móvil para tablets también
    } else {
        console.log('🖥️ Dispositivo de escritorio detectado');
    }
}

// ===== EVENT LISTENERS =====

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initMobileOptimizations();
});

// Manejar cambios de tamaño de ventana
window.addEventListener('resize', function() {
    // Re-aplicar optimizaciones si cambia el tamaño
    setTimeout(() => {
        if (isMobile()) {
            adjustLayoutForOrientation(window.innerWidth > window.innerHeight ? 'landscape' : 'portrait');
        }
    }, 100);
});

// ===== UTILIDADES =====

// Función para detectar si el dispositivo soporta touch
const isTouchDevice = () => {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
};

// Función para detectar si el dispositivo tiene pantalla de alta densidad
const isHighDensityDisplay = () => {
    return window.devicePixelRatio >= 2;
};

// Función para detectar si el dispositivo tiene poca memoria
const isLowMemoryDevice = () => {
    return navigator.deviceMemory && navigator.deviceMemory <= 4;
};

// Exportar funciones para uso global
window.KNDMobileOptimizations = {
    isMobile,
    isTablet,
    isSmallMobile,
    isTouchDevice,
    isHighDensityDisplay,
    isLowMemoryDevice,
    initMobileOptimizations
}; 