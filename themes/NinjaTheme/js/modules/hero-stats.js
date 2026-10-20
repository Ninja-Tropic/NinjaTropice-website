/**
 * Hero Stats — logos carousel (vanilla JS)
 */
(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const wrappers = document.querySelectorAll('[data-hero-stats-logos]');
        if (!wrappers.length) {
            return;
        }

        wrappers.forEach((wrapper) => {
            const track = wrapper.querySelector('.hero-stats__logos-track');

            if (!track) {
                return;
            }

            const logos = Array.from(track.querySelectorAll('.hero-stats__logo:not([data-logo-clone])'));
            if (logos.length < 2) {
                return;
            }

            const cloneLogo = (logo) => {
                const clone = logo.cloneNode(true);
                clone.setAttribute('aria-hidden', 'true');
                clone.dataset.logoClone = 'true';
                clone.querySelectorAll('a, button').forEach((item) => {
                    item.setAttribute('tabindex', '-1');
                });
                if (clone.matches('a, button')) {
                    clone.setAttribute('tabindex', '-1');
                }
                return clone;
            };

            const getGap = () => {
                const styles = window.getComputedStyle(track);
                return parseFloat(styles.columnGap || styles.gap || 0) || 0;
            };

            const setupMarquee = () => {
                track.querySelectorAll('[data-logo-clone="true"]').forEach((clone) => clone.remove());

                const gap = getGap();
                const setWidth = logos.reduce((total, logo) => {
                    return total + logo.getBoundingClientRect().width;
                }, 0) + (gap * logos.length);

                if (!setWidth) {
                    return;
                }

                const viewport = wrapper.querySelector('.hero-stats__logos-viewport');
                const viewportWidth = viewport ? viewport.getBoundingClientRect().width : wrapper.getBoundingClientRect().width;
                let renderedWidth = setWidth;

                while (renderedWidth < viewportWidth + (setWidth * 2)) {
                    logos.forEach((logo) => track.appendChild(cloneLogo(logo)));
                    renderedWidth += setWidth;
                }

                track.style.setProperty('--hero-stats-logos-distance', `${setWidth}px`);
                wrapper.classList.add('is-marquee');
            };

            const debounce = (callback) => {
                let frame = null;
                return () => {
                    if (frame) {
                        window.cancelAnimationFrame(frame);
                    }
                    frame = window.requestAnimationFrame(callback);
                };
            };

            const rebuildMarquee = debounce(setupMarquee);

            setupMarquee();
            window.addEventListener('load', rebuildMarquee, { once: true });
            window.addEventListener('resize', rebuildMarquee);

            track.dataset.marqueeReady = 'true';
        });
    });
})();
