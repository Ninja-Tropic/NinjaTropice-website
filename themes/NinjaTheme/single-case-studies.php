<?php
/**
 * Template for displaying single Case Study posts.
 *
 * Layout (top → bottom):
 *   ① Hero         — breadcrumb, title, meta row (client / industry / date), featured image
 *   ② Body         — 2 columns: main content + sticky sidebar
 *        main:    About the client · Challenge · Solution · Results · Linked project
 *        sidebar: Related case studies · CTA card
 *   ③ Form         — consultation section
 *   ④ Prev/Next    — case study navigation
 *
 * @package NinjaTheme
 */

get_header();

if ( ! have_posts() ) { get_footer(); return; }
?>

<main id="main" class="site-main">
<?php while ( have_posts() ) : the_post();

    $f = get_fields() ?: [];

    $client_name       = $f['client_name']       ?? '';
    $client_logo       = $f['client_logo']       ?? null;
    $client_intro      = $f['client_intro']      ?? '';
    $challenge_title   = $f['challenge_title']   ?: 'The Challenge';
    $challenge_columns = ! empty( $f['challenge_columns'] );
    $challenge         = $f['challenge']         ?? '';
    $solution_title    = $f['solution_title']    ?: 'Our solution';
    $solution_columns  = ! empty( $f['solution_columns'] );
    $solution          = $f['solution']          ?? '';
    $solution_image    = $f['solution_image']    ?? null;
    $project_section_title = $f['project_section_title'] ?? '';
    $project_section_text  = $f['project_section_text']  ?? '';
    $results_title     = $f['results_title']     ?: 'Results';
    $results_columns   = ! empty( $f['results_columns'] );
    $results           = $f['results']           ?? '';

    $client_industry = $f['client_industry'] ?? '';
    $client_location = $f['client_location'] ?? '';

    $cs_archive_page = get_pages( [ 'meta_key' => '_wp_page_template', 'meta_value' => 'page-case-studies.php' ] );
    $cs_archive_url  = $cs_archive_page ? get_permalink( $cs_archive_page[0]->ID ) : home_url( '/case-studies/' );

    $prev_post = get_previous_post();
    $next_post = get_next_post();

    $related = new WP_Query( [
        'post_type'           => 'case-studies',
        'posts_per_page'      => 3,
        'post__not_in'        => [ get_the_ID() ],
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    ] );
?>

    <!-- ① HERO ──────────────────────────────────────────────────────────── -->
    <header class="cs-hero bg-glow bg-glow--mix">
        <div class="container">

            <nav class="cs-breadcrumb" aria-label="Breadcrumb">
                <a class="cs-breadcrumb__link" href="<?php echo esc_url( $cs_archive_url ); ?>">Case Studies</a>
                <span class="cs-breadcrumb__sep" aria-hidden="true">/</span>
                <span class="cs-breadcrumb__current" aria-current="page"><?php the_title(); ?></span>
            </nav>

            <h1 class="cs-hero__title"><?php the_title(); ?></h1>

            <dl class="cs-meta">
                <?php if ( $client_name ) : ?>
                <div class="cs-meta__item">
                    <dt>Client</dt>
                    <dd><?php echo esc_html( $client_name ); ?></dd>
                </div>
                <?php endif; ?>
                <?php if ( $client_industry ) : ?>
                <div class="cs-meta__item">
                    <dt>Industry</dt>
                    <dd><?php echo esc_html( $client_industry ); ?></dd>
                </div>
                <?php endif; ?>
                <?php if ( $client_location ) : ?>
                <div class="cs-meta__item">
                    <dt>Location</dt>
                    <dd><?php echo esc_html( $client_location ); ?></dd>
                </div>
                <?php endif; ?>
                <div class="cs-meta__item">
                    <dt>Date</dt>
                    <dd><?php echo esc_html( get_the_date( 'F Y' ) ); ?></dd>
                </div>
            </dl>

            <?php if ( has_post_thumbnail() ) : ?>
            <div class="cs-hero__media">
                <?php the_post_thumbnail( 'full', [ 'class' => 'cs-hero__img', 'loading' => 'eager', 'fetchpriority' => 'high' ] ); ?>
            </div>
            <?php endif; ?>

        </div>
    </header>

    <!-- ② BODY ──────────────────────────────────────────────────────────── -->
    <div class="cs-body">
        <div class="container cs-layout">

            <article class="cs-main">

                <!-- EDITOR CONTENT (Gutenberg blocks) -->
                <?php if ( '' !== trim( get_the_content() ) ) : ?>
                <div class="cs-editor post-content">
                    <?php the_content(); ?>
                </div>
                <?php endif; ?>

                <!-- ABOUT THE CLIENT -->
                <?php if ( $client_intro || $client_logo ) :
                    $logo_id  = is_array( $client_logo ) ? ( $client_logo['id']  ?? 0 ) : (int) $client_logo;
                    $logo_url = is_array( $client_logo ) ? ( $client_logo['url'] ?? '' ) : '';
                    $logo_alt = is_array( $client_logo ) ? ( $client_logo['alt'] ?? '' ) : '';
                ?>
                <section class="cs-summary">
                    <div class="cs-summary__head">
                        <h2 class="cs-summary__title"><?php echo $client_name ? esc_html( 'About ' . $client_name ) : 'About the client'; ?></h2>
                        <?php if ( $logo_id || $logo_url ) : ?>
                        <div class="cs-summary__logo">
                            <?php if ( $logo_id ) : ?>
                                <?php echo wp_get_attachment_image( $logo_id, 'medium', false, [ 'class' => 'cs-summary__logo-img', 'loading' => 'lazy' ] ); ?>
                            <?php else : ?>
                                <img class="cs-summary__logo-img" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ( $client_intro ) : ?>
                    <div class="cs-summary__text post-content">
                        <?php echo wp_kses_post( $client_intro ); ?>
                    </div>
                    <?php endif; ?>
                </section>
                <?php endif; ?>

                <!-- CHALLENGE -->
                <?php if ( $challenge ) : ?>
                <section class="cs-section">
                    <h2 class="cs-section__heading"><?php echo esc_html( $challenge_title ); ?></h2>
                    <div class="post-content<?php echo $challenge_columns ? ' post-content--2col' : ''; ?>">
                        <?php echo wp_kses_post( $challenge ); ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- SOLUTION -->
                <?php if ( $solution ) :
                    $si_id  = is_array( $solution_image ) ? ( $solution_image['id']  ?? 0 ) : (int) $solution_image;
                    $si_url = is_array( $solution_image ) ? ( $solution_image['url'] ?? '' ) : '';
                    $si_alt = is_array( $solution_image ) ? ( $solution_image['alt'] ?? '' ) : '';
                ?>
                <section class="cs-section">
                    <h2 class="cs-section__heading"><?php echo esc_html( $solution_title ); ?></h2>
                    <div class="post-content<?php echo $solution_columns ? ' post-content--2col' : ''; ?>">
                        <?php echo wp_kses_post( $solution ); ?>
                    </div>
                    <?php if ( $si_id || $si_url ) : ?>
                    <figure class="cs-figure">
                        <?php if ( $si_id ) : ?>
                            <?php echo wp_get_attachment_image( $si_id, 'large', false, [ 'class' => 'cs-figure__img', 'loading' => 'lazy' ] ); ?>
                        <?php else : ?>
                            <img class="cs-figure__img" src="<?php echo esc_url( $si_url ); ?>" alt="<?php echo esc_attr( $si_alt ); ?>" loading="lazy">
                        <?php endif; ?>
                    </figure>
                    <?php endif; ?>
                </section>
                <?php endif; ?>

                <!-- RESULTS -->
                <?php if ( $results ) : ?>
                <section class="cs-section cs-section--results">
                    <h2 class="cs-section__heading"><?php echo esc_html( $results_title ); ?></h2>
                    <div class="post-content cs-results<?php echo $results_columns ? ' post-content--2col' : ''; ?>">
                        <?php echo wp_kses_post( $results ); ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- LINKED PROJECT -->
                <?php
                $linked_project = ! empty( $f['linked_project'] ) ? $f['linked_project'] : null;
                if ( $linked_project ) :
                    $lp_video_url = get_field( 'project_video', $linked_project->ID );
                    $lp_embed     = '';
                    if ( $lp_video_url ) {
                        if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $lp_video_url, $m ) ) {
                            $lp_embed = 'https://www.youtube.com/embed/' . $m[1] . '?rel=0';
                        } elseif ( preg_match( '/vimeo\.com\/(\d+)/', $lp_video_url, $m ) ) {
                            $lp_embed = 'https://player.vimeo.com/video/' . $m[1];
                        }
                    }
                    $lp_url = get_permalink( $linked_project->ID );
                ?>
                <section class="cs-section cs-projects">
                    <?php if ( $project_section_title ) : ?>
                    <h2 class="cs-section__heading"><?php echo esc_html( $project_section_title ); ?></h2>
                    <?php endif; ?>

                    <?php if ( $project_section_text ) : ?>
                    <div class="cs-projects__intro post-content">
                        <?php echo wp_kses_post( $project_section_text ); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( $lp_embed ) : ?>
                    <div class="cs-projects__video">
                        <iframe
                            src="<?php echo esc_url( $lp_embed ); ?>"
                            title="<?php echo esc_attr( $linked_project->post_title ); ?>"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"></iframe>
                    </div>
                    <?php endif; ?>

                    <div class="cs-projects__actions">
                        <a class="cs-projects__link" href="<?php echo esc_url( $lp_url ); ?>">
                            View Project
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                <path d="M1 7h12M8 2l5 5-5 5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </section>
                <?php endif; ?>

            </article>

            <!-- SIDEBAR -->
            <aside class="cs-sidebar">

                <?php if ( $related->have_posts() ) : ?>
                <section class="cs-related" aria-labelledby="cs-related-title">
                    <h2 class="cs-related__title" id="cs-related-title">Related Case Studies</h2>
                    <ul class="cs-related__list">
                        <?php while ( $related->have_posts() ) : $related->the_post();
                            $rel_cats = get_the_category();
                        ?>
                        <li class="cs-related__item">
                            <a class="cs-related__link" href="<?php the_permalink(); ?>">
                                <span class="cs-related__media">
                                    <?php if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( 'medium_large', [ 'class' => 'cs-related__img', 'loading' => 'lazy' ] );
                                    } ?>
                                </span>
                                <span class="cs-related__meta">
                                    <?php if ( $rel_cats ) : ?>
                                    <span class="cs-related__tag"><?php echo esc_html( $rel_cats[0]->name ); ?></span>
                                    <?php endif; ?>
                                    <time class="cs-related__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M Y' ) ); ?></time>
                                </span>
                                <span class="cs-related__name"><?php the_title(); ?></span>
                            </a>
                        </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </section>
                <?php endif; ?>

                <div class="cs-cta-card">
                    <h2 class="cs-cta-card__title">Ready to build yours?</h2>
                    <p class="cs-cta-card__text">Tell us about your project and get a tailored proposal from a Ninja Tropic expert.</p>
                    <a class="cs-cta-card__btn" href="#consultation-form">Book Free Consultation</a>
                </div>

            </aside>

        </div>
    </div><!-- .cs-body -->

    <!-- ③ CONSULTATION FORM -->
    <?php get_template_part( 'template-parts/consultation-form' ); ?>

    <!-- ④ PREV / NEXT NAV -->
    <?php if ( $prev_post || $next_post ) : ?>
    <nav class="post-nav">
        <div class="post-nav__inner">
            <?php if ( $prev_post ) : ?>
            <a class="post-nav__item post-nav__item--prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
                <span class="post-nav__arrow">&#8592;</span>
                <span class="post-nav__text">
                    <span class="post-nav__label">Previous case study</span>
                    <span class="post-nav__title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
                </span>
            </a>
            <?php else : ?><span></span><?php endif; ?>
            <?php if ( $next_post ) : ?>
            <a class="post-nav__item post-nav__item--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
                <span class="post-nav__text">
                    <span class="post-nav__label">Next case study</span>
                    <span class="post-nav__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
                </span>
                <span class="post-nav__arrow">&#8594;</span>
            </a>
            <?php else : ?><span></span><?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
