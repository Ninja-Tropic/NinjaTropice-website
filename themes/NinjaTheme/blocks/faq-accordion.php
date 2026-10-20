<?php
/**
 * Block Name: FAQ Accordion
 *
 * Fields: tag (text), title (text), description (textarea),
 * faqs (relationship to the "faq" post type, returns IDs),
 * open_first (true/false), output_schema (true/false).
 * FAQ title = question, FAQ content = answer. Order = order selected.
 *
 * @package NinjaTheme
 */

$id = 'faq-accordion-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$className = 'faq-accordion';
if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$tag         = get_field( 'tag' );
$title       = get_field( 'title' );
$description = get_field( 'description' );
$faq_ids     = get_field( 'faqs' );
$open_first  = get_field( 'open_first' );
$schema      = get_field( 'output_schema' );

// Fields saved before these toggles existed return null → use the defaults (on).
$open_first = ( null === $open_first ) ? true : (bool) $open_first;
$schema     = ( null === $schema ) ? true : (bool) $schema;

$faqs = array();
if ( ! empty( $faq_ids ) && is_array( $faq_ids ) ) {
	foreach ( $faq_ids as $faq_id ) {
		$faq_post = get_post( (int) $faq_id );
		if ( $faq_post && 'faq' === $faq_post->post_type && 'publish' === $faq_post->post_status ) {
			$faqs[] = $faq_post;
		}
	}
}

if ( empty( $faqs ) ) {
	if ( is_admin() ) {
		echo '<p class="faq-accordion__empty">' . esc_html__( 'FAQ Accordion: select at least one FAQ.', 'ninjatheme' ) . '</p>';
	}
	return;
}

$schema_items = array();
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>" data-faq-accordion>
	<div class="faq-accordion__container container">

		<?php if ( $tag || $title || $description ) : ?>
			<header class="faq-accordion__header">
				<?php if ( ! empty( $tag ) ) : ?>
					<span class="faq-accordion__tag"><?php echo esc_html( $tag ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="faq-accordion__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $description ) ) : ?>
					<div class="faq-accordion__description"><?php echo wp_kses_post( $description ); ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="faq-accordion__list">
			<?php foreach ( $faqs as $i => $faq ) :
				$is_open  = ( $open_first && 0 === $i );
				$question = get_the_title( $faq );
				$answer   = apply_filters( 'the_content', $faq->post_content );
				$btn_id   = $id . '-q-' . $i;
				$panel_id = $id . '-a-' . $i;

				$schema_items[] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $question ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_kses_post( $answer ),
					),
				);
			?>
				<div class="faq-accordion__item<?php echo $is_open ? ' is-open' : ''; ?>">
					<h3 class="faq-accordion__question">
						<button type="button"
								class="faq-accordion__trigger"
								id="<?php echo esc_attr( $btn_id ); ?>"
								aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
								aria-controls="<?php echo esc_attr( $panel_id ); ?>">
							<span class="faq-accordion__question-text"><?php echo esc_html( $question ); ?></span>
							<span class="faq-accordion__icon" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3.5 6l4.5 4.5L12.5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</span>
						</button>
					</h3>
					<div class="faq-accordion__panel"
						 id="<?php echo esc_attr( $panel_id ); ?>"
						 role="region"
						 aria-labelledby="<?php echo esc_attr( $btn_id ); ?>">
						<div class="faq-accordion__panel-inner">
							<div class="faq-accordion__answer"><?php echo wp_kses_post( $answer ); ?></div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>

	<?php if ( $schema && ! empty( $schema_items ) && ! is_admin() ) : ?>
		<script type="application/ld+json"><?php
			echo wp_json_encode(
				array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $schema_items,
				),
				JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG
			);
		?></script>
	<?php endif; ?>
</section>
