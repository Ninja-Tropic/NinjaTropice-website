/**
 * FAQ Accordion — each question toggles its own answer (vanilla JS)
 */
(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const blocks = document.querySelectorAll('[data-faq-accordion]');
        if (!blocks.length) {
            return;
        }

        blocks.forEach((block) => {
            const items = Array.from(block.querySelectorAll('.faq-accordion__item'));

            items.forEach((item) => {
                const trigger = item.querySelector('.faq-accordion__trigger');
                if (!trigger) {
                    return;
                }

                trigger.addEventListener('click', () => {
                    const open = trigger.getAttribute('aria-expanded') !== 'true';

                    // Only one answer open at a time
                    items.forEach((other) => {
                        const otherTrigger = other.querySelector('.faq-accordion__trigger');
                        const isThis = other === item;
                        const state = isThis ? open : false;
                        other.classList.toggle('is-open', state);
                        if (otherTrigger) {
                            otherTrigger.setAttribute('aria-expanded', state ? 'true' : 'false');
                        }
                    });
                });
            });
        });
    });
})();
