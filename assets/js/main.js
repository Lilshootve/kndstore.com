// KND Store - JavaScript Principal

// La verificación de Font Awesome ahora se maneja en header.php
// No necesitamos duplicar la funcionalidad aquí

// Función para inicializar la aplicación
function initApp() {
    // Inicializar otros componentes
    initScrollEffects();
    initColorPanel();
    initMobileOptimizations();
}

// Efectos de scroll
function initScrollEffects() {
    // Scroll suave para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Efecto parallax desactivado para evitar que el hero se sobreponga sobre otras secciones
}

// Panel de personalización de colores
function initColorPanel() {
    const toggle = document.getElementById('colorPanelToggle');
    const sidebar = document.getElementById('colorPanelSidebar');
    const overlay = document.getElementById('colorPanelOverlay');
    
    if (toggle && sidebar && overlay) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            toggle.classList.toggle('active');
        });
        
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            toggle.classList.remove('active');
        });
        
        // Temas de colores
        document.querySelectorAll('.color-theme').forEach(theme => {
            theme.addEventListener('click', () => {
                document.querySelectorAll('.color-theme').forEach(t => t.classList.remove('active'));
                theme.classList.add('active');
                
                const themeName = theme.dataset.theme;
                applyColorTheme(themeName);
            });
        });
    }
}

// Aplicar tema de colores
function applyColorTheme(themeName) {
    const root = document.documentElement;
    
    const themes = {
        'galactic-blue': {
            '--knd-neon-blue': '#00bfff',
            '--knd-electric-purple': '#8a2be2',
            '--knd-gunmetal-gray': '#2c2c2c'
        },
        'cyber-green': {
            '--knd-neon-blue': '#00ff00',
            '--knd-electric-purple': '#32cd32',
            '--knd-gunmetal-gray': '#006400'
        },
        'fire-red': {
            '--knd-neon-blue': '#ff0000',
            '--knd-electric-purple': '#ff4500',
            '--knd-gunmetal-gray': '#8b0000'
        },
        'golden-sun': {
            '--knd-neon-blue': '#ffd700',
            '--knd-electric-purple': '#ffa500',
            '--knd-gunmetal-gray': '#daa520'
        },
        'neon-pink': {
            '--knd-neon-blue': '#ff69b4',
            '--knd-electric-purple': '#ff1493',
            '--knd-gunmetal-gray': '#c71585'
        },
        'ice-blue': {
            '--knd-neon-blue': '#00ffff',
            '--knd-electric-purple': '#87ceeb',
            '--knd-gunmetal-gray': '#4682b4'
        }
    };
    
    if (themes[themeName]) {
        Object.entries(themes[themeName]).forEach(([property, value]) => {
            root.style.setProperty(property, value);
        });
        
        // Guardar preferencia
        localStorage.setItem('knd-color-theme', themeName);
    }
}

// Optimizaciones móviles
function initMobileOptimizations() {
    // Detectar si es móvil
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        // Reducir animaciones en móvil
        document.body.style.setProperty('--animation-duration', '0.3s');
        
        // Optimizar scroll en móvil
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    // Efectos de scroll optimizados para móvil
                    ticking = false;
                });
                ticking = true;
            }
        });
    }
}

