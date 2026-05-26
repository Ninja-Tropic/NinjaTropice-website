<?php
/**
 * NinjaTheme functions and definitions
 *
 * @package NinjaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Load performance utilities from this theme (not parent/Divi)
if ( file_exists( get_stylesheet_directory() . '/inc/performance.php' ) ) {
	require_once get_stylesheet_directory() . '/inc/performance.php';
}

/**
 * Theme setup
 */
function ninjatheme_setup() {
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );
	
	// Enable support for Post Thumbnails on posts and pages
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );
	
	// Register navigation menus
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu' ),
		'mobile'  => esc_html__( 'Mobile Menu' ),
		'footer'  => esc_html__( 'Footer Menu' ),
	) );
	
	// Switch default core markup to output valid HTML5
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	
	// Add theme support for selective refresh for widgets
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	// Add support for custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	
	// Add support for responsive embeds
	add_theme_support( 'responsive-embeds' );
	
	// Add support for editor styles
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'style.css', 'editor-style.css' ) );
	
	// Add support for wide alignment
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
}
add_action( 'after_setup_theme', 'ninjatheme_setup' );

/**
 * Set the content width in pixels
 */
function ninjatheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ninjatheme_content_width', 1200 );
}
add_action( 'after_setup_theme', 'ninjatheme_content_width', 0 );


/**
 * Enqueue Be Vietnam Pro font (matches NinjaTropic.com)
 */
