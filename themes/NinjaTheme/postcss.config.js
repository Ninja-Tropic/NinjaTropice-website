module.exports = (ctx) => {
  const isProduction = ctx.env === 'production' || process.env.NODE_ENV === 'production';
  
  return {
    plugins: {
      autoprefixer: {},
      ...(isProduction ? {
        'cssnano': {
          preset: ['default', {
            discardComments: {
              removeAll: true,
            },
            normalizeWhitespace: true,
            minifyFontValues: true,
            minifySelectors: true,
            reduceIdents: false, // Keep animation names
            zindex: false, // Don't optimize z-index
          }]
        }
      } : {})
    },
  };
};
