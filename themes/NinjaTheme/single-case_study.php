<?php
/**
 * Template for single Case Study posts.
 * No hero, no TOC, full-width content. Works for both Divi and migrated content.
 *
 * @package NinjaTheme
 */

get_header(); ?>

<style>
    /* ── Case Study single layout ──────────────────────────────────── */
    .cs-single {
        width: 100%;
        overflow-x: hidden;
    }

    .cs-single__title {
        max-width: 900px;
        margin: 60px auto 48px;
        padding: 0 24px;
        font-family: 'Nunito', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 800;
        line-height: 1.2;
        color: #1d2327;
    }

    /* Content wrapper — constrains text, lets sections break out */
    .cs-single__content {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 24px 80px;
    }

    /* ── Full-width section backgrounds ─────────────────────────────── */
    .cs-single__content .dtg-section,
    .cs-single__content .dtg-row[style] {
        position: relative;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        padding-top: 64px;
        padding-bottom: 64px;
        padding-left: max(24px, calc((100vw - 900px) / 2));
        padding-right: max(24px, calc((100vw - 900px) / 2));
        box-sizing: border-box;
        margin-top: 48px;
        margin-bottom: 48px;
    }

    /* Sections without a background stay in normal flow */
    .cs-single__content .dtg-section:not([style]),
    .cs-single__content .dtg-row:not([style]) {
        position: static;
        left: auto;
        transform: none;
        width: auto;
        padding: 0;
        margin: 0;
    }

    /* ── Typography inside content ──────────────────────────────────── */
    .cs-single__content h2,
    .cs-single__content h3,
    .cs-single__content h4 {
        font-family: 'Nunito', sans-serif;
        font-weight: 800;
        color: #1d2327;
        margin-top: 2em;
        margin-bottom: 0.6em;
    }
    .cs-single__content h2 { font-size: clamp(1.4rem, 3vw, 1.9rem); }
    .cs-single__content h3 { font-size: clamp(1.2rem, 2.5vw, 1.5rem); }
    .cs-single__content p  { font-size: 1.05rem; line-height: 1.8; color: #3a3a3a; margin-bottom: 1.2em; }
    .cs-single__content a  { color: #ff641b; }
    .cs-single__content ul,
    .cs-single__content ol { padding-left: 1.6em; margin-bottom: 1.2em; }
    .cs-single__content li { font-size: 1.05rem; line-height: 1.8; color: #3a3a3a; margin-bottom: 0.4em; }

    /* ── Images ─────────────────────────────────────────────────────── */
    .cs-single__content figure.wp-block-image { margin: 2em 0; }
    .cs-single__content figure.wp-block-image img { max-width: 100%; height: auto; border-radius: 8px; }

    /* ── Columns ────────────────────────────────────────────────────── */
    .cs-single__content .wp-block-columns { display: flex; gap: 32px; flex-wrap: wrap; margin: 2em 0; }
    .cs-single__content .wp-block-column  { flex: 1 1 280px; min-width: 0; }

    /* ── Buttons ────────────────────────────────────────────────────── */
    .cs-single__content .wp-block-buttons { margin: 1.5em 0; }
    .cs-single__content .wp-block-button__link {
        display: inline-block;
        background: #ff641b;
        color: #fff;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: background .2s;
    }
    .cs-single__content .wp-block-button__link:hover { background: #e5571a; color: #fff; }

    /* ── Accordions ─────────────────────────────────────────────────── */
    .cs-single__content details { border: 1px solid #e5e5e5; border-radius: 6px; margin-bottom: 8px; }
    .cs-single__content summary { padding: 14px 18px; font-weight: 700; cursor: pointer; list-style: none; }
    .cs-single__content details > div { padding: 0 18px 14px; }

    /* ── Gallery / Carousel ─────────────────────────────────────────── */
    .cs-single__content .wp-block-gallery { display: flex; flex-wrap: wrap; gap: 16px; margin: 2em 0; }
    .cs-single__content .wp-block-gallery figure { flex: 1 1 200px; margin: 0; }
    .cs-single__content .wp-block-gallery img { width: 100%; height: auto; border-radius: 6px; }

    /* ── Post navigation ────────────────────────────────────────────── */
    .cs-post-nav {
        max-width: 900px;
        margin: 0 auto 80px;
        padding: 0 24px;
    }
    .cs-post-nav__inner {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        border-top: 1px solid #eee;
        padding-top: 32px;
    }
    .cs-post-nav__item {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: #1d2327;
        flex: 1;
        max-width: 48%;
        transition: color .2s;
    }
    .cs-post-nav__item--next { justify-content: flex-end; text-align: right; }
    .cs-post-nav__item:hover { color: #ff641b; }
    .cs-post-nav__arrow { font-size: 1.4rem; transition: transform .2s; }
    .cs-post-nav__item--prev:hover .cs-post-nav__arrow { transform: translateX(-4px); }
    .cs-post-nav__item--next:hover .cs-post-nav__arrow { transform: translateX(4px); }
    .cs-post-nav__label { display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: .05em; color: #888; margin-bottom: 4px; }
    .cs-post-nav__title { display: block; font-weight: 700; font-size: 0.95rem; line-height: 1.4; }

    /* ── Divi content inside case study ────────────────────────────── */
    /* Let Divi sections render normally — just remove default Divi padding overrides */
    .cs-single__content .et_pb_section { padding: 40px 0; }
    .cs-single__content .et_pb_row { max-width: 900px; margin: 0 auto; padding: 0; }
    .cs-single__content .et_pb_column { padding: 0 16px; }
    /* Divi full-width sections break to edge */
    .cs-single__content .et_pb_section.et_pb_fullwidth_section { padding: 0; }

    @media (max-width: 640px) {
        .cs-single__title { margin: 40px auto 32px; }
        .cs-single__content .dtg-section,
        .cs-single__content .dtg-row[style] { padding-left: 20px; padding-right: 20px; }
        .cs-post-nav__inner { flex-direction: column; gap: 16px; }
        .cs-post-nav__item { max-width: 100%; }
    }
</style>

<main id="main" class="site-main">
    <?php while ( have_posts() ) : the_post(); ?>

    <div class="cs-single">

        <h1 class="cs-single__title"><?php the_title(); ?></h1>

        <div class="cs-single__content">
            <?php the_content(); ?>
        </div>

    </div>

    <?php
    $prev_post = get_previous_post( false, '', 'post_type' );
    $next_post = get_next_post( false, '', 'post_type' );
    if ( $prev_post || $next_post ) : ?>
    <nav class="cs-post-nav">
        <div class="cs-post-nav__inner">
            <?php if ( $prev_post ) : ?>
            <a class="cs-post-nav__item cs-post-nav__item--prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
                <span class="cs-post-nav__arrow">&#8592;</span>
                <span>
                    <span class="cs-post-nav__label">Previous</span>
                    <span class="cs-post-nav__title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
                </span>
            </a>
            <?php else : ?><span></span><?php endif; ?>

            <?php if ( $next_post ) : ?>
            <a class="cs-post-nav__item cs-post-nav__item--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
                <span>
                    <span class="cs-post-nav__label">Next</span>
                    <span class="cs-post-nav__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
                </span>
                <span class="cs-post-nav__arrow">&#8594;</span>
            </a>
            <?php else : ?><span></span><?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
