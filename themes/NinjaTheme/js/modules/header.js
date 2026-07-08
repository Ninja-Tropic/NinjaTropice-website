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

        // Desktop mega menu hover-intent.
        // The dropdown panel is fixed/centered on screen rather than sitting
        // directly under its trigger, so plain CSS :hover closes it the instant
        // the mouse leaves the nav item, before it can reach the panel. This
        // keeps the menu open for a short delay so the cursor can travel there.
        const menuItems = document.querySelectorAll(
            '.clean-menu > li.menu-item-has-children, .clean-menu > li.has-mega-menu, .clean-menu > li.has-mega-menu-simple'
        );
        const MENU_CLOSE_DELAY = 250;

        menuItems.forEach((item) => {
            let closeTimer = null;

            const openMenu = () => {
                clearTimeout(closeTimer);
                item.classList.add('menu-open');
            };

            const scheduleClose = () => {
                clearTimeout(closeTimer);
                closeTimer = setTimeout(() => {
                    item.classList.remove('menu-open');
                }, MENU_CLOSE_DELAY);
            };

            item.addEventListener('mouseenter', openMenu);
            item.addEventListener('mouseleave', scheduleClose);

            const submenu = item.querySelector(':scope > .sub-menu');
            if (submenu) {
                submenu.addEventListener('mouseenter', openMenu);
                submenu.addEventListener('mouseleave', scheduleClose);
            }
        });
    });
})();
