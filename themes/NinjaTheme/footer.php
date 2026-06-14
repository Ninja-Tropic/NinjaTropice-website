<?php
/**
 * The footer template file
 *
 * @package NinjaTheme
 */
$options = get_fields('option');
?>

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="columns">
				<div class="column">
					<?php if( !empty($options['logo']['url']) ): ?>
						<?php echo ninjatheme_get_responsive_image( $options['logo'], 'medium', array( 'class' => 'footer-logo', 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
					<?php if( have_rows('social_icons', 'option') ): ?>
						<ul class="social-icons my-5">
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
					<?php if( !empty($options['info']) ): ?>
						<?php echo wp_kses_post($options['info']); ?>
					<?php endif; ?>
				</div>
			<div class="column">
				<?php if( !empty($options['service_title']) ): ?>
					<h3 class="footer-section-title"><?php echo esc_html($options['service_title']); ?></h3>
				<?php endif; ?>
				
			<?php if( have_rows('service_links', 'option') ): ?>
				<ul class="footer-links">
					<?php while( have_rows('service_links', 'option') ): the_row();
						$link = get_sub_field('link');
						$label = get_sub_field('label');

						if( $link ):
							$link_url = is_array($link) ? $link['url'] : $link;
							$link_target = is_array($link) && !empty($link['target']) ? $link['target'] : '_self';
							if( $label ) {
								$link_title = $label;
							} elseif( is_array($link) && !empty($link['title']) ) {
								$link_title = $link['title'];
							} else {
								$post_id = url_to_postid($link_url);
								$link_title = $post_id ? get_the_title($post_id) : basename(rtrim($link_url, '/'));
							}
							?>
							<li>
								<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php echo esc_html($link_title); ?>
								</a>
							</li>
						<?php endif; ?>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
			</div>
				<div class="column">
				<?php if( !empty($options['ninja_title']) ): ?> 
					<h3 class="footer-section-title"><?php echo esc_html($options['ninja_title']); ?></h3>
				<?php endif; ?>
				
				<?php if( have_rows('ninja_links', 'option') ): ?>
					<ul class="footer-links">
						<?php while( have_rows('ninja_links', 'option') ): the_row();
							$link = get_sub_field('link');
							$label = get_sub_field('label');

							if( $link ):
								$link_url = is_array($link) ? $link['url'] : $link;
								$link_target = is_array($link) && !empty($link['target']) ? $link['target'] : '_self';
								if( $label ) {
									$link_title = $label;
								} elseif( is_array($link) && !empty($link['title']) ) {
									$link_title = $link['title'];
								} else {
									$post_id = url_to_postid($link_url);
									$link_title = $post_id ? get_the_title($post_id) : basename(rtrim($link_url, '/'));
								}
								?>
								<li>
									<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
										<?php echo esc_html($link_title); ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
			</div>
				<div class="column">
				<?php if( !empty($options['resource_title']) ): ?>
					<h3 class="footer-section-title"><?php echo esc_html($options['resource_title']); ?></h3>
				<?php endif; ?>
				
				<?php if( have_rows('resource_links', 'option') ): ?>
					<ul class="footer-links">
						<?php while( have_rows('resource_links', 'option') ): the_row();
							$link = get_sub_field('link');
							$label = get_sub_field('label');

							if( $link ):
								$link_url = is_array($link) ? $link['url'] : $link;
								$link_target = is_array($link) && !empty($link['target']) ? $link['target'] : '_self';
								if( $label ) {
									$link_title = $label;
								} elseif( is_array($link) && !empty($link['title']) ) {
									$link_title = $link['title'];
								} else {
									$post_id = url_to_postid($link_url);
									$link_title = $post_id ? get_the_title($post_id) : basename(rtrim($link_url, '/'));
								}
								?>
								<li>
									<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
										<?php echo esc_html($link_title); ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
			</div>
			</div>
			<div class="site-info" style="display: none;">
				<p>
					&copy; <?php echo date( 'Y' ); ?> 
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php bloginfo( 'name' ); ?>
					</a>
				</p>
				<p>
					<?php
					printf(
						esc_html__( 'Proudly powered by %s' ),
						'<a href="' . esc_url( __( 'https://wordpress.org/' ) ) . '">WordPress</a>'
					);
					?>
				</p>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
