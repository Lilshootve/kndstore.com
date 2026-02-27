// KND Store - Navigation extensions + Orders dropdown (event delegation)

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

    // Single document-level handler for all dropdown interactions
    document.addEventListener('click', function(e) {
        // Check if click is on the dropdown toggle (or a child of it)
        var toggle = e.target.closest('.knd-dropdown-toggle');
        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            var dd = toggle.closest('.knd-dropdown');
            if (dd) {
                dd.classList.toggle('open');
            }
            return;
        }

        // Check if click is on a dropdown item
        var item = e.target.closest('.knd-dropdown-item');
        if (item) {
            var dd = item.closest('.knd-dropdown');
            if (dd) dd.classList.remove('open');
            if (window.innerWidth < 992) {
                var navCollapse = document.getElementById('navbarNav');
                if (navCollapse && navCollapse.classList.contains('show')) {
                    try {
                        var bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
                        if (bsCollapse) bsCollapse.hide();
                    } catch(err) {}
                }
            }
            return;
        }

        // Click outside — close any open dropdown
        var openDd = document.querySelector('.knd-dropdown.open');
        if (openDd && !openDd.contains(e.target)) {
            openDd.classList.remove('open');
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var openDd = document.querySelector('.knd-dropdown.open');
            if (openDd) openDd.classList.remove('open');
        }
    });

    // Close dropdown when Bootstrap collapse hides
    function bindCollapseReset() {
        var navCollapse = document.getElementById('navbarNav');
        if (navCollapse) {
            navCollapse.addEventListener('hide.bs.collapse', function() {
                var openDd = document.querySelector('.knd-dropdown.open');
                if (openDd) openDd.classList.remove('open');
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            extendNavigation();
            bindCollapseReset();
        });
    } else {
        extendNavigation();
        bindCollapseReset();
    }

    setTimeout(extendNavigation, 200);
})();
