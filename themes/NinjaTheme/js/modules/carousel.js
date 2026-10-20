(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const carouselTracks = document.querySelectorAll('.carousel__track');
        if (carouselTracks.length === 0) {
            return;
        }

        const carouselObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const track = entry.target;

                if (entry.isIntersecting) {
                    track.classList.remove('paused');
                    track.style.animationPlayState = 'running';
                } else {
                    track.classList.add('paused');
                    track.style.animationPlayState = 'paused';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });

        carouselTracks.forEach(track => {
            carouselObserver.observe(track);
        });
    });
})();
