<?php
/**
 * [blog_grid] shortcode
 *
 * Renders the first page of published blog posts with a stacked-axis filter UI.
 * Filter axes: Category and Tag.
 * Reuses .pf__ CSS classes and HTML structure from [projects_grid].
 *
 * Usage: [blog_grid]
 *        [blog_grid per_page="9" orderby="date" order="DESC"]
 */

// ── Asset registration ────────────────────────────────────────────────────────

function ninjatheme_register_blog_filter_assets() {
	$js_path = get_stylesheet_directory() . '/js/modules/blog-filter.js';
	if ( file_exists( $js_path ) ) {
		wp_register_script(
			'ninjatheme-blog-filter',
			get_stylesheet_directory_uri() . '/js/modules/blog-filter.js',
			array(),
			filemtime( $js_path ),
			true
		);
		wp_script_add_data( 'ninjatheme-blog-filter', 'defer', true );
	}
}
add_action( 'wp_enqueue_scripts', 'ninjatheme_register_blog_filter_assets' );

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Get unique filter values for category and tag axes.
 *
 * @return array { category: string[], tag: string[] }
 */
function ninjatheme_get_blog_filter_values() {
	global $wpdb;

	$ids_sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish'";

	$result = array();

	foreach ( array( 'category', 'post_tag' ) as $taxonomy ) {
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$values = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT t.name
				 FROM {$wpdb->terms} t
				 INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
				 INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
				 WHERE tt.taxonomy = %s
				   AND t.slug != 'uncategorized'
				   AND tr.object_id IN ($ids_sql)
				 ORDER BY t.name ASC",
				$taxonomy
			)
		);

		$key            = $taxonomy === 'post_tag' ? 'tag' : 'category';
		$result[ $key ] = $values ?: array();
	}

	return $result;
}

/**
 * Get compact filter metadata for all published posts.
 * Passed to the client so JS can compute cascading pill availability.
 *
 * @return array[] Each element: { cats: string[], tags: string[] }
 */
function ninjatheme_get_all_blog_filter_data_for_js() {
	global $wpdb;

	$ids_sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish'";

	$posts_data = array();

	foreach ( array( 'category', 'post_tag' ) as $taxonomy ) {
		$key = $taxonomy === 'post_tag' ? 'tags' : 'cats';

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT tr.object_id AS post_id, t.name AS term_name
				 FROM {$wpdb->terms} t
				 INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
				 INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
				 WHERE tt.taxonomy = %s
				   AND t.slug != 'uncategorized'
				   AND tr.object_id IN ($ids_sql)",
				$taxonomy
			)
		);

		foreach ( $rows as $row ) {
			$id = (int) $row->post_id;
			if ( ! isset( $posts_data[ $id ] ) ) {
				$posts_data[ $id ] = array( 'cats' => array(), 'tags' => array() );
			}
			$posts_data[ $id ][ $key ][] = $row->term_name;
		}
	}

	return array_values( $posts_data );
}

/**
 * Render a single blog post card (shared by shortcode + AJAX handler).
 *
 * @param int $post_id
 * @return string HTML
 */
function ninjatheme_render_blog_card( $post_id ) {
	$title     = get_the_title( $post_id );
	$permalink = get_permalink( $post_id );

	// All non-uncategorized categories.
	$tags       = array();
	$categories = get_the_category( $post_id );
	if ( $categories ) {
		foreach ( $categories as $cat ) {
			if ( 'uncategorized' !== $cat->slug ) {
				$tags[] = $cat->name;
			}
		}
	}

	// Post tags (up to 2 extras to keep the row compact).
	$post_tags = get_the_tags( $post_id );
	if ( $post_tags ) {
		$count = 0;
		foreach ( $post_tags as $tag ) {
			if ( $count >= 2 ) break;
			$tags[] = $tag->name;
			$count++;
		}
	}

	// Featured image.
	$thumbnail = has_post_thumbnail( $post_id )
		? get_the_post_thumbnail(
			$post_id,
			'large',
			array(
				'class'   => 'blog-archive__image',
				'loading' => 'lazy',
				'alt'     => esc_attr( $title ),
			)
		)
		: '';

	ob_start();
	?>
	<article class="blog-archive__item" id="post-<?php echo esc_attr( $post_id ); ?>">

		<a class="blog-archive__link" href="<?php echo esc_url( $permalink ); ?>">

			<div class="blog-archive__media">
				<?php if ( $thumbnail ) : ?>
					<?php echo $thumbnail; // already escaped via WP functions ?>
				<?php else : ?>
					<div class="blog-archive__image-placeholder"></div>
				<?php endif; ?>
			</div>

			<div class="blog-archive__body-inner">
				<h2 class="blog-archive__item-title"><?php echo esc_html( $title ); ?></h2>

				<?php if ( $tags ) : ?>
				<div class="blog-archive__tags">
					<?php foreach ( $tags as $tag_name ) : ?>
					<span class="blog-archive__tag"><?php echo esc_html( $tag_name ); ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<span class="blog-archive__cta">
					Read article
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
						<path d="M1 7h12M8 2l5 5-5 5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
			</div>
		</a>
	</article>
	<?php
	return ob_get_clean();
}

