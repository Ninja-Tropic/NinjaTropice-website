module.exports = (ctx) => {
  const isProduction = ctx.env === 'production' || process.env.NODE_ENV === 'production';

  return {
    plugins: {
      autoprefixer: {},
      ...(isProduction ? {
        '@fullhuman/postcss-purgecss': {
          content: [
            './**/*.php',
            './js/**/*.js',
            './scss/**/*.scss',
          ],
          safelist: {
            // Bulma dynamic/JS-toggled classes
            standard: [
              /^is-/,
              /^has-/,
              /^are-/,
              /^js-/,
            ],
            // WordPress core body/admin classes
            deep: [
              /^wp-/,
              /^page-/,
              /^post-/,
              /^single-/,
              /^archive-/,
              /^category-/,
              /^tag-/,
              /^search-/,
              /^error404/,
              /^logged-in/,
              /^admin-bar/,
              /^menu-/,
              /^nav-/,
              /^comment-/,
              /^widgets-/,
              /^site-/,
              /^entry-/,
              /^alignleft/,
              /^alignright/,
              /^aligncenter/,
              /^alignfull/,
              /^alignwide/,
            ],
            // ACF block classes + NinjaTheme custom classes
            greedy: [
              /^acf-/,
              /^ninja/,
              /^mesh/,
              /^container/,
              /^btn/,
              /^shadow/,
              /^gradient/,
              /^tabs-/,
              /^carousel/,
              /^hero/,
              /^hs-/,          // HubSpot form classes
              /^swiper/,
              /^slick/,
              /^aos-/,         // animate on scroll if used
            ],
          },
          // Keep @font-face, :root vars, keyframes
          rejected: false,
          variables: true,
        },
        'cssnano': {
          preset: ['default', {
            discardComments: { removeAll: true },
            normalizeWhitespace: true,
            minifyFontValues: true,
            minifySelectors: true,
            reduceIdents: false,
            zindex: false,
          }]
        }
      } : {})
    },
  };
};