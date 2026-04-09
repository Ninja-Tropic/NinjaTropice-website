# NinjaTheme - Divi Child Theme

A WordPress child theme for Divi that allows you to customize and extend Divi without modifying the parent theme files.

## Installation

1. Make sure you have the **Divi** parent theme installed and activated
2. Upload the `NinjaTheme` folder to `/wp-content/themes/`
3. Go to **Appearance > Themes** in WordPress admin
4. Activate **NinjaTheme**

## Features

- Properly inherits all Divi functionality
- Custom CSS that loads after Divi's stylesheet
- Custom JavaScript for additional functionality
- Template files for custom page layouts (if needed)
- Safe to update - your customizations won't be lost when Divi updates

## Customization

### Working with Tailwind CSS & SCSS

This theme uses **Tailwind CSS** and **SCSS** for stylesheet development. The source files are in the `scss/` directory and compile to `style.css` through a build process that includes:
- **Sass** - Compiles SCSS to CSS
- **PostCSS** - Processes Tailwind directives and adds autoprefixer
- **Tailwind CSS** - Utility-first CSS framework

#### Setup
1. Install dependencies:
   ```bash
   npm install
   ```

#### Development

**Recommended: Full development workflow with BrowserSync (auto-compiles Tailwind + SCSS + live reload):**
```bash
npm run dev
```
This will:
- Compile Tailwind CSS and SCSS automatically when you save changes
- Watch PHP and JavaScript files
- Auto-reload your browser when files change
- Open your site in the browser automatically

**Individual commands:**
- **Build once**:
  ```bash
  npm run build
  ```
- **Watch mode** (auto-compiles on save):
  ```bash
  npm run build:watch
  ```
- **BrowserSync only** (if build is already being watched separately):
  ```bash
  npm run sync
  ```

**BrowserSync Configuration:**
- The BrowserSync proxy URL is configured in `bs-config.js`
- Default proxy: `http://localhost:8888/ninja26` (common MAMP setup)
- Update the `proxy` setting in `bs-config.js` if your local URL is different
- BrowserSync will inject CSS changes without a full page reload

#### Production
- **Compressed CSS** (for production):
  ```bash
  npm run build:prod
  ```

#### Using Tailwind CSS

You can use Tailwind utility classes directly in your PHP templates:

```php
<div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-gray-900">Hello World</h1>
  <p class="text-gray-600 mt-4">This is a paragraph with Tailwind classes.</p>
</div>
```

**Tailwind Configuration:**
- Configuration file: `tailwind.config.js`
- Content paths are configured to scan PHP and JS files for Tailwind classes
- Customize colors, fonts, and other theme values in the config file
- The `important` option is commented out by default - uncomment it if you need Tailwind to override Divi styles forcefully

#### File Structure
- `scss/style.scss` - Main stylesheet (contains WordPress theme header, Tailwind directives, and SCSS imports)
- `scss/_variables.scss` - SCSS variables (colors, typography, spacing, etc.)
- `scss/_base.scss` - Base/reset styles
- `scss/_header.scss` - Header styles
- `scss/_content.scss` - Content and post styles
- `scss/_footer.scss` - Footer styles
- `scss/_responsive.scss` - Responsive/breakpoint styles
- `tailwind.config.js` - Tailwind CSS configuration
- `postcss.config.js` - PostCSS configuration (Tailwind + Autoprefixer)

**Note:** 
- Always edit SCSS files in the `scss/` directory, not `style.css` directly
- The compiled `style.css` will be overwritten when you compile
- Use Tailwind utility classes in your PHP templates, or add custom Tailwind components in `scss/style.scss` using `@layer components`

### Adding Custom CSS
Edit the SCSS files in `scss/` directory to add your custom styles. These will load after Divi's default styles, so you can override any Divi styles as needed.

### Adding Custom Functions
Edit `functions.php` to add custom PHP functions, hooks, and filters.

### Custom JavaScript
Edit `js/main.js` to add custom JavaScript functionality.

## Important Notes

- **Always keep Divi updated** - The child theme depends on the parent theme
- **Backup before making changes** - Always backup your site before making customizations
- **Use Divi Builder** - Most layout and design should be done through Divi's visual builder
- **Child theme CSS loads last** - Your styles in `style.css` will override Divi's default styles

## Support

For Divi support, visit: https://www.elegantthemes.com/support/

## Version

1.0.0
