<?php
/**
 * Content Grid — ACF block backend
 *
 * One block for blog posts, projects and case studies. The editor picks the
 * content type and can exclude posts, categories and tags. The first page is
 * server-rendered; filters, search and "Load more" use AJAX.
 *
 * The block config is signed (wp_hash) and sent back with every AJAX request,
 * so a visitor cannot change the content type or the exclusions.
 */

// ── Asset registration ────────────────────────────────────────────────────────

function ninjatheme_register_content_grid_assets() {
	$js_path = get_stylesheet_directory() . '/js/modules/content-grid.js';
	if ( file_exists( $js_path ) ) {
		wp_register_script(
			'ninjatheme-content-grid',
			get_stylesheet_directory_uri() . '/js/modules/content-grid.js',
			array(),
			filemtime( $js_path ),
			true
		);
		wp_script_add_data( 'ninjatheme-content-grid', 'defer', true );
	}
}
add_action( 'wp_enqueue_scripts', 'ninjatheme_register_content_grid_assets' );

// ── Content type definitions ──────────────────────────────────────────────────

/**
 * Filter axes per content type.
 * source "tax"  → taxonomy name in 'name'
 * source "meta" → post meta key in 'name'
 */
function ninjatheme_cg_types() {
	return array(
		'post'         => array(
			'label' => 'Blog post',
			'axes'  => array(
				array( 'key' => 'category', 'label' => 'Category', 'placeholder' => 'All Categories', 'source' => 'tax', 'name' => 'category' ),
				array( 'key' => 'tag', 'label' => 'Topic', 'placeholder' => 'All Topics', 'source' => 'tax', 'name' => 'post_tag' ),
				array( 'key' => 'year', 'label' => 'Year', 'placeholder' => 'All Years', 'source' => 'year', 'name' => '' ),
			),
		),
		'project'      => array(
			'label' => 'Project',
			'axes'  => array(
				array( 'key' => 'industry', 'label' => 'Industry', 'placeholder' => 'All Industries', 'source' => 'meta', 'name' => 'project_industry' ),
				array( 'key' => 'animation_style', 'label' => 'Animation Style', 'placeholder' => 'All Animation Styles', 'source' => 'meta', 'name' => 'project_animation_style' ),
				array( 'key' => 'category', 'label' => 'Training Topic', 'placeholder' => 'All Training Topics', 'source' => 'tax', 'name' => 'category' ),
			),
		),
		'case-studies' => array(
			'label' => 'Case study',
			'axes'  => array(
				array( 'key' => 'client', 'label' => 'Client', 'placeholder' => 'All Clients', 'source' => 'meta', 'name' => 'client_name' ),
				array( 'key' => 'category', 'label' => 'Category', 'placeholder' => 'All Categories', 'source' => 'tax', 'name' => 'category' ),
				array( 'key' => 'industry', 'label' => 'Industry', 'placeholder' => 'All Industries', 'source' => 'meta', 'name' => 'client_industry' ),
			),
		),
	);
}

// ── Config helpers ────────────────────────────────────────────────────────────

/**
 * Validate and normalise raw block/AJAX config into a canonical array.
 */
function ninjatheme_cg_normalize_config( $raw ) {
	$types = ninjatheme_cg_types();
	$raw   = is_array( $raw ) ? $raw : array();

	$type    = ( isset( $raw['type'] ) && isset( $types[ $raw['type'] ] ) ) ? $raw['type'] : 'case-studies';
	$orderby = ( isset( $raw['orderby'] ) && in_array( $raw['orderby'], array( 'date', 'title', 'menu_order' ), true ) ) ? $raw['orderby'] : 'date';
	$order   = ( isset( $raw['order'] ) && 'ASC' === strtoupper( $raw['order'] ) ) ? 'ASC' : 'DESC';

	$ids = function ( $key ) use ( $raw ) {
		$list = isset( $raw[ $key ] ) ? array_values( array_unique( array_filter( array_map( 'intval', (array) $raw[ $key ] ) ) ) ) : array();
		sort( $list );
		return $list;
	};

	return array(
		'type'               => $type,
		'per_page'           => min( 48, max( 1, (int) ( $raw['per_page'] ?? 9 ) ) ),
		'orderby'            => $orderby,
		'order'              => $order,
		'show_filters'       => ! empty( $raw['show_filters'] ),
		'show_search'        => ! empty( $raw['show_search'] ),
		'include_categories' => $ids( 'include_categories' ),
		'exclude_posts'      => $ids( 'exclude_posts' ),
		'exclude_categories' => $ids( 'exclude_categories' ),
		'exclude_tags'       => $ids( 'exclude_tags' ),
	);
}

