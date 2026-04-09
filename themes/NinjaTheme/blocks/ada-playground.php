<?php
/**
 * Block Name: ADA Playground
 *
 * @package NinjaTheme
 */

$id = 'ada-playground-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$className = 'ada-playground';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$f = get_fields();
if ( ! $f ) {
	$f = array();
}

$title = ! empty( $f['title'] ) ? $f['title'] : 'ADA Contrast Playground';
$description = ! empty( $f['description'] ) ? $f['description'] : 'Test foreground and background colors, then compare the live contrast ratio against WCAG AA and AAA requirements.';
$preview_text = ! empty( $f['preview_text'] ) ? $f['preview_text'] : 'Inspiring eLearning video and online training animation.';
$foreground_color = ! empty( $f['foreground_color'] ) ? sanitize_hex_color( $f['foreground_color'] ) : '#2F3351';
$background_color = ! empty( $f['background_color'] ) ? sanitize_hex_color( $f['background_color'] ) : '#ffffff';

if ( '#1637ff' === strtolower( (string) $foreground_color ) ) {
	$foreground_color = '#2F3351';
}

if ( empty( $foreground_color ) ) {
	$foreground_color = '#2F3351';
}

if ( empty( $background_color ) ) {
	$background_color = '#ffffff';
}

$font_options = array(
	array(
		'label' => 'Arial',
		'value' => 'Arial, Helvetica, sans-serif',
	),
	array(
		'label' => 'Verdana',
		'value' => 'Verdana, Geneva, sans-serif',
	),
	array(
		'label' => 'Trebuchet MS',
		'value' => '"Trebuchet MS", Helvetica, sans-serif',
	),
	array(
		'label' => 'Georgia',
		'value' => 'Georgia, "Times New Roman", serif',
	),
	array(
		'label' => 'Tahoma',
		'value' => 'Tahoma, Geneva, sans-serif',
	),
);

$weight_options = array(
	array(
		'label' => 'Normal',
		'value' => '400',
	),
	array(
		'label' => 'Medium',
		'value' => '500',
	),
	array(
		'label' => 'Semibold',
		'value' => '600',
	),
	array(
		'label' => 'Bold',
		'value' => '700',
	),
);

$normal_size_options = array( 12, 14, 16, 18, 20, 24 );
$large_size_options = array( 14, 18, 24, 32, 40, 48 );
$heading_id = $id . '-title';
$description_id = $id . '-description';
?>

<section
	id="<?php echo esc_attr( $id ); ?>"
	class="<?php echo esc_attr( $className ); ?>"
	data-foreground-color="<?php echo esc_attr( $foreground_color ); ?>"
	data-background-color="<?php echo esc_attr( $background_color ); ?>"
	data-preview-text="<?php echo esc_attr( $preview_text ); ?>"
