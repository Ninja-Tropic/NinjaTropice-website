<?php
/**
 * The template for displaying all single posts
 *
 * @package NinjaTheme
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
                <?php
                // Check if show_title field exists and is not unchecked (0)
                $show_title = get_field( 'show_title' );
                if ( $show_title !== 0 && $show_title !== '0' ) :
                ?>
                <header class="entry-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    <div class="post-meta">
                        <span class="posted-on">
                            <?php echo get_the_date(); ?>
                        </span>
                        <span class="byline">
                            by <?php the_author(); ?>
                        </span>
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            echo '<span class="cat-links">';
                            echo ' in ';
                            the_category( ', ' );
                            echo '</span>';
                        }
                        ?>
                    </div>
                </header>
                <?php endif; ?>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:' ),
                    'after'  => '</div>',
                ) );
                ?>

                <footer class="entry-footer">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) {
                        echo '<div class="post-tags">';
                        the_tags( 'Tags: ', ', ', '' );
                        echo '</div>';
                    }
                    ?>
                </footer>
            </article>

            <?php
            // Previous/next post navigation
            the_post_navigation( array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:' ) . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:' ) . '</span> <span class="nav-title">%title</span>',
            ) );

            // Comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();
