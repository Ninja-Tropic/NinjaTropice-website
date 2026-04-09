<?php
/**
 * Block Name: CTA Box
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'cta-box-' . $block['id'];
if ( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'cta-box';
if ( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if ( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Get ACF fields.
$f = get_fields();
if ( !$f ) {
    $f = array();
}

$title = !empty($f['title']) ? $f['title'] : '';
$description = !empty($f['description']) ? $f['description'] : '';
$link = !empty($f['link']) && is_array($f['link']) ? $f['link'] : null;
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="cta-box__container container">
        <div class="cta-box__inner ninja-box ninja-box--hover-border">
            <div class="cta-box__content">
                <?php if ( !empty($title) ): ?>
                    <div class="cta-box__title">
                        <?php echo wp_kses_post($title); ?>
                    </div>
                <?php endif; ?>

                <div class="cta-box__dots" aria-hidden="true">
                    <span class="cta-box__dot"></span>
                    <span class="cta-box__dot"></span>
                    <span class="cta-box__dot"></span>
                </div>

                <?php if ( !empty($description) ): ?>
                    <div class="cta-box__description">
                        <?php echo wp_kses_post($description); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( !empty($link) ): ?>
                <div class="cta-box__actions">
                    <a
                        href="<?php echo esc_url($link['url']); ?>"
                        class="btn cta-box__btn"
                        <?php if ( !empty($link['target']) ): ?>target="<?php echo esc_attr($link['target']); ?>"<?php endif; ?>
                        <?php if ( !empty($link['title']) ): ?>aria-label="<?php echo esc_attr($link['title']); ?>"<?php endif; ?>
                    >
                        <?php echo esc_html(!empty($link['title']) ? $link['title'] : $link['url']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
