<?php
/**
 * The template for displaying all single posts
 *
 * @package NinjaTheme
 */

get_header(); ?>

<main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>

                <?php
                $show_title = get_field( 'show_title' );
                if ( $show_title !== 0 && $show_title !== '0' ) :
                ?>
                <header class="post-hero">
                    <div class="post-hero__image">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large' ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="post-hero__text">
                        <h1 class="post-hero__title"><?php the_title(); ?></h1>
                        <div class="post-author-card">
                            <div class="post-author-card__row">
                                <span class="post-author-card__icon">👉</span>
                                <span class="post-author-card__label">Author:</span>
                                <a class="post-author-card__name" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
                            </div>
                            <?php $author_bio = get_the_author_meta( 'description' ); if ( $author_bio ) : ?>
                            <div class="post-author-card__row">
                                <span class="post-author-card__icon">🤓</span>
                                <span class="post-author-card__bio"><?php echo esc_html( $author_bio ); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="post-author-card__row">
                                <span class="post-author-card__icon">📅</span>
                                <span class="post-author-card__date"><?php echo get_the_date(); ?></span>
                            </div>
                        </div>
                    </div>
                </header>
                <?php endif; ?>

                <div class="post-body-bg">
                    <div class="post-body">
                        <aside class="post-body__toc" id="js-post-toc">
                            <div class="custom-sidebar-navigation">
                                <nav><ul id="js-toc-links"></ul></nav>
                            </div>
                        </aside>
                        <div class="post-content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <?php
                    // ── Previous/Next navigation ──────────────────────────────
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    if ( $prev_post || $next_post ) : ?>
                    <nav class="post-nav">
                        <div class="post-nav__inner">
                            <?php if ( $prev_post ) : ?>
                            <a class="post-nav__item post-nav__item--prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
                                <span class="post-nav__arrow">&#8592;</span>
                                <span class="post-nav__text">
                                    <span class="post-nav__label">Previous post</span>
                                    <span class="post-nav__title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
                                </span>
                            </a>
                            <?php else : ?>
                            <span></span>
                            <?php endif; ?>

                            <?php if ( $next_post ) : ?>
                            <a class="post-nav__item post-nav__item--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
                                <span class="post-nav__text">
                                    <span class="post-nav__label">Next post</span>
                                    <span class="post-nav__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
                                </span>
                                <span class="post-nav__arrow">&#8594;</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </nav>
                    <?php endif; ?>
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