// ── AJAX handler ──────────────────────────────────────────────────────────────

function ninjatheme_blog_filter_ajax() {
	check_ajax_referer( 'ninjatheme_blog_filter', 'nonce' );

	$page     = max( 1, intval( $_POST['page'] ?? 1 ) );
	$per_page = max( 1, intval( $_POST['per_page'] ?? 9 ) );
	$orderby  = sanitize_key( $_POST['orderby'] ?? 'date' );
	$order    = in_array( strtoupper( $_POST['order'] ?? 'DESC' ), array( 'ASC', 'DESC' ), true )
		? strtoupper( $_POST['order'] )
		: 'DESC';

	$f_category = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $_POST['category'] ?? array() ) ) ) );
	$f_tag      = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $_POST['tag']      ?? array() ) ) ) );

	$query_args = array(
		'post_type'      => 'post',
		'posts_per_page' => $per_page,
		'paged'          => $page,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);

	$tax_query = array( 'relation' => 'AND' );

	if ( ! empty( $f_category ) ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'name',
			'terms'    => $f_category,
			'operator' => 'IN',
		);
	}
	if ( ! empty( $f_tag ) ) {
		$tax_query[] = array(
			'taxonomy' => 'post_tag',
			'field'    => 'name',
			'terms'    => $f_tag,
			'operator' => 'IN',
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$query_args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $query_args );

	$html = '';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= ninjatheme_render_blog_card( get_the_ID() );
		}
		wp_reset_postdata();
	}

	wp_send_json_success( array(
		'html'     => $html,
		'has_more' => $page < $query->max_num_pages,
		'total'    => $query->found_posts,
		'page'     => $page,
	) );
}
add_action( 'wp_ajax_ninjatheme_blog_filter',        'ninjatheme_blog_filter_ajax' );
add_action( 'wp_ajax_nopriv_ninjatheme_blog_filter', 'ninjatheme_blog_filter_ajax' );

// ── Shortcode ─────────────────────────────────────────────────────────────────

