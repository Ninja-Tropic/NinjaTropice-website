<?php
/**
 * The template for displaying archive pages
 *
 * @package NinjaTheme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <header class="page-header">
                <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
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
                            <span class="byline">
                                by <?php the_author(); ?>
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
            <div class="no-posts">
                <h2><?php esc_html_e( 'Nothing Found' ); ?></h2>
                <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.' ); ?></p>
            </div>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
