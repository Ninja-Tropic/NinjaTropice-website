(function() {
    'use strict';

    const SELECTOR = '[data-tabs-block]';
    const PANEL_TRANSITION_MS = 220;
    const DEFAULT_PARAM = 'tab';

    const getUrlParamName = (block) => block.dataset.tabsParam || DEFAULT_PARAM;

    const getIndexFromUrl = (block, buttons, total) => {
        try {
            const params = new URLSearchParams(window.location.search);
            const requestedSlug = params.get(getUrlParamName(block));

            if (!requestedSlug) {
                return null;
            }

            const normalizedRequestedSlug = requestedSlug.trim().toLowerCase();
            const matchedIndex = buttons.findIndex((button) => {
                const tabSlug = (button.dataset.tabSlug || '').trim().toLowerCase();
                return tabSlug !== '' && tabSlug === normalizedRequestedSlug;
            });

            return matchedIndex >= 0 && matchedIndex < total ? matchedIndex : null;
        } catch (error) {
            return null;
        }
    };

    const updateUrlParam = (block, button) => {
        if (!button || !button.dataset.tabSlug || !window.history || typeof window.history.replaceState !== 'function') {
            return;
        }

        try {
            const url = new URL(window.location.href);
            url.searchParams.set(getUrlParamName(block), button.dataset.tabSlug);
            window.history.replaceState({}, '', url.toString());
        } catch (error) {
            // Ignore malformed URL edge cases.
        }
    };

    const getScopedPanels = (block) => {
        const explicitPanels = Array.from(block.querySelectorAll('[data-tab-panel]'));
        if (explicitPanels.length > 0) {
            return explicitPanels;
        }

        return Array.from(block.querySelectorAll('[data-tab-panel-preview]'));
    };

    const ensureAria = (block, buttons, panels) => {
        const blockId = block.id || `tabs-block-${Math.random().toString(36).slice(2, 10)}`;

        if (!block.id) {
            block.id = blockId;
        }

        buttons.forEach((button, index) => {
            const buttonId = button.id || `${blockId}-tab-${index + 1}`;
            const panelId = `${blockId}-panel-${index + 1}`;

            button.id = buttonId;
            button.setAttribute('role', 'tab');
            button.setAttribute('aria-controls', panelId);
            button.setAttribute('data-tab-button', String(index));
        });

        panels.forEach((panel, index) => {
            const button = buttons[index];
            const panelId = panel.id || `${blockId}-panel-${index + 1}`;

            panel.id = panelId;
            panel.setAttribute('role', 'tabpanel');
            panel.setAttribute('tabindex', panel.classList.contains('is-active') ? '0' : '-1');

            if (button) {
                panel.setAttribute('aria-labelledby', button.id);
            }

            if (!panel.hasAttribute('data-tab-panel')) {
                panel.setAttribute('data-tab-panel', String(index));
            }
        });
    };

    const hidePanel = (panel) => {
        if (panel._tabsHideTimer) {
            window.clearTimeout(panel._tabsHideTimer);
        }

        panel.classList.remove('is-active', 'is-entering');
        panel.classList.add('is-leaving');
        panel.setAttribute('aria-hidden', 'true');
        panel.setAttribute('tabindex', '-1');

        panel._tabsHideTimer = window.setTimeout(() => {
            panel.hidden = true;
            panel.classList.remove('is-leaving');
            panel._tabsHideTimer = null;
        }, PANEL_TRANSITION_MS);
    };

    const showPanel = (panel) => {
        if (panel._tabsHideTimer) {
            window.clearTimeout(panel._tabsHideTimer);
            panel._tabsHideTimer = null;
        }

        panel.hidden = false;
        panel.classList.remove('is-leaving');
        panel.setAttribute('aria-hidden', 'false');
        panel.setAttribute('tabindex', '0');
        panel.classList.add('is-entering');

        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => {
                panel.classList.add('is-active');
                panel.classList.remove('is-entering');
            });
        });
    };

    const activateTab = (block, nextIndex, focusButton, updateUrl = false) => {
        const buttons = Array.from(block.querySelectorAll('[data-tab-button]'));
        const panels = getScopedPanels(block);
        const total = Math.min(buttons.length, panels.length);

        if (total === 0 || nextIndex < 0 || nextIndex >= total) {
            return;
        }

        buttons.slice(0, total).forEach((button, index) => {
            const isActive = index === nextIndex;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            button.setAttribute('tabindex', isActive ? '0' : '-1');

            if (isActive && focusButton) {
                button.focus();
            }
        });

        panels.slice(0, total).forEach((panel, index) => {
            if (index === nextIndex) {
                showPanel(panel);
                return;
            }

            hidePanel(panel);
        });

        block.dataset.activeTab = String(nextIndex);

        if (updateUrl) {
            updateUrlParam(block, buttons[nextIndex]);
        }
    };

    const bindEvents = (block) => {
        if (block.dataset.tabsBlockReady === 'true') {
            return;
        }

        const buttons = Array.from(block.querySelectorAll('[data-tab-button]'));
        const panels = getScopedPanels(block);
        const total = Math.min(buttons.length, panels.length);

        if (total === 0) {
            return;
        }

        ensureAria(block, buttons, panels);
        buttons.slice(0, total).forEach((button, index) => {
            button.addEventListener('click', () => {
                activateTab(block, index, false, true);
            });

            button.addEventListener('keydown', (event) => {
                let targetIndex = null;

                switch (event.key) {
                    case 'ArrowRight':
                    case 'ArrowDown':
                        targetIndex = (index + 1) % total;
                        break;
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        targetIndex = (index - 1 + total) % total;
                        break;
                    case 'Home':
                        targetIndex = 0;
                        break;
                    case 'End':
                        targetIndex = total - 1;
                        break;
                    case ' ':
                    case 'Enter':
                        event.preventDefault();
                        activateTab(block, index, true, true);
                        return;
                    default:
                        return;
                }

                event.preventDefault();
                activateTab(block, targetIndex, true, true);
            });
        });

        block.dataset.tabsBlockReady = 'true';
        const urlIndex = getIndexFromUrl(block, buttons.slice(0, total), total);
        const initialIndex = urlIndex !== null ? urlIndex : Number(block.dataset.activeTab || 0);
        activateTab(block, initialIndex, false, false);
    };

    const initTabsBlocks = (scope) => {
        const root = scope && scope.querySelectorAll ? scope : document;
        const blocks = root.matches && root.matches(SELECTOR)
            ? [root]
            : Array.from(root.querySelectorAll(SELECTOR));

        blocks.forEach(bindEvents);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => initTabsBlocks(document));
    } else {
        initTabsBlocks(document);
    }

    if (window.acf && typeof window.acf.addAction === 'function') {
        window.acf.addAction('render_block_preview/type=tabs-block', (element) => {
            initTabsBlocks(element && element[0] ? element[0] : element);
        });
    }
})();
