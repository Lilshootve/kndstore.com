// Scroll suave por bloques para KND Store - Versión simplificada y confiable
document.addEventListener('DOMContentLoaded', function() {
    console.log('Scroll smooth script loaded'); // Debug
    
    // Función de scroll suave simple
    function smoothScrollTo(target) {
        console.log('Scrolling to:', target); // Debug
        
        if (target === 0) {
            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            return;
        }
        
        const targetElement = typeof target === 'string' ? document.querySelector(target) : target;
        if (!targetElement) {
            console.log('Target not found:', target); // Debug
            return;
        }
        
        const navbarHeight = 120; // Altura del navbar
        const targetPosition = targetElement.offsetTop - navbarHeight;
        
        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    }
    
    // Crear botones de navegación rápida
    function createScrollNav() {
        console.log('Creating scroll navigation'); // Debug
        
        const nav = document.createElement('div');
        nav.className = 'scroll-nav';
        nav.innerHTML = `
            <button class="scroll-nav-btn" id="scroll-top" title="Ir arriba">
                <i class="fas fa-chevron-up"></i>
            </button>
            <button class="scroll-nav-btn" id="scroll-products" title="Catálogo">
                <i class="fas fa-th"></i>
            </button>
            <button class="scroll-nav-btn" id="scroll-about" title="Sobre Nosotros">
                <i class="fas fa-info-circle"></i>
            </button>
            <button class="scroll-nav-btn" id="scroll-contact" title="Contacto">
                <i class="fas fa-envelope"></i>
            </button>
            <a href="/order.php" class="scroll-nav-btn scroll-nav-link" id="scroll-order" title="Mi Pedido">
                <i class="fas fa-shopping-cart"></i>
                <span class="scroll-nav-badge" id="scroll-order-count" style="display: none;"></span>
            </a>
        `;
        document.body.appendChild(nav);
        
        // Agregar event listeners a los botones
        const topBtn = document.getElementById('scroll-top');
        const productsBtn = document.getElementById('scroll-products');
        const aboutBtn = document.getElementById('scroll-about');
        const contactBtn = document.getElementById('scroll-contact');
        const orderBtn = document.getElementById('scroll-order');
        
        if (topBtn) {
            topBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Top button clicked'); // Debug
                smoothScrollTo(0);
            });
        }
        
        if (productsBtn) {
            productsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Products button clicked'); // Debug
                // Primero intentar buscar la sección en la página actual
                const productsSection = document.querySelector('.products-section, .featured-products');
                if (productsSection) {
                    smoothScrollTo(productsSection);
                } else {
                    // Si no existe, navegar a la página de productos
                    window.location.href = '/products.php';
                }
            });
        }
        
        if (aboutBtn) {
            aboutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('About button clicked'); // Debug
                // Primero intentar buscar la sección en la página actual
                const aboutSection = document.querySelector('.about-section');
                if (aboutSection) {
                    smoothScrollTo(aboutSection);
                } else {
                    // Si no existe, navegar a la página de about
                    window.location.href = '/about.php';
                }
            });
        }
        
        if (contactBtn) {
            contactBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Contact button clicked'); // Debug
                // Primero intentar buscar la sección en la página actual
                const contactSection = document.querySelector('.contact-section');
                if (contactSection) {
                    smoothScrollTo(contactSection);
                } else {
                    // Si no existe, navegar a la página de contacto
                    window.location.href = '/contact.php';
                }
            });
        }
        
        // El botón de pedido es un enlace, no necesita event listener adicional
        // Pero actualizamos el badge si existe
        updateOrderBadgeInScrollNav();
    }
    
    // Función para actualizar el badge del pedido en la barra lateral
    function updateOrderBadgeInScrollNav() {
        try {
            const ORDER_KEY = 'knd_order_items';
            const raw = localStorage.getItem(ORDER_KEY);
            if (!raw) {
                const badge = document.getElementById('scroll-order-count');
                if (badge) badge.style.display = 'none';
                return;
            }
            
            const items = JSON.parse(raw);
            const totalQty = items.reduce((sum, item) => sum + item.qty, 0);
            const badge = document.getElementById('scroll-order-count');
            
            if (badge) {
                if (totalQty > 0) {
                    badge.textContent = totalQty;
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        } catch (e) {
            console.error('Error actualizando badge en scroll nav', e);
        }
    }
    
    // Actualizar badge cuando se carga la página
    setTimeout(updateOrderBadgeInScrollNav, 500);
    
    // Escuchar cambios en localStorage para actualizar el badge
    window.addEventListener('storage', function(e) {
        if (e.key === 'knd_order_items') {
            updateOrderBadgeInScrollNav();
        }
    });
    
    // Actualizar badge periódicamente (por si se modifica desde la misma página)
    setInterval(updateOrderBadgeInScrollNav, 1000);
    
    // Exponer función globalmente para que main.js pueda llamarla
    window.updateOrderBadgeInScrollNav = updateOrderBadgeInScrollNav;
    
    // Crear indicador de progreso
    function createScrollProgress() {
        const progress = document.createElement('div');
        progress.className = 'scroll-progress';
        progress.innerHTML = '<div class="scroll-progress-bar"></div>';
        document.body.appendChild(progress);
        
        // Actualizar progreso al hacer scroll
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            
            const progressBar = document.querySelector('.scroll-progress-bar');
            if (progressBar) {
                progressBar.style.width = scrollPercent + '%';
            }
        });
    }
    
    // Funciones globales para compatibilidad
    window.scrollToTop = function() {
        smoothScrollTo(0);
    };
    
    window.scrollToSection = function(selector) {
        smoothScrollTo(selector);
    };
    
    // Inicializar componentes
    createScrollNav();
    createScrollProgress();
    
    // Mostrar/ocultar botones de navegación según scroll
    window.addEventListener('scroll', function() {
        const scrollNav = document.querySelector('.scroll-nav');
        if (scrollNav) {
            if (window.pageYOffset > 300) {
                scrollNav.style.opacity = '1';
                scrollNav.style.visibility = 'visible';
            } else {
                scrollNav.style.opacity = '0';
                scrollNav.style.visibility = 'hidden';
            }
        }
    });
    
    // Inicializar estado de navegación
    const scrollNav = document.querySelector('.scroll-nav');
    if (scrollNav) {
        scrollNav.style.opacity = '0';
        scrollNav.style.visibility = 'hidden';
        scrollNav.style.transition = 'opacity 0.3s ease, visibility 0.3s ease';
    }
    
    console.log('Scroll navigation initialized'); // Debug
});

// Configuración adicional para mejor experiencia
document.addEventListener('DOMContentLoaded', function() {
    // Prevenir scroll jump en carga
    if (window.location.hash) {
        setTimeout(() => {
            const target = document.querySelector(window.location.hash);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }, 100);
    }
    
    // Optimizar scroll para dispositivos móviles
    if ('ontouchstart' in window) {
        document.body.style.overflowScrolling = 'touch';
    }
}); 