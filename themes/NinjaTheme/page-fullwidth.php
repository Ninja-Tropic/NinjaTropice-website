<?php
/**
 * Template Name: Full Width
 * The template for displaying full-width pages
 *
 * @package NinjaTheme
 */

get_header(); ?>

<main id="main" class="site-main site-main--fullwidth">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post post--fullwidth' ); ?>>
            <?php
            // Check if show_title field exists and is not unchecked (0)
            $show_title = get_field( 'show_title' );
            if ( $show_title !== 0 && $show_title !== '0' ) :
            ?>
            <header class="entry-header">
                <h1 class="post-title"><?php the_title(); ?></h1>
            </header>
            <?php endif; ?>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail post-thumbnail--fullwidth">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>

            <div class="post-content post-content--fullwidth">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
        </article>

        <?php
        // Comments
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

    endwhile;
    ?>
</main>

<?php
get_footer();
