<?php
/**
 * Template for displaying the Projects archive
 *
 * @package NinjaTheme
 */

get_header();

// ── Filter data ───────────────────────────────────────────────────────────────
global $wp_query;

$per_page    = 12;
$total       = (int) $wp_query->found_posts;
$has_more    = $wp_query->max_num_pages > 1;

$filter_values     = ninjatheme_get_project_filter_values();
$all_projects_data = ninjatheme_get_all_projects_filter_data_for_js();

$axes = array_filter( array(
	array(
		'label'  => 'Training Topic',
		'key'    => 'category',
		'param'  => 'category',
		'values' => $filter_values['category'],
	),
	array(
		'label'  => 'Industry',
		'key'    => 'industry',
		'param'  => 'industry',
		'values' => $filter_values['industry'],
	),
	array(
		'label'  => 'Animation Style',
		'key'    => 'animationStyle',
		'param'  => 'animation_style',
		'values' => $filter_values['animation_style'],
	),
	array(
		'label'  => 'Art Style',
		'key'    => 'artStyle',
		'param'  => 'art_style',
		'values' => $filter_values['art_style'],
	),
), fn( $a ) => ! empty( $a['values'] ) );

// Enqueue and configure JS.
wp_enqueue_script( 'ninjatheme-projects-filter' );
wp_localize_script( 'ninjatheme-projects-filter', 'pfData', array(
	'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
	'nonce'           => wp_create_nonce( 'ninjatheme_projects_filter' ),
	'perPage'         => $per_page,
	'orderby'         => 'menu_order',
	'order'           => 'ASC',
	'total'           => $total,
	'hasMore'         => $has_more,
	'projects'        => $all_projects_data,
	'defaultCategory' => array(),
) );

// Render initial cards from the main WP_Query.
$initial_html = '';
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$initial_html .= ninjatheme_render_project_card( get_the_ID() );
	}
}
?>

<main id="main" class="site-main project-archive">

	<!-- Archive header -->
	<div class="project-archive__header">
		<div class="container">
			<div class="project-archive__header-inner">
				<div class="project-archive__accent"></div>
				<h1 class="project-archive__title">
					<?php
					if ( is_post_type_archive() ) {
						post_type_archive_title();
					} else {
						esc_html_e( 'Projects' );
					}
					?>
				</h1>
				<?php
				$archive_description = get_the_archive_description();
				if ( $archive_description ) :
				?>
				<p class="project-archive__description"><?php echo wp_kses_post( $archive_description ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Filter + Project grid -->
	<div class="project-archive__body">
		<div class="container">
		<div class="pf" id="projects-filter-wrap">

			<!-- Sticky bar -->
			<div class="pf__sticky-bar" id="pf-sticky-bar" aria-label="<?php esc_attr_e( 'Active filters' ); ?>">
				<div class="pf__sticky-inner">
					<!-- Sticky search -->
					<div class="pf__sticky-search">
						<svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
							<circle cx="6.5" cy="6.5" r="5" stroke="currentColor" stroke-width="1.75"/>
							<path d="M10.5 10.5L13.5 13.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
						</svg>
						<input type="search" placeholder="Search projects…" aria-label="Search projects" tabindex="-1">
					</div>

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
							<ul class="pf__sd-list" role="listbox" aria-multiselectable="true">
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

					<button class="pf__sticky-clear" id="pf-sticky-clear" type="button" hidden>
						<?php esc_html_e( 'Clear all' ); ?>
					</button>
				</div>
			</div>

			<!-- Search bar -->
			<div class="pf__search-bar">
				<div class="pf__search-wrap">
					<svg class="pf__search-icon" width="17" height="17" viewBox="0 0 17 17" fill="none" aria-hidden="true">
						<circle cx="7.5" cy="7.5" r="5.75" stroke="currentColor" stroke-width="1.75"/>
						<path d="M12 12L15 15" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
					</svg>
					<input
						class="pf__search-input"
						id="pf-search"
						type="search"
						placeholder="Search projects by name…"
						aria-label="Search projects"
						autocomplete="off"
					>
				</div>
			</div>

			<!-- Filter axes -->
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
								<button class="pf__pill pf__pill--active"
									data-axis="<?php echo esc_attr( $axis['key'] ); ?>"
									data-param="<?php echo esc_attr( $axis['param'] ); ?>"
									data-value="" aria-pressed="true" type="button">All</button>
								<?php foreach ( $axis['values'] as $val ) : ?>
								<button class="pf__pill"
									data-axis="<?php echo esc_attr( $axis['key'] ); ?>"
									data-param="<?php echo esc_attr( $axis['param'] ); ?>"
									data-value="<?php echo esc_attr( $val ); ?>"
									aria-pressed="false" type="button"><?php echo esc_html( $val ); ?></button>
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

			<!-- Active chips + count -->
			<div class="pf__meta-bar">
				<div class="pf__active" id="pf-active-chips" aria-live="polite" hidden></div>
				<p class="pf__count" id="pf-count" aria-live="polite"></p>
			</div>

			<!-- Grid -->
			<div class="project-archive__grid pf__grid" id="pf-grid">
				<?php echo $initial_html; ?>
			</div>

			<p class="project-archive__empty pf__empty" id="pf-empty" hidden>
				<?php esc_html_e( 'No projects match your search.' ); ?>
			</p>

			<!-- Load more -->
			<div class="pf__load-more-wrap" id="pf-load-more-wrap"<?php echo ! $has_more ? ' hidden' : ''; ?>>
				<button class="pf__load-more" id="pf-load-more" type="button">
					<span class="pf__load-more-label">Load more projects</span>
					<svg class="pf__spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
						<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" stroke-dasharray="42 15" stroke-linecap="round"/>
					</svg>
				</button>
			</div>

		</div><!-- /.pf -->
		</div><!-- /.container -->
	</div><!-- /.project-archive__body -->

</main>

<?php get_footer(); ?>
