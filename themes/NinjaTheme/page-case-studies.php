<?php
/**
 * Template Name: Case Studies Archive
 *
 * Page template for the Case Studies listing page.
 * Hero: page title + description (left) / featured image (right) — 50/50.
 * Grid: first page server-rendered; subsequent pages via AJAX load more.
 * Footer: consultation form template part.
 *
 * @package NinjaTheme
 */

get_header();

if ( ! have_posts() ) {
	get_footer();
	return;
}

the_post();

$description = get_field( 'cs_archive_description' ) ?: '';
$image_left  = (bool) get_field( 'cs_archive_image_position' );
$per_page    = max( 1, (int) ( get_field( 'cs_archive_posts_per_page' ) ?: 9 ) );

$cs_query = new WP_Query( array(
	'post_type'      => 'case-studies',
	'posts_per_page' => $per_page,
	'paged'          => 1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$total    = $cs_query->found_posts;
$has_more = 1 < $cs_query->max_num_pages;

// Enqueue JS and pass server data to the client.
wp_enqueue_script( 'ninjatheme-case-studies-filter' );
wp_localize_script( 'ninjatheme-case-studies-filter', 'csfData', array(
	'ajaxUrl' => admin_url( 'admin-ajax.php' ),
	'nonce'   => wp_create_nonce( 'ninjatheme_case_studies_filter' ),
	'perPage' => $per_page,
	'orderby' => 'date',
	'order'   => 'DESC',
	'total'   => $total,
	'hasMore' => $has_more,
) );
?>

<main id="main" class="site-main cs-archive">

	<!-- ① HERO — title + description / featured image (50/50, direction via toggle) -->
	<div class="cs-archive__hero">
		<div class="cs-archive__hero-inner<?php echo $image_left ? ' cs-archive__hero-inner--rtl' : ''; ?>">

			<?php if ( has_post_thumbnail() && $image_left ) : ?>
			<div class="cs-archive__hero-media">
				<div class="cs-archive__hero-media-frame">
					<?php the_post_thumbnail( 'large', array( 'class' => 'cs-archive__hero-img', 'loading' => 'eager' ) ); ?>
				</div>
			</div>
			<?php endif; ?>

			<div class="cs-archive__hero-content">
				<div class="cs-archive__accent" aria-hidden="true"></div>
				<h1 class="cs-archive__title"><?php the_title(); ?></h1>
				<?php if ( $description ) : ?>
				<div class="cs-archive__description"><?php echo wp_kses_post( $description ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( has_post_thumbnail() && ! $image_left ) : ?>
			<div class="cs-archive__hero-media">
				<div class="cs-archive__hero-media-frame">
					<?php the_post_thumbnail( 'large', array( 'class' => 'cs-archive__hero-img', 'loading' => 'eager' ) ); ?>
				</div>
			</div>
			<?php endif; ?>

		</div>
	</div>

	<!-- ② GRID -->
	<div class="cs-archive__body">
		<div class="container" id="case-studies-filter-wrap">

			<div class="cs-archive__grid" id="csf-grid">
				<?php
				if ( $cs_query->have_posts() ) {
					while ( $cs_query->have_posts() ) {
						$cs_query->the_post();
						echo ninjatheme_render_case_study_card( get_the_ID() );
					}
					wp_reset_postdata();
				}
				?>
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
	</div>

	<!-- ③ CONSULTATION FORM -->
	<?php get_template_part( 'template-parts/consultation-form' ); ?>

</main>

<?php get_footer(); ?>
