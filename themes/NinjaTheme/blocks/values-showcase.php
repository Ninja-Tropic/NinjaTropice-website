<?php
/**
 * Block Name: Values Showcase
 *
 * @package NinjaTheme
 */

$block_id = ! empty( $block['id'] ) ? $block['id'] : wp_unique_id( 'values-showcase-' );
$id = 'values-showcase-' . $block_id;
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = 'values-showcase';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$fields = get_fields();
if ( ! $fields ) {
	$fields = array();
}

$badge = ! empty( $fields['badge'] ) ? $fields['badge'] : '';
$eyebrow = ! empty( $fields['eyebrow'] ) ? $fields['eyebrow'] : '';
$title = ! empty( $fields['title'] ) ? $fields['title'] : '';
$description = ! empty( $fields['description'] ) ? $fields['description'] : '';
$items = ! empty( $fields['items'] ) && is_array( $fields['items'] ) ? $fields['items'] : array();
$has_featured_item = false;

foreach ( $items as $item ) {
	if ( ! empty( $item['featured'] ) ) {
		$has_featured_item = true;
		break;
	}
}
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class_name ); ?>">
	<div class="values-showcase__aurora values-showcase__aurora--one" aria-hidden="true"></div>
	<div class="values-showcase__aurora values-showcase__aurora--two" aria-hidden="true"></div>

	<div class="values-showcase__container container">
		<?php if ( ! empty( $badge ) || ! empty( $eyebrow ) || ! empty( $title ) || ! empty( $description ) ) : ?>
			<header class="values-showcase__header">
				<?php if ( ! empty( $badge ) ) : ?>
					<div class="values-showcase__badge"><?php echo esc_html( $badge ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $eyebrow ) ) : ?>
					<p class="values-showcase__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="values-showcase__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<div class="values-showcase__description">
						<?php echo wp_kses_post( $description ); ?>
					</div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="values-showcase__grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<?php
					$item_title = ! empty( $item['title'] ) ? $item['title'] : '';
					$item_kicker = ! empty( $item['kicker'] ) ? $item['kicker'] : '';
					$item_description = ! empty( $item['description'] ) ? $item['description'] : '';
					$item_icon = ! empty( $item['icon'] ) ? $item['icon'] : null;
					$item_is_featured = ! empty( $item['featured'] ) || ( ! $has_featured_item && 0 === $index );
					$item_classes = 'values-showcase__card';
					$item_classes .= $item_is_featured ? ' values-showcase__card--featured' : '';
					$item_classes .= ! empty( $item_description ) ? ' values-showcase__card--detailed' : ' values-showcase__card--compact';
					$item_number = str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT );
					$item_monogram_source = ! empty( $item_title ) ? wp_strip_all_tags( $item_title ) : $item_number;
					$item_monogram = function_exists( 'mb_substr' ) ? mb_substr( $item_monogram_source, 0, 1 ) : substr( $item_monogram_source, 0, 1 );
					?>
					<article class="<?php echo esc_attr( $item_classes ); ?>">
						<div class="values-showcase__card-head">
							<span class="values-showcase__index"><?php echo esc_html( $item_number ); ?></span>

							<?php if ( ! empty( $item_kicker ) ) : ?>
								<span class="values-showcase__kicker"><?php echo esc_html( $item_kicker ); ?></span>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $item_icon ) ) : ?>
							<div class="values-showcase__media">
								<div class="values-showcase__media-shell">
									<?php
									echo ninjatheme_get_responsive_image(
										$item_icon,
										'medium',
										array(
											'class' => 'values-showcase__icon',
											'alt'   => $item_title,
										)
									);
									?>
								</div>
							</div>
						<?php else : ?>
							<div class="values-showcase__media values-showcase__media--fallback" aria-hidden="true">
								<div class="values-showcase__monogram"><?php echo esc_html( strtoupper( $item_monogram ) ); ?></div>
							</div>
						<?php endif; ?>

						<div class="values-showcase__copy">
							<?php if ( ! empty( $item_title ) ) : ?>
								<h3 class="values-showcase__card-title"><?php echo esc_html( $item_title ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $item_description ) ) : ?>
								<div class="values-showcase__card-description">
									<?php echo wp_kses_post( $item_description ); ?>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php elseif ( ! empty( $is_preview ) ) : ?>
			<div class="values-showcase__empty">
				Add at least one value card to preview this section.
			</div>
		<?php endif; ?>
	</div>
</section>
