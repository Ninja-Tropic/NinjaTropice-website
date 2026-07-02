<?php
/**
 * Block Name: Related Solutions — CPT
 *
 * Picks up to 3 Related Solution CPT posts and renders them as cards.
 *
 * @package NinjaTheme
 */

$id = 'solutions-picker-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$className = 'related-solutions solutions-picker';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

$section_title = get_field( 'section_title' );

$slots = array(
	get_field( 'solution_1' ),
	get_field( 'solution_2' ),
	get_field( 'solution_3' ),
);

$cards = array();
foreach ( $slots as $post_obj ) {
	if ( empty( $post_obj ) ) {
		continue;
	}
	$post_id = is_object( $post_obj ) ? $post_obj->ID : (int) $post_obj;
	if ( ! $post_id ) {
		continue;
	}
	$cards[] = array(
		'title'       => get_the_title( $post_id ),
		'description' => get_field( 'description', $post_id ),
		'image'       => get_field( 'image', $post_id ),
		'link'        => get_field( 'link', $post_id ),
	);
}

// Always output something so Gutenberg never gets an empty response.
if ( empty( $cards ) ) {
	echo '<div style="padding:2rem;background:#f5f5f5;text-align:center;border:2px dashed #ccc;border-radius:8px;">'
		. '<p style="margin:0;color:#888;font-size:.875rem;">'
		. '<strong>Related Solutions — CPT</strong><br>'
		. 'Selecciona hasta 3 Related Solutions en el panel lateral (Solution 1 / 2 / 3).'
		. '</p></div>';
	return;
}
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="related-solutions__container container">

		<?php if ( ! empty( $section_title ) ) : ?>
			<div class="related-solutions__intro">
				<div class="related-solutions__title">
					<h2><?php echo esc_html( $section_title ); ?></h2>
				</div>
			</div>
		<?php endif; ?>

		<div class="related-solutions__list">
			<?php foreach ( $cards as $card ) :
				$item_link = ! empty( $card['link'] ) && is_array( $card['link'] ) ? $card['link'] : null;
			?>
				<article class="related-solutions__item">

					<?php if ( ! empty( $card['image'] ) ) : ?>
						<div class="related-solutions__media">
							<?php if ( $item_link ) : ?>
								<a href="<?php echo esc_url( $item_link['url'] ); ?>" class="related-solutions__media-link"
									<?php if ( ! empty( $item_link['target'] ) ) echo 'target="' . esc_attr( $item_link['target'] ) . '"'; ?>
									aria-label="<?php echo esc_attr( $card['title'] ); ?>">
							<?php endif; ?>
							<div class="related-solutions__image-wrap">
								<?php echo ninjatheme_get_responsive_image(
									$card['image'],
									'medium',
									array( 'class' => 'related-solutions__image', 'loading' => 'lazy', 'alt' => $card['title'] )
								); ?>
							</div>
							<?php if ( $item_link ) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="related-solutions__content">

						<?php if ( ! empty( $card['title'] ) ) : ?>
							<div class="related-solutions__item-title">
								<p><?php echo esc_html( $card['title'] ); ?></p>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $card['description'] ) ) : ?>
							<div class="related-solutions__item-description">
								<?php echo wp_kses_post( nl2br( $card['description'] ) ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $item_link ) : ?>
							<div class="related-solutions__actions">
								<a href="<?php echo esc_url( $item_link['url'] ); ?>"
									class="btn related-solutions__link"
									<?php if ( ! empty( $item_link['target'] ) ) echo 'target="' . esc_attr( $item_link['target'] ) . '"'; ?>>
									<?php echo esc_html( ! empty( $item_link['title'] ) ? $item_link['title'] : $card['title'] ); ?>
								</a>
							</div>
						<?php endif; ?>

					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
