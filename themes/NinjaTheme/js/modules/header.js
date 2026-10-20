/**
 * Header functionality
 */
(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        // Sticky Header / Scrolled State
        const header = document.querySelector('.site-header');
        
        if (header) {
            const handleScroll = () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            };

            // Initial check
            handleScroll();

            // Throttle scroll event slightly for performance
            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }

        // A short delay lets the pointer cross the gap below the header.
        const menuItems = document.querySelectorAll('.clean-menu > li');
        const desktop = window.matchMedia('(min-width: 1025px)');
        const closeMenus = [];

        menuItems.forEach((item, index) => {
            const submenu = item.querySelector(':scope > .sub-menu');
            const trigger = item.querySelector(':scope > a');
            if (!submenu || !trigger) return;

            let closeTimer;
            submenu.id = submenu.id || `desktop-submenu-${index}`;
            trigger.setAttribute('aria-controls', submenu.id);
            trigger.setAttribute('aria-expanded', 'false');

            const closeMenu = () => {
                clearTimeout(closeTimer);
                item.classList.remove('menu-open');
                trigger.setAttribute('aria-expanded', 'false');
            };
            closeMenus.push(closeMenu);

            const openMenu = () => {
                if (!desktop.matches) return;
                closeMenus.forEach((close) => close());
                item.classList.add('menu-open');
                trigger.setAttribute('aria-expanded', 'true');
            };

            item.addEventListener('mouseenter', openMenu);
            item.addEventListener('mouseleave', () => {
                if (!item.contains(document.activeElement)) {
                    closeTimer = setTimeout(closeMenu, 180);
                }
            });
            item.addEventListener('focusin', openMenu);
            item.addEventListener('focusout', (event) => {
                if (!item.contains(event.relatedTarget)) closeMenu();
            });
            trigger.addEventListener('click', (event) => {
                if (desktop.matches && !item.classList.contains('menu-open')) {
                    event.preventDefault();
                    openMenu();
                }
            });
            item.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    event.preventDefault();
                    trigger.focus();
                    closeMenu();
                } else if (event.key === 'ArrowDown' && event.target === trigger) {
                    event.preventDefault();
                    openMenu();
                    submenu.querySelector('a')?.focus();
                }
            });
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.clean-menu')) closeMenus.forEach((close) => close());
        });
        desktop.addEventListener('change', () => closeMenus.forEach((close) => close()));
    });
})();