>
	<div class="ada-playground__container container">
		<div class="ada-playground__card ada-playground__card--workspace">
			<div class="ada-playground__card-head">
				<div class="ada-playground__header">
					<span class="ada-playground__eyebrow">ADA Contrast Test</span>
					<?php if ( ! empty( $title ) ) : ?>
						<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="ada-playground__title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $description ) ) : ?>
						<p id="<?php echo esc_attr( $description_id ); ?>" class="ada-playground__description"><?php echo wp_kses_post( $description ); ?></p>
					<?php endif; ?>
				</div>

				<div class="ada-playground__summary-card" data-summary-panel aria-live="polite">
					<div class="ada-playground__summary-top">
						<div>
							<span class="ada-playground__summary-kicker">Live verdict</span>
							<p class="ada-playground__summary-message" data-overall-message>Choose colors to see whether the combination supports body text, large text, or neither.</p>
						</div>
						<span class="ada-playground__status-pill is-fail" data-overall-status>Checking</span>
					</div>

					<div class="ada-playground__summary-list">
						<div class="ada-playground__summary-item">
							<span>Body text</span>
							<strong data-overall-body>Pending</strong>
						</div>
						<div class="ada-playground__summary-item">
							<span>Large text</span>
							<strong data-overall-large>Pending</strong>
						</div>
						<div class="ada-playground__summary-item">
							<span>Highest level</span>
							<strong data-overall-tier>Pending</strong>
						</div>
					</div>
				</div>
			</div>

			<div class="ada-playground__panel-grid">
				<div class="ada-playground__panel ada-playground__panel--color">
					<label class="ada-playground__panel-label" for="<?php echo esc_attr( $id ); ?>-foreground-picker">Foreground Color</label>
					<div class="ada-playground__color-control">
						<input id="<?php echo esc_attr( $id ); ?>-foreground-picker" class="ada-playground__color-input" type="color" value="<?php echo esc_attr( $foreground_color ); ?>" data-control="foreground-picker" />
						<input class="ada-playground__hex-input" type="text" inputmode="text" value="<?php echo esc_attr( strtoupper( $foreground_color ) ); ?>" aria-label="Foreground color hex value" data-control="foreground-hex" />
					</div>
				</div>

				<div class="ada-playground__panel ada-playground__panel--color">
					<label class="ada-playground__panel-label" for="<?php echo esc_attr( $id ); ?>-background-picker">Background Color</label>
					<div class="ada-playground__color-control">
						<input id="<?php echo esc_attr( $id ); ?>-background-picker" class="ada-playground__color-input" type="color" value="<?php echo esc_attr( $background_color ); ?>" data-control="background-picker" />
						<input class="ada-playground__hex-input" type="text" inputmode="text" value="<?php echo esc_attr( strtoupper( $background_color ) ); ?>" aria-label="Background color hex value" data-control="background-hex" />
					</div>
				</div>

				<div class="ada-playground__panel ada-playground__panel--ratio">
					<span class="ada-playground__panel-label">Contrast Ratio</span>
					<div class="ada-playground__ratio" aria-live="polite">1.00:1</div>
					<p class="ada-playground__ratio-note">Body text needs 4.5:1 for AA and 7:1 for AAA. Large text only qualifies at 18pt regular or 14pt bold.</p>
				</div>
			</div>

			<div class="ada-playground__controls">
				<h3 class="ada-playground__controls-title">Customize your text preview</h3>
				<div class="ada-playground__controls-grid">
					<div class="ada-playground__field">
						<label for="<?php echo esc_attr( $id ); ?>-font-family">Font Family</label>
						<select id="<?php echo esc_attr( $id ); ?>-font-family" class="ada-playground__select" data-control="font-family">
							<?php foreach ( $font_options as $option ) : ?>
								<option value="<?php echo esc_attr( $option['value'] ); ?>"><?php echo esc_html( $option['label'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="ada-playground__field">
						<label for="<?php echo esc_attr( $id ); ?>-font-weight">Font Weight</label>
						<select id="<?php echo esc_attr( $id ); ?>-font-weight" class="ada-playground__select" data-control="font-weight">
							<?php foreach ( $weight_options as $option ) : ?>
								<option value="<?php echo esc_attr( $option['value'] ); ?>"><?php echo esc_html( $option['label'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="ada-playground__field">
						<label for="<?php echo esc_attr( $id ); ?>-normal-size">Normal Text Size</label>
						<select id="<?php echo esc_attr( $id ); ?>-normal-size" class="ada-playground__select" data-control="normal-size">
							<?php foreach ( $normal_size_options as $size ) : ?>
								<option value="<?php echo esc_attr( $size ); ?>" <?php selected( 16, $size ); ?>><?php echo esc_html( $size . ' pt' ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="ada-playground__field">
						<label for="<?php echo esc_attr( $id ); ?>-large-size">Large Text Size</label>
						<select id="<?php echo esc_attr( $id ); ?>-large-size" class="ada-playground__select" data-control="large-size">
							<?php foreach ( $large_size_options as $size ) : ?>
								<option value="<?php echo esc_attr( $size ); ?>" <?php selected( 24, $size ); ?>><?php echo esc_html( $size . ' pt' ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="ada-playground__card ada-playground__card--results" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>" <?php if ( ! empty( $description ) ) : ?>aria-describedby="<?php echo esc_attr( $description_id ); ?>"<?php endif; ?>>
			<div class="ada-playground__results-head">
				<div>
					<h3 class="ada-playground__results-title">Live preview tests</h3>
					<p class="ada-playground__results-copy">Both checks stay visible at once so your team can validate body text and large text without scrolling through separate cards.</p>
				</div>
			</div>

			<div class="ada-playground__preview-stack">
				<article class="ada-playground__preview-card" data-preview-card="normal">
					<div class="ada-playground__preview-header">
						<div>
							<h3 class="ada-playground__preview-title">Body Text</h3>
							<p class="ada-playground__preview-meta">Paragraphs, labels, and smaller interface copy.</p>
						</div>
						<p class="ada-playground__preview-threshold">AA 4.5:1, AAA 7:1</p>
					</div>

					<div class="ada-playground__badge-row">
						<div class="ada-playground__badge-group">
							<span class="ada-playground__badge-label">WCAG AA</span>
							<span class="ada-playground__badge" data-result="aa">Checking</span>
						</div>
						<div class="ada-playground__badge-group">
							<span class="ada-playground__badge-label">WCAG AAA</span>
							<span class="ada-playground__badge" data-result="aaa">Checking</span>
						</div>
					</div>

					<p class="ada-playground__sample" data-sample="normal"><?php echo esc_html( $preview_text ); ?></p>
				</article>

				<article class="ada-playground__preview-card" data-preview-card="large">
					<div class="ada-playground__preview-header">
						<div>
							<h3 class="ada-playground__preview-title">Large Text</h3>
							<p class="ada-playground__preview-meta">Headings and larger callouts only.</p>
						</div>
						<p class="ada-playground__preview-threshold" data-large-text-note>AA 3:1, AAA 4.5:1</p>
					</div>

					<div class="ada-playground__badge-row">
						<div class="ada-playground__badge-group">
							<span class="ada-playground__badge-label">WCAG AA</span>
							<span class="ada-playground__badge" data-result="aa">Checking</span>
						</div>
						<div class="ada-playground__badge-group">
							<span class="ada-playground__badge-label">WCAG AAA</span>
							<span class="ada-playground__badge" data-result="aaa">Checking</span>
						</div>
					</div>

					<p class="ada-playground__sample" data-sample="large"><?php echo esc_html( $preview_text ); ?></p>
				</article>
			</div>
		</div>
	</div>
</section>
