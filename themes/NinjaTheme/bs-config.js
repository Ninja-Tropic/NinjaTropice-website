const wpPublicUrl = process.env.WP_PUBLIC_URL || "http://localhost:8080";
const wpPublicHost = new URL(wpPublicUrl).host;
const bsUrl = "http://localhost:3001";
const wpUrlRegex = () => new RegExp(wpPublicUrl.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"), "g");

module.exports = {
  proxy: {
    target: process.env.WP_URL || wpPublicUrl,
    proxyReq: [
      function(proxyReq) {
        proxyReq.setHeader("Host", wpPublicHost);
      }
    ],
    proxyRes: [
      function(proxyRes) {
        if (proxyRes.headers.location) {
          proxyRes.headers.location = proxyRes.headers.location
            .replace(wpUrlRegex(), bsUrl);
        }
      }
    ]
  },
  port: 3001,
  rewriteRules: [
    {
      match: wpUrlRegex(),
      replace: bsUrl
    }
  ],
  files: [
    "style.css",
    "*.php",
    "js/**/*.js",
    "**/*.php"
  ],
  watchOptions: {
    ignoreInitial: true,
    ignored: ["node_modules/**", ".git/**"]
  },
  reloadDelay: 300,
  open: false,
  notify: false,
  ghostMode: {
    clicks: true,
    forms: true,
    scroll: true
  },
  logLevel: "info",
  logFileChanges: true,
  injectChanges: true
};