// Cargar tema guardado
function loadSavedTheme() {
    const savedTheme = localStorage.getItem('knd-color-theme');
    if (savedTheme) {
        applyColorTheme(savedTheme);
        
        // Marcar como activo en el panel
        const themeElement = document.querySelector(`[data-theme="${savedTheme}"]`);
        if (themeElement) {
            document.querySelectorAll('.color-theme').forEach(t => t.classList.remove('active'));
            themeElement.classList.add('active');
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    initApp();
    loadSavedTheme();
});

// Inicializar cuando la ventana se cargue completamente
window.addEventListener('load', () => {
    // Font Awesome se verifica en header.php
});

// ====== Gestor de pedido simple (localStorage) ======
const KND_ORDER_KEY = 'knd_order_items';

function loadOrderItems() {
    try {
        const raw = localStorage.getItem(KND_ORDER_KEY);
        if (!raw) return [];
        return JSON.parse(raw);
    } catch (e) {
        console.error('Error leyendo pedido desde localStorage', e);
        return [];
    }
}

function saveOrderItems(items) {
    localStorage.setItem(KND_ORDER_KEY, JSON.stringify(items));
}

function addItemToOrder(item) {
    const items = loadOrderItems();
    const index = items.findIndex(i => i.id === item.id);
    if (index !== -1) {
        items[index].qty += 1;
    } else {
        items.push({ ...item, qty: 1 });
    }
    saveOrderItems(items);
    return items;
}

function getOrderTotal(items) {
    return items.reduce((sum, item) => {
        return sum + (item.price * item.qty);
    }, 0);
}

// Actualizar un pequeño indicador (opcional) del número de items en el pedido
function updateOrderBadge() {
    const items = loadOrderItems();
    const totalQty = items.reduce((sum, item) => sum + item.qty, 0);
    
    // Actualizar badge en el navbar
    const badge = document.querySelector('#order-count');
    if (badge) {
        if (totalQty > 0) {
            badge.textContent = totalQty;
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
    }
    
    // Actualizar badge en la barra lateral de scroll
    const scrollBadge = document.getElementById('scroll-order-count');
    if (scrollBadge) {
        if (totalQty > 0) {
            scrollBadge.textContent = totalQty;
            scrollBadge.style.display = 'inline-flex';
        } else {
            scrollBadge.style.display = 'none';
        }
    }
}

// Función para mostrar notificación en la parte inferior izquierda
function showOrderNotification(message, type = 'success') {
    // Eliminar notificación anterior si existe
    const existing = document.getElementById('order-notification');
    if (existing) {
        existing.remove();
    }

    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.id = 'order-notification';
    notification.className = `order-notification order-notification-${type}`;
    notification.innerHTML = `
        <div class="order-notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Mostrar con animación
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Listeners para botones "Añadir al pedido"
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.add-to-order');
    if (!btn) return;

    const id = parseInt(btn.dataset.id, 10);
    const name = btn.dataset.name;
    const price = parseFloat(btn.dataset.price);
    const type = btn.dataset.type || 'digital';

    if (!id || !name || isNaN(price)) {
        console.warn('Datos de producto inválidos para el pedido', btn.dataset);
        return;
    }

    // Para productos apparel, capturar variants (talla/color)
    let variants = null;
    if (type === 'apparel') {
        const color = btn.dataset.variantColor || document.getElementById('variant-color')?.value || '';
        const size = btn.dataset.variantSize || document.getElementById('variant-size')?.value || '';
        
        if (!size) {
            alert('Por favor selecciona una talla antes de agregar al pedido.');
            return;
        }
        
        variants = { color, size };
    }

    // Para productos service, capturar brief si existe
    let brief = null;
    if (type === 'service') {
        const briefData = localStorage.getItem('knd_custom_design_brief');
        if (briefData) {
            try {
                brief = JSON.parse(briefData);
            } catch (e) {
                console.error('Error parsing brief:', e);
            }
        }
    }

    const itemData = { id, name, price, type };
    if (variants) itemData.variants = variants;
    if (brief) itemData.brief = brief;

    const items = addItemToOrder(itemData);
    updateOrderBadge();

    // Mostrar notificación en la parte inferior izquierda
    const item = items.find(i => i.id === id);
    let message = item.qty > 1 
        ? `${name} añadido (${item.qty} en pedido)`
        : `${name} añadido al pedido`;
    
    if (variants && variants.size) {
        message += ` - Talla: ${variants.size}`;
        if (variants.color) {
            message += `, Color: ${variants.color}`;
        }
    }
    
    showOrderNotification(message, 'success');
});

// Inicializar badge al cargar
document.addEventListener('DOMContentLoaded', function() {
    updateOrderBadge();
    // También actualizar badge en scroll nav si existe la función
    if (typeof window.updateOrderBadgeInScrollNav === 'function') {
        setTimeout(window.updateOrderBadgeInScrollNav, 100);
    }
}); 