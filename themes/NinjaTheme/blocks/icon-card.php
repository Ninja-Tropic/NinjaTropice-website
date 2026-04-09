<?php
/**
 * Block Name: Icon Card
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'icon-card-' . $block['id'];
if ( !empty($block['anchor']) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'icon-card';
if ( !empty($block['className']) ) {
	$className .= ' ' . $block['className'];
}
if ( !empty($block['align']) ) {
	$className .= ' align' . $block['align'];
}

// Get ACF fields.
$f = get_fields();
if ( !$f ) {
	$f = array();
}

$title = !empty($f['title']) ? $f['title'] : '';
$description = !empty($f['description']) ? $f['description'] : '';
$icon = !empty($f['icon']) ? $f['icon'] : null;
$link = !empty($f['link']) && is_array($f['link']) ? $f['link'] : null;
$wrapper_tag = $link ? 'a' : 'div';

$link_label = '';
if ( $link && !empty($link['title']) ) {
	$link_label = $link['title'];
} elseif ( !empty($title) ) {
	$link_label = wp_strip_all_tags( $title );
} elseif ( !empty($description) ) {
	$link_label = wp_strip_all_tags( $description );
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="icon-card__container container">
		<<?php echo esc_attr($wrapper_tag); ?>
			class="icon-card__inner"
			<?php if ( $link ) : ?>
				href="<?php echo esc_url($link['url']); ?>"
				<?php if ( !empty($link['target']) ) : ?>target="<?php echo esc_attr($link['target']); ?>"<?php endif; ?>
				<?php if ( !empty($link_label) ) : ?>aria-label="<?php echo esc_attr($link_label); ?>"<?php endif; ?>
			<?php endif; ?>
		>
			<?php if ( !empty($icon) ) : ?>
				<div class="icon-card__media">
					<?php echo ninjatheme_get_responsive_image( $icon, 'medium', array( 'class' => 'icon-card__icon', 'alt' => wp_strip_all_tags( $link_label ) ) ); ?>
				</div>
			<?php endif; ?>

			<div class="icon-card__content">
				<?php if ( !empty($title) ) : ?>
					<div class="icon-card__title">
						<?php echo wp_kses_post($title); ?>
					</div>
				<?php endif; ?>

				<?php if ( !empty($description) ) : ?>
					<div class="icon-card__description">
						<?php echo wp_kses_post($description); ?>
					</div>
				<?php endif; ?>
			</div>
		</<?php echo esc_attr($wrapper_tag); ?>>
	</div>
</section>
