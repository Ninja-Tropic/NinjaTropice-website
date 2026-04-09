<?php
/**
 * Block Name: Related Solutions
 *
 * Fields: title (WYSIWYG), solutions (repeater), solution title (WYSIWYG),
 * solution description (WYSIWYG), image, link.
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'related-solutions-' . $block['id'];
if ( !empty($block['anchor']) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'related-solutions';
if ( !empty($block['className']) ) {
	$className .= ' ' . $block['className'];
}
if ( !empty($block['align']) ) {
	$className .= ' align' . $block['align'];
}

$block_data = !empty( $block['data'] ) && is_array( $block['data'] ) ? $block['data'] : array();

$title = get_field( 'title' );
if ( empty( $title ) && !empty( $block_data['title'] ) ) {
	$title = $block_data['title'];
}
if ( empty( $title ) ) {
	$title = get_field( 'section_title' );
}
if ( empty( $title ) && !empty( $block_data['section_title'] ) ) {
	$title = $block_data['section_title'];
}

$solutions = get_field( 'solution' );
if ( empty( $solutions ) ) {
	$solutions = get_field( 'solutions' );
}
if ( empty( $solutions ) && !empty( $block_data['solution'] ) && is_array( $block_data['solution'] ) ) {
	$solutions = $block_data['solution'];
}
if ( empty( $solutions ) && !empty( $block_data['solutions'] ) && is_array( $block_data['solutions'] ) ) {
	$solutions = $block_data['solutions'];
}

if ( !is_array( $solutions ) ) {
	$solutions = array();
}

if ( empty( $solutions ) ) {
	$repeater_name = '';

	if ( isset( $block_data['solution'] ) && is_numeric( $block_data['solution'] ) ) {
		$repeater_name = 'solution';
	} elseif ( isset( $block_data['solutions'] ) && is_numeric( $block_data['solutions'] ) ) {
		$repeater_name = 'solutions';
	}

	if ( !empty( $repeater_name ) ) {
		$row_count = (int) $block_data[ $repeater_name ];

		for ( $i = 0; $i < $row_count; $i++ ) {
			$prefix = $repeater_name . '_' . $i . '_';

			$solutions[] = array(
				'title' => isset( $block_data[ $prefix . 'title' ] ) ? $block_data[ $prefix . 'title' ] : '',
				'description' => isset( $block_data[ $prefix . 'description' ] ) ? $block_data[ $prefix . 'description' ] : '',
				'image' => isset( $block_data[ $prefix . 'image' ] ) ? $block_data[ $prefix . 'image' ] : '',
				'link' => isset( $block_data[ $prefix . 'link' ] ) ? $block_data[ $prefix . 'link' ] : '',
			);
		}
	}
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="related-solutions__container container">
		<?php if ( !empty($title) ): ?>
			<div class="related-solutions__intro">
				<div class="related-solutions__title">
					<?php echo wp_kses_post($title); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( !empty($solutions) ): ?>
			<div class="related-solutions__list">
				<?php foreach ( $solutions as $index => $solution ) : ?>
					<?php
					if ( !is_array($solution) ) {
						continue;
					}

						$item_title = !empty($solution['title']) ? $solution['title'] : '';
						if ( empty( $item_title ) && !empty( $solution['solution_title'] ) ) {
							$item_title = $solution['solution_title'];
						}

						$item_description = !empty($solution['description']) ? $solution['description'] : '';
						if ( empty( $item_description ) && !empty( $solution['solution_description'] ) ) {
							$item_description = $solution['solution_description'];
						}
						if ( empty( $item_description ) && !empty( $solution['content'] ) ) {
							$item_description = $solution['content'];
						}

						$item_image = !empty($solution['image']) ? $solution['image'] : null;
						if ( empty( $item_image ) && !empty( $solution['solution_image'] ) ) {
							$item_image = $solution['solution_image'];
						}

						$item_link = !empty($solution['link']) && is_array($solution['link']) ? $solution['link'] : null;
						if ( empty( $item_link ) && !empty( $solution['solution_link'] ) && is_array( $solution['solution_link'] ) ) {
							$item_link = $solution['solution_link'];
						}
						if ( empty( $item_link ) && !empty( $solution['button_link'] ) && is_array( $solution['button_link'] ) ) {
							$item_link = $solution['button_link'];
						}
					$item_classes = 'related-solutions__item';
					$has_text = !empty( trim( wp_strip_all_tags( (string) $item_title ) ) ) || !empty( trim( wp_strip_all_tags( (string) $item_description ) ) );
					$has_media = !empty( $item_image );
					$has_link = !empty( $item_link );

					if ( !$has_text && !$has_media && !$has_link ) {
						continue;
					}

					if ( $index % 2 !== 0 ) {
						$item_classes .= ' related-solutions__item--reverse';
					}
					?>
					<article class="<?php echo esc_attr($item_classes); ?>">
						<?php if ( !empty($item_image) ): ?>
							<div class="related-solutions__media">
								<?php if ( !empty($item_link) ): ?>
									<a
										href="<?php echo esc_url($item_link['url']); ?>"
										class="related-solutions__media-link"
										<?php if ( !empty($item_link['target']) ): ?>target="<?php echo esc_attr($item_link['target']); ?>"<?php endif; ?>
										<?php if ( !empty($item_link['title']) ): ?>aria-label="<?php echo esc_attr($item_link['title']); ?>"<?php endif; ?>
									>
								<?php endif; ?>
								<div class="related-solutions__image-wrap">
									<?php echo ninjatheme_get_responsive_image( $item_image, 'large', array( 'class' => 'related-solutions__image', 'loading' => 'lazy' ) ); ?>
								</div>
								<?php if ( !empty($item_link) ): ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<div class="related-solutions__content">
							<?php if ( !empty($item_title) ): ?>
								<div class="related-solutions__item-title">
									<?php echo wp_kses_post($item_title); ?>
								</div>
							<?php endif; ?>

							<?php if ( !empty($item_description) ): ?>
								<div class="related-solutions__item-description">
									<?php echo wp_kses_post($item_description); ?>
								</div>
							<?php endif; ?>

							<?php if ( !empty($item_link) ): ?>
								<div class="related-solutions__actions">
									<a
										href="<?php echo esc_url($item_link['url']); ?>"
										class="btn-ghost related-solutions__link"
										<?php if ( !empty($item_link['target']) ): ?>target="<?php echo esc_attr($item_link['target']); ?>"<?php endif; ?>
										<?php if ( !empty($item_link['title']) ): ?>aria-label="<?php echo esc_attr($item_link['title']); ?>"<?php endif; ?>
									>
										<?php echo esc_html(!empty($item_link['title']) ? $item_link['title'] : $item_link['url']); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
