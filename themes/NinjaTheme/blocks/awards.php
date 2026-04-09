<?php
/**
 * Block Name: Awards
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'awards-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'awards';
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
$eyebrow = !empty($f['eyebrow']) ? $f['eyebrow'] : '';
$stats = !empty($f['stats']) ? $f['stats'] : array();
$has_stats = !empty($stats);
$layout_class = $has_stats ? 'awards__layout' : 'awards__layout awards__layout--single';
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="awards__container container">
        <div class="<?php echo esc_attr($layout_class); ?>">
            <div class="awards__content-col">
                <div class="awards__intro">
                    <?php if( !empty($eyebrow) ): ?>
                        <p class="awards__eyebrow"><?php echo esc_html($eyebrow); ?></p>
                    <?php endif; ?>

                    <?php if( !empty($f['title']) ): ?>
                        <h2 class="awards__title"><?php echo esc_html($f['title']); ?></h2>
                    <?php endif; ?>

                    <?php if( !empty($f['content']) ): ?>
                        <div class="awards__text">
                            <?php echo wp_kses_post($f['content']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if( !empty($f['link']) ): ?>
                        <div class="awards__cta">
                            <a href="<?php echo esc_url($f['link']['url']); ?>"
                               class="btn"
                               <?php if( !empty($f['link']['target']) ): ?>target="<?php echo esc_attr($f['link']['target']); ?>"<?php endif; ?>>
                                <?php echo esc_html(!empty($f['link']['title']) ? $f['link']['title'] : $f['link']['url']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if( !empty($items) ): ?>
                    <div class="awards__logos">
                        <?php foreach( $items as $item ): ?>
                            <?php
                            $item_link = !empty($item['link']) ? $item['link'] : null;
                            $item_tag = $item_link ? 'a' : 'div';
                            ?>
                            <<?php echo esc_attr($item_tag); ?>
                                class="awards__item"
                                <?php if( $item_link ): ?>
                                    href="<?php echo esc_url($item_link['url']); ?>"
                                    <?php if( !empty($item_link['target']) ): ?>target="<?php echo esc_attr($item_link['target']); ?>"<?php endif; ?>
                                <?php endif; ?>
                            >
                                <?php if( !empty($item['image']) ): ?>
                                    <div class="awards__logo">
                                        <?php echo ninjatheme_get_responsive_image( $item['image'], 'medium', array( 'class' => 'awards__img' ) ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if( !empty($item['title']) || !empty($item['description']) ): ?>
                                    <div class="awards__item-content">
                                        <?php if( !empty($item['title']) ): ?>
                                            <h3 class="awards__item-title"><?php echo esc_html($item['title']); ?></h3>
                                        <?php endif; ?>
                                        <?php if( !empty($item['description']) ): ?>
                                            <p class="awards__item-text"><?php echo esc_html($item['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </<?php echo esc_attr($item_tag); ?>>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="awards__stats-col">
                <?php if( !empty($stats) ): ?>
                    <div class="awards__stats">
                        <?php foreach( $stats as $index => $stat ): ?>
                            <?php
                            $stat_value = !empty($stat['value']) ? $stat['value'] : '';
                            $stat_label = !empty($stat['label']) ? $stat['label'] : '';
                            $stat_ring = !empty($stat['ring_text']) ? $stat['ring_text'] : '';
                            $ring_id = 'awards-ring-' . $block['id'] . '-' . $index;
                            ?>
                            <div class="awards__stat">
                                <?php if( !empty($stat_ring) ): ?>
                                    <div class="awards__stat-ring" aria-hidden="true">
                                        <svg viewBox="0 0 120 120" role="img" focusable="false">
                                            <defs>
                                                <path id="<?php echo esc_attr($ring_id); ?>" d="M60,10 a50,50 0 1,1 0,100 a50,50 0 1,1 0,-100" />
                                            </defs>
                                            <text>
                                                <textPath href="#<?php echo esc_attr($ring_id); ?>" startOffset="50%">
                                                    <?php echo esc_html($stat_ring); ?>
                                                </textPath>
                                            </text>
                                        </svg>
                                    </div>
                                <?php endif; ?>

                                <?php if( !empty($stat_value) || !empty($stat_label) ): ?>
                                    <div class="awards__stat-content">
                                        <?php if( !empty($stat_value) ): ?>
                                            <div class="awards__stat-value"><?php echo esc_html($stat_value); ?></div>
                                        <?php endif; ?>

                                        <?php if( !empty($stat_label) ): ?>
                                            <div class="awards__stat-label"><?php echo esc_html($stat_label); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
