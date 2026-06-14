<?php
/**
 * [projects_grid] shortcode
 *
 * Renders the first page of published projects with a stacked-axis filter UI.
 * Subsequent pages and filter changes load via AJAX so the initial page only
 * runs a handful of DB queries regardless of total project count.
 *
 * Usage: [projects_grid]
 *        [projects_grid per_page="12" orderby="menu_order" order="ASC"]
 */

// ── Projects page URL helper ──────────────────────────────────────────────────

/**
 * Returns the permalink of the page that contains [projects_grid].
 * Result is cached in a transient for 24 hours so no extra DB hit on each page load.
 * Falls back to the CPT archive URL if no matching page is found.
 */
function ninjatheme_get_projects_page_url() {
	$page_id = get_transient( 'ninjatheme_projects_page_id' );

	if ( false === $page_id ) {
		global $wpdb;
		$page_id = (int) $wpdb->get_var(
			"SELECT ID FROM {$wpdb->posts}
			 WHERE post_status = 'publish'
			   AND post_type   = 'page'
			   AND post_content LIKE '%[projects_grid%'
			 LIMIT 1"
		);
		set_transient( 'ninjatheme_projects_page_id', $page_id, DAY_IN_SECONDS );
	}

	if ( $page_id ) {
		return get_permalink( $page_id );
	}

	return get_post_type_archive_link( 'project' ) ?: home_url( '/projects/' );
}

// Bust the transient whenever any page is saved, in case the shortcode moves.
add_action( 'save_post_page', function () {
	delete_transient( 'ninjatheme_projects_page_id' );
} );

// ── Asset registration ────────────────────────────────────────────────────────

/**
 * Register the JS (lazy — only enqueued when the shortcode fires on this page).
 * CSS lives in scss/components/_projects-filter.scss → compiled into style.css.
 */
function ninjatheme_register_projects_filter_assets() {
	$js_path = get_stylesheet_directory() . '/js/modules/projects-filter.js';
	if ( file_exists( $js_path ) ) {
		wp_register_script(
			'ninjatheme-projects-filter',
			get_stylesheet_directory_uri() . '/js/modules/projects-filter.js',
			array(),
			filemtime( $js_path ),
			true
		);
		wp_script_add_data( 'ninjatheme-projects-filter', 'defer', true );
	}
}
add_action( 'wp_enqueue_scripts', 'ninjatheme_register_projects_filter_assets' );

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Get unique filter values for all four axes using direct DB queries.
 * 4 queries total — avoids calling get_field() × 170+ projects.
 *
 * @return array { industry: string[], animation_style: string[], art_style: string[], category: string[] }
 */
function ninjatheme_get_project_filter_values() {
	global $wpdb;

	$ids_sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'project' AND post_status = 'publish'";

	$meta_axes = array(
		'industry'        => 'project_industry',
		'animation_style' => 'project_animation_style',
		'art_style'       => 'project_art_style',
	);

	$result = array();
	foreach ( $meta_axes as $key => $meta_key ) {
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$values = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT meta_value
				 FROM {$wpdb->postmeta}
				 WHERE meta_key = %s
				   AND meta_value != ''
				   AND post_id IN ($ids_sql)
				 ORDER BY meta_value ASC",
				$meta_key
			)
		);
		$result[ $key ] = $values ?: array();
	}

	// Categories (taxonomy) — excludes "Uncategorized".
	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	$result['category'] = $wpdb->get_col(
		"SELECT DISTINCT t.name
		 FROM {$wpdb->terms} t
		 INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
		 INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
		 WHERE tt.taxonomy = 'category'
		   AND t.slug != 'uncategorized'
		   AND tr.object_id IN ($ids_sql)
		 ORDER BY t.name ASC"
	) ?: array();

	return $result;
}

/**
 * Get compact filter metadata for all published projects.
 * Passed to the client so JS can compute cascading pill availability without extra AJAX.
 *
 * @return array[] Each element: { industry, animStyle, artStyle, cats[] }
 */