/**
 * Build WP_Query args for a config + optional filters/search.
 *
 * @param array  $cfg     Normalised config.
 * @param int    $page
 * @param array  $filters axis key => string[] of selected values.
 * @param string $search
 */
function ninjatheme_cg_query_args( $cfg, $page = 1, $filters = array(), $search = '' ) {
	$types = ninjatheme_cg_types();

	$args = array(
		'post_type'      => $cfg['type'],
		'post_status'    => 'publish',
		'posts_per_page' => $cfg['per_page'],
		'paged'          => $page,
		'orderby'        => $cfg['orderby'],
		'order'          => $cfg['order'],
	);

	if ( $cfg['exclude_posts'] ) {
		$args['post__not_in'] = $cfg['exclude_posts'];
	}

	$tax_query  = array();
	$meta_query = array();
	$date_query = array();

	// Only show items in these categories (e.g. Blog → Corporate Training).
	if ( $cfg['include_categories'] && is_object_in_taxonomy( $cfg['type'], 'category' ) ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => $cfg['include_categories'],
			'operator' => 'IN',
		);
	}
	if ( $cfg['exclude_categories'] && is_object_in_taxonomy( $cfg['type'], 'category' ) ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => $cfg['exclude_categories'],
			'operator' => 'NOT IN',
		);
	}
	if ( $cfg['exclude_tags'] && is_object_in_taxonomy( $cfg['type'], 'post_tag' ) ) {
		$tax_query[] = array(
			'taxonomy' => 'post_tag',
			'field'    => 'term_id',
			'terms'    => $cfg['exclude_tags'],
			'operator' => 'NOT IN',
		);
	}

	foreach ( $types[ $cfg['type'] ]['axes'] as $axis ) {
		$values = isset( $filters[ $axis['key'] ] ) ? (array) $filters[ $axis['key'] ] : array();
		if ( empty( $values ) ) {
			continue;
		}
		if ( 'year' === $axis['source'] ) {
			foreach ( $values as $year ) {
				$date_query[] = array( 'year' => (int) $year );
			}
		} elseif ( 'tax' === $axis['source'] ) {
			$tax_query[] = array(
				'taxonomy' => $axis['name'],
				'field'    => 'name',
				'terms'    => $values,
				'operator' => 'IN',
			);
		} else {
			$meta_query[] = array(
				'key'     => $axis['name'],
				'value'   => $values,
				'compare' => 'IN',
			);
		}
	}

	if ( $tax_query ) {
		$tax_query['relation'] = 'AND';
		$args['tax_query']     = $tax_query;
	}
	if ( $meta_query ) {
		$meta_query['relation'] = 'AND';
		$args['meta_query']     = $meta_query;
	}
	if ( $date_query ) {
		$date_query['relation'] = 'OR';
		$args['date_query']     = $date_query;
	}
	if ( '' !== $search ) {
		$args['s'] = $search;
	}

	return $args;
}

/**
 * Filter axes that have at least one value among the posts the block can show
 * (so excluded posts never contribute options).
 *
 * @return array[] Each axis definition plus 'values' => string[].
 */
