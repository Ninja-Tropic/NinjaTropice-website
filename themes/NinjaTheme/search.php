<?php
/**
 * The template for displaying search results pages
 *
 * @package NinjaTheme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__( 'Search Results for: %s' ),
                        '<span>' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            </header>

            <?php
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
                        </div>
                    </header>

                    <div class="post-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
                <?php
            endwhile;

            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( 'Previous' ),
                'next_text' => __( 'Next' ),
            ) );

        else :
            ?>
            <div class="no-results">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Nothing Found' ); ?></h1>
                </header>
                <div class="page-content">
                    <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.' ); ?></p>
                    <?php get_search_form(); ?>
                </div>
            </div>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
