<?php
/**
 * Block Name: Hero Stats
 *
 * Fields: title (text), description (textarea), button_1 (link), button_2 (link),
 * image (image, full-width), stats (repeater: value, label), logos (repeater: image, link).
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'hero-stats-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'hero-stats';
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

$title = !empty($f['title']) ? $f['title'] : '';
$description = !empty($f['description']) ? $f['description'] : '';
$button_1 = !empty($f['button_1']) ? $f['button_1'] : null;
$button_2 = !empty($f['button_2']) ? $f['button_2'] : null;
$image = !empty($f['image']) ? $f['image'] : null;
$stats = !empty($f['stats']) ? $f['stats'] : array();
$logos = !empty($f['logos']) ? $f['logos'] : array();
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="hero-stats__container container">

        <div class="hero-stats__intro">
            <div class="hero-stats__title-col">
                <?php if( !empty($title) ): ?>
                    <h1 class="hero-stats__title"><?php echo esc_html($title); ?></h1>
                <?php endif; ?>
            </div>

            <div class="hero-stats__content-col">
                <?php if( !empty($description) ): ?>
                    <div class="hero-stats__description">
                        <?php echo wp_kses_post($description); ?>
                    </div>
                <?php endif; ?>

                <?php if( !empty($button_1) || !empty($button_2) ): ?>
                    <div class="hero-stats__cta">
                        <?php if( !empty($button_1) ): ?>
                            <a href="<?php echo esc_url($button_1['url']); ?>"
                               class="btn"
                               <?php if( !empty($button_1['target']) ): ?>target="<?php echo esc_attr($button_1['target']); ?>"<?php endif; ?>>
                                <?php echo esc_html(!empty($button_1['title']) ? $button_1['title'] : $button_1['url']); ?>
                            </a>
                        <?php endif; ?>

                        <?php if( !empty($button_2) ): ?>
                            <a href="<?php echo esc_url($button_2['url']); ?>"
                               class="btn btn--outline"
                               <?php if( !empty($button_2['target']) ): ?>target="<?php echo esc_attr($button_2['target']); ?>"<?php endif; ?>>
                                <?php echo esc_html(!empty($button_2['title']) ? $button_2['title'] : $button_2['url']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if( !empty($image) ): ?>
            <div class="hero-stats__media">
                <?php echo ninjatheme_get_responsive_image( $image, 'large', array( 'class' => 'hero-stats__img', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
            </div>
        <?php endif; ?>

        <?php if( !empty($stats) || !empty($logos) ): ?>
            <div class="hero-stats__footer">
                <?php if( !empty($stats) ): ?>
                    <div class="hero-stats__stats">
                        <?php foreach( $stats as $stat ):
                            $stat_value = !empty($stat['value']) ? $stat['value'] : '';
                            $stat_label = !empty($stat['label']) ? $stat['label'] : '';
                            if( empty($stat_value) && empty($stat_label) ) {
                                continue;
                            }
                        ?>
                            <div class="hero-stats__stat">
                                <?php if( !empty($stat_value) ): ?>
                                    <div class="hero-stats__stat-value"><?php echo esc_html($stat_value); ?></div>
                                <?php endif; ?>
                                <?php if( !empty($stat_label) ): ?>
                                    <div class="hero-stats__stat-label"><?php echo esc_html($stat_label); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if( !empty($logos) ): ?>
                    <div class="hero-stats__logos" data-hero-stats-logos>
                        <p class="hero-stats__logos-label">Chosen by industry leaders</p>

                        <div class="hero-stats__logos-viewport">
                            <button type="button" class="hero-stats__logos-nav" data-direction="prev" aria-label="Previous logos">
                                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" aria-hidden="true"><path d="M9 1L1.5 8L9 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            <div class="hero-stats__logos-track">
                                <?php foreach( $logos as $logo ):
                                    $logo_image = !empty($logo['image']) ? $logo['image'] : null;
                                    if( empty($logo_image) ) {
                                        continue;
                                    }
                                    $logo_link = !empty($logo['link']) ? $logo['link'] : null;
                                    $logo_tag = $logo_link ? 'a' : 'div';
                                ?>
                                    <<?php echo esc_attr($logo_tag); ?>
                                        class="hero-stats__logo"
                                        <?php if( $logo_link ): ?>
                                            href="<?php echo esc_url($logo_link['url']); ?>"
                                            <?php if( !empty($logo_link['target']) ): ?>target="<?php echo esc_attr($logo_link['target']); ?>"<?php endif; ?>
                                        <?php endif; ?>
                                    >
                                        <?php echo ninjatheme_get_responsive_image( $logo_image, 'medium', array( 'class' => 'hero-stats__logo-img' ) ); ?>
                                    </<?php echo esc_attr($logo_tag); ?>>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" class="hero-stats__logos-nav" data-direction="next" aria-label="Next logos">
                                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" aria-hidden="true"><path d="M1 1L8.5 8L1 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
