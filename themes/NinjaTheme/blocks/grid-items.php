<?php
/**
 * Block Name: Grid Items
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'grid-items-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'grid-items';
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

$items = !empty($f['items']) ? $f['items'] : array();
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="grid-items__container container">
        <?php if( !empty($f['title']) || !empty($f['description']) ): ?>
            <div class="grid-items__intro">
                <?php if( !empty($f['title']) ): ?>
                    <h2 class="grid-items__title"><?php echo esc_html($f['title']); ?></h2>
                <?php endif; ?>

                <?php if( !empty($f['description']) ): ?>
                    <div class="grid-items__description">
                        <?php echo wp_kses_post($f['description']); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if( !empty($items) ): ?>
            <div class="grid-items__grid">
                <?php foreach( $items as $item ): ?>
                    <?php
                    $item_link = !empty($item['link']) ? $item['link'] : null;
                    $item_tag = $item_link ? 'a' : 'div';
                    $publication_date = null;
                    if( !empty($item['publication_date']) ) {
                        $parsed_date = DateTimeImmutable::createFromFormat('!Y-m-d', $item['publication_date'], wp_timezone());
                        if( $parsed_date && $parsed_date->format('Y-m-d') === $item['publication_date'] ) {
                            $publication_date = $parsed_date;
                        }
                    }
                    if( !$publication_date && !empty($item_link['url']) ) {
                        $linked_post_id = url_to_postid($item_link['url']);
                        if( $linked_post_id && get_post_status($linked_post_id) === 'publish' ) {
                            $publication_date = get_post_datetime($linked_post_id, 'date');
                        }
                    }
                    ?>
                    <<?php echo esc_attr($item_tag); ?>
                        class="grid-items__item"
                        <?php if( $item_link ): ?>
                            href="<?php echo esc_url($item_link['url']); ?>"
                            <?php if( !empty($item_link['target']) ): ?>target="<?php echo esc_attr($item_link['target']); ?>"<?php endif; ?>
                        <?php endif; ?>
                    >
                        <?php if( !empty($item['image']) ): ?>
                            <div class="grid-items__image">
                                <?php echo ninjatheme_get_responsive_image( $item['image'], 'large', array( 'class' => 'grid-items__img' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if( !empty($item['tag']) || $publication_date || !empty($item['time_label']) ): ?>
                            <div class="grid-items__meta">
                                <?php if( !empty($item['tag']) ): ?>
                                    <p class="grid-items__item-tag"><?php echo esc_html($item['tag']); ?></p>
                                <?php endif; ?>
                                <?php if( $publication_date ): ?>
                                    <time class="grid-items__item-time" datetime="<?php echo esc_attr($publication_date->format('Y-m-d')); ?>"><?php echo esc_html(wp_date(get_option('date_format'), $publication_date->getTimestamp())); ?></time>
                                <?php elseif( !empty($item['time_label']) ): ?>
                                    <p class="grid-items__item-time"><?php echo esc_html($item['time_label']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if( !empty($item['title']) ): ?>
                            <h3 class="grid-items__item-title"><?php echo esc_html($item['title']); ?></h3>
                        <?php endif; ?>
                    </<?php echo esc_attr($item_tag); ?>>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
