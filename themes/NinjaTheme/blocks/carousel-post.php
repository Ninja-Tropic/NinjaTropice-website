<?php
/**
 * Block Name: Carousel Post
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'carousel-post-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'carousel-post';
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

$selected_posts = !empty($f['post_selector']) ? $f['post_selector'] : array();
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="carousel-post__container container">
        <?php if( !empty($f['title']) || !empty($f['description']) ): ?>
            <div class="carousel-post__header">
                <?php if( !empty($f['title']) ): ?>
                    <h2 class="carousel-post__title"><?php echo esc_html($f['title']); ?></h2>
                <?php endif; ?>

                <?php if( !empty($f['description']) ): ?>
                    <div class="carousel-post__description">
                        <?php echo wp_kses_post($f['description']); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if( !empty($selected_posts) ): ?>
            <div class="carousel-post__track" role="list">
                <?php foreach( $selected_posts as $row ):
                    $post_obj = !empty($row['post']) ? $row['post'] : null;
                    if( empty($post_obj) ) {
                        continue;
                    }
                    $post_id = is_object($post_obj) ? $post_obj->ID : $post_obj;
                    $post_title = get_the_title($post_id);
                    $post_link = get_permalink($post_id);
                    $post_excerpt = get_the_excerpt($post_id);
                ?>
                    <article class="carousel-post__item" role="listitem">
                        <a class="carousel-post__link" href="<?php echo esc_url($post_link); ?>">
                            <?php if( has_post_thumbnail($post_id) ): ?>
                                <div class="carousel-post__media">
                                    <?php echo get_the_post_thumbnail(
                                        $post_id,
                                        'large',
                                        array(
                                            'class' => 'carousel-post__image',
                                            'loading' => 'lazy',
                                        )
                                    ); ?>
                                </div>
                            <?php endif; ?>

                            <div class="carousel-post__body">
                                <?php if( !empty($post_title) ): ?>
                                    <h3 class="carousel-post__item-title"><?php echo esc_html($post_title); ?></h3>
                                <?php endif; ?>

                                <?php if( !empty($post_excerpt) ): ?>
                                    <div class="carousel-post__excerpt">
                                        <?php echo wp_kses_post($post_excerpt); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
