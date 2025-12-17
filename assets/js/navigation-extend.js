// KND Store - Extensión de navegación sin modificar header.php
// Este script agrega los items de navegación adicionales (Apparel, Custom Design)
// después de que se carga la página

(function() {
    'use strict';
    
    function extendNavigation() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.php';
        const navList = document.querySelector('#navbarNav .navbar-nav');
        
        if (!navList) {
            // Reintentar después de un breve delay
            setTimeout(extendNavigation, 100);
            return;
        }
        
        // Verificar si ya se agregaron los items (evitar duplicados)
        if (navList.querySelector('a[href="/apparel.php"]')) {
            return;
        }
        
        // Encontrar el item "Sobre Nosotros" para insertar después
        const aboutItem = navList.querySelector('a[href="/about.php"]');
        const contactItem = navList.querySelector('a[href="/contact.php"]');
        
        if (!aboutItem || !contactItem) {
            return;
        }
        
        // Helper para traducciones JS
        function tJs(key, fallback) {
            return (window.I18N && window.I18N[key]) || fallback || key;
        }
        
        // Crear item Apparel
        const apparelItem = document.createElement('li');
        apparelItem.className = 'nav-item';
        const apparelLink = document.createElement('a');
        apparelLink.className = 'nav-link' + (currentPage === 'apparel.php' ? ' active' : '');
        apparelLink.href = '/apparel.php';
        apparelLink.textContent = tJs('nav.apparel', 'Apparel');
        apparelItem.appendChild(apparelLink);
        
        // Crear item Custom Design
        const customItem = document.createElement('li');
        customItem.className = 'nav-item';
        const customLink = document.createElement('a');
        customLink.className = 'nav-link' + (currentPage === 'custom-design.php' ? ' active' : '');
        customLink.href = '/custom-design.php';
        customLink.textContent = tJs('nav.custom_design', 'Custom Design');
        customItem.appendChild(customLink);
        
        // Insertar después de "Sobre Nosotros" y antes de "Contacto"
        aboutItem.parentElement.insertAdjacentElement('afterend', apparelItem);
        apparelItem.insertAdjacentElement('afterend', customItem);
    }
    
    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', extendNavigation);
    } else {
        extendNavigation();
    }
    
    // También ejecutar después de un breve delay por si acaso
    setTimeout(extendNavigation, 200);
})();

