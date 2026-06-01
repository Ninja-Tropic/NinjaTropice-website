<?php
/**
 * Template Name: Case Studies Archive
 *
 * Page template for the Case Studies listing page.
 * Hero: page title + description (left) / featured image (right) — 50/50.
 * Grid: WP_Query on case-studies CPT, posts-per-page configurable via ACF.
 * Footer: the_content() — editor places the HubSpot Form block here.
 *
 * @package NinjaTheme
 */

get_header();

if ( ! have_posts() ) {
	get_footer();
	return;
}

the_post();

$description    = get_field( 'cs_archive_description' ) ?: '';
$image_left     = (bool) get_field( 'cs_archive_image_position' );
$posts_per_page = max( 1, (int) ( get_field( 'cs_archive_posts_per_page' ) ?: 9 ) );
$paged          = max( 1, (int) ( $_GET['paged'] ?? get_query_var( 'paged' ) ?: 1 ) );

$cs_query = new WP_Query( [
	'post_type'      => 'case-studies',
	'posts_per_page' => $posts_per_page,
	'paged'          => $paged,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
] );
?>

<main id="main" class="site-main cs-archive">

	<!-- ① HERO — title + description / featured image (50/50, direction via toggle) -->
	<div class="cs-archive__hero">
		<div class="container cs-archive__hero-inner<?php echo $image_left ? ' cs-archive__hero-inner--rtl' : ''; ?>">

			<?php if ( has_post_thumbnail() && $image_left ) : ?>
			<div class="cs-archive__hero-media">
				<div class="cs-archive__hero-media-frame">
					<?php the_post_thumbnail( 'large', [ 'class' => 'cs-archive__hero-img', 'loading' => 'eager' ] ); ?>
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
					<?php the_post_thumbnail( 'large', [ 'class' => 'cs-archive__hero-img', 'loading' => 'eager' ] ); ?>
				</div>
			</div>
			<?php endif; ?>

		</div>
	</div>

	<!-- ② GRID -->
	<div class="cs-archive__body">
		<div class="container">

			<?php if ( $cs_query->have_posts() ) : ?>
			<div class="cs-archive__grid">
				<?php while ( $cs_query->have_posts() ) : $cs_query->the_post();
					$client_name = get_field( 'client_name' );
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cs-archive__item' ); ?>>
					<a class="cs-archive__link" href="<?php the_permalink(); ?>">

						<div class="cs-archive__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', [
									'class'   => 'cs-archive__image',
									'loading' => 'lazy',
									'alt'     => get_the_title(),
								] ); ?>
							<?php else : ?>
								<div class="cs-archive__image-placeholder"></div>
							<?php endif; ?>
						</div>

						<div class="cs-archive__body-inner">
							<h2 class="cs-archive__item-title"><?php the_title(); ?></h2>

							<?php if ( $client_name ) : ?>
							<div class="cs-archive__tags">
								<span class="cs-archive__tag"><?php echo esc_html( $client_name ); ?></span>
							</div>
							<?php endif; ?>

							<?php if ( has_excerpt() ) : ?>
							<p class="cs-archive__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
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
				<?php endwhile; wp_reset_postdata(); ?>
			</div>

			<?php
			if ( $cs_query->max_num_pages > 1 ) :
				echo paginate_links( [
					'base'      => trailingslashit( get_permalink() ) . '%_%',
					'format'    => '?paged=%#%',
					'current'   => $paged,
					'total'     => $cs_query->max_num_pages,
					'prev_text' => '&larr; Previous',
					'next_text' => 'Next &rarr;',
					'type'      => 'plain',
				] );
			endif;
			?>

			<?php else : ?>
			<p class="cs-archive__empty">No case studies found.</p>
			<?php endif; ?>

		</div>
	</div>

	<!-- ③ CONSULTATION FORM — same as other pages -->
	<?php get_template_part( 'template-parts/consultation-form' ); ?>

</main>

<?php get_footer(); ?>
