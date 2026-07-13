(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    window.NinjaTheme.modules.push(function() {
        const heroMedia = document.querySelectorAll('.main-hero__media');
        if (heroMedia.length > 0) {
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-revealed');
                            obs.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.25,
                    rootMargin: '0px 0px -10% 0px'
                });

                heroMedia.forEach((media) => observer.observe(media));
            } else {
                heroMedia.forEach((media) => media.classList.add('is-revealed'));
            }
        }

        const heroSections = document.querySelectorAll('.main-hero');
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

        const getModalElements = (section) => {
            if (!section || !section.classList.contains('main-hero')) {
                return { modal: null, videoTarget: null, embedData: null };
            }
            const media = section.querySelector('.main-hero__media');
            const modal = section.querySelector('.main-hero__modal');
            const videoTarget = section.querySelector('[data-main-hero-video]');
            const embedData = media ? media.getAttribute('data-video-embed') : null;
            return { modal, videoTarget, embedData };
        };

        const openModal = (section) => {
            const { modal, videoTarget, embedData } = getModalElements(section);
            if (!modal || !videoTarget || !embedData) {
                return;
            }
            try {
                let embedHtml = atob(embedData);
                embedHtml = applyAutoplayToEmbed(embedHtml);
                videoTarget.innerHTML = embedHtml;
            } catch (e) {
                console.error('Main hero video embed error:', e);
            }
            modal.classList.add('is-active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('has-main-hero-modal');
        };

        const closeModal = (section) => {
            const { modal, videoTarget } = getModalElements(section);
            if (!modal || !videoTarget) {
                return;
            }
            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('has-main-hero-modal');
            videoTarget.innerHTML = '';
        };

        // Custom unmute toggle for the inline Vimeo hero embed (postMessage API)
        document.querySelectorAll('[data-hero-unmute]').forEach((btn) => {
            const wrap = btn.closest('.main-hero__video');
            const iframe = wrap ? wrap.querySelector('iframe') : null;
            if (!iframe) {
                btn.hidden = true;
                return;
            }
            let muted = true;
            const send = (method, value) => {
                if (iframe.contentWindow) {
                    iframe.contentWindow.postMessage(JSON.stringify({ method: method, value: value }), '*');
                }
            };
            btn.addEventListener('click', () => {
                muted = !muted;
                send('setMuted', muted);
                if (!muted) {
                    send('setVolume', 1);
                }
                const label = btn.querySelector('span');
                if (label) {
                    label.textContent = muted ? 'Unmute' : 'Mute';
                }
                btn.setAttribute('aria-label', muted ? 'Unmute video' : 'Mute video');
                btn.classList.toggle('is-unmuted', !muted);
            });
        });

        heroSections.forEach((section) => {
            const modal = section.querySelector('.main-hero__modal');
            if (!modal) {
                return;
            }

            const openFromSection = () => openModal(section);
            const closeFromSection = () => closeModal(section);

            section.querySelectorAll('.main-hero__play-trigger').forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openFromSection();
                });
            });

            section.querySelectorAll('.main-hero__image--has-video').forEach((image) => {
                const playButton = image.querySelector('.main-hero__play');
                if (playButton) {
                    playButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        openFromSection();
                    });
                }
            });

            modal.addEventListener('click', (e) => {
                const target = e.target;
                if (target && target.hasAttribute('data-main-hero-close')) {
                    closeFromSection();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                    closeFromSection();
                }
            });
        });
    });
})();
