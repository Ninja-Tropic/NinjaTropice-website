(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const videoPlaceholders = document.querySelectorAll('.video-block__placeholder');
        if (videoPlaceholders.length === 0) {
            return;
        }

        const applyAutoplayToEmbed = (embedHtml) => {
            try {
                const parser = new DOMParser();
                const doc = parser.parseFromString(embedHtml, 'text/html');
                const iframe = doc.querySelector('iframe');
                if (!iframe) {
                    return embedHtml;
                }
                const src = iframe.getAttribute('src');
                if (!src) {
                    return embedHtml;
                }
                const url = new URL(src, window.location.href);
                url.searchParams.set('autoplay', '1');
                iframe.setAttribute('src', url.toString());
                return iframe.outerHTML;
            } catch (e) {
                return embedHtml;
            }
        };

        let videoObserver = null;

        const loadVideoFromPlaceholder = (placeholder, { autoplay = false } = {}) => {
            const wrapper = placeholder.closest('.video-block__video-wrapper');
            if (!wrapper || wrapper.getAttribute('data-video-loaded') === 'true') {
                return;
            }
            const embedData = placeholder.getAttribute('data-video-embed');
            if (!embedData) {
                return;
            }
            try {
                let embedHtml = atob(embedData);
                if (autoplay) {
                    embedHtml = applyAutoplayToEmbed(embedHtml);
                }
                wrapper.innerHTML = embedHtml;
                wrapper.setAttribute('data-video-loaded', 'true');
                if (videoObserver) {
                    videoObserver.unobserve(placeholder);
                }
            } catch (e) {
                console.error('Error loading video embed:', e);
            }
        };

        if ('IntersectionObserver' in window) {
            videoObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const placeholder = entry.target;
                        loadVideoFromPlaceholder(placeholder, { autoplay: false });
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '100px'
            });

            videoPlaceholders.forEach(placeholder => {
                videoObserver.observe(placeholder);
            });
        }

        videoPlaceholders.forEach(placeholder => {
            placeholder.addEventListener('click', function() {
                loadVideoFromPlaceholder(placeholder, { autoplay: true });
            });

            const playButton = placeholder.querySelector('.video-block__play-button');
            if (playButton) {
                playButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    loadVideoFromPlaceholder(placeholder, { autoplay: true });
                });
            }
        });
    });
})();
