<?php
/**
 * Block Name: Principles Grid
 *
 * Fields: tag (text), title (text),
 * items (repeater: lucide_icon, icon (fallback image), color [gray|purple|orange], title, description).
 * Bento-style grid; the layout repeats every 7 cards (2 + 2 + 3 per row).
 *
 * @package NinjaTheme
 */

$id = 'principles-grid-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$className = 'principles-grid';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$tag   = get_field( 'tag' );
$title = get_field( 'title' );
$items = get_field( 'items' );
$items = is_array( $items ) ? array_values( array_filter( $items, function ( $item ) {
	return ! empty( $item['title'] );
} ) ) : array();

$allowed_colors = array( 'gray', 'purple', 'orange' );
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="principles-grid__container container">

		<?php if ( ! empty( $tag ) || ! empty( $title ) ) : ?>
			<header class="principles-grid__header">
				<?php if ( ! empty( $tag ) ) : ?>
					<span class="principles-grid__tag"><?php echo esc_html( $tag ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="principles-grid__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="principles-grid__grid">
				<?php foreach ( $items as $i => $item ) :
					$color = ( ! empty( $item['color'] ) && in_array( $item['color'], $allowed_colors, true ) ) ? $item['color'] : 'gray';
					$icon  = ! empty( $item['icon'] ) ? $item['icon'] : null;
					$lucide = ! empty( $item['lucide_icon'] ) ? ninja_lucide( $item['lucide_icon'], array( 'size' => 28, 'class' => 'principles-grid__icon-svg' ) ) : '';
					$desc  = ! empty( $item['description'] ) ? $item['description'] : '';
					// Position inside the 7-card cycle drives the column span.
					$slot  = ( $i % 7 ) + 1;
				?>
					<article class="principles-grid__card principles-grid__card--<?php echo esc_attr( $color ); ?> principles-grid__card--slot-<?php echo esc_attr( $slot ); ?>">
						<span class="principles-grid__icon">
							<?php if ( $lucide ) {
								echo $lucide; // Sanitized, local SVG from ninja_lucide().
							} elseif ( $icon ) {
								echo ninjatheme_get_responsive_image( $icon, 'thumbnail', array( 'class' => 'principles-grid__icon-img', 'alt' => '' ) );
							} ?>
						</span>

						<div class="principles-grid__copy">
							<h3 class="principles-grid__card-title"><?php echo esc_html( $item['title'] ); ?></h3>

							<?php if ( ! empty( $desc ) ) : ?>
								<div class="principles-grid__card-description"><?php echo wp_kses_post( $desc ); ?></div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
