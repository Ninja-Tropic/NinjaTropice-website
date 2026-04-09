<?php
/**
 * Performance utilities and optimizations
 *
 * @package NinjaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enable object caching support
 */
function ninjatheme_object_cache_support() {
	if ( ! wp_using_ext_object_cache() ) {
		// Object cache not available, use transients
		return;
	}
	
	// Object cache is available, use it for better performance
	// This is handled automatically by WordPress when object cache is installed
}

/**
 * Optimize database queries
 */
function ninjatheme_optimize_queries() {
	// Limit post revisions
	if ( ! defined( 'WP_POST_REVISIONS' ) ) {
		define( 'WP_POST_REVISIONS', 3 );
	}
	
	// Enable query caching
	if ( ! defined( 'WP_CACHE' ) ) {
		// WP_CACHE should be set in wp-config.php
	}
}

/**
 * Add browser caching headers (if not using a caching plugin)
 */
function ninjatheme_browser_caching_headers() {
	if ( ! is_admin() && ! headers_sent() ) {
		// Cache static assets for 1 year
		if ( preg_match( '/\.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)(\?.*)?$/i', $_SERVER['REQUEST_URI'] ) ) {
			header( 'Cache-Control: public, max-age=31536000, immutable' );
		}
	}
}
// Uncomment if not using a caching plugin
// add_action( 'send_headers', 'ninjatheme_browser_caching_headers' );

/**
 * Defer parsing of JavaScript
 */
function ninjatheme_defer_parsing_js( $tag, $handle, $src ) {
	// Skip defer for critical scripts
	$defer_exceptions = array( 'jquery-core', 'jquery-migrate' );
	
	if ( in_array( $handle, $defer_exceptions ) ) {
		return $tag;
	}
	
	// Add defer to non-critical scripts
	if ( strpos( $tag, 'defer' ) === false && strpos( $tag, 'async' ) === false ) {
		$tag = str_replace( ' src', ' defer src', $tag );
	}
	
	return $tag;
}
// Note: This is handled by wp_script_add_data in functions.php for main.js

/**
 * Lazy load images (native WordPress 5.5+ handles this automatically).
 * This function is kept as a reference but is NOT hooked — WP core manages
 * loading="lazy" on all images via wp_lazy_loading_enabled filter.
 * iframe lazy loading is handled in functions.php via ninjatheme_lazy_load_iframes().
 */
function ninjatheme_lazy_load_images( $content ) {
	if ( is_admin() || is_feed() ) {
		return $content;
	}

	$content = preg_replace_callback(
		'/<img([^>]+?)>/i',
		function( $matches ) {
			$img = $matches[0];
			if ( strpos( $img, 'loading=' ) !== false ) {
				return $img;
			}
			if ( strpos( $img, 'data:image' ) !== false || strpos( $img, '.svg' ) !== false ) {
				return $img;
			}
			return str_replace( '<img', '<img loading="lazy"', $img );
		},
		$content
	);

	return $content;
}
// Not hooked: WP 5.5+ handles <img> natively. Use ninjatheme_lazy_load_iframes() for iframes.

/**
 * Remove query strings from static resources
 */
function ninjatheme_remove_query_strings( $src ) {
	if ( strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
// Note: This may break cache busting, use with caution
// add_filter( 'script_loader_src', 'ninjatheme_remove_query_strings', 15, 1 );
// add_filter( 'style_loader_src', 'ninjatheme_remove_query_strings', 15, 1 );
