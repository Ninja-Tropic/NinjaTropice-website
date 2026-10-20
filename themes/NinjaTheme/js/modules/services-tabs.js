/**
 * Services Tabs — left list switches the right panel (vanilla JS)
 */
(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const blocks = document.querySelectorAll('[data-services-tabs]');
        if (!blocks.length) {
            return;
        }

        blocks.forEach((block) => {
            const tabs = Array.from(block.querySelectorAll('[role="tab"]'));
            const panels = Array.from(block.querySelectorAll('[role="tabpanel"]'));

            if (!tabs.length) {
                return;
            }

            const activate = (index, focus) => {
                tabs.forEach((tab, i) => {
                    const active = i === index;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                    tab.tabIndex = active ? 0 : -1;
                    if (active && focus) {
                        tab.focus();
                    }
                });

                panels.forEach((panel, i) => {
                    const active = i === index;
                    panel.classList.toggle('is-active', active);
                    panel.hidden = !active;
                });
            };

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => activate(index, false));

                tab.addEventListener('keydown', (event) => {
                    let next = null;
                    if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
                        next = (index + 1) % tabs.length;
                    } else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
                        next = (index - 1 + tabs.length) % tabs.length;
                    } else if (event.key === 'Home') {
                        next = 0;
                    } else if (event.key === 'End') {
                        next = tabs.length - 1;
                    }

                    if (next !== null) {
                        event.preventDefault();
                        activate(next, true);
                    }
                });
            });
        });
    });
})();
