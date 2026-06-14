<?php
/**
 * Case Studies grid helpers
 *
 * Card renderer + AJAX load-more handler used by page-case-studies.php.
 * Asset registration lives here so the JS is only enqueued when the
 * template actually needs it.
 */

// ── Asset registration ────────────────────────────────────────────────────────

function ninjatheme_register_case_studies_filter_assets() {
	$js_path = get_stylesheet_directory() . '/js/modules/case-studies-filter.js';
	if ( file_exists( $js_path ) ) {
		wp_register_script(
			'ninjatheme-case-studies-filter',
			get_stylesheet_directory_uri() . '/js/modules/case-studies-filter.js',
			array(),
			filemtime( $js_path ),
			true
		);
		wp_script_add_data( 'ninjatheme-case-studies-filter', 'defer', true );
	}
}
add_action( 'wp_enqueue_scripts', 'ninjatheme_register_case_studies_filter_assets' );

// ── Card renderer ─────────────────────────────────────────────────────────────

/**
 * Render a single case study card (shared by template + AJAX handler).
 *
 * @param int $post_id
 * @return string HTML
 */
function ninjatheme_render_case_study_card( $post_id ) {
	$title       = get_the_title( $post_id );
	$permalink   = get_permalink( $post_id );
	$client_name = get_field( 'client_name', $post_id );
	$excerpt     = get_the_excerpt( $post_id );

	$thumbnail = has_post_thumbnail( $post_id )
		? get_the_post_thumbnail(
			$post_id,
			'large',
			array(
				'class'   => 'cs-archive__image',
				'loading' => 'lazy',
				'alt'     => esc_attr( $title ),
			)
		)
		: '';

	ob_start();
	?>
	<article class="cs-archive__item" id="post-<?php echo esc_attr( $post_id ); ?>">
		<a class="cs-archive__link" href="<?php echo esc_url( $permalink ); ?>">

			<div class="cs-archive__media">
				<?php if ( $thumbnail ) : ?>
					<?php echo $thumbnail; // already escaped via WP functions ?>
				<?php else : ?>
					<div class="cs-archive__image-placeholder"></div>
				<?php endif; ?>
			</div>

			<div class="cs-archive__body-inner">
				<h2 class="cs-archive__item-title"><?php echo esc_html( $title ); ?></h2>

				<?php if ( $client_name ) : ?>
				<div class="cs-archive__tags">
					<span class="cs-archive__tag"><?php echo esc_html( $client_name ); ?></span>
				</div>
				<?php endif; ?>

				<?php if ( $excerpt ) : ?>
				<p class="cs-archive__excerpt"><?php echo esc_html( $excerpt ); ?></p>
				<?php endif; ?>

				<span class="cs-archive__cta">
					Read case study
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

function ninjatheme_case_studies_load_more_ajax() {
	check_ajax_referer( 'ninjatheme_case_studies_filter', 'nonce' );

	$page       = max( 1, intval( $_POST['page'] ?? 1 ) );
	$per_page   = max( 1, intval( $_POST['per_page'] ?? 9 ) );
	$orderby    = sanitize_key( $_POST['orderby'] ?? 'date' );
	$order      = in_array( strtoupper( $_POST['order'] ?? 'DESC' ), array( 'ASC', 'DESC' ), true )
		? strtoupper( $_POST['order'] )
		: 'DESC';
	$f_category = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $_POST['category'] ?? array() ) ) ) );

	$query_args = array(
		'post_type'      => 'case-studies',
		'posts_per_page' => $per_page,
		'paged'          => $page,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);

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
			$html .= ninjatheme_render_case_study_card( get_the_ID() );
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
add_action( 'wp_ajax_ninjatheme_case_studies_filter',        'ninjatheme_case_studies_load_more_ajax' );
add_action( 'wp_ajax_nopriv_ninjatheme_case_studies_filter', 'ninjatheme_case_studies_load_more_ajax' );

// ── Shortcode ─────────────────────────────────────────────────────────────────

/**
 * [case_studies_grid] shortcode
 *
 * Usage: [case_studies_grid]
 *        [case_studies_grid per_page="6" category="SaaS"]
 *        [case_studies_grid category="SaaS,Fintech"]
 */
function ninjatheme_case_studies_grid_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'per_page' => 9,
			'orderby'  => 'date',
			'order'    => 'DESC',
			'category' => '',
		),
		$atts,
		'case_studies_grid'
	);

	$per_page           = max( 1, intval( $atts['per_page'] ) );
	$orderby            = sanitize_key( $atts['orderby'] );
	$order              = in_array( strtoupper( $atts['order'] ), array( 'ASC', 'DESC' ), true )
		? strtoupper( $atts['order'] )
		: 'DESC';
	$default_categories = array_values( array_filter( array_map( 'sanitize_text_field', explode( ',', $atts['category'] ) ) ) );

	$initial_query_args = array(
		'post_type'      => 'case-studies',
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
			$initial_html .= ninjatheme_render_case_study_card( get_the_ID() );
		}
		wp_reset_postdata();
	}

	// Resolve slugs → display names so AJAX uses the human-readable label.
	$default_categories_names = array();
	foreach ( $default_categories as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term && ! is_wp_error( $term ) ) {
			$default_categories_names[] = $term->name;
		}
	}

	wp_enqueue_script( 'ninjatheme-case-studies-filter' );
	wp_localize_script( 'ninjatheme-case-studies-filter', 'csfData', array(
		'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
		'nonce'           => wp_create_nonce( 'ninjatheme_case_studies_filter' ),
		'perPage'         => $per_page,
		'orderby'         => $orderby,
		'order'           => $order,
		'total'           => $total,
		'hasMore'         => $has_more,
		'defaultCategory' => $default_categories_names,
	) );

	ob_start();
	?>
	<div class="cs-archive__body" id="case-studies-filter-wrap">

		<div class="cs-archive__grid" id="csf-grid">
			<?php echo $initial_html; ?>
		</div>

		<p class="cs-archive__empty" id="csf-empty" hidden>
			<?php esc_html_e( 'No case studies found.' ); ?>
		</p>

		<div class="pf__load-more-wrap" id="csf-load-more-wrap"<?php echo ! $has_more ? ' hidden' : ''; ?>>
			<button class="pf__load-more" id="csf-load-more" type="button">
				<span class="pf__load-more-label">Load more case studies</span>
				<svg class="pf__spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" stroke-dasharray="42 15" stroke-linecap="round"/>
				</svg>
			</button>
		</div>

	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'case_studies_grid', 'ninjatheme_case_studies_grid_shortcode' );
