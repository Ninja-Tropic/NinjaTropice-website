<?php
/**
 * Block Name: Section 2 Rows
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'section-2-rows-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'section-2-rows';
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

$design = !empty($f['design']) ? sanitize_key($f['design']) : 'mesh';
$design = in_array($design, array('simple', 'gradient', 'mesh'), true) ? $design : 'mesh';
$right_to_left = !empty($f['right_to_left']);
$className .= ' section-2-rows--design-' . sanitize_html_class($design);
if ( $right_to_left ) {
    $className .= ' section-2-rows--rtl';
}

$has_text = !empty($f['title']) || !empty($f['description']);
$image = !empty($f['image']) ? $f['image'] : null;
$image_id = null;
$image_url = '';
$image_alt = '';
if ( is_array($image) ) {
    $image_id = !empty($image['id']) ? (int) $image['id'] : null;
    $image_url = !empty($image['url']) ? $image['url'] : '';
    $image_alt = !empty($image['alt']) ? $image['alt'] : '';
} elseif ( is_numeric($image) ) {
    $image_id = (int) $image;
} elseif ( is_string($image) ) {
    $image_url = $image;
}

$text_html = '';
if ( $has_text ) {
    ob_start();
    ?>
        <div class="section-2-rows__panel section-2-rows__panel--text column">
            <div class="section-2-rows__row-content" <?php if( !empty($f['text-color']) ): ?>style="color: <?php echo esc_attr($f['text-color']); ?>;"<?php endif; ?>>
                <?php if( !empty($f['title']) ): ?>
                    <div class="section-2-rows__title mb-4">
                        <?php echo wp_kses_post($f['title']); ?>
                    </div>
                <?php endif; ?>

                <?php if( !empty($f['description']) ): ?>
                    <div class="section-2-rows__description">
                        <?php echo wp_kses_post($f['description']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php
    $text_html = ob_get_clean();
}

$image_html = '';
if ( $image_id || $image_url ) {
    ob_start();
    ?>
        <div class="section-2-rows__panel section-2-rows__panel--image column">
            <div class="section-2-rows__image-wrap">
                <?php if ( $image_id ) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'full', false, array('class' => 'section-2-rows__image', 'alt' => $image_alt)); ?>
                <?php else : ?>
                    <img class="section-2-rows__image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                <?php endif; ?>
            </div>
        </div>
    <?php
    $image_html = ob_get_clean();
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" 
    <?php if( !empty($f['background']) ): ?>style="background-color: <?php echo esc_attr($f['background']); ?>;"<?php endif; ?>>
    <div class="section-2-rows__container container">
        <div class="section-2-rows__grid columns<?php if ( $right_to_left ) : ?> section-2-rows__grid--rtl<?php endif; ?>">
            <?php if ( $right_to_left ) : ?>
                <?php echo $image_html; ?>
                <?php echo $text_html; ?>
            <?php else : ?>
                <?php echo $text_html; ?>
                <?php echo $image_html; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
