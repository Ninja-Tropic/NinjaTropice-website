<?php
/**
 * Block Name: Post Highlights
 *
 * Selects up to 3 posts (any public post type) and displays them as cards.
 * 1 post: image left, content right. 2-3 posts: image top, content bottom (grid).
 *
 * @package NinjaTheme
 */

$id = 'post-highlights-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$rows = get_field( 'posts' );
if ( ! is_array( $rows ) ) {
	$rows = array();
}

$posts = array();
foreach ( $rows as $row ) {
	if ( ! empty( $row['post'] ) ) {
		$posts[] = $row['post'];
	}
}
$posts = array_slice( $posts, 0, 3 );
$count = count( $posts );

$className = 'post-highlights';
$className .= $count === 1 ? ' post-highlights--single' : ' post-highlights--grid post-highlights--grid-' . $count;
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

if ( empty( $posts ) ) {
	echo '<div style="padding:2rem;background:#f5f5f5;text-align:center;border:2px dashed #ccc;border-radius:8px;">'
		. '<p style="margin:0;color:#888;font-size:.875rem;">'
		. '<strong>Post Highlights</strong><br>'
		. 'Selecciona entre 1 y 3 posts en el panel lateral.'
		. '</p></div>';
	return;
}
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="post-highlights__container container">
		<div class="post-highlights__list">
			<?php foreach ( $posts as $post_obj ) :
				$post_id = is_object( $post_obj ) ? $post_obj->ID : (int) $post_obj;
				if ( ! $post_id ) {
					continue;
				}

				$post_title   = get_the_title( $post_id );
				$post_excerpt = get_the_excerpt( $post_id );
				$post_link    = get_permalink( $post_id );
			?>
				<article class="post-highlights__item">
					<?php if ( has_post_thumbnail( $post_id ) ) : ?>
						<a href="<?php echo esc_url( $post_link ); ?>" class="post-highlights__media" aria-hidden="true" tabindex="-1">
							<?php echo get_the_post_thumbnail(
								$post_id,
								'large',
								array(
									'class'   => 'post-highlights__image',
									'loading' => 'lazy',
									'alt'     => esc_attr( $post_title ),
								)
							); ?>
						</a>
					<?php endif; ?>

					<div class="post-highlights__content">
						<?php if ( ! empty( $post_title ) ) : ?>
							<h3 class="post-highlights__title">
								<a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
							</h3>
						<?php endif; ?>

						<?php if ( ! empty( $post_excerpt ) ) : ?>
							<div class="post-highlights__excerpt">
								<?php echo wp_kses_post( $post_excerpt ); ?>
							</div>
						<?php endif; ?>

						<div class="post-highlights__actions">
							<a href="<?php echo esc_url( $post_link ); ?>" class="btn post-highlights__link">
								<?php esc_html_e( 'Learn more', 'ninjatheme' ); ?>
							</a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
