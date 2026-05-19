module.exports = {
  proxy: {
    target: process.env.WP_URL || "http://localhost:8080",
    proxyReq: [
      function(proxyReq, req) {
        proxyReq.setHeader("Host", req.headers.host);
      }
    ]
  },
  port: 3001,
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