function ninjatheme_cg_filter_axes( $cfg ) {
	global $wpdb;

	$types = ninjatheme_cg_types();

	$args                   = ninjatheme_cg_query_args( $cfg );
	$args['posts_per_page'] = -1;
	$args['fields']         = 'ids';
	$args['no_found_rows']  = true;
	unset( $args['paged'] );

	$ids = array_map( 'intval', get_posts( $args ) );
	if ( empty( $ids ) ) {
		return array();
	}
	$ids_sql = implode( ',', $ids );

	$axes = array();
	foreach ( $types[ $cfg['type'] ]['axes'] as $axis ) {
		// A single included category makes the category select pointless.
		if ( 'tax' === $axis['source'] && 'category' === $axis['name'] && 1 === count( $cfg['include_categories'] ) ) {
			continue;
		}
		if ( 'year' === $axis['source'] ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$values = $wpdb->get_col( "SELECT DISTINCT YEAR(post_date) FROM {$wpdb->posts} WHERE ID IN ($ids_sql) ORDER BY 1 DESC" );
		} elseif ( 'meta' === $axis['source'] ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$values = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT DISTINCT meta_value FROM {$wpdb->postmeta}
					 WHERE meta_key = %s AND meta_value != '' AND post_id IN ($ids_sql)
					 ORDER BY meta_value ASC",
					$axis['name']
				)
			);
		} else {
			$terms  = wp_get_object_terms( $ids, $axis['name'], array( 'orderby' => 'name', 'order' => 'ASC' ) );
			$values = array();
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( 'uncategorized' !== $term->slug ) {
						$values[] = $term->name;
					}
				}
			}
		}

		if ( ! empty( $values ) ) {
			$axis['values'] = array_values( $values );
			$axes[]         = $axis;
		}
	}

	return $axes;
}

// ── Card ──────────────────────────────────────────────────────────────────────

/**
 * Text for the badge above the title: industry for projects, client for case
 * studies, otherwise the first category.
 */
function ninjatheme_cg_badge( $post_id, $type ) {
	if ( 'project' === $type && function_exists( 'get_field' ) ) {
		$industry = get_field( 'project_industry', $post_id );
		if ( $industry ) {
			return (string) $industry;
		}
	}
	if ( 'case-studies' === $type && function_exists( 'get_field' ) ) {
		$client = get_field( 'client_name', $post_id );
		if ( $client ) {
			return (string) $client;
		}
	}

	$cats = get_the_category( $post_id );
	foreach ( (array) $cats as $cat ) {
		if ( 'uncategorized' !== $cat->slug ) {
			return $cat->name;
		}
	}
	return '';
}

function ninjatheme_cg_render_card( $post_id, $type ) {
	$title     = get_the_title( $post_id );
	$permalink = get_permalink( $post_id );
	$badge     = ninjatheme_cg_badge( $post_id, $type );

	$thumbnail = has_post_thumbnail( $post_id )
		? get_the_post_thumbnail(
			$post_id,
			'large',
			array(
				'class'   => 'cg-card__image',
				'loading' => 'lazy',
				'alt'     => esc_attr( $title ),
			)
		)
		: '';

	// Projects without a featured image fall back to their video thumbnail.
	if ( ! $thumbnail && 'project' === $type && function_exists( 'ninjatheme_video_thumbnail_url' ) && function_exists( 'get_field' ) ) {
		$video_thumb = ninjatheme_video_thumbnail_url( get_field( 'project_video', $post_id ), $post_id );
		if ( $video_thumb ) {
			$fallback  = esc_url( str_replace( 'maxresdefault', 'hqdefault', $video_thumb ) );
			$thumbnail = '<img class="cg-card__image" src="' . esc_url( $video_thumb ) . '" alt="' . esc_attr( $title ) . '" width="1280" height="720" loading="lazy" onerror="this.onerror=null;this.src=\'' . $fallback . '\'">';
		}
	}

	ob_start();
	?>
	<article class="cg-card__item" id="post-<?php echo esc_attr( $post_id ); ?>">
		<a class="cg-card__link" href="<?php echo esc_url( $permalink ); ?>">
			<div class="cg-card__media">
				<?php if ( $thumbnail ) : ?>
					<?php echo $thumbnail; // already escaped via WP functions ?>
				<?php else : ?>
					<div class="cg-card__image-placeholder"></div>
				<?php endif; ?>
			</div>

			<div class="cg-card__body-inner">
				<div class="cg-card__meta">
					<?php if ( $badge ) : ?>
					<span class="cg-card__badge"><?php echo esc_html( $badge ); ?></span>
					<?php endif; ?>
					<time class="cg-card__date" datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>">
						<?php echo esc_html( sprintf( '%s ago', human_time_diff( get_post_time( 'U', true, $post_id ) ) ) ); ?>
					</time>
				</div>
				<h2 class="cg-card__item-title"><?php echo esc_html( $title ); ?></h2>
			</div>
		</a>
	</article>
	<?php
	return ob_get_clean();
}