function ninjatheme_get_all_projects_filter_data_for_js() {
	global $wpdb;

	$ids_sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'project' AND post_status = 'publish'";

	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	$meta_rows = $wpdb->get_results(
		"SELECT post_id, meta_key, meta_value
		 FROM {$wpdb->postmeta}
		 WHERE meta_key IN ('project_industry', 'project_animation_style', 'project_art_style')
		   AND meta_value != ''
		   AND post_id IN ($ids_sql)"
	);

	$projects = array();
	foreach ( $meta_rows as $row ) {
		$id = (int) $row->post_id;
		if ( ! isset( $projects[ $id ] ) ) {
			$projects[ $id ] = array( 'industry' => '', 'animStyle' => '', 'artStyle' => '', 'cats' => array() );
		}
		switch ( $row->meta_key ) {
			case 'project_industry':        $projects[ $id ]['industry']  = $row->meta_value; break;
			case 'project_animation_style': $projects[ $id ]['animStyle'] = $row->meta_value; break;
			case 'project_art_style':       $projects[ $id ]['artStyle']  = $row->meta_value; break;
		}
	}

	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	$cat_rows = $wpdb->get_results(
		"SELECT tr.object_id AS post_id, t.name AS cat_name
		 FROM {$wpdb->terms} t
		 INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
		 INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
		 WHERE tt.taxonomy = 'category'
		   AND t.slug != 'uncategorized'
		   AND tr.object_id IN ($ids_sql)"
	);

	foreach ( $cat_rows as $row ) {
		$id = (int) $row->post_id;
		if ( ! isset( $projects[ $id ] ) ) {
			$projects[ $id ] = array( 'industry' => '', 'animStyle' => '', 'artStyle' => '', 'cats' => array() );
		}
		$projects[ $id ]['cats'][] = $row->cat_name;
	}

	return array_values( $projects );
}

/**
 * Render a single project card (shared by shortcode + AJAX handler).
 *
 * @param int $post_id
 * @return string HTML
 */
