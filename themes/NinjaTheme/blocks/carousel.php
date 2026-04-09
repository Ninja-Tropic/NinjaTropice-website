<?php
/**
 * Block Name: Carousel
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'carousel-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'carousel';
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

// Get carousel items (logos)
$carousel_items = !empty($f['carousel_items']) ? $f['carousel_items'] : array();

// Duplicate items for seamless infinite scroll (3 copies for robust looping)
// Using three identical segments allows the CSS animation to scroll by exactly 1/3
// of the total width, creating a seamless, gapless loop even with few logos.
$duplicated_items = !empty($carousel_items)
    ? array_merge($carousel_items, $carousel_items, $carousel_items)
    : array();
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php if( !empty($f['title']) ): ?>
        <div class="carousel__header">
            <div class="carousel__container container">
                <h2 class="carousel__title"><?php echo esc_html($f['title']); ?></h2>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if( !empty($carousel_items) ): ?>
        <div class="carousel__wrapper">
            <!-- First Row -->
            <div class="carousel__row carousel__row--1">
                <div class="carousel__track" data-direction="left">
                    <?php foreach( $duplicated_items as $item ): ?>
                        <div class="carousel__item">
                            <?php if( !empty($item['link']) ): ?>
                                <a href="<?php echo esc_url($item['link']['url']); ?>" 
                                   class="carousel__item-link"
                                   <?php if( !empty($item['link']['target']) ): ?>target="<?php echo esc_attr($item['link']['target']); ?>"<?php endif; ?>
                                   aria-label="<?php echo esc_attr(!empty($item['image']['alt']) ? $item['image']['alt'] : 'Logo'); ?>">
                            <?php endif; ?>
                            
                            <?php if( !empty($item['image']) ): ?>
                                <div class="carousel__item-image">
                                    <?php echo ninjatheme_get_responsive_image( $item['image'], 'medium', array( 'class' => 'carousel__img' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if( !empty($item['link']) ): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Second Row -->
            <div class="carousel__row carousel__row--2">
                <div class="carousel__track" data-direction="right">
                    <?php foreach( $duplicated_items as $item ): ?>
                        <div class="carousel__item">
                            <?php if( !empty($item['link']) ): ?>
                                <a href="<?php echo esc_url($item['link']['url']); ?>" 
                                   class="carousel__item-link"
                                   <?php if( !empty($item['link']['target']) ): ?>target="<?php echo esc_attr($item['link']['target']); ?>"<?php endif; ?>
                                   aria-label="<?php echo esc_attr(!empty($item['image']['alt']) ? $item['image']['alt'] : 'Logo'); ?>">
                            <?php endif; ?>
                            
                            <?php if( !empty($item['image']) ): ?>
                                <div class="carousel__item-image">
                                    <?php echo ninjatheme_get_responsive_image( $item['image'], 'medium', array( 'class' => 'carousel__img' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if( !empty($item['link']) ): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
