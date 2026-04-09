(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const blocks = document.querySelectorAll('.hubspot-form');

        blocks.forEach((block) => {
            const openButton = block.querySelector('.hubspot-form__button');
            const modal = block.querySelector('.hubspot-form__modal');

            if (!openButton || !modal) {
                return;
            }

            const closeButtons = modal.querySelectorAll('[data-hubspot-close]');
            const focusableSelector = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex=\"-1\"])';
            let lastFocusedElement = null;

            const openModal = () => {
                lastFocusedElement = document.activeElement;
                modal.hidden = false;
                requestAnimationFrame(() => {
                    modal.classList.add('is-open');
                });
                openButton.setAttribute('aria-expanded', 'true');
                document.body.classList.add('hubspot-form-modal-open');

                const firstFocusable = modal.querySelector(focusableSelector);
                if (firstFocusable) {
                    firstFocusable.focus();
                }
            };

            const closeModal = () => {
                modal.classList.remove('is-open');
                openButton.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('hubspot-form-modal-open');

                const onTransitionEnd = () => {
                    if (!modal.classList.contains('is-open')) {
                        modal.hidden = true;
                    }
                    modal.removeEventListener('transitionend', onTransitionEnd);
                };

                modal.addEventListener('transitionend', onTransitionEnd);

                if (lastFocusedElement) {
                    lastFocusedElement.focus();
                }
            };

            openButton.addEventListener('click', openModal);

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });

            modal.addEventListener('keydown', (event) => {
                if (event.key !== 'Tab') {
                    return;
                }

                const focusable = Array.from(modal.querySelectorAll(focusableSelector));
                if (!focusable.length) {
                    return;
                }

                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            });
        });
    });
})();
