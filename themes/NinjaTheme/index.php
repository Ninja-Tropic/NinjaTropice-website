<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package NinjaTheme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
                    <header class="entry-header">
                        <h2 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="post-meta">
                            <span class="posted-on">
                                <?php echo get_the_date(); ?>
                            </span>
                            <span class="byline">
                                by <?php the_author(); ?>
                            </span>
                        </div>
                    </header>

                    <div class="post-content">
                        <?php
                        if ( is_home() || is_archive() ) {
                            the_excerpt();
                        } else {
                            the_content();
                        }
                        ?>
                    </div>
                </article>
                <?php
            endwhile;

            // Pagination
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( 'Previous' ),
                'next_text' => __( 'Next' ),
            ) );

        else :
            ?>
            <div class="no-posts">
                <h2><?php esc_html_e( 'Nothing Found' ); ?></h2>
                <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.' ); ?></p>
                <?php get_search_form(); ?>
            </div>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
