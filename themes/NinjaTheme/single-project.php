<?php
/**
 * Template for displaying single Project posts
 *
 * @package NinjaTheme
 */

/**
 * Build a privacy-enhanced embed URL from a YouTube or Vimeo link.
 *
 * @param string $url Raw video URL.
 * @return string Embed URL, or empty string if not recognized.
 */
function ninjatheme_project_embed_url( $url ) {
	if ( empty( $url ) ) {
		return '';
	}

	// YouTube: watch?v= or youtu.be/
	if ( preg_match( '/youtube\.com\/watch\?.*[?&]v=([A-Za-z0-9_-]{11})/', $url, $m )
		|| preg_match( '/youtu\.be\/([A-Za-z0-9_-]{11})/', $url, $m ) ) {
		return 'https://www.youtube-nocookie.com/embed/' . $m[1];
	}

	// Vimeo: vimeo.com/ID or vimeo.com/ID/HASH (unlisted/private videos)
	if ( preg_match( '/vimeo\.com\/(\d+)(?:\/([a-f0-9]+))?/', $url, $m ) ) {
		$embed = 'https://player.vimeo.com/video/' . $m[1];
		if ( ! empty( $m[2] ) ) {
			$embed .= '?h=' . $m[2];
		}
		return $embed;
	}

	return '';
}

get_header();

while ( have_posts() ) :
	the_post();

	$video_url        = get_field( 'project_video' );
	$industry         = get_field( 'project_industry' );
	$main_goal        = get_field( 'project_main_goal' );
	$animation_style  = get_field( 'project_animation_style' );
	$training_topic   = get_the_terms( get_the_ID(), 'category' );
	$art_style        = get_field( 'project_art_style' );

	$archive_link = ninjatheme_get_projects_page_url();
	$has_meta     = $industry || $main_goal || $animation_style || ( $training_topic && ! is_wp_error( $training_topic ) ) || $art_style;
	$embed_url    = ninjatheme_project_embed_url( $video_url );
	$has_media    = $embed_url || has_post_thumbnail();

	// ── VideoObject / CreativeWork schema ──────────────────────────────────────
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'CreativeWork',
		'name'     => get_the_title(),
		'url'      => get_permalink(),
		'author'   => array(
			'@type' => 'Organization',
			'name'  => 'Ninja Tropic',
			'url'   => home_url(),
		),
	);

	if ( $video_url ) {
		$thumb_url = ninjatheme_video_thumbnail_url( $video_url, get_the_ID() );
		$schema['@type']        = 'VideoObject';
		$schema['embedUrl']     = $embed_url ?: $video_url;
		$schema['thumbnailUrl'] = $thumb_url ?: '';
		$schema['uploadDate']   = get_the_date( 'c' );
		$schema['description']  = wp_strip_all_tags( get_the_excerpt() ?: get_the_title() );
	}

	if ( $industry ) {
		$schema['genre'] = $industry;
	}
?>
<script type="application/ld+json"><?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>

