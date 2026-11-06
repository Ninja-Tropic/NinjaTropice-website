<?php
/**
 * Block Name: Content Grid
 *
 * Filterable grid of blog posts, projects or case studies with AJAX load more.
 * Fields: content_type, title, subtitle, per_page, orderby, order, show_search,
 * show_filters, include_categories, exclude_posts, exclude_categories, exclude_tags.
 *
 * @package NinjaTheme
 */

$id = 'content-grid-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = 'pf pf--cg';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$config = array(
	'type'               => get_field( 'content_type' ) ?: 'case-studies',
	'per_page'           => get_field( 'per_page' ) ?: 9,
	'orderby'            => get_field( 'orderby' ) ?: 'date',
	'order'              => get_field( 'order' ) ?: 'DESC',
	'show_search'        => null === get_field( 'show_search' ) ? true : (bool) get_field( 'show_search' ),
	'show_filters'       => null === get_field( 'show_filters' ) ? true : (bool) get_field( 'show_filters' ),
	'include_categories' => get_field( 'include_categories' ) ?: array(),
	'exclude_posts'      => get_field( 'exclude_posts' ) ?: array(),
	'exclude_categories' => get_field( 'exclude_categories' ) ?: array(),
	'exclude_tags'       => get_field( 'exclude_tags' ) ?: array(),
);

// Relationship / taxonomy fields can return objects when the format is changed; keep IDs only.
foreach ( array( 'include_categories', 'exclude_posts', 'exclude_categories', 'exclude_tags' ) as $key ) {
	$config[ $key ] = array_map(
		function ( $item ) {
			return is_object( $item ) ? ( $item->ID ?? $item->term_id ?? 0 ) : $item;
		},
		(array) $config[ $key ]
	);
}

echo ninjatheme_cg_render(
	$config,
	(string) get_field( 'title' ),
	(string) get_field( 'subtitle' ),
	$id,
	$class_name,
	! empty( $is_preview )
);
