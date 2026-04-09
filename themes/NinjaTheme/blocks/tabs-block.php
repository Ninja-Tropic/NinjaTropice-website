<?php
/**
 * Block Name: Tabs Block
 *
 * @package NinjaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id = 'tabs-block-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = 'nt-tabs-block';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

$fields = get_fields();
if ( ! is_array( $fields ) ) {
	$fields = array();
}

$content_mode = ! empty( $fields['content_mode'] ) && 'inner_blocks' === $fields['content_mode'] ? 'inner_blocks' : 'wysiwyg';
$class_name  .= ' nt-tabs-block--mode-' . sanitize_html_class( $content_mode );

$tabs = array();
$used_tab_slugs = array();
if ( ! empty( $fields['tabs'] ) && is_array( $fields['tabs'] ) ) {
	foreach ( $fields['tabs'] as $index => $tab ) {
		if ( ! is_array( $tab ) ) {
			continue;
		}

		$tab_title = ! empty( $tab['tab_title'] ) ? $tab['tab_title'] : sprintf( __( 'Tab %d', 'ninjatheme' ), $index + 1 );
		$base_tab_slug = sanitize_title( $tab_title );
		if ( '' === $base_tab_slug ) {
			$base_tab_slug = 'tab-' . ( $index + 1 );
		}

		$tab_slug = $base_tab_slug;
		$suffix = 2;
		while ( in_array( $tab_slug, $used_tab_slugs, true ) ) {
			$tab_slug = $base_tab_slug . '-' . $suffix;
			++$suffix;
		}

		$used_tab_slugs[] = $tab_slug;

		$tabs[] = array(
			'tab_title'   => $tab_title,
			'tab_content' => ! empty( $tab['tab_content'] ) ? $tab['tab_content'] : '',
			'tab_slug'    => $tab_slug,
		);
	}
}

if ( empty( $tabs ) ) :
	?>
	<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class_name ); ?>">
		<div class="nt-tabs-block__empty">
			<?php esc_html_e( 'Add at least one tab to display this block.', 'ninjatheme' ); ?>
		</div>
	</section>
	<?php
	return;
endif;

$active_index = 0;
$requested_tab_slug = '';
if ( isset( $_GET['tab'] ) ) {
	$requested_tab_slug = sanitize_title( wp_unslash( $_GET['tab'] ) );
}

if ( '' !== $requested_tab_slug ) {
	foreach ( $tabs as $index => $tab ) {
		if ( $requested_tab_slug === $tab['tab_slug'] ) {
			$active_index = $index;
			break;
		}
	}
}

$allowed_blocks = array( 'acf/tab-panel' );
$processed_panels = array(
	'count' => 0,
	'markup' => '',
);

if ( 'inner_blocks' === $content_mode && ! empty( trim( $content ) ) ) {
	$inner_markup = function_exists( 'do_blocks' ) ? do_blocks( $content ) : $content;

	if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
		$processor = new WP_HTML_Tag_Processor( $inner_markup );
		$panel_index = 0;
		$tab_total = count( $tabs );

			while ( $processor->next_tag( array( 'class_name' => 'nt-tabs-block__panel' ) ) ) {
				$processor->remove_attribute( 'data-tab-panel-preview' );

				if ( $panel_index < $tab_total ) {
					$button_id = sprintf( '%s-tab-%d', $id, $panel_index + 1 );
					$panel_id = sprintf( '%s-panel-%d', $id, $panel_index + 1 );
					$is_active = $active_index === $panel_index;

					$processor->set_attribute( 'id', $panel_id );
					$processor->set_attribute( 'role', 'tabpanel' );
					$processor->set_attribute( 'aria-labelledby', $button_id );
					$processor->set_attribute( 'tabindex', $is_active ? '0' : '-1' );
					$processor->set_attribute( 'data-tab-panel', (string) $panel_index );
					$processor->set_attribute( 'data-tab-slug', $tabs[ $panel_index ]['tab_slug'] );
					$processor->set_attribute( 'aria-hidden', $is_active ? 'false' : 'true' );

					if ( $is_active ) {
						$processor->add_class( 'is-active' );
						$processor->remove_attribute( 'hidden' );
					} else {
						$processor->remove_class( 'is-active' );
						$processor->set_attribute( 'hidden', 'hidden' );
					}
				} else {
					$processor->remove_attribute( 'data-tab-panel' );
					$processor->remove_attribute( 'data-tab-slug' );
					$processor->set_attribute( 'data-tab-extra-panel', (string) $panel_index );
					$processor->set_attribute( 'aria-hidden', 'true' );
					$processor->set_attribute( 'hidden', 'hidden' );
				}

			++$panel_index;
		}

		$processed_panels['count'] = $panel_index;
		$processed_panels['markup'] = $processor->get_updated_html();
	} else {
		$processed_panels['markup'] = $inner_markup;
	}
}
?>

<section
	id="<?php echo esc_attr( $id ); ?>"
	class="<?php echo esc_attr( $class_name ); ?>"
	data-tabs-block
	data-tabs-mode="<?php echo esc_attr( $content_mode ); ?>"
	data-tabs-param="tab"
	data-active-tab="<?php echo esc_attr( $active_index ); ?>"
>
	<div class="nt-tabs-block__nav" role="tablist" aria-label="<?php esc_attr_e( 'Tabs navigation', 'ninjatheme' ); ?>">
		<?php foreach ( $tabs as $index => $tab ) : ?>
			<?php
			$button_id = sprintf( '%s-tab-%d', $id, $index + 1 );
			$panel_id = sprintf( '%s-panel-%d', $id, $index + 1 );
			$is_active = $active_index === $index;
			?>
			<button
				id="<?php echo esc_attr( $button_id ); ?>"
				class="nt-tabs-block__tab<?php echo $is_active ? ' is-active' : ''; ?>"
				type="button"
				role="tab"
				aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
				aria-controls="<?php echo esc_attr( $panel_id ); ?>"
				tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
				data-tab-button="<?php echo esc_attr( $index ); ?>"
				data-tab-slug="<?php echo esc_attr( $tab['tab_slug'] ); ?>"
			>
				<?php echo esc_html( $tab['tab_title'] ); ?>
			</button>
		<?php endforeach; ?>
	</div>

	<div class="nt-tabs-block__panels">
		<?php if ( 'inner_blocks' === $content_mode ) : ?>
			<?php if ( $is_preview ) : ?>
				<div class="nt-tabs-block__editor-note">
					<?php esc_html_e( 'Add one Tab Panel block per tab and keep the order matched with the repeater rows.', 'ninjatheme' ); ?>
				</div>
				<InnerBlocks
					allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"
					templateLock="false"
				/>
			<?php else : ?>
				<?php
				echo $processed_panels['markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				for ( $panel_index = $processed_panels['count']; $panel_index < count( $tabs ); $panel_index++ ) :
					$button_id = sprintf( '%s-tab-%d', $id, $panel_index + 1 );
					$panel_id = sprintf( '%s-panel-%d', $id, $panel_index + 1 );
					$is_active = $active_index === $panel_index;
					?>
					<div
						id="<?php echo esc_attr( $panel_id ); ?>"
						class="nt-tabs-block__panel<?php echo $is_active ? ' is-active' : ''; ?>"
						role="tabpanel"
						aria-labelledby="<?php echo esc_attr( $button_id ); ?>"
						tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
						aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>"
						data-tab-panel="<?php echo esc_attr( $panel_index ); ?>"
						data-tab-slug="<?php echo esc_attr( $tabs[ $panel_index ]['tab_slug'] ); ?>"
						<?php if ( ! $is_active ) : ?>hidden="hidden"<?php endif; ?>
					>
						<div class="nt-tabs-block__panel-content"></div>
					</div>
				<?php endfor; ?>
			<?php endif; ?>
		<?php else : ?>
			<?php foreach ( $tabs as $index => $tab ) : ?>
				<?php
				$button_id = sprintf( '%s-tab-%d', $id, $index + 1 );
				$panel_id = sprintf( '%s-panel-%d', $id, $index + 1 );
				$is_active = $active_index === $index;
				?>
				<div
					id="<?php echo esc_attr( $panel_id ); ?>"
					class="nt-tabs-block__panel<?php echo $is_active ? ' is-active' : ''; ?>"
					role="tabpanel"
					aria-labelledby="<?php echo esc_attr( $button_id ); ?>"
					tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
					aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>"
					data-tab-panel="<?php echo esc_attr( $index ); ?>"
					data-tab-slug="<?php echo esc_attr( $tab['tab_slug'] ); ?>"
					<?php if ( ! $is_active ) : ?>hidden="hidden"<?php endif; ?>
				>
					<div class="nt-tabs-block__panel-content">
						<?php echo wp_kses_post( $tab['tab_content'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>
