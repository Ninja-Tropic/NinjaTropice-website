<?php
/**
 * Template for displaying single Case Study posts.
 *
 * Layout (top → bottom):
 *   ① Hero         — featured image full-width, title + client badge overlaid
 *   ② Client intro — logo + about text
 *   ③ Challenge    — lavender stripe
 *   ④ Solution     — white
 *   ⑤ Solution img — optional full-width visual break
 *   ⑥ Key Metrics  — orange stats grid
 *   ⑦ Results      — lavender stripe
 *   ⑧ Key Takeaways— white
 *   ⑨ Closing CTA  — orange
 *   ⑩ Form         — consultation section
 *   ⑪ Prev/Next    — case study navigation
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
    $solution_title          = $f['solution_title']          ?: 'Our solution';
    $solution_columns        = ! empty( $f['solution_columns'] );
    $solution                = $f['solution']                ?? '';
    $solution_image          = $f['solution_image']          ?? null;
    $solution_image_left     = ! empty( $f['solution_image_position'] );
    $project_section_title = $f['project_section_title'] ?? '';
    $project_section_text  = $f['project_section_text']  ?? '';
    $results_title     = $f['results_title']     ?: 'Results';
    $results_columns   = ! empty( $f['results_columns'] );
    $results           = $f['results']           ?? '';

    $prev_post = get_previous_post();
    $next_post = get_next_post();
?>

    <!-- ① HERO ──────────────────────────────────────────────────────────── -->
    <header class="cs-hero<?php echo has_post_thumbnail() ? ' cs-hero--has-image' : ''; ?>">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="cs-hero__bg">
            <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'fetchpriority' => 'high' ] ); ?>
        </div>
        <?php endif; ?>
        <div class="cs-hero__overlay" aria-hidden="true"></div>
        <div class="cs-hero__content">
            <div class="container">
                <?php if ( $client_name ) : ?>
                <div class="cs-client-badge">
                    <span class="cs-client-badge__label">Client</span>
                    <span class="cs-client-badge__name"><?php echo esc_html( $client_name ); ?></span>
                </div>
                <?php endif; ?>
                <h1 class="cs-hero__title"><?php the_title(); ?></h1>
            </div>
        </div>
    </header>

    <!-- BACK LINK -->
    <?php
    $cs_archive_page = get_pages( [ 'meta_key' => '_wp_page_template', 'meta_value' => 'page-case-studies.php' ] );
    $cs_archive_url  = $cs_archive_page ? get_permalink( $cs_archive_page[0]->ID ) : home_url( '/case-studies/' );
    ?>
    <div class="cs-back-bar">
        <div class="container">
            <a class="cs-back-link" href="<?php echo esc_url( $cs_archive_url ); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <path d="M13 8H3M7 4l-4 4 4 4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Case Studies
            </a>
        </div>
    </div>

    <!-- ② – ⑧ BODY ──────────────────────────────────────────────────────── -->
    <div class="cs-body">

        <!-- ② CLIENT INTRO -->
        <?php if ( $client_intro || $client_logo ) :
            $logo_id  = is_array( $client_logo ) ? ( $client_logo['id']  ?? 0 ) : (int) $client_logo;
            $logo_url = is_array( $client_logo ) ? ( $client_logo['url'] ?? '' ) : '';
            $logo_alt = is_array( $client_logo ) ? ( $client_logo['alt'] ?? '' ) : '';
        ?>
        <section class="cs-client-intro">
            <div class="container cs-client-intro__inner">
                <?php if ( $client_intro ) : ?>
                <div class="cs-client-intro__text post-content">
                    <?php echo wp_kses_post( $client_intro ); ?>
                </div>
                <?php endif; ?>
                <?php if ( $logo_id || $logo_url ) : ?>
                <div class="cs-client-intro__logo">
                    <?php if ( $logo_id ) : ?>
                        <?php echo wp_get_attachment_image( $logo_id, 'medium', false, [ 'class' => 'cs-client-intro__logo-img', 'loading' => 'lazy' ] ); ?>
                    <?php else : ?>
                        <img class="cs-client-intro__logo-img" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- ③ CHALLENGE -->
        <?php if ( $challenge ) : ?>
        <section class="cs-section cs-section--alt">
            <div class="container">
                <h2 class="cs-section__heading"><?php echo esc_html( $challenge_title ); ?></h2>
                <div class="post-content<?php echo $challenge_columns ? ' post-content--2col' : ''; ?>">
                    <?php echo wp_kses_post( $challenge ); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- ④ SOLUTION -->
        <?php if ( $solution ) :
            $si_id  = is_array( $solution_image ) ? ( $solution_image['id']  ?? 0 ) : (int) $solution_image;
            $si_url = is_array( $solution_image ) ? ( $solution_image['url'] ?? '' ) : '';
            $si_alt = is_array( $solution_image ) ? ( $solution_image['alt'] ?? '' ) : '';
            $has_si = $si_id || $si_url;
        ?>
        <section class="cs-section">
            <div class="container">
                <h2 class="cs-section__heading"><?php echo esc_html( $solution_title ); ?></h2>

                <?php if ( $has_si ) : ?>
                <div class="cs-section__split<?php echo $solution_image_left ? ' cs-section__split--rtl' : ''; ?>">
                    <?php if ( $solution_image_left ) : ?>
                    <div class="cs-section__split-media">
                        <?php if ( $si_id ) : ?>
                            <?php echo wp_get_attachment_image( $si_id, 'large', false, [ 'class' => 'cs-section__split-img', 'loading' => 'lazy' ] ); ?>
                        <?php else : ?>
                            <img class="cs-section__split-img" src="<?php echo esc_url( $si_url ); ?>" alt="<?php echo esc_attr( $si_alt ); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="post-content cs-section__split-text">
                        <?php echo wp_kses_post( $solution ); ?>
                    </div>

                    <?php if ( ! $solution_image_left ) : ?>
                    <div class="cs-section__split-media">
                        <?php if ( $si_id ) : ?>
                            <?php echo wp_get_attachment_image( $si_id, 'large', false, [ 'class' => 'cs-section__split-img', 'loading' => 'lazy' ] ); ?>
                        <?php else : ?>
                            <img class="cs-section__split-img" src="<?php echo esc_url( $si_url ); ?>" alt="<?php echo esc_attr( $si_alt ); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php else : ?>
                <div class="post-content<?php echo $solution_columns ? ' post-content--2col' : ''; ?>">
                    <?php echo wp_kses_post( $solution ); ?>
                </div>
                <?php endif; ?>

            </div>
        </section>
        <?php endif; ?>

        <!-- RESULTS -->
        <?php if ( $results ) : ?>
        <section class="cs-section cs-section--alt">
            <div class="container">
                <h2 class="cs-section__heading"><?php echo esc_html( $results_title ); ?></h2>
                <div class="post-content<?php echo $results_columns ? ' post-content--2col' : ''; ?>">
                    <?php echo wp_kses_post( $results ); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

    </div><!-- .cs-body -->

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
    <section class="cs-projects">
        <div class="container">

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

        </div>
    </section>
    <?php endif; ?>

    <!-- ⑩ CONSULTATION FORM -->
    <?php get_template_part( 'template-parts/consultation-form' ); ?>

    <!-- ⑪ PREV / NEXT NAV -->
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
