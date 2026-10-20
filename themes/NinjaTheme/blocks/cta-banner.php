<?php
/**
 * Block Name: CTA Banner
 *
 * Fields: title (text), description (textarea), button (link).
 * Purple gradient banner with text on the left and a button on the right.
 *
 * @package NinjaTheme
 */

$id = 'cta-banner-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$className = 'cta-banner';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$title       = get_field( 'title' );
$description = get_field( 'description' );
$button      = get_field( 'button' );
$button      = ( ! empty( $button ) && is_array( $button ) && ! empty( $button['url'] ) ) ? $button : null;
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="cta-banner__container container">
		<div class="cta-banner__inner">
			<div class="cta-banner__content">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="cta-banner__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<div class="cta-banner__description"><?php echo wp_kses_post( $description ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( $button ) : ?>
				<a href="<?php echo esc_url( $button['url'] ); ?>"
				   class="cta-banner__button"
				   <?php if ( ! empty( $button['target'] ) ) : ?>target="<?php echo esc_attr( $button['target'] ); ?>" rel="noopener"<?php endif; ?>>
					<?php echo esc_html( ! empty( $button['title'] ) ? $button['title'] : $button['url'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
