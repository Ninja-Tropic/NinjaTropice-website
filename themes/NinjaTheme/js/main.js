/**
 * NinjaTheme Main JavaScript
 */
(function() {
    'use strict';

    const namespace = window.NinjaTheme || {};
    const modules = Array.isArray(namespace.modules) ? namespace.modules : [];

    modules.forEach((init) => {
        if (typeof init === 'function') {
            try {
                init();
            } catch (e) {
                console.error('NinjaTheme module error:', e);
            }
        }
    });
})();
