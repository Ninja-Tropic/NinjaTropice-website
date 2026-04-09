<?php
/**
 * Block Name: Video Block
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'video-block-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'video-block';
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

$right_to_left = !empty($f['right_to_left']);
$design = !empty($f['design']) ? sanitize_key($f['design']) : '';
if ( $right_to_left ) {
    $className .= ' video-block--rtl';
}
if ( !empty($design) && $design !== 'mesh' ) {
    $className .= ' video-block--design-' . $design;
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="video-block__container container">
        <div class="video-block__grid">
            <!-- Left: Video -->
            <div class="video-block__video">
                <?php 
                // If a thumbnail is set, show it instead of the video
                $thumbnail_field = get_field('thumbnail');
                $thumbnail_url = '';
                $thumbnail_alt = 'Video thumbnail';

                if ( !empty($thumbnail_field) ) {
                    if ( is_array($thumbnail_field) ) {
                        if ( !empty($thumbnail_field['sizes']['large']) ) {
                            $thumbnail_url = $thumbnail_field['sizes']['large'];
                        } elseif ( !empty($thumbnail_field['url']) ) {
                            $thumbnail_url = $thumbnail_field['url'];
                        }
                        if ( !empty($thumbnail_field['alt']) ) {
                            $thumbnail_alt = $thumbnail_field['alt'];
                        } elseif ( !empty($thumbnail_field['title']) ) {
                            $thumbnail_alt = $thumbnail_field['title'];
                        }
                    } elseif ( is_numeric($thumbnail_field) ) {
                        $thumbnail_url = wp_get_attachment_image_url((int) $thumbnail_field, 'large');
                        $thumbnail_alt = get_post_meta((int) $thumbnail_field, '_wp_attachment_image_alt', true) ?: $thumbnail_alt;
                    } elseif ( is_string($thumbnail_field) ) {
                        $thumbnail_url = $thumbnail_field;
                    }
                }

                if ( $thumbnail_url ): 
                ?>
                    <div class="video-block__video-wrapper">
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($thumbnail_alt); ?>" class="video-block__thumbnail" loading="lazy" />
                    </div>
                <?php else: ?>
                <?php
                    // Get the video field value - ACF oembed returns formatted embed code
                    $video_field = get_field('video');
                    if( !empty($video_field) ): 
                        // If it's already HTML (embed code), use it directly
                        // If it's a URL, convert to embed with caching
                        if ( filter_var($video_field, FILTER_VALIDATE_URL) ) {
                            $video_embed = ninjatheme_get_cached_oembed($video_field);
                        } else {
                            $video_embed = $video_field;
                        }
                        if( $video_embed ):
                ?>
                    <div class="video-block__video-wrapper" data-video-loaded="false">
                        <?php 
                        // Allow iframe and other embed tags
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
                        
                        // Add lazy loading to iframe if it's a URL-based embed
                        if ( filter_var($video_field, FILTER_VALIDATE_URL) && strpos($video_embed, '<iframe') !== false ) {
                            // Add loading="lazy" to iframe
                            $video_embed = str_replace('<iframe', '<iframe loading="lazy"', $video_embed);
                        }
                        
                        // For lazy loading, we'll use a placeholder initially
                        $video_id = 'video-' . $block['id'];
                        $is_above_fold = false; // Can be determined based on block position if needed
                        
                        if ( $is_above_fold ) {
                            // Load immediately for above-the-fold videos
                            echo wp_kses($video_embed, $allowed_html);
                        } else {
                            // Lazy load: show placeholder, load on scroll
                            // Extract thumbnail URL if possible (YouTube/Vimeo)
                            $thumbnail_url = '';
                            $thumbnail_alt = 'Video thumbnail';

                            // Fallback to provider thumbnail when no custom thumbnail is set
                            if ( empty($thumbnail_url) ) {
                                if ( preg_match('/youtube\.com\/watch\?v=([^&]+)/', $video_field, $matches) ) {
                                    $thumbnail_url = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
                                } elseif ( preg_match('/youtu\.be\/([^?]+)/', $video_field, $matches) ) {
                                    $thumbnail_url = 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
                                } elseif ( preg_match('/vimeo\.com\/(\d+)/', $video_field, $matches) ) {
                                    // Vimeo thumbnail requires API call, use placeholder
                                    $thumbnail_url = '';
                                }
                            }
                            
                            // Store embed HTML in data attribute for lazy loading
                            $embed_escaped = esc_attr( base64_encode( $video_embed ) );
                            ?>
                            <div class="video-block__placeholder" data-video-embed="<?php echo $embed_escaped; ?>" data-video-id="<?php echo esc_attr($video_id); ?>">
                                <?php if ( $thumbnail_url ): ?>
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($thumbnail_alt); ?>" class="video-block__thumbnail" loading="lazy" />
                                <?php endif; ?>
                                <button class="video-block__play-button" aria-label="Play video">
                                    <span class="video-block__play-button-inner" aria-hidden="true">
                                        <svg width="22" height="22" viewBox="0 0 24 24">
                                            <path d="M8 5.5v13l11-6.5-11-6.5z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                <?php 
                    endif;
                endif; 
                ?>
                <?php endif; ?>
            </div>
            
            <!-- Right: Title, Content, Link -->
            <div class="video-block__content">
                <?php if( !empty($f['title']) ): ?>
                    <h2 class="video-block__title"><?php echo esc_html($f['title']); ?></h2>
                <?php endif; ?>
                
                <?php if( !empty($f['content']) ): ?>
                    <div class="video-block__text">
                        <?php echo wp_kses_post($f['content']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if( !empty($f['link']) ): ?>
                    <div class="video-block__link-wrapper">
                        <a href="<?php echo esc_url($f['link']['url']); ?>" 
                           class="video-block__link"
                           <?php if( !empty($f['link']['target']) ): ?>target="<?php echo esc_attr($f['link']['target']); ?>"<?php endif; ?>>
                            <?php echo esc_html(!empty($f['link']['title']) ? $f['link']['title'] : $f['link']['url']); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
