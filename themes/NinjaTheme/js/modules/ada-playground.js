(function() {
    'use strict';

    window.NinjaTheme = window.NinjaTheme || {};
    window.NinjaTheme.modules = window.NinjaTheme.modules || [];

    const HEX_PATTERN = /^#?([a-f\d]{3}|[a-f\d]{6})$/i;

    const normalizeHex = (value) => {
        if (typeof value !== 'string') {
            return null;
        }

        const trimmed = value.trim();
        const match = trimmed.match(HEX_PATTERN);

        if (!match) {
            return null;
        }

        let hex = match[1].toLowerCase();

        if (hex.length === 3) {
            hex = hex.split('').map((char) => char + char).join('');
        }

        return `#${hex}`;
    };

    const normalizeForegroundDefault = (value) => {
        const normalized = normalizeHex(value);

        if (normalized === '#1637ff') {
            return '#2f3351';
        }

        return normalized;
    };

    const hexToRgb = (hex) => {
        const normalized = normalizeHex(hex);

        if (!normalized) {
            return null;
        }

        return {
            r: parseInt(normalized.slice(1, 3), 16),
            g: parseInt(normalized.slice(3, 5), 16),
            b: parseInt(normalized.slice(5, 7), 16),
        };
    };

    const getLuminance = ({ r, g, b }) => {
        const transform = (channel) => {
            const value = channel / 255;
            return value <= 0.03928 ? value / 12.92 : Math.pow((value + 0.055) / 1.055, 2.4);
        };

        return (0.2126 * transform(r)) + (0.7152 * transform(g)) + (0.0722 * transform(b));
    };

    const getContrastRatio = (foreground, background) => {
        const foregroundRgb = hexToRgb(foreground);
        const backgroundRgb = hexToRgb(background);

        if (!foregroundRgb || !backgroundRgb) {
            return 1;
        }

        const foregroundLuminance = getLuminance(foregroundRgb);
        const backgroundLuminance = getLuminance(backgroundRgb);
        const lighter = Math.max(foregroundLuminance, backgroundLuminance);
        const darker = Math.min(foregroundLuminance, backgroundLuminance);

        return (lighter + 0.05) / (darker + 0.05);
    };

    const updateBadge = (badge, state) => {
        if (!badge || !state) {
            return;
        }

        badge.textContent = state.text;
        badge.classList.remove('is-pass', 'is-fail', 'is-warn', 'is-aa', 'is-aaa', 'is-large');
        badge.classList.add(state.className);
    };

    const qualifiesAsLargeText = (sizePt, weight) => {
        return sizePt >= 18 || (sizePt >= 14 && weight >= 700);
    };

    const getComplianceState = (ratio, aaThreshold, aaaThreshold) => {
        if (ratio >= aaaThreshold) {
            return { text: 'Pass', className: 'is-pass' };
        }

        if (ratio >= aaThreshold) {
            return { text: 'Pass', className: 'is-pass' };
        }

        return { text: 'Fail', className: 'is-fail' };
    };

    const getTierState = (ratio, isLargeText) => {
        if (ratio >= 7) {
            return {
                status: { text: 'AAA Pass', className: 'is-aaa' },
                body: 'AAA pass',
                large: isLargeText ? 'AAA pass' : 'Too small',
                tier: 'AAA',
                message: 'This combination clears the strongest WCAG contrast target and is safe for body copy, interface labels, and large headings.'
            };
        }

        if (ratio >= 4.5) {
            return {
                status: { text: 'AA Pass', className: 'is-aa' },
                body: 'AA pass',
                large: isLargeText ? 'AAA pass' : 'Too small',
                tier: 'AA',
                message: 'This combination meets ADA-oriented contrast expectations for standard body text and also covers large-text use.'
            };
        }

        if (isLargeText && ratio >= 3) {
            return {
                status: { text: 'Large Only', className: 'is-large' },
                body: 'Fail',
                large: 'AA pass',
                tier: 'Large text only',
                message: 'This color pair is only safe for large text. Do not use it for paragraphs, captions, or smaller UI labels.'
            };
        }

        if (!isLargeText && ratio >= 3) {
            return {
                status: { text: 'Fail', className: 'is-fail' },
                body: 'Fail',
                large: 'Too small',
                tier: 'Insufficient',
                message: 'The ratio is only high enough for large text, but the selected size does not qualify. Increase size, weight, or contrast.'
            };
        }

        return {
            status: { text: 'Fail', className: 'is-fail' },
            body: 'Fail',
            large: isLargeText ? 'Fail' : 'Too small',
            tier: 'Insufficient',
            message: 'This combination does not meet minimum WCAG contrast targets commonly used for ADA-conscious design decisions.'
        };
    };

    window.NinjaTheme.modules.push(function() {
        const blocks = document.querySelectorAll('.ada-playground');

        blocks.forEach((block) => {
            const foregroundColorInput = block.querySelector('[data-control="foreground-picker"]');
            const backgroundColorInput = block.querySelector('[data-control="background-picker"]');
            const foregroundHexInput = block.querySelector('[data-control="foreground-hex"]');
            const backgroundHexInput = block.querySelector('[data-control="background-hex"]');
            const ratioOutput = block.querySelector('.ada-playground__ratio');
            const fontFamilySelect = block.querySelector('[data-control="font-family"]');
            const fontWeightSelect = block.querySelector('[data-control="font-weight"]');
            const normalSizeSelect = block.querySelector('[data-control="normal-size"]');
            const largeSizeSelect = block.querySelector('[data-control="large-size"]');
            const normalCard = block.querySelector('[data-preview-card="normal"]');
            const largeCard = block.querySelector('[data-preview-card="large"]');
            const normalSample = block.querySelector('[data-sample="normal"]');
            const largeSample = block.querySelector('[data-sample="large"]');
            const largeTextNote = block.querySelector('[data-large-text-note]');
            const overallStatus = block.querySelector('[data-overall-status]');
            const overallMessage = block.querySelector('[data-overall-message]');
            const overallBody = block.querySelector('[data-overall-body]');
            const overallLarge = block.querySelector('[data-overall-large]');
            const overallTier = block.querySelector('[data-overall-tier]');
            const summaryPanel = block.querySelector('[data-summary-panel]');

            if (
                !foregroundColorInput ||
                !backgroundColorInput ||
                !foregroundHexInput ||
                !backgroundHexInput ||
                !ratioOutput ||
                !fontFamilySelect ||
                !fontWeightSelect ||
                !normalSizeSelect ||
                !largeSizeSelect ||
                !normalCard ||
                !largeCard ||
                !normalSample ||
                !largeSample ||
                !largeTextNote ||
                !overallStatus ||
                !overallMessage ||
                !overallBody ||
                !overallLarge ||
                !overallTier ||
                !summaryPanel
            ) {
                return;
            }

            const syncColorInputs = (colorInput, hexInput, fallback, commitTextInput) => {
                const normalizedHexInput = normalizeHex(hexInput.value);
                const normalizedColorInput = normalizeHex(colorInput.value) || fallback;

                if (normalizedHexInput) {
                    colorInput.value = normalizedHexInput;
                    if (commitTextInput) {
                        hexInput.value = normalizedHexInput.toUpperCase();
                    }
                    hexInput.setAttribute('aria-invalid', 'false');
                    return normalizedHexInput;
                }

                if (commitTextInput) {
                    hexInput.value = normalizedColorInput.toUpperCase();
                    hexInput.setAttribute('aria-invalid', 'false');
                } else {
                    hexInput.setAttribute('aria-invalid', hexInput.value.trim() === '' ? 'false' : 'true');
                }

                return normalizedColorInput;
            };

            const updatePreview = (commitTextInput) => {
                const foreground = syncColorInputs(
                    foregroundColorInput,
                    foregroundHexInput,
                    normalizeForegroundDefault(block.dataset.foregroundColor) || '#2f3351',
                    commitTextInput
                );
                const background = syncColorInputs(
                    backgroundColorInput,
                    backgroundHexInput,
                    normalizeHex(block.dataset.backgroundColor) || '#ffffff',
                    commitTextInput
                );
                const fontFamily = fontFamilySelect.value;
                const fontWeight = Number(fontWeightSelect.value || 400);
                const normalSize = Number(normalSizeSelect.value || 16);
                const largeSize = Number(largeSizeSelect.value || 24);
                const ratio = getContrastRatio(foreground, background);
                const ratioText = `${ratio.toFixed(2)}:1`;

                ratioOutput.textContent = ratioText;

                [normalCard, largeCard].forEach((card) => {
                    card.style.backgroundColor = background;
                    card.style.borderColor = 'rgba(15, 23, 42, 0.08)';
                });

                [normalSample, largeSample].forEach((sample) => {
                    sample.style.backgroundColor = background;
                    sample.style.color = foreground;
                    sample.style.fontFamily = fontFamily;
                    sample.style.fontWeight = String(fontWeight);
                });

                normalSample.style.fontSize = `${normalSize}pt`;
                largeSample.style.fontSize = `${largeSize}pt`;

                const normalAaBadge = normalCard.querySelector('[data-result="aa"]');
                const normalAaaBadge = normalCard.querySelector('[data-result="aaa"]');
                const largeAaBadge = largeCard.querySelector('[data-result="aa"]');
                const largeAaaBadge = largeCard.querySelector('[data-result="aaa"]');
                const isLargeText = qualifiesAsLargeText(largeSize, fontWeight);
                const overallState = getTierState(ratio, isLargeText);

                updateBadge(normalAaBadge, getComplianceState(ratio, 4.5, 7));
                updateBadge(normalAaaBadge, ratio >= 7 ? { text: 'Pass', className: 'is-pass' } : { text: 'Fail', className: 'is-fail' });
                updateBadge(
                    largeAaBadge,
                    isLargeText
                        ? (ratio >= 3 ? { text: 'Pass', className: 'is-pass' } : { text: 'Fail', className: 'is-fail' })
                        : { text: 'Resize', className: 'is-warn' }
                );
                updateBadge(
                    largeAaaBadge,
                    isLargeText
                        ? (ratio >= 4.5 ? { text: 'Pass', className: 'is-pass' } : { text: 'Fail', className: 'is-fail' })
                        : { text: 'Resize', className: 'is-warn' }
                );

                updateBadge(overallStatus, overallState.status);
                overallMessage.textContent = overallState.message;
                overallBody.textContent = overallState.body;
                overallLarge.textContent = overallState.large;
                overallTier.textContent = overallState.tier;
                summaryPanel.classList.remove('is-aaa', 'is-aa', 'is-large', 'is-fail');
                summaryPanel.classList.add(overallState.status.className);

                largeTextNote.textContent = isLargeText
                    ? 'This selection qualifies as large text. AA needs 3:1. AAA needs 4.5:1.'
                    : 'This selection does not qualify as large text yet. Use at least 18pt regular or 14pt bold.';
            };

            foregroundColorInput.addEventListener('input', () => {
                foregroundHexInput.value = foregroundColorInput.value.toUpperCase();
                updatePreview(true);
            });

            backgroundColorInput.addEventListener('input', () => {
                backgroundHexInput.value = backgroundColorInput.value.toUpperCase();
                updatePreview(true);
            });

            [foregroundHexInput, backgroundHexInput].forEach((input) => {
                input.addEventListener('input', () => updatePreview(false));
                input.addEventListener('blur', () => updatePreview(true));
            });

            [fontFamilySelect, fontWeightSelect, normalSizeSelect, largeSizeSelect].forEach((input) => {
                input.addEventListener('change', () => updatePreview(true));
            });

            updatePreview(true);
        });
    });
})();
