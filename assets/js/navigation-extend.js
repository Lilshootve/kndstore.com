// KND Store - Navigation extensions (apparel/custom-design items + Orders dropdown)

(function() {
    'use strict';

    function extendNavigation() {
        var currentPage = window.location.pathname.split('/').pop() || 'index.php';
        var navList = document.querySelector('#navbarNav .navbar-nav');

        if (!navList) {
            setTimeout(extendNavigation, 100);
            return;
        }

        if (navList.querySelector('a[href="/apparel.php"]')) {
            return;
        }

        var aboutItem = navList.querySelector('a[href="/about.php"]');
        var contactItem = navList.querySelector('a[href="/contact.php"]');

        if (!aboutItem || !contactItem) {
            return;
        }

        function tJs(key, fallback) {
            return (window.I18N && window.I18N[key]) || fallback || key;
        }

        var apparelItem = document.createElement('li');
        apparelItem.className = 'nav-item';
        var apparelLink = document.createElement('a');
        apparelLink.className = 'nav-link' + (currentPage === 'apparel.php' ? ' active' : '');
        apparelLink.href = '/apparel.php';
        apparelLink.textContent = tJs('nav.apparel', 'Apparel');
        apparelItem.appendChild(apparelLink);

        var customItem = document.createElement('li');
        customItem.className = 'nav-item';
        var customLink = document.createElement('a');
        customLink.className = 'nav-link' + (currentPage === 'custom-design.php' ? ' active' : '');
        customLink.href = '/custom-design.php';
        customLink.textContent = tJs('nav.custom_design', 'Custom Design');
        customItem.appendChild(customLink);

        aboutItem.parentElement.insertAdjacentElement('afterend', apparelItem);
        apparelItem.insertAdjacentElement('afterend', customItem);
    }

    var dropdownReady = false;

    function initOrdersDropdown() {
        if (dropdownReady) return;
        var dd = document.querySelector('.knd-dropdown');
        if (!dd) return;

        var toggle = dd.querySelector('.knd-dropdown-toggle');
        if (!toggle) return;

        dropdownReady = true;

        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dd.classList.toggle('open');
        });

        var ddItems = dd.querySelectorAll('.knd-dropdown-item');
        ddItems.forEach(function(item) {
            item.addEventListener('click', function() {
                dd.classList.remove('open');
                var navCollapse = document.getElementById('navbarNav');
                if (navCollapse && navCollapse.classList.contains('show')) {
                    var bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
                    if (bsCollapse) bsCollapse.hide();
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (!dd.contains(e.target)) {
                dd.classList.remove('open');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') dd.classList.remove('open');
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            extendNavigation();
            initOrdersDropdown();
        });
    } else {
        extendNavigation();
        initOrdersDropdown();
    }

    setTimeout(function() {
        extendNavigation();
        initOrdersDropdown();
    }, 200);
})();

