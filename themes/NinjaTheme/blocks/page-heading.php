<?php
/**
 * Block Name: Page Heading
 *
 * Fields: title (WYSIWYG), description (WYSIWYG), image, link_1, link_2,
 * right_to_left (true/false), design (select).
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'page-heading-' . $block['id'];
if ( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'page-heading';
if ( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if ( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Get ACF fields
$f = get_fields();
if ( !$f ) {
    $f = array();
}

$title = !empty($f['title']) ? $f['title'] : '';
$description = !empty($f['description']) ? $f['description'] : '';
$image = !empty($f['image']) ? $f['image'] : null;
$link_1 = !empty($f['link_1']) ? $f['link_1'] : null;
$link_2 = !empty($f['link_2']) ? $f['link_2'] : null;
$right_to_left = !empty($f['right_to_left']);
$design = !empty($f['design']) ? $f['design'] : '';

if ( $right_to_left ) {
    $className .= ' page-heading--rtl';
}
if ( !empty($design) ) {
    $className .= ' page-heading--' . sanitize_title($design);
}

$has_media = !empty($image);
$has_links = !empty($link_1) || !empty($link_2);

if ( !$has_media ) {
    $className .= ' page-heading--no-media';
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="page-heading__aurora page-heading__aurora--one" aria-hidden="true"></div>
    <div class="page-heading__aurora page-heading__aurora--two" aria-hidden="true"></div>

    <div class="page-heading__container container">
        <div class="page-heading__shell">
            <div class="page-heading__grid">
                <div class="page-heading__content">
                    <div class="page-heading__panel">
                        <div class="page-heading__accent" aria-hidden="true"></div>

                        <?php if ( !empty($title) ): ?>
                            <div class="page-heading__title">
                                <?php echo wp_kses_post($title); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( !empty($description) ): ?>
                            <div class="page-heading__description">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $has_links ): ?>
                            <div class="page-heading__actions">
                                <?php if ( !empty($link_1) ): ?>
                                    <a
                                        href="<?php echo esc_url($link_1['url']); ?>"
                                        class="btn page-heading__btn page-heading__btn--primary"
                                        <?php if ( !empty($link_1['target']) ): ?>target="<?php echo esc_attr($link_1['target']); ?>"<?php endif; ?>
                                        <?php if ( !empty($link_1['title']) ): ?>aria-label="<?php echo esc_attr($link_1['title']); ?>"<?php endif; ?>
                                    >
                                        <?php echo esc_html(!empty($link_1['title']) ? $link_1['title'] : $link_1['url']); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ( !empty($link_2) ): ?>
                                    <a
                                        href="<?php echo esc_url($link_2['url']); ?>"
                                        class="btn btn--secondary page-heading__btn page-heading__btn--secondary"
                                        <?php if ( !empty($link_2['target']) ): ?>target="<?php echo esc_attr($link_2['target']); ?>"<?php endif; ?>
                                        <?php if ( !empty($link_2['title']) ): ?>aria-label="<?php echo esc_attr($link_2['title']); ?>"<?php endif; ?>
                                    >
                                        <?php echo esc_html(!empty($link_2['title']) ? $link_2['title'] : $link_2['url']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( $has_media ): ?>
                    <div class="page-heading__media">
                        <div class="page-heading__media-frame">
                            <div class="page-heading__media-glow" aria-hidden="true"></div>
                            <?php echo ninjatheme_get_responsive_image( $image, 'large', array( 'class' => 'page-heading__image', 'loading' => 'lazy' ) ); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