function ninjatheme_render_project_card( $post_id ) {
	$industry   = get_field( 'project_industry', $post_id );
	$anim_style = get_field( 'project_animation_style', $post_id );
	$art_style  = get_field( 'project_art_style', $post_id );
	$title      = get_the_title( $post_id );
	$permalink  = get_permalink( $post_id );

	$thumbnail = has_post_thumbnail( $post_id )
		? get_the_post_thumbnail(
			$post_id,
			'large',
			array(
				'class'   => 'project-archive__image',
				'loading' => 'lazy',
				'alt'     => esc_attr( $title ),
			)
		)
		: '';

	ob_start();
	?>
	<article
		class="project-archive__item"
		id="post-<?php echo esc_attr( $post_id ); ?>"
		data-industry="<?php echo esc_attr( $industry ); ?>"
		data-animation-style="<?php echo esc_attr( $anim_style ); ?>"
		data-art-style="<?php echo esc_attr( $art_style ); ?>">

		<a class="project-archive__link" href="<?php echo esc_url( $permalink ); ?>">
			<div class="project-archive__media">
				<?php if ( $thumbnail ) : ?>
					<?php echo $thumbnail; // already escaped via WP functions ?>
				<?php else : ?>
					<div class="project-archive__image-placeholder"></div>
				<?php endif; ?>
			</div>

			<div class="project-archive__body-inner">
				<h2 class="project-archive__item-title"><?php echo esc_html( $title ); ?></h2>

				<?php if ( $industry || $anim_style || $art_style ) : ?>
				<div class="project-archive__tags">
					<?php if ( $industry ) : ?>
					<span class="project-archive__tag"><?php echo esc_html( $industry ); ?></span>
					<?php endif; ?>
					<?php if ( $anim_style ) : ?>
					<span class="project-archive__tag"><?php echo esc_html( $anim_style ); ?></span>
					<?php endif; ?>
					<?php if ( $art_style ) : ?>
					<span class="project-archive__tag"><?php echo esc_html( $art_style ); ?></span>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<span class="project-archive__cta">
					Watch project
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

/**
 * Returns a JSON payload with the next page of project cards.
 * Called by the JS filter when pills change or "Load More" is clicked.
 */
function ninjatheme_projects_filter_ajax() {
	check_ajax_referer( 'ninjatheme_projects_filter', 'nonce' );

	$page     = max( 1, intval( $_POST['page'] ?? 1 ) );
	$per_page = max( 1, intval( $_POST['per_page'] ?? 12 ) );
	$orderby  = sanitize_key( $_POST['orderby'] ?? 'menu_order' );
	$order    = in_array( strtoupper( $_POST['order'] ?? 'ASC' ), array( 'ASC', 'DESC' ), true )
		? strtoupper( $_POST['order'] )
		: 'ASC';

	// Sanitize multi-value filter params (each axis accepts an array of values).
	$f_industry   = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $_POST['industry']        ?? array() ) ) ) );
	$f_anim_style = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $_POST['animation_style'] ?? array() ) ) ) );
	$f_art_style  = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $_POST['art_style']       ?? array() ) ) ) );
	$f_category   = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $_POST['category']        ?? array() ) ) ) );

	$meta_query = array( 'relation' => 'AND' );

	if ( ! empty( $f_industry ) ) {
		$meta_query[] = array( 'key' => 'project_industry', 'value' => $f_industry, 'compare' => 'IN' );
	}
	if ( ! empty( $f_anim_style ) ) {
		$meta_query[] = array( 'key' => 'project_animation_style', 'value' => $f_anim_style, 'compare' => 'IN' );
	}
	if ( ! empty( $f_art_style ) ) {
		$meta_query[] = array( 'key' => 'project_art_style', 'value' => $f_art_style, 'compare' => 'IN' );
	}

	$query_args = array(
		'post_type'      => 'project',
		'posts_per_page' => $per_page,
		'paged'          => $page,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);

	if ( count( $meta_query ) > 1 ) {
		$query_args['meta_query'] = $meta_query;
	}

	if ( ! empty( $f_category ) ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'name',
				'terms'    => $f_category,
				'operator' => 'IN',
			),
		);
	}

	$query = new WP_Query( $query_args );

	$html = '';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= ninjatheme_render_project_card( get_the_ID() );
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
add_action( 'wp_ajax_ninjatheme_projects_filter',        'ninjatheme_projects_filter_ajax' );
add_action( 'wp_ajax_nopriv_ninjatheme_projects_filter', 'ninjatheme_projects_filter_ajax' );

// ── Shortcode ─────────────────────────────────────────────────────────────────