function ninjatheme_blog_grid_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'per_page' => 9,
			'orderby'  => 'date',
			'order'    => 'DESC',
			'category' => '',
			'tag'      => '',
			'filter'   => 'true',
		),
		$atts,
		'blog_grid'
	);

	$per_page          = max( 1, intval( $atts['per_page'] ) );
	$orderby           = sanitize_key( $atts['orderby'] );
	$order             = in_array( strtoupper( $atts['order'] ), array( 'ASC', 'DESC' ), true )
		? strtoupper( $atts['order'] )
		: 'DESC';
	$default_categories = array_values( array_filter( array_map( 'sanitize_text_field', explode( ',', $atts['category'] ) ) ) );
	$default_tags       = array_values( array_filter( array_map( 'sanitize_text_field', explode( ',', $atts['tag'] ) ) ) );
	$show_filter        = filter_var( $atts['filter'], FILTER_VALIDATE_BOOLEAN );

	$filter_values  = $show_filter ? ninjatheme_get_blog_filter_values() : array( 'category' => array(), 'tag' => array() );
	$all_posts_data = $show_filter ? ninjatheme_get_all_blog_filter_data_for_js() : array();

	// First page only — subsequent pages load via AJAX.
	$initial_query_args = array(
		'post_type'      => 'post',
		'posts_per_page' => $per_page,
		'paged'          => 1,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);

	$tax_query = array( 'relation' => 'AND' );
	if ( ! empty( $default_categories ) ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => $default_categories,
			'operator' => 'IN',
		);
	}
	if ( ! empty( $default_tags ) ) {
		$tax_query[] = array(
			'taxonomy' => 'post_tag',
			'field'    => 'slug',
			'terms'    => $default_tags,
			'operator' => 'IN',
		);
	}
	if ( count( $tax_query ) > 1 ) {
		$initial_query_args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $initial_query_args );

	$total    = $query->found_posts;
	$has_more = 1 < $query->max_num_pages;

	$initial_html = '';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$initial_html .= ninjatheme_render_blog_card( get_the_ID() );
		}
		wp_reset_postdata();
	}

	// Resolve slugs → display names so JS chips and AJAX use the human-readable label.
	$default_categories_names = array();
	foreach ( $default_categories as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			$default_categories_names[] = $term->name;
		}
	}
	$default_tags_names = array();
	foreach ( $default_tags as $slug ) {
		$term = get_term_by( 'slug', $slug, 'post_tag' );
		if ( $term && ! is_wp_error( $term ) ) {
			$default_tags_names[] = $term->name;
		}
	}

	wp_enqueue_script( 'ninjatheme-blog-filter' );
	wp_localize_script( 'ninjatheme-blog-filter', 'bfData', array(
		'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
		'nonce'           => wp_create_nonce( 'ninjatheme_blog_filter' ),
		'perPage'         => $per_page,
		'orderby'         => $orderby,
		'order'           => $order,
		'total'           => $total,
		'hasMore'         => $has_more,
		'posts'           => $all_posts_data,
		'defaultCategory' => $default_categories_names,
		'defaultTag'      => $default_tags_names,
	) );

	// Build filter axes (only those that have values).
	$axes = array_filter( array(
		array(
			'label'  => 'Category',
			'key'    => 'category',
			'param'  => 'category',
			'values' => $filter_values['category'],
		),
		array(
			'label'  => 'Topic',
			'key'    => 'tag',
			'param'  => 'tag',
			'values' => $filter_values['tag'],
		),
	), fn( $a ) => ! empty( $a['values'] ) );

	ob_start();
	?>
	<div class="pf pf--wrap-pills" id="blog-filter-wrap">

		<?php if ( $show_filter ) : ?>
		<!-- Sticky bar — appears when .pf__axes scrolls out of viewport -->
		<div class="pf__sticky-bar" id="bf-sticky-bar" aria-label="<?php esc_attr_e( 'Active filters' ); ?>">
			<div class="pf__sticky-inner">
				<?php foreach ( $axes as $axis ) : ?>
				<div class="pf__sd" data-axis="<?php echo esc_attr( $axis['key'] ); ?>" data-param="<?php echo esc_attr( $axis['param'] ); ?>">
					<button class="pf__sd-trigger" type="button">
						<span class="pf__sd-label"><?php echo esc_html( $axis['label'] ); ?></span>
						<span class="pf__sd-count" hidden></span>
						<svg class="pf__sd-chevron" width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
							<path d="M1.5 3.5L5.5 7.5L9.5 3.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<div class="pf__sd-panel" hidden>
						<ul class="pf__sd-list" role="listbox" aria-multiselectable="true" aria-label="<?php echo esc_attr( 'Filter by ' . $axis['label'] ); ?>">
							<?php foreach ( $axis['values'] as $val ) : ?>
							<li class="pf__sd-item"
								data-value="<?php echo esc_attr( $val ); ?>"
								role="option"
								aria-selected="false">
								<span class="pf__sd-check" aria-hidden="true"></span>
								<span class="pf__sd-item-label"><?php echo esc_html( $val ); ?></span>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endforeach; ?>

				<button class="pf__sticky-clear" id="bf-sticky-clear" type="button" hidden>
					<?php esc_html_e( 'Clear all' ); ?>
				</button>
			</div>
		</div>

		<?php if ( ! empty( $axes ) ) : ?>
		<div class="pf__axes" id="bf-axes-panel" role="group" aria-label="<?php esc_attr_e( 'Filter articles' ); ?>">
			<?php foreach ( $axes as $axis ) : ?>
			<div class="pf__axis">
				<span class="pf__axis-label"><?php echo esc_html( $axis['label'] ); ?></span>
				<div class="pf__axis-track">

					<button class="pf__arrow pf__arrow--prev" type="button" aria-label="<?php esc_attr_e( 'Scroll left' ); ?>">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
							<path d="M10 3.5L5.5 8l4.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>

					<div class="pf__pills-wrap">
						<div class="pf__pills" role="group" aria-label="<?php echo esc_attr( 'Filter by ' . $axis['label'] ); ?>">

							<button
								class="pf__pill pf__pill--active"
								data-axis="<?php echo esc_attr( $axis['key'] ); ?>"
								data-param="<?php echo esc_attr( $axis['param'] ); ?>"
								data-value=""
								aria-pressed="true"
								type="button">All</button>

							<?php foreach ( $axis['values'] as $val ) : ?>
							<button
								class="pf__pill"
								data-axis="<?php echo esc_attr( $axis['key'] ); ?>"
								data-param="<?php echo esc_attr( $axis['param'] ); ?>"
								data-value="<?php echo esc_attr( $val ); ?>"
								aria-pressed="false"
								type="button"><?php echo esc_html( $val ); ?></button>
							<?php endforeach; ?>

						</div>
					</div>

					<button class="pf__arrow pf__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Scroll right' ); ?>">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
							<path d="M6 3.5L10.5 8 6 12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>

				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php endif; // $show_filter ?>

		<div class="pf__meta-bar"<?php echo ! $show_filter ? ' hidden' : ''; ?>>
			<div class="pf__active" id="bf-active-chips" aria-live="polite" hidden></div>
			<p class="pf__count" id="bf-count" aria-live="polite"></p>
		</div>

		<div class="blog-archive__grid pf__grid" id="bf-grid">
			<?php echo $initial_html; ?>
		</div>

		<p class="blog-archive__empty pf__empty" id="bf-empty" hidden>
			<?php esc_html_e( 'No articles match the selected filters.' ); ?>
		</p>

		<div class="pf__load-more-wrap" id="bf-load-more-wrap"<?php echo ! $has_more ? ' hidden' : ''; ?>>
			<button class="pf__load-more" id="bf-load-more" type="button">
				<span class="pf__load-more-label">Load more articles</span>
				<svg class="pf__spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" stroke-dasharray="42 15" stroke-linecap="round"/>
				</svg>
			</button>
		</div>

	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'blog_grid', 'ninjatheme_blog_grid_shortcode' );
