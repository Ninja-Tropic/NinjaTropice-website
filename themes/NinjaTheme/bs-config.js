module.exports = {
  // Proxy your MAMP local WordPress site
  // Update this URL to match your local setup
  // Common MAMP URLs:
  // - http://localhost:8888/ninja26
  // - http://localhost/ninja26
  // - http://ninja26.local
  proxy: "http://localhost:8080",
  
  // Files to watch for changes
  files: [
    "style.css",
    "*.php",
    "js/**/*.js",
    "**/*.php"
  ],
  
  // Watch options
  watchOptions: {
    ignoreInitial: true,
    ignored: ["node_modules/**", ".git/**"]
  },
  
  // Reload delay to prevent multiple reloads
  reloadDelay: 300,
  
  // Open browser automatically
  open: true,
  
  // Don't show the BrowserSync UI overlay
  notify: false,
  
  // Ghost mode - sync clicks, scrolls, form inputs across devices
  ghostMode: {
    clicks: true,
    forms: true,
    scroll: true
  },
  
  // Log level
  logLevel: "info",
  
  // Log file changes
  logFileChanges: true,
  
  // Inject CSS changes without full page reload
  injectChanges: true
};