function ninjatheme_projects_grid_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'per_page' => 12,
			'orderby'  => 'menu_order',
			'order'    => 'ASC',
			'category' => '',
			'filter'   => 'true',
		),
		$atts,
		'projects_grid'
	);

	$per_page           = max( 1, intval( $atts['per_page'] ) );
	$orderby            = sanitize_key( $atts['orderby'] );
	$order              = in_array( strtoupper( $atts['order'] ), array( 'ASC', 'DESC' ), true )
		? strtoupper( $atts['order'] )
		: 'ASC';
	$default_categories = array_values( array_filter( array_map( 'sanitize_text_field', explode( ',', $atts['category'] ) ) ) );
	$show_filter        = filter_var( $atts['filter'], FILTER_VALIDATE_BOOLEAN );

	$filter_values     = $show_filter ? ninjatheme_get_project_filter_values() : array( 'industry' => array(), 'animation_style' => array(), 'art_style' => array(), 'category' => array() );
	$all_projects_data = $show_filter ? ninjatheme_get_all_projects_filter_data_for_js() : array();

	// Only load the first page.
	$initial_query_args = array(
		'post_type'      => 'project',
		'posts_per_page' => $per_page,
		'paged'          => 1,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);
	if ( ! empty( $default_categories ) ) {
		$initial_query_args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $default_categories,
				'operator' => 'IN',
			),
		);
	}
	$query = new WP_Query( $initial_query_args );

	$total    = $query->found_posts;
	$has_more = 1 < $query->max_num_pages;

	$initial_html = '';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$initial_html .= ninjatheme_render_project_card( get_the_ID() );
		}
		wp_reset_postdata();
	}

	// Enqueue JS and pass server data to the client.
	wp_enqueue_script( 'ninjatheme-projects-filter' );
	// Resolve slugs → display names so JS chips and AJAX use the human-readable label.
	$default_categories_names = array();
	foreach ( $default_categories as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			$default_categories_names[] = $term->name;
		}
	}

	wp_localize_script( 'ninjatheme-projects-filter', 'pfData', array(
		'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
		'nonce'           => wp_create_nonce( 'ninjatheme_projects_filter' ),
		'perPage'         => $per_page,
		'orderby'         => $orderby,
		'order'           => $order,
		'total'           => $total,
		'hasMore'         => $has_more,
		'projects'        => $all_projects_data,
		'defaultCategory' => $default_categories_names,
	) );

	// Build the filter axes (only axes that have values).
	$axes = array_filter( array(
		array(
			'label'  => 'Industry',
			'key'    => 'industry',       // JS dataset key: dataset.industry
			'param'  => 'industry',       // AJAX param name
			'values' => $filter_values['industry'],
		),
		array(
			'label'  => 'Animation Style',
			'key'    => 'animationStyle', // JS dataset key: dataset.animationStyle
			'param'  => 'animation_style',
			'values' => $filter_values['animation_style'],
		),
		array(
			'label'  => 'Art Style',
			'key'    => 'artStyle',       // JS dataset key: dataset.artStyle
			'param'  => 'art_style',
			'values' => $filter_values['art_style'],
		),
		array(
			'label'  => 'Training Topic',
			'key'    => 'category',       // JS dataset key: dataset.category
			'param'  => 'category',       // AJAX param name
			'values' => $filter_values['category'],
		),
	), fn( $a ) => ! empty( $a['values'] ) );

	ob_start();
	?>
	<div class="pf" id="projects-filter-wrap">

		<?php if ( $show_filter ) : ?>
		<!-- Sticky bar — appears when .pf__axes scrolls out of viewport -->
		<div class="pf__sticky-bar" id="pf-sticky-bar" aria-label="<?php esc_attr_e( 'Active filters' ); ?>">
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

				<button class="pf__sticky-clear" id="pf-sticky-clear" type="button" hidden>
					<?php esc_html_e( 'Clear all' ); ?>
				</button>
			</div>
		</div>

		<?php if ( ! empty( $axes ) ) : ?>
		<div class="pf__axes" id="pf-axes-panel" role="group" aria-label="<?php esc_attr_e( 'Filter projects' ); ?>">
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
			<div class="pf__active" id="pf-active-chips" aria-live="polite" hidden></div>
			<p class="pf__count" id="pf-count" aria-live="polite"></p>
		</div>

		<div class="project-archive__grid pf__grid" id="pf-grid">
			<?php echo $initial_html; ?>
		</div>

		<p class="project-archive__empty pf__empty" id="pf-empty" hidden>
			<?php esc_html_e( 'No projects match the selected filters.' ); ?>
		</p>

		<div class="pf__load-more-wrap" id="pf-load-more-wrap"<?php echo ! $has_more ? ' hidden' : ''; ?>>
			<button class="pf__load-more" id="pf-load-more" type="button">
				<span class="pf__load-more-label">Load more projects</span>
				<svg class="pf__spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" stroke-dasharray="42 15" stroke-linecap="round"/>
				</svg>
			</button>
		</div>

	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'projects_grid', 'ninjatheme_projects_grid_shortcode' );
