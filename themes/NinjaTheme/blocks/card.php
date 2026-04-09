<?php
/**
 * Block Name: Card
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'card-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'card';
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
$items = !empty($f['items']) && is_array($f['items']) ? $f['items'] : array();
$item_count = count($items);
$show_nav = $item_count > 1;
if ( 3 === $item_count ) {
    $className .= ' card--three';
}
$className .= ' card--carousel';
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="card__header container">
        <div class="card__header-content">
            <div class="card__header-col card__header-col--title">
                <?php if( !empty($f['title']) ): ?>
                    <h2 class="card__section-title has-text-primary-100"><?php echo esc_html($f['title']); ?></h2>
                <?php endif; ?>
            </div>
            <div class="card__header-col card__header-col--content">
                <?php if( !empty($f['description']) ): ?>
                    <div class="card__section-text has-text-primary-100">
                        <?php echo wp_kses_post($f['description']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

       
    </div>
    <?php if ( $show_nav ) : ?>
        <div class="card__nav-wrap container is-flex is-justify-content-right my-6">
            <div class="card__nav" aria-label="Carousel navigation">
                <button class="card__nav-btn" type="button" data-direction="prev" aria-label="Previous slide">
                    <span aria-hidden="true">‹</span>
                </button>
                <button class="card__nav-btn" type="button" data-direction="next" aria-label="Next slide">
                    <span aria-hidden="true">›</span>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( !empty($items) ) : ?>
        <div class="card__track-wrap">
            <div class="card__track" role="list">
                <?php foreach( $items as $item ) : ?>
                    <?php
                    if ( !is_array($item) ) {
                        continue;
                    }
                    $item_link = !empty($item['link']) && is_array($item['link']) ? $item['link'] : null;
                    $item_tag = $item_link ? 'a' : 'div';
                    ?>
                    <article class="card__item" role="listitem">
                        <<?php echo esc_attr($item_tag); ?>
                            class="card__item-link"
                            <?php if( $item_link ): ?>
                                href="<?php echo esc_url($item_link['url']); ?>"
                                <?php if( !empty($item_link['target']) ): ?>target="<?php echo esc_attr($item_link['target']); ?>"<?php endif; ?>
                            <?php endif; ?>
                        >
                            <div class="card__item-body">
                                <?php if( !empty($item['eyebrow']) ): ?>
                                    <p class="card__item-eyebrow"><?php echo esc_html($item['eyebrow']); ?></p>
                                <?php endif; ?>
                                <?php if( !empty($item['image']) ): ?>
                                <div class="card__item-media">
                                    <?php echo ninjatheme_get_responsive_image( $item['image'], 'large', array( 'class' => 'card__item-image' ) ); ?>
                                </div>
                            <?php endif; ?>
                                <?php if( !empty($item['title']) ): ?>
                                    <h3 class="card__item-title"><?php echo esc_html($item['title']); ?></h3>
                                <?php endif; ?>
                                <?php if( !empty($item['description']) ): ?>
                                    <p class="card__item-text"><?php echo esc_html($item['description']); ?></p>
                                <?php endif; ?>
                            </div>

                        </<?php echo esc_attr($item_tag); ?>>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