function ninjatheme_fonts() {
	wp_enqueue_style(
		'ninjatheme-fonts',
		'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Nunito:wght@700;800;900&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'ninjatheme_fonts' );
add_action( 'enqueue_block_editor_assets', 'ninjatheme_fonts' );
add_action( 'admin_enqueue_scripts', 'ninjatheme_fonts' );

/**
 * Enqueue scripts and styles
 */
function ninjatheme_scripts() {
	// Self-hosted Bulma (eliminates external CDN connection from the critical path)
	$bulma_local = get_stylesheet_directory() . '/assets/css/bulma.min.css';
	if ( file_exists( $bulma_local ) ) {
		wp_enqueue_style( 'bulma-css', get_stylesheet_directory_uri() . '/assets/css/bulma.min.css', array(), filemtime( $bulma_local ) );
	} else {
		wp_enqueue_style( 'bulma-css', 'https://cdn.jsdelivr.net/npm/bulma@1.0.4/css/bulma.min.css', array(), '1.0.4' );
	}

	// Enqueue theme stylesheet
	$style_version = file_exists( get_stylesheet_directory() . '/style.css' ) ? filemtime( get_stylesheet_directory() . '/style.css' ) : '1.0.0';
	wp_enqueue_style( 'ninjatheme-style', get_stylesheet_uri(), array( 'bulma-css' ), $style_version );

	// Scripts always needed on every page (navigation, smooth scroll)
	$always_scripts = array(
		'ninjatheme-header'        => '/js/modules/header.js',
		'ninjatheme-mobile-menu'   => '/js/modules/mobile-menu.js',
		'ninjatheme-smooth-scroll' => '/js/modules/smooth-scroll.js',
	);

	// Scripts loaded only when their ACF block is present on the page
	$conditional_scripts = array(
		'ninjatheme-ada-playground' => array(
			'path'   => '/js/modules/ada-playground.js',
			'blocks' => array( 'acf/ada-playground' ),
		),
		'ninjatheme-video-block'    => array(
			'path'   => '/js/modules/video-block.js',
			'blocks' => array( 'acf/video-block' ),
		),
		'ninjatheme-main-hero'      => array(
			'path'   => '/js/modules/main-hero.js',
			'blocks' => array( 'acf/main-hero' ),
		),
		'ninjatheme-carousel'       => array(
			'path'   => '/js/modules/carousel.js',
			'blocks' => array( 'acf/carousel' ),
		),
		'ninjatheme-card-carousel'  => array(
			'path'   => '/js/modules/card-carousel.js',
			'blocks' => array( 'acf/carousel-post' ),
		),
		'ninjatheme-hubspot-form'   => array(
			'path'   => '/js/modules/hubspot-form.js',
			'blocks' => array( 'acf/hubspot-form' ),
		),
	);

	$module_handles = array();

	// Enqueue always-on scripts
	foreach ( $always_scripts as $handle => $path ) {
		$full_path = get_stylesheet_directory() . $path;
		if ( file_exists( $full_path ) ) {
			$module_handles[] = $handle;
			wp_enqueue_script( $handle, get_stylesheet_directory_uri() . $path, array(), filemtime( $full_path ), true );
			wp_script_add_data( $handle, 'defer', true );
		}
	}

	// Enqueue conditional scripts only if their block exists on the current page
	foreach ( $conditional_scripts as $handle => $config ) {
		$full_path  = get_stylesheet_directory() . $config['path'];
		$block_used = false;

		if ( is_singular() ) {
			$post_content = get_post_field( 'post_content', get_the_ID() );
			foreach ( $config['blocks'] as $block_name ) {
				if ( has_block( $block_name ) || strpos( $post_content, '"name":"' . $block_name . '"' ) !== false ) {
					$block_used = true;
					break;
				}
			}
		} else {
			// On archives / home: load conditionally — safe fallback is to load them
			$block_used = true;
		}

		if ( $block_used && file_exists( $full_path ) ) {
			$module_handles[] = $handle;
			wp_enqueue_script( $handle, get_stylesheet_directory_uri() . $config['path'], array(), filemtime( $full_path ), true );
			wp_script_add_data( $handle, 'defer', true );
		}
	}

	if ( file_exists( get_stylesheet_directory() . '/js/main.js' ) ) {
		$script_version = filemtime( get_stylesheet_directory() . '/js/main.js' );
		wp_enqueue_script(
			'ninjatheme-script',
			get_stylesheet_directory_uri() . '/js/main.js',
			$module_handles,
			$script_version,
			true
		);
		wp_script_add_data( 'ninjatheme-script', 'defer', true );
	}

	// Enqueue comment reply script on single posts
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ninjatheme_scripts' );

/**
 * Register widget areas
 */
function ninjatheme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'ninjatheme_widgets_init' );

/**
 * Custom excerpt length
 */
function ninjatheme_excerpt_length( $length ) {
	return 55;
}
add_filter( 'excerpt_length', 'ninjatheme_excerpt_length' );

/**
 * Custom excerpt more
 */
function ninjatheme_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'ninjatheme_excerpt_more' );

/**
 * Get cached post ID from URL
 * 
 * @param string $url Post URL
 * @return int|false Post ID or false if not found
 */
function ninjatheme_get_post_id_from_url( $url ) {
	// Create cache key from URL
	$cache_key = 'post_id_' . md5( $url );
	
	// Try to get from cache
	$post_id = get_transient( $cache_key );
	
	if ( false === $post_id ) {
		// Cache miss - query database
		$post_id = url_to_postid( $url );
		
		// Cache for 1 hour (3600 seconds)
		set_transient( $cache_key, $post_id, HOUR_IN_SECONDS );
	}
	
	return $post_id;
}

/**
 * Get cached oEmbed HTML
 * 
 * @param string $url Video URL
 * @return string|false oEmbed HTML or false on failure
 */
function ninjatheme_get_cached_oembed( $url ) {
	if ( empty( $url ) ) {
		return false;
	}
	
	// Create cache key from URL
	$cache_key = 'oembed_' . md5( $url );
	
	// Try to get from cache
	$embed_html = get_transient( $cache_key );
	
	if ( false === $embed_html ) {
		// Cache miss - fetch oEmbed
		$embed_html = wp_oembed_get( $url );
		
		if ( $embed_html ) {
			// Cache for 24 hours (86400 seconds)
			set_transient( $cache_key, $embed_html, DAY_IN_SECONDS );
		}
	}
	
	return $embed_html;
}

/**
 * Add autoplay to oEmbed iframe src (YouTube, Vimeo, etc.)
 *
 * @param string $embed_html Raw oEmbed HTML containing an iframe
 * @return string Modified HTML with autoplay=1 in the iframe src
 */
function ninjatheme_oembed_add_autoplay( $embed_html ) {
	if ( empty( $embed_html ) || strpos( $embed_html, '<iframe' ) === false ) {
		return $embed_html;
	}
	return preg_replace_callback(
		'/<iframe([^>]+)src=(["\'])([^"\']+)\2([^>]*)>/i',
		function ( $m ) {
			$src = $m[3];
			$sep = strpos( $src, '?' ) !== false ? '&' : '?';
			$src = $src . $sep . 'autoplay=1';
			return '<iframe' . $m[1] . ' src=' . $m[2] . $src . $m[2] . $m[4] . '>';
		},
		$embed_html,
		1
	);
}

/**
 * Get responsive image HTML with lazy loading
 * 
 * @param array|int $image ACF image array or attachment ID
 * @param string $size Image size name
 * @param array $args Additional arguments (class, loading, fetchpriority)
 * @return string Image HTML
 */
function ninjatheme_get_responsive_image( $image, $size = 'large', $args = array() ) {
	$defaults = array(
		'class' => '',
		'loading' => 'lazy',
		'fetchpriority' => '',
		'alt' => '',
	);
	$args = wp_parse_args( $args, $defaults );
	
	// Get attachment ID
	$attachment_id = 0;
	if ( is_numeric( $image ) ) {
		$attachment_id = $image;
	} elseif ( is_array( $image ) && !empty( $image['ID'] ) ) {
		$attachment_id = $image['ID'];
	} elseif ( is_array( $image ) && !empty( $image['id'] ) ) {
		$attachment_id = $image['id'];
	} elseif ( is_array( $image ) && !empty( $image['url'] ) ) {
		// Try to get attachment ID from URL
		$attachment_id = attachment_url_to_postid( $image['url'] );
	}
	
	// If we have an attachment ID, use WordPress responsive image functions
	if ( $attachment_id ) {
		$image_attr = array(
			'class' => $args['class'],
			'loading' => $args['loading'],
		);
		
		if ( !empty( $args['fetchpriority'] ) ) {
			$image_attr['fetchpriority'] = $args['fetchpriority'];
		}
		
		if ( !empty( $args['alt'] ) ) {
			$image_attr['alt'] = $args['alt'];
		}
		
		return wp_get_attachment_image( $attachment_id, $size, false, $image_attr );
	}
	
	// Fallback to direct URL — include width/height to prevent CLS
	if ( is_array( $image ) && ! empty( $image['url'] ) ) {
		$url           = esc_url( $image['url'] );
		$alt           = ! empty( $args['alt'] ) ? esc_attr( $args['alt'] ) : ( ! empty( $image['alt'] ) ? esc_attr( $image['alt'] ) : '' );
		$class         = ! empty( $args['class'] ) ? esc_attr( $args['class'] ) : '';
		$loading       = esc_attr( $args['loading'] );
		$fetchpriority = ! empty( $args['fetchpriority'] ) ? ' fetchpriority="' . esc_attr( $args['fetchpriority'] ) . '"' : '';

		// Width/height from ACF array prevent CLS (browser reserves space)
		$width_attr  = ! empty( $image['width'] )  ? ' width="' . (int) $image['width'] . '"' : '';
		$height_attr = ! empty( $image['height'] ) ? ' height="' . (int) $image['height'] . '"' : '';

		return sprintf(
			'<img src="%s" alt="%s" class="%s" loading="%s"%s%s%s />',
			$url,
			$alt,
			$class,
			$loading,
			$fetchpriority,
			$width_attr,
			$height_attr
		);
	}
	
	return '';
}

/**
 * Register ACF Options Page and Blocks
 * Requires Advanced Custom Fields Pro plugin
 */
function ninjatheme_has_external_block_field_group( $block_name, $exclude_key = '' ) {
	if ( ! function_exists( 'acf_get_field_groups' ) ) {
		return false;
	}

	$field_groups = acf_get_field_groups();
	if ( empty( $field_groups ) || ! is_array( $field_groups ) ) {
		return false;
	}

	foreach ( $field_groups as $field_group ) {
		if ( empty( $field_group['location'] ) || ! is_array( $field_group['location'] ) ) {
			continue;
		}

		if ( ! empty( $exclude_key ) && ! empty( $field_group['key'] ) && $exclude_key === $field_group['key'] ) {
			continue;
		}

		foreach ( $field_group['location'] as $rule_group ) {
			if ( ! is_array( $rule_group ) ) {
				continue;
			}

			foreach ( $rule_group as $rule ) {
				if (
					! empty( $rule['param'] ) &&
					'block' === $rule['param'] &&
					! empty( $rule['operator'] ) &&
					'==' === $rule['operator'] &&
					! empty( $rule['value'] ) &&
					$block_name === $rule['value']
				) {
					return true;
				}
			}
		}
	}

	return false;
}

/**
 * Enqueue Tabs block assets in both frontend and editor previews.
 *
 * @return void
 */
function ninjatheme_enqueue_tabs_block_assets() {
	$style_path = get_stylesheet_directory() . '/assets/css/tabs-block.css';
	if ( file_exists( $style_path ) ) {
		wp_enqueue_style(
			'ninjatheme-tabs-block',
			get_stylesheet_directory_uri() . '/assets/css/tabs-block.css',
			array(),
			filemtime( $style_path )
		);
	}

	$script_path = get_stylesheet_directory() . '/js/modules/tabs-block.js';
	if ( file_exists( $script_path ) ) {
		wp_enqueue_script(
			'ninjatheme-tabs-block',
			get_stylesheet_directory_uri() . '/js/modules/tabs-block.js',
			array(),
			filemtime( $script_path ),
			true
		);
	}
}

function ninjatheme_register_acf_assets() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(array(
			'page_title' 	=> 'Theme General Settings',
			'menu_title'	=> 'Theme Settings',
			'menu_slug' 	=> 'theme-general-settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> false,
			'icon_url'		=> 'dashicons-admin-generic',
			'position'		=> 60
		));
	}

	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	// Register Section 2 Rows block
	acf_register_block_type(array(
		'name'              => 'section-2-rows',
		'title'             => __('Section 2 Rows'),
		'description'       => __('A custom block that displays content in 2 rows.'),
		'render_template'   => 'blocks/section-2-rows.php',
		'category'          => 'layout',
		'icon'              => 'columns',
		'keywords'          => array('section', 'rows', '2 columns', 'layout'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Main Hero block
	acf_register_block_type(array(
		'name'              => 'main-hero',
		'title'             => __('Main Hero'),
		'description'       => __('A main hero block with title, content, link, image, and video.'),
		'render_template'   => 'blocks/main-hero.php',
		'category'          => 'layout',
		'icon'              => 'cover-image',
		'keywords'          => array('hero', 'banner', 'main', 'intro'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Page Heading block
	acf_register_block_type(array(
		'name'              => 'page-heading',
		'title'             => __('Page Heading'),
		'description'       => __('A page heading block with title, description, links, and image.'),
		'render_template'   => 'blocks/page-heading.php',
		'category'          => 'layout',
		'icon'              => 'heading',
		'keywords'          => array('page', 'heading', 'title', 'intro'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));
	
	// Register Card block
	acf_register_block_type(array(
		'name'              => 'card',
		'title'             => __('Card'),
		'description'       => __('A custom card block with image, title, content, and optional link.'),
		'render_template'   => 'blocks/card.php',
		'category'          => 'layout',
		'icon'              => 'id',
		'keywords'          => array('card', 'content', 'image', 'link'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Awards block
	acf_register_block_type(array(
		'name'              => 'awards',
		'title'             => __('Awards'),
		'description'       => __('A custom awards block with title, content, link, and award items.'),
		'render_template'   => 'blocks/awards.php',
		'category'          => 'layout',
		'icon'              => 'awards',
		'keywords'          => array('awards', 'recognition', 'badges', 'logos'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Hubspot Form block
	acf_register_block_type(array(
		'name'              => 'hubspot-form',
		'title'             => __('Hubspot Form'),
		'description'       => __('A custom Hubspot form block with title, description, and form embed.'),
		'render_template'   => 'blocks/hubspot-form.php',
		'category'          => 'layout',
		'icon'              => 'feedback',
		'keywords'          => array('hubspot', 'form', 'lead', 'contact'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register CTA Box block
	acf_register_block_type(array(
		'name'              => 'cta-box',
		'title'             => __('CTA Box'),
		'description'       => __('A custom CTA box block with title, description, and link.'),
		'render_template'   => 'blocks/cta-box.php',
		'category'          => 'layout',
		'icon'              => 'megaphone',
		'keywords'          => array('cta', 'call to action', 'link', 'box'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Grid Items block
	acf_register_block_type(array(
		'name'              => 'grid-items',
		'title'             => __('Grid Items'),
		'description'       => __('A custom grid block with a title, description, and repeater items.'),
		'render_template'   => 'blocks/grid-items.php',
		'category'          => 'layout',
		'icon'              => 'screenoptions',
		'keywords'          => array('grid', 'items', 'cards', 'image'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));
	
	// Register Carousel block
	acf_register_block_type(array(
		'name'              => 'carousel',
		'title'             => __('Carousel'),
		'description'       => __('A custom carousel block with title and repeater items (image, title, link).'),
		'render_template'   => 'blocks/carousel.php',
		'category'          => 'layout',
		'icon'              => 'images-alt2',
		'keywords'          => array('carousel', 'slider', 'gallery', 'images'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Carousel Post block
	acf_register_block_type(array(
		'name'              => 'carousel-post',
		'title'             => __('Carousel Post'),
		'description'       => __('A custom carousel block that displays selected posts.'),
		'render_template'   => 'blocks/carousel-post.php',
		'category'          => 'layout',
		'icon'              => 'slides',
		'keywords'          => array('carousel', 'posts', 'slider', 'featured'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));
	
	// Register Video Block
	acf_register_block_type(array(
		'name'              => 'video-block',
		'title'             => __('Video Block'),
		'description'       => __('A custom video block with title, content, link, and video embed.'),
		'render_template'   => 'blocks/video-block.php',
		'category'          => 'layout',
		'icon'              => 'video-alt3',
		'keywords'          => array('video', 'embed', 'content', 'media'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Related Solutions block
	acf_register_block_type(array(
		'name'              => 'related-solutions',
		'title'             => __('Related Solutions'),
		'description'       => __('A custom block with a section title and a repeater of related solutions.'),
		'render_template'   => 'blocks/related-solutions.php',
		'category'          => 'layout',
		'icon'              => 'networking',
		'keywords'          => array('solutions', 'related', 'services', 'cards'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Icon Card block
	acf_register_block_type(array(
		'name'              => 'icon-card',
		'title'             => __('Icon Card'),
		'description'       => __('A clickable icon card block with icon, title, description, and link.'),
		'render_template'   => 'blocks/icon-card.php',
		'category'          => 'layout',
		'icon'              => 'format-image',
		'keywords'          => array('icon', 'card', 'link', 'cta'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Values Showcase block
	acf_register_block_type(array(
		'name'              => 'values-showcase',
		'title'             => __('Values Showcase'),
		'description'       => __('A modern values grid with editorial-style cards and optional featured items.'),
		'render_template'   => 'blocks/values-showcase.php',
		'category'          => 'layout',
		'icon'              => 'screenoptions',
		'keywords'          => array('values', 'culture', 'cards', 'icons'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register ADA Playground block
	acf_register_block_type(array(
		'name'              => 'ada-playground',
		'title'             => __('ADA Playground'),
		'description'       => __('An interactive ADA/WCAG contrast checker with live text previews.'),
		'render_template'   => 'blocks/ada-playground.php',
		'category'          => 'layout',
		'icon'              => 'visibility',
		'keywords'          => array('ada', 'wcag', 'contrast', 'accessibility'),
		'supports'          => array(
			'align' => false,
			'anchor' => true,
		),
	));

	// Register Tabs block
	acf_register_block_type(array(
		'name'              => 'tabs-block',
		'title'             => __('Tabs Block'),
		'description'       => __('A tabs block with repeater-driven titles and WYSIWYG or InnerBlocks content.'),
		'render_template'   => 'blocks/tabs-block.php',
		'category'          => 'layout',
		'icon'              => 'index-card',
		'keywords'          => array('tabs', 'accordion', 'navigation', 'content'),
		'enqueue_assets'    => 'ninjatheme_enqueue_tabs_block_assets',
		'supports'          => array(
			'align' => false,
			'anchor' => true,
			'jsx' => true,
		),
	));

	// Register Tab Panel helper block for InnerBlocks mode
	acf_register_block_type(array(
		'name'              => 'tab-panel',
		'title'             => __('Tab Panel'),
		'description'       => __('A helper panel block used inside Tabs Block when InnerBlocks mode is enabled.'),
		'render_template'   => 'blocks/tab-panel.php',
		'category'          => 'layout',
		'icon'              => 'excerpt-view',
		'keywords'          => array('tab', 'panel', 'content'),
		'enqueue_assets'    => 'ninjatheme_enqueue_tabs_block_assets',
		'supports'          => array(
			'align' => false,
			'anchor' => false,
			'jsx' => true,
		),
	));

	if (
		function_exists( 'acf_add_local_field_group' ) &&
		! ninjatheme_has_external_block_field_group( 'acf/ada-playground', 'group_ninjatheme_ada_playground' )
	) {
		acf_add_local_field_group(array(
			'key' => 'group_ninjatheme_ada_playground',
			'title' => 'Block: ADA Playground',
			'fields' => array(
				array(
					'key' => 'field_ninjatheme_ada_playground_title',
					'label' => 'Title',
					'name' => 'title',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => 'Shown above the playground.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'ADA Contrast Playground',
				),
				array(
					'key' => 'field_ninjatheme_ada_playground_preview_text',
					'label' => 'Preview Text',
					'name' => 'preview_text',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => 'Used in both preview panels.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'Inspiring eLearning video and online training animation.',
				),
				array(
					'key' => 'field_ninjatheme_ada_playground_description',
					'label' => 'Description',
					'name' => 'description',
					'aria-label' => '',
					'type' => 'textarea',
					'instructions' => 'Short helper text shown below the heading.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'Test foreground and background colors, then compare the live contrast ratio against WCAG AA and AAA requirements.',
					'rows' => 3,
					'new_lines' => 'br',
				),
				array(
					'key' => 'field_ninjatheme_ada_playground_foreground_color',
					'label' => 'Default Foreground Color',
					'name' => 'foreground_color',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => 'Hex value, for example #2F3351.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '#2F3351',
				),
				array(
					'key' => 'field_ninjatheme_ada_playground_background_color',
					'label' => 'Default Background Color',
					'name' => 'background_color',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => 'Hex value, for example #ffffff.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '#ffffff',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/ada-playground',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));
	}

	if (
		function_exists( 'acf_add_local_field_group' ) &&
		! ninjatheme_has_external_block_field_group( 'acf/icon-card', 'group_ninjatheme_icon_card' )
	) {
		acf_add_local_field_group(array(
			'key' => 'group_ninjatheme_icon_card',
			'title' => 'Block: Icon Card',
			'fields' => array(
				array(
					'key' => 'field_ninjatheme_icon_card_title',
					'label' => 'Title',
					'name' => 'title',
					'aria-label' => '',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'visual',
					'toolbar' => 'basic',
					'media_upload' => 0,
					'delay' => 0,
				),
				array(
					'key' => 'field_ninjatheme_icon_card_description',
					'label' => 'Description',
					'name' => 'description',
					'aria-label' => '',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'visual',
					'toolbar' => 'basic',
					'media_upload' => 0,
					'delay' => 0,
				),
				array(
					'key' => 'field_ninjatheme_icon_card_icon',
					'label' => 'Icon',
					'name' => 'icon',
					'aria-label' => '',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
					'preview_size' => 'medium',
				),
				array(
					'key' => 'field_ninjatheme_icon_card_link',
					'label' => 'Link',
					'name' => 'link',
					'aria-label' => '',
					'type' => 'link',
					'instructions' => 'If set, the full card becomes clickable.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/icon-card',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));
	}

	if (
		function_exists( 'acf_add_local_field_group' ) &&
		! ninjatheme_has_external_block_field_group( 'acf/values-showcase', 'group_ninjatheme_values_showcase' )
	) {
		acf_add_local_field_group(array(
			'key' => 'group_ninjatheme_values_showcase',
			'title' => 'Block: Values Showcase',
			'fields' => array(
				array(
					'key' => 'field_ninjatheme_values_showcase_badge',
					'label' => 'Badge',
					'name' => 'badge',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => 'Optional pill label shown above the heading.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'Culture DNA',
				),
				array(
					'key' => 'field_ninjatheme_values_showcase_eyebrow',
					'label' => 'Eyebrow',
					'name' => 'eyebrow',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => 'Small uppercase label above the title.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'Our Values',
				),
				array(
					'key' => 'field_ninjatheme_values_showcase_title',
					'label' => 'Title',
					'name' => 'title',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => 'Main section title.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'The principles that shape how we work.',
				),
				array(
					'key' => 'field_ninjatheme_values_showcase_description',
					'label' => 'Description',
					'name' => 'description',
					'aria-label' => '',
					'type' => 'textarea',
					'instructions' => 'Short supporting copy below the title.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'Use the repeater below to add each value card. Cards can include an illustration, a short label, and a richer description when needed.',
					'rows' => 3,
					'new_lines' => 'br',
				),
				array(
					'key' => 'field_ninjatheme_values_showcase_items',
					'label' => 'Items',
					'name' => 'items',
					'aria-label' => '',
					'type' => 'repeater',
					'instructions' => 'Add one card per value. If no card is marked as featured, the first one will be featured automatically.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'button_label' => 'Add Value',
					'collapsed' => 'field_ninjatheme_values_showcase_item_title',
					'min' => 1,
					'max' => 0,
					'sub_fields' => array(
						array(
							'key' => 'field_ninjatheme_values_showcase_item_title',
							'label' => 'Title',
							'name' => 'title',
							'aria-label' => '',
							'type' => 'text',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '40',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
						),
						array(
							'key' => 'field_ninjatheme_values_showcase_item_kicker',
							'label' => 'Kicker',
							'name' => 'kicker',
							'aria-label' => '',
							'type' => 'text',
							'instructions' => 'Optional small label shown in the card header.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '30',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
						),
						array(
							'key' => 'field_ninjatheme_values_showcase_item_featured',
							'label' => 'Featured Card',
							'name' => 'featured',
							'aria-label' => '',
							'type' => 'true_false',
							'instructions' => 'Featured cards span wider in the grid.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '30',
								'class' => '',
								'id' => '',
							),
							'message' => '',
							'default_value' => 0,
							'ui' => 1,
							'ui_on_text' => 'Yes',
							'ui_off_text' => 'No',
						),
						array(
							'key' => 'field_ninjatheme_values_showcase_item_description',
							'label' => 'Description',
							'name' => 'description',
							'aria-label' => '',
							'type' => 'textarea',
							'instructions' => 'Optional longer copy. Leave blank for a cleaner, title-only card.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '60',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'rows' => 4,
							'new_lines' => 'br',
						),
						array(
							'key' => 'field_ninjatheme_values_showcase_item_icon',
							'label' => 'Icon / Illustration',
							'name' => 'icon',
							'aria-label' => '',
							'type' => 'image',
							'instructions' => 'Upload an icon or illustration. SVGs and transparent PNGs work well.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '40',
								'class' => '',
								'id' => '',
							),
							'return_format' => 'array',
							'library' => 'all',
							'preview_size' => 'medium',
							'min_width' => '',
							'min_height' => '',
							'min_size' => '',
							'max_width' => '',
							'max_height' => '',
							'max_size' => '',
							'mime_types' => 'jpg,jpeg,png,svg,webp',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/values-showcase',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));
	}

}
add_action( 'acf/init', 'ninjatheme_register_acf_assets' );

// Project Details field group is loaded from acf-json/group_ninjatheme_project.json

/**
 * Enqueue card carousel JS on single project pages.
 */
function ninjatheme_project_scripts() {
	if ( ! is_singular( 'project' ) ) {
		return;
	}
	$path = get_stylesheet_directory() . '/js/modules/card-carousel.js';
	if ( file_exists( $path ) ) {
		wp_enqueue_script(
			'ninjatheme-card-carousel',
			get_stylesheet_directory_uri() . '/js/modules/card-carousel.js',
			array(),
			filemtime( $path ),
			true
		);
		wp_script_add_data( 'ninjatheme-card-carousel', 'defer', true );
	}
}
add_action( 'wp_enqueue_scripts', 'ninjatheme_project_scripts' );

/**
 * Register Projects custom post type
 */
function ninjatheme_register_projects_cpt() {
	$labels = array(
		'name'                  => _x( 'Projects', 'Post Type General Name' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name' ),
		'menu_name'             => __( 'Projects' ),
		'name_admin_bar'        => __( 'Project' ),
		'add_new'               => __( 'Add New' ),
		'add_new_item'          => __( 'Add New Project' ),
		'new_item'              => __( 'New Project' ),
		'edit_item'             => __( 'Edit Project' ),
		'view_item'             => __( 'View Project' ),
		'all_items'             => __( 'All Projects' ),
		'search_items'          => __( 'Search Projects' ),
		'parent_item_colon'     => __( 'Parent Projects:' ),
		'not_found'             => __( 'No projects found.' ),
		'not_found_in_trash'    => __( 'No projects found in Trash.' ),
		'featured_image'        => __( 'Project Image' ),
		'set_featured_image'    => __( 'Set project image' ),
		'remove_featured_image' => __( 'Remove project image' ),
		'use_featured_image'    => __( 'Use as project image' ),
		'archives'              => __( 'Project Archives' ),
		'insert_into_item'      => __( 'Insert into project' ),
		'uploaded_to_this_item' => __( 'Uploaded to this project' ),
		'filter_items_list'     => __( 'Filter projects list' ),
		'items_list_navigation' => __( 'Projects list navigation' ),
		'items_list'            => __( 'Projects list' ),
	);

	$args = array(
		'label'               => __( 'Projects' ),
		'labels'              => $labels,
		'public'              => true,
		'has_archive'         => true,
		'show_in_rest'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-portfolio',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
		'rewrite'             => array( 'slug' => 'projects' ),
		'taxonomies'          => array( 'category', 'post_tag' ),
	);

	register_post_type( 'project', $args );
}
add_action( 'init', 'ninjatheme_register_projects_cpt' );

/**
 * Register Case Studies custom post type
 */
function ninjatheme_register_case_studies_cpt() {
	$labels = array(
		'name'                  => _x( 'Case Studies', 'Post Type General Name' ),
		'singular_name'         => _x( 'Case Study', 'Post Type Singular Name' ),
		'menu_name'             => __( 'Case Studies' ),
		'name_admin_bar'        => __( 'Case Study' ),
		'add_new'               => __( 'Add New' ),
		'add_new_item'          => __( 'Add New Case Study' ),
		'new_item'              => __( 'New Case Study' ),
		'edit_item'             => __( 'Edit Case Study' ),
		'view_item'             => __( 'View Case Study' ),
		'all_items'             => __( 'All Case Studies' ),
		'search_items'          => __( 'Search Case Studies' ),
		'parent_item_colon'     => __( 'Parent Case Studies:' ),
		'not_found'             => __( 'No case studies found.' ),
		'not_found_in_trash'    => __( 'No case studies found in Trash.' ),
		'featured_image'        => __( 'Case Study Image' ),
		'set_featured_image'    => __( 'Set case study image' ),
		'remove_featured_image' => __( 'Remove case study image' ),
		'use_featured_image'    => __( 'Use as case study image' ),
		'archives'              => __( 'Case Study Archives' ),
		'insert_into_item'      => __( 'Insert into case study' ),
		'uploaded_to_this_item' => __( 'Uploaded to this case study' ),
		'filter_items_list'     => __( 'Filter case studies list' ),
		'items_list_navigation' => __( 'Case studies list navigation' ),
		'items_list'            => __( 'Case studies list' ),
	);

	$args = array(
		'label'        => __( 'Case Studies' ),
		'labels'       => $labels,
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'menu_position'=> 21,
		'menu_icon'    => 'dashicons-media-document',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
		'rewrite'      => array( 'slug' => 'case-studies' ),
	);

	register_post_type( 'case-studies', $args );
}
add_action( 'init', 'ninjatheme_register_case_studies_cpt' );

/**
 * Add performance optimizations
 */
function ninjatheme_performance_optimizations() {
	// Disable emoji scripts and styles (if not needed)
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	
	// Disable embeds (if not needed)
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	
	// Remove unnecessary header links
	remove_action( 'wp_head', 'rsd_link' ); // Remove RSD link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Remove Windows Live Writer link
	remove_action( 'wp_head', 'wp_generator' ); // Remove WordPress version
	remove_action( 'wp_head', 'wp_shortlink_wp_head' ); // Remove shortlink
}
add_action( 'init', 'ninjatheme_performance_optimizations' );

// Resource hints are registered in the PERFORMANCE OPTIMIZATIONS section below.

/**
 * Add preload hints in header
 */
// Resource hints (preconnect/dns-prefetch) are handled via ninjatheme_resource_hints()
// using the wp_resource_hints filter. No manual echo needed here.

// Script defer is handled by wp_script_add_data() in ninjatheme_scripts()
// and by ninjatheme_script_defer_fallback() for WP < 6.3.

/**
 * Auto-display icons in mega menu based on menu item titles
 */
function ninjatheme_add_menu_icons( $item_output, $item, $depth, $args ) {
	// Only add icons to first-level items (categories) in mega menu
	if ( $depth === 1 ) {
		// Icon mapping: menu title => attachment ID
		$icon_map = array(
			'Instructional Design Services' => wp_get_attachment_url( 271 ),
			'Custom eLearning Development'  => wp_get_attachment_url( 269 ),
			'LMS Support Services'           => wp_get_attachment_url( 270 ),
		);

		// Check if this menu item has an icon mapped
		if ( isset( $icon_map[ $item->title ] ) ) {
			$icon_path = $icon_map[ $item->title ];
			$icon_html = '<span class="mega-menu-icon"><img src="' . esc_url( $icon_path ) . '" alt=""></span>';

			// Add icon before the link text
			$item_output = str_replace(
				$args->link_before . $item->title,
				$icon_html . $args->link_before . $item->title,
				$item_output
			);
		}
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'ninjatheme_add_menu_icons', 10, 4 );

/**
 * Add mega menu class to menu items with children
 */
function ninjatheme_add_mega_menu_class( $classes, $item, $args ) {
	// Check if this item has the custom class 'mega-menu'
	if ( in_array( 'mega-menu', $classes ) ) {
		$classes[] = 'has-mega-menu';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'ninjatheme_add_mega_menu_class', 10, 3 );

/**
 * Gradient text shortcode - creates animated gradient text effect
 * Usage: [gradient]Your text here[/gradient]
 *
 * @param array $atts Shortcode attributes
 * @param string $content Text content to apply gradient to
 * @return string HTML output with gradient effect
 */
function ninjatheme_gradient_shortcode( $atts, $content = null ) {
	if ( empty( $content ) ) {
		return '';
	}

	return '<i>' . do_shortcode( trim( $content ) ) . '</i>';
}
add_shortcode( 'gradient', 'ninjatheme_gradient_shortcode' );

/**
 * Gradient text helper function for PHP templates
 * Usage: echo ninjatheme_gradient_text( 'Your text here' );
 *
 * @param string $text Text to apply gradient effect to
 * @return string HTML output with gradient effect
 */
function ninjatheme_gradient_text( $text ) {
	if ( empty( $text ) ) {
		return '';
	}

	return '<i>' . esc_html( $text ) . '</i>';
}
//Sort by last modified
// Add Last Modified column to Posts and Pages
function add_last_modified_column($columns) {
    $columns['last_modified'] = 'Last Modified';
    return $columns;
}

add_filter('manage_posts_columns', 'add_last_modified_column');
add_filter('manage_pages_columns', 'add_last_modified_column');

// Show the value
function show_last_modified_column($column_name, $post_id) {
    if ($column_name === 'last_modified') {
        echo get_the_modified_date('Y/m/d g:i a', $post_id);
    }
}

add_action('manage_posts_custom_column', 'show_last_modified_column', 10, 2);
add_action('manage_pages_custom_column', 'show_last_modified_column', 10, 2);

// Make column sortable
function sortable_last_modified_column($columns) {
    $columns['last_modified'] = 'modified';
    return $columns;
}

add_filter('manage_edit-post_sortable_columns', 'sortable_last_modified_column');

/**
 * =========================================================================
 * PERFORMANCE OPTIMIZATIONS
 * =========================================================================
 */

/**
 * Resource hints: preconnect to external origins used by the theme.
 * Reduces DNS + TCP + TLS handshake time for CDN and third-party scripts.
 */
function ninjatheme_resource_hints( $hints, $relation_type ) {
	// Note: Bulma is now self-hosted — no CDN preconnect needed for it.
	if ( 'preconnect' === $relation_type ) {
		// Google Fonts — reduces font CSS + woff2 handshake time
		$hints[] = array( 'href' => 'https://fonts.googleapis.com', 'crossorigin' => 'anonymous' );
		$hints[] = array( 'href' => 'https://fonts.gstatic.com',    'crossorigin' => 'anonymous' );
		// HubSpot (used by hubspot-form block)
		$hints[] = array( 'href' => 'https://js.hsforms.net' );
		$hints[] = array( 'href' => 'https://forms.hsforms.com' );
		// YouTube / Vimeo embeds
		$hints[] = array( 'href' => 'https://www.youtube-nocookie.com' );
		$hints[] = array( 'href' => 'https://player.vimeo.com' );
	}

	if ( 'dns-prefetch' === $relation_type ) {
		$hints[] = array( 'href' => '//js.hsforms.net' );
	}

	return $hints;
}
add_filter( 'wp_resource_hints', 'ninjatheme_resource_hints', 10, 2 );

/**
 * Additional <head> cleanup not covered by ninjatheme_performance_optimizations():
 * - REST API link headers (privacy + payload size)
 * - Shortlink tag
 */
function ninjatheme_clean_head() {
	// REST API link header (not needed on frontend)
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	// Shortlink (e.g. ?p=123)
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'ninjatheme_clean_head' );

/**
 * Add loading="lazy" to all iframes output via the_content.
 * WordPress 5.5+ handles <img> natively; this covers <iframe> embeds
 * (YouTube, Vimeo, HubSpot, etc.).
 */
function ninjatheme_lazy_load_iframes( $content ) {
	if ( is_admin() || is_feed() || empty( $content ) ) {
		return $content;
	}

	return preg_replace_callback(
		'/<iframe([^>]*)>/i',
		function ( $matches ) {
			$attrs = $matches[1];
			if ( strpos( $attrs, 'loading=' ) !== false ) {
				return $matches[0];
			}
			return '<iframe' . $attrs . ' loading="lazy">';
		},
		$content
	);
}
add_filter( 'the_content', 'ninjatheme_lazy_load_iframes', 99 );
add_filter( 'widget_text', 'ninjatheme_lazy_load_iframes', 99 );

/**
 * Add defer attribute support for wp_enqueue_script.
 * WordPress core handles 'defer' via wp_script_add_data() since WP 6.3+;
 * this filter provides a safe fallback for earlier versions.
 */
function ninjatheme_script_defer_fallback( $tag, $handle, $src ) {
	// WP 6.3+ already handles defer natively via wp_script_add_data
	if ( version_compare( $GLOBALS['wp_version'], '6.3', '>=' ) ) {
		return $tag;
	}

	// Skip admin
	if ( is_admin() ) {
		return $tag;
	}

	// Handles that should NOT be deferred (critical / inline-dep scripts)
	$no_defer = array( 'jquery-core', 'jquery-migrate', 'comment-reply' );
	if ( in_array( $handle, $no_defer, true ) ) {
		return $tag;
	}

	// Only defer NinjaTheme scripts
	if ( strpos( $handle, 'ninjatheme-' ) === 0 ) {
		if ( strpos( $tag, ' defer' ) === false && strpos( $tag, ' async' ) === false ) {
			$tag = str_replace( '<script ', '<script defer ', $tag );
		}
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'ninjatheme_script_defer_fallback', 10, 3 );

/**
 * Async CSS loading — converts render-blocking stylesheets to non-blocking.
 *
 * Uses the media="print" trick: the browser fetches the file immediately
 * (not render-blocking) and the onload handler switches it to media="all".
 * A <noscript> fallback covers JS-disabled environments.
 *
 * Applied to: bulma-css, ninjatheme-style.
 * The theme CSS is the last paint-critical file, so we preload it as well
 * to ensure the browser discovers it at the earliest opportunity.
 */
/**
 * LCP Request Discovery — emite <link rel="preload"> para la imagen hero
 * en <head>, antes de que el contenido se renderice.
 *
 * Usa parse_blocks() para leer los atributos del bloque ACF directamente
 * del post_content, sin depender del contexto de bloque. Fallback a
 * get_post_meta() para versiones antiguas de ACF Pro.
 */
function ninjatheme_preload_lcp_image() {
	if ( is_admin() || ! is_singular() ) {
		return;
	}

	$post = get_post();
	if ( ! $post || empty( $post->post_content ) ) {
		return;
	}

	$lcp_blocks    = array( 'acf/main-hero', 'acf/page-heading' );
	$attachment_id = 0;

	// Método primario: parse_blocks() lee los datos del bloque ACF desde el
	// post_content serializado. ACF Pro 6+ guarda los fields en attrs['data'].
	$blocks = parse_blocks( $post->post_content );

	foreach ( $blocks as $block ) {
		if ( ! in_array( $block['blockName'], $lcp_blocks, true ) ) {
			continue;
		}

		// ACF Pro 6.x: datos en attrs['data']
		$block_data = $block['attrs']['data'] ?? array();
		if ( ! empty( $block_data['image'] ) && is_numeric( $block_data['image'] ) ) {
			$attachment_id = (int) $block_data['image'];
			break;
		}

		// ACF Pro < 6: datos solo en post meta — buscar por la key del bloque
		$block_id = $block['attrs']['id'] ?? '';
		if ( $block_id ) {
			$meta_image = get_post_meta( $post->ID, $block_id . '_image', true );
			if ( $meta_image && is_numeric( $meta_image ) ) {
				$attachment_id = (int) $meta_image;
				break;
			}
		}

		// Fallback final: get_field() estándar (funciona para la mayoría de casos)
		if ( ! $attachment_id && function_exists( 'get_field' ) ) {
			$image = get_post_meta( $post->ID, 'image', true );
			if ( $image && is_numeric( $image ) ) {
				$attachment_id = (int) $image;
				break;
			}
		}
	}

	if ( ! $attachment_id ) {
		return;
	}

	$image_url = wp_get_attachment_image_url( $attachment_id, 'large' );
	if ( ! $image_url ) {
		return;
	}

	$image_srcset = wp_get_attachment_image_srcset( $attachment_id, 'large' );
	$image_sizes  = wp_get_attachment_image_sizes( $attachment_id, 'large' );

	$srcset_attr = $image_srcset ? ' imagesrcset="' . esc_attr( $image_srcset ) . '"' : '';
	$sizes_attr  = $image_sizes  ? ' imagesizes="' . esc_attr( $image_sizes ) . '"' : '';

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high"%s%s>' . "\n",
		esc_url( $image_url ),
		$srcset_attr,
		$sizes_attr
	);
}
add_action( 'wp_head', 'ninjatheme_preload_lcp_image', 1 );

add_filter('manage_edit-page_sortable_columns', 'sortable_last_modified_column');