// ── AJAX handler ──────────────────────────────────────────────────────────────

function ninjatheme_content_grid_ajax() {
	$config_json = isset( $_POST['config'] ) ? wp_unslash( $_POST['config'] ) : '';
	$sig         = isset( $_POST['sig'] ) ? (string) wp_unslash( $_POST['sig'] ) : '';

	if ( '' === $config_json || ! hash_equals( wp_hash( $config_json ), $sig ) ) {
		wp_send_json_error( array( 'message' => 'Invalid config.' ), 403 );
	}

	$cfg = ninjatheme_cg_normalize_config( json_decode( $config_json, true ) );

	$page   = max( 1, intval( $_POST['page'] ?? 1 ) );
	$search = sanitize_text_field( wp_unslash( $_POST['search'] ?? '' ) );

	$filters = array();
	if ( isset( $_POST['filters'] ) && is_array( $_POST['filters'] ) ) {
		foreach ( wp_unslash( $_POST['filters'] ) as $axis_key => $values ) {
			$filters[ sanitize_key( $axis_key ) ] = array_values( array_filter( array_map( 'sanitize_text_field', (array) $values ) ) );
		}
	}

	$query = new WP_Query( ninjatheme_cg_query_args( $cfg, $page, $filters, $search ) );

	$html = '';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= ninjatheme_cg_render_card( get_the_ID(), $cfg['type'] );
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
add_action( 'wp_ajax_ninjatheme_content_grid',        'ninjatheme_content_grid_ajax' );
add_action( 'wp_ajax_nopriv_ninjatheme_content_grid', 'ninjatheme_content_grid_ajax' );

// ── Block render ──────────────────────────────────────────────────────────────

/**
 * Render the block.
 *
 * @param array  $raw        Raw config (see ninjatheme_cg_normalize_config).
 * @param string $title
 * @param string $subtitle
 * @param string $wrapper_id
 * @param string $class_name
 * @param bool   $is_preview True inside the block editor.
 * @return string HTML
 */
function ninjatheme_cg_render( $raw, $title, $subtitle, $wrapper_id, $class_name, $is_preview = false ) {
	$cfg   = ninjatheme_cg_normalize_config( $raw );
	$types = ninjatheme_cg_types();

	// In the editor show a compact summary instead of running the real grid.
	if ( $is_preview ) {
		$excluded = count( $cfg['exclude_posts'] ) + count( $cfg['exclude_categories'] ) + count( $cfg['exclude_tags'] );
		ob_start();
		?>
		<div class="<?php echo esc_attr( $class_name ); ?>" id="<?php echo esc_attr( $wrapper_id ); ?>" style="padding:24px;border:1px dashed #a8a8b3;border-radius:16px;">
			<strong><?php echo esc_html( $title ?: 'Content Grid' ); ?></strong>
			<p style="margin:8px 0 0;">
				<?php
				echo esc_html(
					sprintf(
						'%s · %d per page · %s · %d excluded',
						$types[ $cfg['type'] ]['label'],
						$cfg['per_page'],
						$cfg['show_filters'] ? 'filters on' : 'filters off',
						$excluded
					)
				);
				?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}

	$query    = new WP_Query( ninjatheme_cg_query_args( $cfg ) );
	$total    = $query->found_posts;
	$has_more = 1 < $query->max_num_pages;

	$initial_html = '';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$initial_html .= ninjatheme_cg_render_card( get_the_ID(), $cfg['type'] );
		}
		wp_reset_postdata();
	}

	$axes        = $cfg['show_filters'] ? ninjatheme_cg_filter_axes( $cfg ) : array();
	$config_json = wp_json_encode( $cfg );

	wp_enqueue_script( 'ninjatheme-content-grid' );

	ob_start();
	?>
	<div
		class="<?php echo esc_attr( $class_name ); ?>"
		id="<?php echo esc_attr( $wrapper_id ); ?>"
		data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
		data-config="<?php echo esc_attr( $config_json ); ?>"
		data-sig="<?php echo esc_attr( wp_hash( $config_json ) ); ?>"
		data-total="<?php echo esc_attr( $total ); ?>"
		data-has-more="<?php echo $has_more ? '1' : '0'; ?>">

		<?php if ( $title || $subtitle ) : ?>
		<header class="pf__header">
			<?php if ( $title ) : ?>
			<h2 class="pf__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $subtitle ) : ?>
			<p class="pf__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</header>
		<?php endif; ?>

		<?php if ( $cfg['show_search'] || ! empty( $axes ) ) : ?>
		<div class="pf__filterbar">
			<?php if ( $cfg['show_search'] ) : ?>
			<div class="pf__search-wrap">
				<label for="<?php echo esc_attr( $wrapper_id ); ?>-search" class="screen-reader-text"><?php esc_html_e( 'Search' ); ?></label>
				<svg class="pf__search-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
					<circle cx="6.5" cy="6.5" r="5" stroke="currentColor" stroke-width="1.75"/>
					<path d="M10.5 10.5L14 14" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
				</svg>
				<input id="<?php echo esc_attr( $wrapper_id ); ?>-search" class="pf__search-input" type="search" placeholder="<?php echo esc_attr( 'Search ' . strtolower( $types[ $cfg['type'] ]['label'] ) . 's…' ); ?>" autocomplete="off">
			</div>
			<?php endif; ?>

			<?php if ( ! empty( $axes ) ) : ?>
			<div class="pf__selects" role="group" aria-label="<?php esc_attr_e( 'Filters' ); ?>">
				<?php foreach ( $axes as $axis ) : ?>
				<div class="pf__sd pf__sd--field" data-axis="<?php echo esc_attr( $axis['key'] ); ?>">
					<button class="pf__sd-trigger" type="button" aria-haspopup="listbox">
						<span class="pf__sd-label" data-placeholder="<?php echo esc_attr( $axis['placeholder'] ); ?>"><?php echo esc_html( $axis['placeholder'] ); ?></span>
						<span class="pf__sd-count" hidden></span>
						<svg class="pf__sd-chevron" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<div class="pf__sd-panel" hidden>
						<ul class="pf__sd-list" role="listbox" aria-multiselectable="true" aria-label="<?php echo esc_attr( 'Filter by ' . $axis['label'] ); ?>">
							<?php foreach ( $axis['values'] as $val ) : ?>
							<li class="pf__sd-item" data-value="<?php echo esc_attr( $val ); ?>" role="option" aria-selected="false">
								<span class="pf__sd-check" aria-hidden="true"></span>
								<span class="pf__sd-item-label"><?php echo esc_html( $val ); ?></span>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="pf__meta-bar">
			<div class="pf__active" aria-live="polite" hidden></div>
			<p class="pf__count" aria-live="polite"></p>
		</div>

		<div class="pf__grid">
			<?php echo $initial_html; ?>
		</div>

		<p class="pf__empty" hidden><?php esc_html_e( 'No results match the selected filters.' ); ?></p>

		<div class="pf__load-more-wrap"<?php echo ! $has_more ? ' hidden' : ''; ?>>
			<button class="pf__load-more" type="button">
				<span class="pf__load-more-label"><?php esc_html_e( 'Load more' ); ?></span>
				<svg class="pf__spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" stroke-dasharray="42 15" stroke-linecap="round"/>
				</svg>
			</button>
		</div>

	</div>
	<?php
	return ob_get_clean();
}
