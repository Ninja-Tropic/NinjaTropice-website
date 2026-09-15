<?php
/**
 * Plugin Name: NinjaTheme Anonymous Page Cache
 * Description: Small full-page cache for public GET requests.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ninjatheme_page_cache_is_eligible() {
	if ( is_admin() || 'GET' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return false;
	}

	if ( ! empty( $_GET ) || is_user_logged_in() ) {
		return false;
	}

	$uri = (string) ( $_SERVER['REQUEST_URI'] ?? '/' );
	return strpos( $uri, '/wp-admin/' ) !== 0
		&& strpos( $uri, '/wp-login.php' ) !== 0
		&& strpos( $uri, '/wp-json/' ) !== 0
		&& strpos( $uri, '/feed/' ) === false;
}

function ninjatheme_page_cache_file() {
	$host = strtolower( (string) ( $_SERVER['HTTP_HOST'] ?? 'localhost' ) );
	$uri  = (string) ( $_SERVER['REQUEST_URI'] ?? '/' );
	$key  = hash( 'sha256', $host . '|' . $uri );
	return WP_CONTENT_DIR . '/cache/ninjatheme/' . $key . '.html';
}

function ninjatheme_page_cache_boot() {
	if ( ! ninjatheme_page_cache_is_eligible() ) {
		return;
	}

	$file = ninjatheme_page_cache_file();
	$ttl  = 5 * MINUTE_IN_SECONDS;
	if ( is_readable( $file ) && ( time() - filemtime( $file ) ) < $ttl ) {
		header( 'X-NinjaTheme-Cache: HIT' );
		header( 'Cache-Control: public, max-age=60, stale-while-revalidate=300' );
		readfile( $file );
		exit;
	}

	header( 'X-NinjaTheme-Cache: MISS' );
	ob_start( function ( $html ) use ( $file ) {
		if ( http_response_code() === 200 && is_string( $html ) && stripos( $html, '<!DOCTYPE html' ) !== false ) {
			$dir = dirname( $file );
			if ( wp_mkdir_p( $dir ) ) {
				$tmp = $file . '.' . getmypid() . '.tmp';
				if ( false !== file_put_contents( $tmp, $html, LOCK_EX ) ) {
					rename( $tmp, $file );
				}
			}
		}
		return $html;
	} );
}
add_action( 'plugins_loaded', 'ninjatheme_page_cache_boot', -1000 );

function ninjatheme_page_cache_flush() {
	$files = glob( WP_CONTENT_DIR . '/cache/ninjatheme/*.html' );
	if ( ! is_array( $files ) ) {
		return;
	}
	foreach ( $files as $file ) {
		if ( is_file( $file ) ) {
			unlink( $file );
		}
	}
}
add_action( 'save_post', 'ninjatheme_page_cache_flush' );
add_action( 'deleted_post', 'ninjatheme_page_cache_flush' );
add_action( 'customize_save_after', 'ninjatheme_page_cache_flush' );
add_action( 'switch_theme', 'ninjatheme_page_cache_flush' );

