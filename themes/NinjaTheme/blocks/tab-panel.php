<?php
/**
 * Block Name: Tab Panel
 *
 * @package NinjaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="nt-tabs-block__panel nt-tabs-block__panel--builder" data-tab-panel-preview>
	<div class="nt-tabs-block__panel-content">
		<?php if ( $is_preview ) : ?>
			<InnerBlocks templateLock="false" />
		<?php else : ?>
			<?php echo function_exists( 'do_blocks' ) ? do_blocks( $content ) : $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
	</div>
</div>
