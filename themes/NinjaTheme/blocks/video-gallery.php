<?php
/**
 * Block Name: Video Gallery
 * Filtered video grid with tab pills. Default: show all sections.
 *
 * @package NinjaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id = 'video-gallery-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$class_name = 'nt-vg';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

$fields = get_fields();
$raw_tabs = ! empty( $fields['tabs'] ) && is_array( $fields['tabs'] ) ? $fields['tabs'] : array();

if ( empty( $raw_tabs ) ) {
	echo '<section id="' . esc_attr( $id ) . '" class="' . esc_attr( $class_name ) . '"><p class="nt-vg__empty">' . esc_html__( 'Add at least one tab to display the gallery.', 'ninjatheme' ) . '</p></section>';
	return;
}

// Build normalised tab list with a stable slug.
$tabs = array();
$used_slugs = array();
foreach ( $raw_tabs as $i => $tab ) {
	$title     = ! empty( $tab['tab_title'] ) ? $tab['tab_title'] : sprintf( __( 'Tab %d', 'ninjatheme' ), $i + 1 );
	$base_slug = sanitize_title( $title );
	if ( '' === $base_slug ) {
		$base_slug = 'tab-' . ( $i + 1 );
	}
	$slug   = $base_slug;
	$suffix = 2;
	while ( in_array( $slug, $used_slugs, true ) ) {
		$slug = $base_slug . '-' . $suffix++;
	}
	$used_slugs[] = $slug;

	$tabs[] = array(
		'title'       => $title,
		'description' => ! empty( $tab['tab_description'] ) ? $tab['tab_description'] : '',
		'slug'        => $slug,
		'sections'    => ! empty( $tab['sections'] ) && is_array( $tab['sections'] ) ? $tab['sections'] : array(),
	);
}

$allowed_iframe = array(
	'iframe' => array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'frameborder'     => true,
		'allow'           => true,
		'allowfullscreen' => true,
		'loading'         => true,
		'style'           => true,
		'class'           => true,
		'id'              => true,
		'title'           => true,
	),
);
$allowed_video = array_merge( wp_kses_allowed_html( 'post' ), $allowed_iframe );
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class_name ); ?>" data-vg-block>

	<!-- Pills -->
	<div class="nt-vg__pills" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'ninjatheme' ); ?>">
		<button
			class="nt-vg__pill nt-vg__pill--active"
			type="button"
			data-vg-filter="all"
			aria-pressed="true"
		><?php esc_html_e( 'Show All', 'ninjatheme' ); ?></button>

		<?php foreach ( $tabs as $tab ) : ?>
			<button
				class="nt-vg__pill"
				type="button"
				data-vg-filter="<?php echo esc_attr( $tab['slug'] ); ?>"
				aria-pressed="false"
			><?php echo esc_html( $tab['title'] ); ?></button>
		<?php endforeach; ?>
	</div>

	<!-- Active tab description (hidden by default, shown when a specific tab pill is active) -->
	<?php foreach ( $tabs as $tab ) : ?>
		<?php if ( ! empty( $tab['description'] ) ) : ?>
			<p
				class="nt-vg__tab-desc"
				data-vg-desc="<?php echo esc_attr( $tab['slug'] ); ?>"
				hidden
			><?php echo wp_kses( nl2br( $tab['description'] ), array( 'br' => array() ) ); ?></p>
		<?php endif; ?>
	<?php endforeach; ?>

	<!-- Cards grid -->
	<div class="nt-vg__grid" data-vg-grid>
		<?php foreach ( $tabs as $tab ) : ?>
			<?php foreach ( $tab['sections'] as $section ) : ?>
				<?php
				$s_title = ! empty( $section['title'] ) ? $section['title'] : '';
				$s_tag   = ! empty( $section['tagline'] ) ? $section['tagline'] : '';
				$s_desc  = ! empty( $section['description'] ) ? $section['description'] : '';
				$s_video = ! empty( $section['video_url'] ) ? trim( $section['video_url'] ) : '';

				$embed_html    = '';
				$thumb_url     = '';
				if ( $s_video && filter_var( $s_video, FILTER_VALIDATE_URL ) ) {
					// Auto-thumbnail for YouTube.
					if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_-]{11})/', $s_video, $m ) ) {
						$thumb_url = 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
					}
					if ( function_exists( 'ninjatheme_get_cached_oembed' ) ) {
						$embed_html = ninjatheme_get_cached_oembed( $s_video );
					} else {
						$embed_html = wp_oembed_get( $s_video );
					}
					if ( $embed_html && strpos( $embed_html, '<iframe' ) !== false ) {
						$embed_html = str_replace( '<iframe', '<iframe loading="lazy"', $embed_html );
					}
				}
				?>
				<article
					class="nt-vg__card"
					data-vg-tab="<?php echo esc_attr( $tab['slug'] ); ?>"
				>
					<?php if ( $embed_html || $thumb_url ) : ?>
						<div class="nt-vg__card-media">
							<?php if ( $embed_html ) : ?>
								<div class="nt-vg__video-wrap">
									<?php if ( $thumb_url ) : ?>
										<div class="nt-vg__placeholder" data-vg-embed="<?php echo esc_attr( base64_encode( $embed_html ) ); ?>">
											<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $s_title ); ?>" loading="lazy" />
											<button class="nt-vg__play" type="button" aria-label="<?php esc_attr_e( 'Play video', 'ninjatheme' ); ?>">
												<svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
													<path d="M8 5.5v13l11-6.5-11-6.5z" fill="currentColor"/>
												</svg>
											</button>
										</div>
									<?php else : ?>
										<?php echo wp_kses( $embed_html, $allowed_video ); ?>
									<?php endif; ?>
								</div>
							<?php elseif ( $thumb_url ) : ?>
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $s_title ); ?>" loading="lazy" class="nt-vg__thumb" />
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="nt-vg__card-body">
						<?php if ( $s_title ) : ?>
							<h3 class="nt-vg__card-title"><?php echo esc_html( $s_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $s_tag ) : ?>
							<div class="nt-vg__tagline"><?php echo wp_kses_post( $s_tag ); ?></div>
						<?php endif; ?>
						<?php if ( $s_desc ) : ?>
							<div class="nt-vg__card-desc-wrap">
								<div class="nt-vg__card-desc"><?php echo wp_kses_post( $s_desc ); ?></div>
								<button class="nt-vg__toggle" type="button" aria-expanded="false">
									<span class="nt-vg__toggle-more">Ver más <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg></span>
									<span class="nt-vg__toggle-less" hidden>Ver menos <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 15l-6-6-6 6"/></svg></span>
								</button>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>

</section>

<script>
(function () {
	'use strict';
	document.querySelectorAll('[data-vg-block]').forEach(function (block) {
		var pills     = block.querySelectorAll('[data-vg-filter]');
		var cards     = block.querySelectorAll('[data-vg-tab]');
		var descs     = block.querySelectorAll('[data-vg-desc]');
		var active    = 'all';

		function applyFilter(filter) {
			active = filter;

			pills.forEach(function (pill) {
				var isActive = pill.dataset.vgFilter === filter;
				pill.classList.toggle('nt-vg__pill--active', isActive);
				pill.setAttribute('aria-pressed', isActive ? 'true' : 'false');
			});

			cards.forEach(function (card) {
				var show = filter === 'all' || card.dataset.vgTab === filter;
				card.hidden = !show;
			});

			descs.forEach(function (desc) {
				desc.hidden = desc.dataset.vgDesc !== filter;
			});
		}

		pills.forEach(function (pill) {
			pill.addEventListener('click', function () {
				applyFilter(pill.dataset.vgFilter);
			});
		});

		// Lazy-load: swap placeholder for iframe on play click.
		block.querySelectorAll('[data-vg-embed]').forEach(function (placeholder) {
			var btn = placeholder.querySelector('.nt-vg__play');
			if (!btn) return;
			btn.addEventListener('click', function () {
				var html = atob(placeholder.dataset.vgEmbed);
				var wrapper = placeholder.parentNode;
				wrapper.innerHTML = html;
			});
		});

		// Ver más / Ver menos toggle.
		block.querySelectorAll('.nt-vg__toggle').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var wrap = btn.closest('.nt-vg__card-desc-wrap');
				var desc = wrap.querySelector('.nt-vg__card-desc');
				var expanded = btn.getAttribute('aria-expanded') === 'true';
				btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
				btn.querySelector('.nt-vg__toggle-more').hidden = !expanded;
				btn.querySelector('.nt-vg__toggle-less').hidden = expanded;
				desc.classList.toggle('is-expanded', !expanded);
			});
		});
	});
})();
</script>