<main id="main" class="site-main project-single">

	<!-- ── Back button ── -->
	<div class="project-single__back-wrap">
		<div class="container">
			<a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"
			   class="project-single__back-link">
				<svg width="8" height="14" viewBox="0 0 8 14" fill="none" aria-hidden="true">
					<path d="M7 1L1 7l6 6" stroke="currentColor" stroke-width="1.75"
					      stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				Back to Projects
			</a>
		</div>
	</div>

	<!-- ── Hero: title centred on top, full-width video below ── -->
	<section class="project-single__hero">
		<div class="project-single__hero-bg" aria-hidden="true"></div>
		<div class="container project-single__hero-header">
			<span class="project-single__eyebrow">Project</span>
			<h1 class="project-single__title"><?php the_title(); ?></h1>
			<?php if ( get_the_excerpt() ) : ?>
			<p class="project-single__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $has_media ) : ?>
		<div class="project-single__hero-media">
			<?php if ( $embed_url ) : ?>
			<div class="project-single__video-wrap container">
				<iframe
					src="<?php echo esc_url( $embed_url ); ?>"
					title="<?php echo esc_attr( get_the_title() ); ?>"
					frameborder="0"
					allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media"
					allowfullscreen
					loading="lazy"
				></iframe>
			</div>
			<?php elseif ( has_post_thumbnail() ) : ?>
			<div class="project-single__video-wrap project-single__video-wrap--thumb">
				<?php the_post_thumbnail( 'full', array(
					'class'   => 'project-single__thumb-img',
					'loading' => 'lazy',
					'alt'     => get_the_title(),
				) ); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</section>

	<!-- ── Meta strip ── -->
	<?php if ( $has_meta ) : ?>
	<div class="project-single__meta-strip">
		<div class="container">
			<div class="project-single__meta">

				<?php if ( $industry ) : ?>
				<div class="project-single__meta-item">
					<span class="project-single__meta-label">Industry</span>
					<span class="project-single__meta-value"><?php echo esc_html( $industry ); ?></span>
				</div>
				<?php endif; ?>

				<?php if ( $main_goal ) : ?>
				<div class="project-single__meta-item">
					<span class="project-single__meta-label">Main goal</span>
					<span class="project-single__meta-value"><?php echo nl2br( esc_html( wp_strip_all_tags( $main_goal ) ) ); ?></span>
				</div>
				<?php endif; ?>

				<?php if ( $animation_style ) : ?>
				<div class="project-single__meta-item">
					<span class="project-single__meta-label">Animation Style</span>
					<span class="project-single__meta-value"><?php echo esc_html( $animation_style ); ?></span>
				</div>
				<?php endif; ?>

				<?php if ( $training_topic && ! is_wp_error( $training_topic ) ) : ?>
				<div class="project-single__meta-item">
					<span class="project-single__meta-label">Training Topic</span>
					<span class="project-single__meta-value"><?php echo esc_html( implode( ', ', wp_list_pluck( $training_topic, 'name' ) ) ); ?></span>
				</div>
				<?php endif; ?>

				<?php if ( $art_style ) : ?>
				<div class="project-single__meta-item">
					<span class="project-single__meta-label">Art Style</span>
					<span class="project-single__meta-value"><?php echo esc_html( $art_style ); ?></span>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- ── Related projects carousel ── -->
	<?php
	$current_id   = get_the_ID();
	$cur_industry = get_field( 'project_industry', $current_id );
	$cur_anim     = get_field( 'project_animation_style', $current_id );
	$cur_art      = get_field( 'project_art_style', $current_id );

	$related_ids = array();

	// Collect related projects by shared meta values.
	if ( $cur_industry || $cur_anim || $cur_art ) {
		$meta_query = array( 'relation' => 'OR' );
		if ( $cur_industry ) $meta_query[] = array( 'key' => 'project_industry',        'value' => $cur_industry );
		if ( $cur_anim )     $meta_query[] = array( 'key' => 'project_animation_style', 'value' => $cur_anim );
		if ( $cur_art )      $meta_query[] = array( 'key' => 'project_art_style',       'value' => $cur_art );

		$related_ids = get_posts( array(
			'post_type'      => 'project',
			'posts_per_page' => 9,
			'post__not_in'   => array( $current_id ),
			'fields'         => 'ids',
			'orderby'        => 'rand',
			'meta_query'     => $meta_query,
		) );
	}

	// Fill remaining slots with random projects.
	if ( count( $related_ids ) < 9 ) {
		$exclude   = array_merge( array( $current_id ), $related_ids );
		$filler    = get_posts( array(
			'post_type'      => 'project',
			'posts_per_page' => 9 - count( $related_ids ),
			'post__not_in'   => $exclude,
			'fields'         => 'ids',
			'orderby'        => 'rand',
		) );
		$related_ids = array_merge( $related_ids, $filler );
	}
	?>

	<?php if ( ! empty( $related_ids ) ) : ?>
	<section class="project-single__related card--carousel" aria-label="More projects">
		<div class="container">
			<div class="project-single__related-header">
				<h2 class="project-single__related-title">More Projects</h2>
				<div class="card__nav" aria-label="Carousel navigation">
					<button class="card__nav-btn" type="button" data-direction="prev" aria-label="Previous slide">
						<span aria-hidden="true">&#8249;</span>
					</button>
					<button class="card__nav-btn" type="button" data-direction="next" aria-label="Next slide">
						<span aria-hidden="true">&#8250;</span>
					</button>
				</div>
			</div>
		</div>

		<div class="card__track-wrap">
			<div class="card__track" role="list">
				<?php foreach ( $related_ids as $related_id ) :
					$r_title     = get_the_title( $related_id );
					$r_permalink = get_permalink( $related_id );
					$r_thumb     = has_post_thumbnail( $related_id )
						? get_the_post_thumbnail( $related_id, 'large', array(
							'class'   => 'project-single__related-image',
							'loading' => 'lazy',
							'alt'     => esc_attr( $r_title ),
						) )
						: '';

					if ( ! $r_thumb ) {
						$r_video_url = get_field( 'project_video', $related_id );
						$r_thumb_url = ninjatheme_video_thumbnail_url( $r_video_url, $related_id );
						if ( $r_thumb_url ) {
							$r_fallback = esc_url( str_replace( 'maxresdefault', 'hqdefault', $r_thumb_url ) );
						$r_thumb = '<img class="project-single__related-image" src="' . esc_url( $r_thumb_url ) . '" alt="' . esc_attr( $r_title ) . '" width="1280" height="720" loading="lazy" onerror="this.onerror=null;this.src=\'' . $r_fallback . '\'">';
						}
					}
				?>
				<article class="card__item project-single__related-card" role="listitem">
					<a class="project-single__related-link" href="<?php echo esc_url( $r_permalink ); ?>">
						<div class="project-single__related-media<?php echo $r_thumb ? '' : ' project-single__related-media--empty'; ?>">
							<?php echo $r_thumb; ?>
						</div>
						<div class="project-single__related-body">
							<h3 class="project-single__related-item-title"><?php echo esc_html( $r_title ); ?></h3>
						</div>
					</a>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── Post content: CTA blocks, HubSpot form, etc. ── -->
	<div class="project-single__content">
		<?php the_content(); ?>
	</div>

</main>

<?php
endwhile;
get_footer();