<?php $cta_url = 'https://www.ninjatropic.com/contact-us/?hsCtaAttrib=143096257696'; ?>
<script>
(function () {
    var imgUrl  = '<?php echo esc_url( content_url( "uploads/2026/04/image.png" ) ); ?>';
    var LAVENDER = 'rgba(203,169,255,0.12)';
    var WAVE_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 220" preserveAspectRatio="none" style="display:block;width:100%;height:100%"><path fill="#ffffff" d="M0,110 C280,220 1160,0 1440,110 L1440,220 L0,220 Z"/></svg>';

    // ── Phase 1: DOM ready — inject divider images, CTA, and build TOC ──────
    document.addEventListener('DOMContentLoaded', function () {
        var content  = document.querySelector('.post-content');
        var headings = document.querySelectorAll('.post-content h2, .post-content h3');
        var tocList  = document.getElementById('js-toc-links');

        function makeDivider() {
            var img = document.createElement('img');
            img.src = imgUrl; img.alt = ''; img.className = 'post-section-divider';
            return img;
        }

        function makeCta() {
            var ctaUrl  = '<?php echo esc_url( $cta_url ); ?>';
            var logoId  = '<?php echo esc_attr( get_theme_mod( "custom_logo" ) ); ?>';
            var logoSrc = '<?php
                $logo_id  = get_theme_mod( "custom_logo" );
                echo $logo_id ? esc_url( wp_get_attachment_image_url( $logo_id, "medium" ) ) : "";
            ?>';
            var siteName = '<?php bloginfo( "name" ); ?>';

            var wrap = document.createElement('div');
            wrap.className = 'post-cta post-cta--inline';

            var inner = document.createElement('div');
            inner.className = 'post-cta__inner';

            if (logoSrc) {
                var img = document.createElement('img');
                img.src = logoSrc; img.alt = siteName; img.className = 'post-cta__logo';
                inner.appendChild(img);
            } else {
                var name = document.createElement('span');
                name.className = 'post-cta__site-name';
                name.textContent = siteName;
                inner.appendChild(name);
            }

            var btn = document.createElement('a');
            btn.href = ctaUrl; btn.className = 'post-cta__btn';
            btn.textContent = 'Contact us!';
            inner.appendChild(btn);

            wrap.appendChild(inner);
            return wrap;
        }

        if (content) {
            var firstElement = null;
            for (var i = 0; i < content.children.length; i++) {
                var tagName = content.children[i].tagName.toLowerCase();
                if (tagName !== 'script' && tagName !== 'style') {
                    firstElement = content.children[i];
                    break;
                }
            }

            if (!firstElement || firstElement.tagName.toLowerCase() !== 'h2') {
                content.insertBefore(makeDivider(), content.firstChild);
            }
        }

        document.querySelectorAll('.post-content h2').forEach(function (h) {
            h.parentNode.insertBefore(makeDivider(), h);
        });

        // Inject CTA before the 3rd H3, fallback to last H3 if fewer than 3
        var h3s = document.querySelectorAll('.post-content h3');
        if (h3s.length > 0) {
            var target = h3s.length >= 3 ? h3s[2] : h3s[h3s.length - 1];
            target.parentNode.insertBefore(makeCta(), target);
        }

        if (!headings.length || !tocList) return;
        headings.forEach(function (h, i) {
            if (!h.id) h.id = 'toc-section-' + i;
            var li = document.createElement('li');
            var a  = document.createElement('a');
            a.href = '#' + h.id;
            a.textContent = h.innerText.trim();
            li.appendChild(a);
            tocList.appendChild(li);
        });
    });

    // ── Phase 2: fully loaded — build alternating bg stripes ─────────────────
    window.addEventListener('load', function () {
        var bg = document.querySelector('.post-body-bg');
        if (!bg) return;

        function buildStripes() {
            bg.querySelectorAll('.nt-bg-stripe').forEach(function (el) { el.remove(); });

            var bgTop    = bg.getBoundingClientRect().top + window.scrollY;
            var bgHeight = bg.offsetHeight;
            var FIRST = 700;   // first white block height
            var INTERVAL = 1500; // subsequent sections

            var boundaries = [0, FIRST];
            for (var pos = FIRST + INTERVAL; pos < bgHeight; pos += INTERVAL) {
                boundaries.push(pos);
            }
            boundaries.push(bgHeight);

            // Sections at odd indices (1, 3, 5…) → lavender
            for (var i = 0; i < boundaries.length - 1; i++) {
                if (i % 2 === 1) {
                    var stripe = document.createElement('div');
                    stripe.className = 'nt-bg-stripe';
                    stripe.style.top    = boundaries[i] + 'px';
                    stripe.style.height = (boundaries[i + 1] - boundaries[i]) + 'px';
                    stripe.style.background = LAVENDER;

                    // white wave at the top edge (inverted)
                    var waveTop = document.createElement('div');
                    waveTop.style.cssText = 'position:absolute;top:-1px;left:0;width:100%;height:220px;';
                    waveTop.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 220" preserveAspectRatio="none" style="display:block;width:100%;height:100%"><path fill="#ffffff" d="M0,110 C280,0 1160,220 1440,110 L1440,0 L0,0 Z"/></svg>';
                    stripe.appendChild(waveTop);

                    // white wave at the bottom edge
                    var waveWrap = document.createElement('div');
                    waveWrap.style.cssText = 'position:absolute;bottom:-1px;left:0;width:100%;height:220px;';
                    waveWrap.innerHTML = WAVE_SVG;
                    stripe.appendChild(waveWrap);

                    bg.appendChild(stripe);
                }
            }
        }

        buildStripes();
        window.addEventListener('resize', buildStripes);
    });
}());
</script>

<?php
get_footer();
