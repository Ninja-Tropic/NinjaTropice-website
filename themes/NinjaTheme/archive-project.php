<?php
/**
 * Template for displaying the Projects archive
 *
 * @package NinjaTheme
 */

get_header();
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

	<!-- Project grid -->
	<div class="project-archive__body">
		<div class="container">
			<?php if ( have_posts() ) : ?>
			<div class="project-archive__grid">
				<?php while ( have_posts() ) : the_post();
					$industry        = get_field( 'project_industry' );
					$animation_style = get_field( 'project_animation_style' );
					$art_style       = get_field( 'project_art_style' );
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'project-archive__item' ); ?>>
					<a class="project-archive__link" href="<?php the_permalink(); ?>">
						<div class="project-archive__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', array(
									'class'   => 'project-archive__image',
									'loading' => 'lazy',
									'alt'     => get_the_title(),
								) ); ?>
							<?php else : ?>
								<div class="project-archive__image-placeholder"></div>
							<?php endif; ?>
						</div>

						<div class="project-archive__body-inner">
							<h2 class="project-archive__item-title"><?php the_title(); ?></h2>

							<?php if ( $industry || $animation_style || $art_style ) : ?>
							<div class="project-archive__tags">
								<?php if ( $industry ) : ?>
								<span class="project-archive__tag"><?php echo esc_html( $industry ); ?></span>
								<?php endif; ?>
								<?php if ( $animation_style ) : ?>
								<span class="project-archive__tag"><?php echo esc_html( $animation_style ); ?></span>
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
				<?php endwhile; ?>
			</div>

			<!-- Pagination -->
			<?php
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => '&larr; Previous',
				'next_text' => 'Next &rarr;',
				'class'     => 'project-archive__pagination',
			) );
			?>

			<?php else : ?>
			<p class="project-archive__empty"><?php esc_html_e( 'No projects found.' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

</main>

<?php get_footer(); ?>
