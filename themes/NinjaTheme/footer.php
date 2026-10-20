<?php
/**
 * The footer template file
 *
 * @package NinjaTheme
 */
$options = get_fields('option');

/**
 * Render a footer link list from an ACF options repeater (label + page_link).
 */
function ninjatheme_footer_link_list( $repeater ) {
	if ( ! have_rows( $repeater, 'option' ) ) {
		return;
	}
	echo '<ul class="footer-links">';
	while ( have_rows( $repeater, 'option' ) ) {
		the_row();
		$link  = get_sub_field( 'link' );
		$label = get_sub_field( 'label' );
		if ( ! $link ) {
			continue;
		}
		$url    = is_array( $link ) ? $link['url'] : $link;
		$target = is_array( $link ) && ! empty( $link['target'] ) ? $link['target'] : '_self';
		if ( $label ) {
			$title = $label;
		} elseif ( is_array( $link ) && ! empty( $link['title'] ) ) {
			$title = $link['title'];
		} else {
			$post_id = url_to_postid( $url );
			$title   = $post_id ? get_the_title( $post_id ) : basename( rtrim( $url, '/' ) );
		}
		printf( '<li><a href="%s" target="%s">%s</a></li>', esc_url( $url ), esc_attr( $target ), esc_html( $title ) );
	}
	echo '</ul>';
}

$cta_button = $options['footer_cta_button'] ?? null;
$copyright  = $options['footer_copyright'] ?? '';
$copyright  = str_replace( '{year}', date( 'Y' ), $copyright );
?>

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="site-footer__top">
				<div class="site-footer__brand">
					<?php if( !empty($options['logo']['url']) ): ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo-link">
							<?php echo ninjatheme_get_responsive_image( $options['logo'], 'medium', array( 'class' => 'footer-logo', 'loading' => 'lazy' ) ); ?>
						</a>
					<?php endif; ?>
					<?php if( !empty($options['footer_tagline']) ): ?>
						<p class="footer-tagline"><?php echo esc_html($options['footer_tagline']); ?></p>
					<?php endif; ?>
				</div>
				<?php if( !empty($options['footer_cta_text']) || !empty($cta_button['url']) ): ?>
					<div class="site-footer__cta">
						<?php if( !empty($options['footer_cta_text']) ): ?>
							<p><?php echo nl2br( esc_html($options['footer_cta_text']) ); ?></p>
						<?php endif; ?>
						<?php if( !empty($cta_button['url']) ): ?>
							<a class="footer-cta-button" href="<?php echo esc_url($cta_button['url']); ?>" target="<?php echo esc_attr($cta_button['target'] ?: '_self'); ?>">
								<?php echo esc_html($cta_button['title']); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="site-footer__columns">
				<div class="site-footer__column">
					<?php if( !empty($options['service_title']) ): ?>
						<h3 class="footer-section-title"><?php echo esc_html($options['service_title']); ?></h3>
					<?php endif; ?>
					<?php ninjatheme_footer_link_list( 'service_links' ); ?>
				</div>
				<div class="site-footer__column">
					<?php if( !empty($options['ninja_title']) ): ?>
						<h3 class="footer-section-title"><?php echo esc_html($options['ninja_title']); ?></h3>
					<?php endif; ?>
					<?php ninjatheme_footer_link_list( 'ninja_links' ); ?>
				</div>
				<div class="site-footer__column">
					<?php if( !empty($options['resource_title']) ): ?>
						<h3 class="footer-section-title"><?php echo esc_html($options['resource_title']); ?></h3>
					<?php endif; ?>
					<?php ninjatheme_footer_link_list( 'resource_links' ); ?>
				</div>
				<div class="site-footer__column">
					<?php if( !empty($options['connect_title']) ): ?>
						<h3 class="footer-section-title"><?php echo esc_html($options['connect_title']); ?></h3>
					<?php endif; ?>
					<ul class="footer-links">
						<?php if( !empty($options['connect_email']) ): ?>
							<li><a href="mailto:<?php echo esc_attr($options['connect_email']); ?>"><?php echo esc_html($options['connect_email']); ?></a></li>
						<?php endif; ?>
						<?php if( !empty($options['connect_location']) ): ?>
							<li><span><?php echo esc_html($options['connect_location']); ?></span></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>

			<div class="site-footer__bottom">
				<div class="site-footer__meta">
					<?php if( $copyright ): ?>
						<span class="footer-copyright"><?php echo esc_html($copyright); ?></span>
					<?php endif; ?>
					<?php if( have_rows('social_icons', 'option') ): ?>
						<ul class="social-icons">
							<?php while( have_rows('social_icons', 'option') ): the_row();
								$social_image = get_sub_field('social_image');
								$social_link = get_sub_field('social_link');

								if( $social_link && $social_image ): ?>
									<li class="social-icon-item">
										<a href="<?php echo esc_url($social_link); ?>" target="_blank" rel="noopener noreferrer" class="social-icon-link">
											<?php echo ninjatheme_get_responsive_image( $social_image, 'thumbnail', array( 'class' => 'social-icon-image', 'alt' => $social_image['alt'] ?: 'Social media icon' ) ); ?>
										</a>
									</li>
								<?php endif; ?>
							<?php endwhile; ?>
						</ul>
					<?php endif; ?>
				</div>
				<?php if( have_rows('legal_links', 'option') ): ?>
					<ul class="footer-legal">
						<?php while( have_rows('legal_links', 'option') ): the_row();
							$legal = get_sub_field('link');
							if( !empty($legal['url']) ): ?>
								<li><a href="<?php echo esc_url($legal['url']); ?>" target="<?php echo esc_attr($legal['target'] ?: '_self'); ?>"><?php echo esc_html($legal['title']); ?></a></li>
							<?php endif;
						endwhile; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
