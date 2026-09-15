<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package NinjaTheme
 */

get_header();

// Latest 4 published posts.
$nt_404_posts = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 4,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );

$nt_404_blog_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );
?>

<main id="main" class="site-main error-404-page">

	<section class="error-404__hero">
		<span class="error-404__aurora error-404__aurora--one" aria-hidden="true"></span>
		<span class="error-404__aurora error-404__aurora--two" aria-hidden="true"></span>

		<div class="error-404__container">
			<p class="error-404__code" aria-hidden="true">404</p>
			<h1 class="error-404__title"><?php esc_html_e( 'This page went off the map' ); ?></h1>
			<p class="error-404__text">
				<?php esc_html_e( 'The page you are looking for was moved, renamed or never existed. Let\'s get you back on track.' ); ?>
			</p>

			<div class="error-404__actions">
				<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home' ); ?></a>
				<a class="btn btn--outline" href="<?php echo esc_url( $nt_404_blog_url ); ?>"><?php esc_html_e( 'Visit the blog' ); ?></a>
			</div>

			<div class="error-404__search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</section>

	<?php if ( $nt_404_posts->have_posts() ) : ?>
	<section class="error-404__section">
		<div class="error-404__container">
			<header class="error-404__section-head">
				<h2 class="error-404__section-title"><?php esc_html_e( 'Latest from the blog' ); ?></h2>
				<a class="error-404__section-link" href="<?php echo esc_url( $nt_404_blog_url ); ?>">
					<?php esc_html_e( 'See all articles' ); ?>
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
						<path d="M1 7h12M8 2l5 5-5 5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</a>
			</header>

			<div class="blog-archive__grid error-404__posts">
				<?php
				while ( $nt_404_posts->have_posts() ) :
					$nt_404_posts->the_post();

					if ( function_exists( 'ninjatheme_render_blog_card' ) ) {
						echo ninjatheme_render_blog_card( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						?>
						<article class="blog-archive__item">
							<a class="blog-archive__link" href="<?php the_permalink(); ?>">
								<div class="blog-archive__media">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'large', array( 'class' => 'blog-archive__image', 'loading' => 'lazy' ) ); ?>
									<?php else : ?>
										<div class="blog-archive__image-placeholder"></div>
									<?php endif; ?>
								</div>
								<div class="blog-archive__body-inner">
									<h3 class="blog-archive__item-title"><?php the_title(); ?></h3>
								</div>
							</a>
						</article>
						<?php
					}
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php endif; ?>

</main>

<?php
get_footer();
