<?php
/**
 * Block Name: Hubspot Form
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'hubspot-form-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'hubspot-form';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Get ACF fields
$f = get_fields();
if( !$f ) {
    $f = array();
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="hubspot-form__container container">
        <div class="hubspot-form__cta">
            <div class="hubspot-form__content">
                <?php if( !empty($f['title']) ): ?>
                    <h2 class="hubspot-form__title"><?php echo esc_html($f['title']); ?></h2>
                <?php endif; ?>

                <?php if( !empty($f['description']) ): ?>
                    <div class="hubspot-form__description">
                        <?php echo wp_kses_post($f['description']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="hubspot-form__actions">
                <?php if( !empty($f['form']) ): ?>
                    <div class="hubspot-form__form-card">
                        <div class="hubspot-form__form">
                            <?php echo ninjatheme_defer_hubspot_embed( $f['form'] ); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
