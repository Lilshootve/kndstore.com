/**
 * KND Store - Home fullscreen sections
 * Scroll-snap activation, reveal animations via IntersectionObserver
 * Sin librerías pesadas; degradación correcta en móvil.
 */
(function () {
    'use strict';

    var isHome = document.body.querySelector('#home-fullpage') || document.body.querySelector('.home-section-full');
    if (!isHome) return;

    // Activar scroll-snap solo en desktop (opcional: también en tablet)
    function enableScrollSnap() {
        var w = window.innerWidth;
        // En móvil estrecho, no forzar snap para no secuestrar scroll
        if (w < 768) {
            document.documentElement.classList.remove('home-fullpage-active');
            return;
        }
        document.documentElement.classList.add('home-fullpage-active');
    }
    enableScrollSnap();
    window.addEventListener('resize', function () {
        enableScrollSnap();
    });

    // IntersectionObserver: añadir .is-visible cuando la sección entra en viewport
    var sections = document.querySelectorAll('.home-section-full');
    if (!sections.length) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                // Opcional: dejar de observar para no re-ejecutar animaciones
                // observer.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -15% 0px',
        threshold: 0.1
    });

    sections.forEach(function (section) {
        observer.observe(section);
        // Si ya está en viewport al cargar, marcar visible
        var rect = section.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.85) {
            section.classList.add('is-visible');
        }
    });
})();
