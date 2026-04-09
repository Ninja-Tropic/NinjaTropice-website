/**
 * Card Carousel (vanilla JS)
 */
(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const carousels = document.querySelectorAll('.card--carousel');
        if (!carousels.length) {
            return;
        }

        const updateButtons = (track, prevBtn, nextBtn) => {
            if (!track || !prevBtn || !nextBtn) {
                return;
            }

            const maxScrollLeft = Math.max(0, track.scrollWidth - track.clientWidth - 1);
            prevBtn.disabled = track.scrollLeft <= 0;
            nextBtn.disabled = track.scrollLeft >= maxScrollLeft;
        };

        const getStep = (track) => {
            const item = track.querySelector('.card__item');
            if (!item) {
                return track.clientWidth * 0.9;
            }
            const itemWidth = item.getBoundingClientRect().width;
            const styles = window.getComputedStyle(track);
            const gap = parseFloat(styles.columnGap || styles.gap || 0);
            return itemWidth + gap;
        };

        carousels.forEach((carousel) => {
            const track = carousel.querySelector('.card__track');
            const items = track ? track.querySelectorAll('.card__item') : [];
            if (items.length === 3) {
                carousel.classList.add('card--three');
            }
            const prevBtn = carousel.querySelector('.card__nav-btn[data-direction="prev"]');
            const nextBtn = carousel.querySelector('.card__nav-btn[data-direction="next"]');

            if (!track || !prevBtn || !nextBtn) {
                return;
            }

            const onScroll = () => {
                window.requestAnimationFrame(() => updateButtons(track, prevBtn, nextBtn));
            };

            prevBtn.addEventListener('click', () => {
                track.scrollBy({ left: -getStep(track), behavior: 'smooth' });
            });

            nextBtn.addEventListener('click', () => {
                track.scrollBy({ left: getStep(track), behavior: 'smooth' });
            });

            track.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll);
            updateButtons(track, prevBtn, nextBtn);
        });
    });
})();
