(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileNavigation = document.querySelector('.mobile-navigation');
        const body = document.body;

        if (mobileMenuToggle && mobileNavigation) {
            mobileMenuToggle.addEventListener('click', function() {
                const isActive = mobileNavigation.classList.toggle('active');
                body.classList.toggle('mobile-menu-open', isActive);
                mobileMenuToggle.classList.toggle('active', isActive);
                mobileMenuToggle.setAttribute('aria-expanded', isActive);
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileNavigation.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                    if (mobileNavigation.classList.contains('active')) {
                        mobileNavigation.classList.remove('active');
                        body.classList.remove('mobile-menu-open');
                        mobileMenuToggle.classList.remove('active');
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Close menu on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileNavigation.classList.contains('active')) {
                    mobileNavigation.classList.remove('active');
                    body.classList.remove('mobile-menu-open');
                    mobileMenuToggle.classList.remove('active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Handle sub-menu toggles on mobile
            // Inject a separate toggle button so the <a> link can still navigate
            const menuItemsWithChildren = mobileNavigation.querySelectorAll('.menu-item-has-children');
            menuItemsWithChildren.forEach(function(menuItem) {
                const menuLink = menuItem.querySelector(':scope > a');
                const subMenu = menuItem.querySelector(':scope > .sub-menu');
                if (!menuLink || !subMenu) return;

                // Create arrow toggle button
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'sub-menu-toggle';
                toggleBtn.setAttribute('aria-label', 'Open submenu for ' + menuLink.textContent.trim());
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.innerHTML = '<span aria-hidden="true">→</span>';
                menuItem.insertBefore(toggleBtn, menuLink.nextSibling);

                toggleBtn.addEventListener('click', function(e) {
                    if (window.innerWidth <= 1024) {
                        e.stopPropagation();

                        const isTopLevel = menuItem.parentElement.classList.contains('mobile-menu-list');

                        if (isTopLevel) {
                            // Slide-in panel behaviour for 2nd level
                            let backButton = subMenu.querySelector('.mobile-back-button');
                            if (!backButton) {
                                const parentTitle = menuLink.textContent.trim();
                                backButton = document.createElement('li');
                                backButton.className = 'mobile-back-button';
                                backButton.innerHTML = '<a href="#"><span>← ' + parentTitle + '</span></a>';
                                subMenu.insertBefore(backButton, subMenu.firstChild);

                                backButton.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    subMenu.classList.remove('active');
                                    toggleBtn.classList.remove('active');
                                    toggleBtn.setAttribute('aria-expanded', 'false');
                                });
                            }
                            subMenu.classList.add('active');
                        } else {
                            // Inline accordion for 3rd level
                            subMenu.classList.toggle('active');
                        }

                        const isOpen = subMenu.classList.contains('active');
                        toggleBtn.classList.toggle('active', isOpen);
                        toggleBtn.setAttribute('aria-expanded', String(isOpen));
                    }
                });
            });
        }
    });
})();
