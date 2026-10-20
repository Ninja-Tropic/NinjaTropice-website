<?php
/**
 * Block Name: Blog Showcase
 *
 * Fields: tag (text), title (text), description (textarea),
 * featured_post (post object, optional), button_label (text).
 * Large featured post on the left + 3 smaller posts on the right.
 * Default: featured = latest post, side = posts 2-4.
 * With a chosen featured post: side = the 3 latest posts (excluding it).
 *
 * @package NinjaTheme
 */

$id = 'blog-showcase-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$className = 'blog-showcase';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$tag          = get_field( 'tag' );
$title        = get_field( 'title' );
$description  = get_field( 'description' );
$featured_id  = (int) get_field( 'featured_post' );
$button_label = get_field( 'button_label' );
if ( empty( $button_label ) ) {
	$button_label = __( 'Read more', 'ninjatheme' );
}

if ( $featured_id && 'publish' !== get_post_status( $featured_id ) ) {
	$featured_id = 0;
}

$query_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $featured_id ? 3 : 4,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'fields'              => 'ids',
);
if ( $featured_id ) {
	$query_args['post__not_in'] = array( $featured_id );
}

$ids = get_posts( $query_args );
if ( $featured_id ) {
	array_unshift( $ids, $featured_id );
}

$featured_post_id = array_shift( $ids );
$side_ids         = $ids;

if ( ! $featured_post_id ) {
	echo '<div style="padding:2rem;background:#f5f5f5;text-align:center;border:2px dashed #ccc;border-radius:8px;">'
		. '<p style="margin:0;color:#888;font-size:.875rem;"><strong>Blog Showcase</strong><br>'
		. 'No hay posts publicados todavia.</p></div>';
	return;
}
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="blog-showcase__container container">

		<header class="blog-showcase__header">
			<?php if ( ! empty( $tag ) ) : ?>
				<span class="blog-showcase__tag"><?php echo esc_html( $tag ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $title ) ) : ?>
				<h2 class="blog-showcase__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<div class="blog-showcase__description"><?php echo wp_kses_post( $description ); ?></div>
			<?php endif; ?>
		</header>

		<div class="blog-showcase__grid">
			<?php
			$featured_title = get_the_title( $featured_post_id );
			$featured_link  = get_permalink( $featured_post_id );
			?>
			<article class="blog-showcase__featured">
				<?php if ( has_post_thumbnail( $featured_post_id ) ) : ?>
					<a href="<?php echo esc_url( $featured_link ); ?>" class="blog-showcase__featured-media" aria-hidden="true" tabindex="-1">
						<?php echo get_the_post_thumbnail(
							$featured_post_id,
							'large',
							array(
								'class'   => 'blog-showcase__img',
								'loading' => 'lazy',
								'alt'     => esc_attr( $featured_title ),
							)
						); ?>
					</a>
				<?php endif; ?>

				<h3 class="blog-showcase__featured-title">
					<a href="<?php echo esc_url( $featured_link ); ?>"><?php echo esc_html( $featured_title ); ?></a>
				</h3>

				<a href="<?php echo esc_url( $featured_link ); ?>" class="blog-showcase__button">
					<?php echo esc_html( $button_label ); ?>
				</a>
			</article>

			<?php if ( ! empty( $side_ids ) ) : ?>
				<div class="blog-showcase__list">
					<?php foreach ( $side_ids as $side_id ) :
						$side_title = get_the_title( $side_id );
						$side_link  = get_permalink( $side_id );
					?>
						<article class="blog-showcase__item">
							<?php if ( has_post_thumbnail( $side_id ) ) : ?>
								<a href="<?php echo esc_url( $side_link ); ?>" class="blog-showcase__item-media" aria-hidden="true" tabindex="-1">
									<?php echo get_the_post_thumbnail(
										$side_id,
										'medium',
										array(
											'class'   => 'blog-showcase__img',
											'loading' => 'lazy',
											'alt'     => esc_attr( $side_title ),
										)
									); ?>
								</a>
							<?php endif; ?>

							<h3 class="blog-showcase__item-title">
								<a href="<?php echo esc_url( $side_link ); ?>"><?php echo esc_html( $side_title ); ?></a>
							</h3>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
