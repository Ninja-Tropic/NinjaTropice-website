<?php
/**
 * Block Name: Main Hero
 *
 * Fields: title (text/h1), content (WYSIWYG), link, image, video (oEmbed).
 * Title may include <span class="main-hero__title-highlight"> for yellow highlight.
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'main-hero-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'main-hero';
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
$content = !empty($f['content']) ? $f['content'] : '';
$link = !empty($f['link']) ? $f['link'] : null;
$image = !empty($f['image']) ? $f['image'] : null;
$video_field = !empty($f['video']) ? $f['video'] : '';
$video_embed = '';

if ( !empty($video_field) ) {
    if ( filter_var($video_field, FILTER_VALIDATE_URL) ) {
        $video_embed = ninjatheme_get_cached_oembed($video_field);
    } else {
        $video_embed = $video_field;
    }
}

$has_video = !empty($video_embed);
$has_image = !empty($image);
$modal_embed = '';
if ( $has_video ) {
    $allowed_html = wp_kses_allowed_html('post');
    $allowed_html['iframe'] = array(
        'src' => true,
        'width' => true,
        'height' => true,
        'frameborder' => true,
        'allow' => true,
        'allowfullscreen' => true,
        'style' => true,
        'class' => true,
        'id' => true,
        'loading' => true,
    );

    if ( filter_var($video_field, FILTER_VALIDATE_URL) && strpos($video_embed, '<iframe') !== false ) {
        $video_embed = str_replace('<iframe', '<iframe loading="lazy"', $video_embed);
    }

    $video_embed = ninjatheme_oembed_add_autoplay( $video_embed );
    $modal_embed = wp_kses($video_embed, $allowed_html);
}

// Allow span with class in title for highlight (e.g. <span class="main-hero__title-highlight">Right Loan</span>)
$title_allowed = array( 'span' => array( 'class' => true ) );
$title_safe = $title ? wp_kses( $title, $title_allowed ) : '';
$embed_escaped = $has_video ? esc_attr( base64_encode( $modal_embed ) ) : '';
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="main-hero__bg-decoration" aria-hidden="true"></div>
    <div class="main-hero__container container ">
        <div class="main-hero__content">
            <?php if ( $title_safe ): ?>
                <h1 class="main-hero__title"><?php echo $title_safe; ?></h1>
            <?php endif; ?>

            <?php if ( !empty($content) ): ?>
                <div class="main-hero__text">
                    <?php echo wp_kses_post($content); ?>
                </div>
            <?php endif; ?>

            <div class="main-hero__cta">
                <?php if ( !empty($link) ): ?>
                    <a href="<?php echo esc_url($link['url']); ?>"
                       class="btn main-hero__btn"
                       <?php if ( !empty($link['target']) ): ?>target="<?php echo esc_attr($link['target']); ?>"<?php endif; ?>
                       <?php if ( !empty($link['title']) ): ?>aria-label="<?php echo esc_attr($link['title']); ?>"<?php endif; ?>
                    >
                        <?php echo esc_html(!empty($link['title']) ? $link['title'] : $link['url']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ( $has_video || $has_image ): ?>
            <div class="main-hero__media">
                <span class="main-hero__blob" aria-hidden="true"></span>
                <?php if ( $has_video ): ?>
                    <div class="main-hero__video">
                        <?php echo $modal_embed; ?>
                    </div>
                <?php elseif ( $has_image ): ?>
                    <div class="main-hero__image">
                        <?php echo ninjatheme_get_responsive_image( $image, 'large', array( 'class' => 'main-hero__img', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>
    </div>
</section>
