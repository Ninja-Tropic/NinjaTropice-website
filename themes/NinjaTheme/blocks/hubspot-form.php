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
        <?php $title_id = ''; ?>
        <div class="hubspot-form__cta">
            <div class="hubspot-form__content">
                <?php if( !empty($f['title']) ): ?>
                    <?php $title_id = 'hubspot-form-title-' . $block['id']; ?>
                    <h2 id="<?php echo esc_attr($title_id); ?>" class="hubspot-form__title"><?php echo esc_html($f['title']); ?></h2>
                <?php endif; ?>

                <?php if( !empty($f['description']) ): ?>
                    <div class="hubspot-form__description">
                        <?php echo wp_kses_post($f['description']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="hubspot-form__actions">
                <span class="hubspot-form__line" aria-hidden="true"></span>
                <span class="hubspot-form__kicker">Let's talk</span>
                <?php if( !empty($f['form']) ): ?>
                    <?php $modal_id = 'hubspot-form-modal-' . $block['id']; ?>
                    <button class="btn hubspot-form__button" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="<?php echo esc_attr($modal_id); ?>">
                        Contact Us
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if( !empty($f['form']) ): ?>
            <div id="<?php echo esc_attr($modal_id); ?>" class="hubspot-form__modal" hidden>
                <div class="hubspot-form__modal-backdrop" data-hubspot-close aria-hidden="true"></div>
                <div class="hubspot-form__modal-dialog" role="dialog" aria-modal="true" <?php if( !empty($title_id) ): ?>aria-labelledby="<?php echo esc_attr($title_id); ?>"<?php endif; ?>>
                    <button class="hubspot-form__modal-close" type="button" data-hubspot-close aria-label="Close form">
                        &times;
                    </button>
                    <div class="hubspot-form__form-card">
                        <div class="hubspot-form__form">
                            <?php echo $f['form']; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
