# Animated Gradient Text Effect

An animated gradient text effect that uses the footer's gradient colors (orange `#ff641b` to purple `#a36dee`) with a continuous moving animation.

## Preview

The text will display with a smooth, flowing gradient that moves from left to right, creating a dynamic shimmer effect.

---

## Usage Methods

### 1. WordPress Editor / ACF WYSIWYG Fields

Use the `[gradient]` shortcode in any WordPress content area:

```
World-class Interactive [gradient]Microlearning Solutions & Custom eLearning Development[/gradient]
```

**Where you can use it:**
- WordPress WYSIWYG editor
- ACF WYSIWYG fields
- Text widgets
- Post/Page content

---

### 2. PHP Templates - Direct Function

Use the helper function directly in your theme files:

```php
<?php echo ninjatheme_gradient_text( 'Microlearning Solutions' ); ?>
```

**Example in a template:**
```php
<h1 class="hero-title">
    World-class Interactive <?php echo ninjatheme_gradient_text( 'Microlearning Solutions' ); ?>
</h1>
```

---

### 3. ACF Text Fields with Shortcode Support

If you have text in an ACF field that contains the shortcode, process it with `do_shortcode()`:

```php
<?php echo do_shortcode( get_field('title') ); ?>
```

**Example in card.php block:**
```php
<h2 class="card__section-title">
    <?php echo do_shortcode( $f['title'] ); ?>
</h2>
```

Then in your ACF field, add:
```
World-class [gradient]Interactive Microlearning Solutions[/gradient]
```

---

## Manual HTML Usage

If you need to add it directly in HTML (not recommended in WordPress due to sanitization):

```html
<i>Your gradient text here</i>
```

---

## Technical Details

### CSS Animation
- **Duration**: 3 seconds per cycle
- **Gradient Colors**: `#ff641b` (orange) → `#a36dee` (purple)
- **Effect**: Continuous left-to-right movement
- **Performance**: Hardware-accelerated CSS animation

### File Locations
- **SCSS Source**: `scss/_base.scss` (lines 77-95)
- **Compiled CSS**: `style.css`
- **PHP Functions**: `functions.php` (lines 646-676)

---

## Customization

### Change Animation Speed

Edit `scss/_base.scss` line 84:

```scss
animation: gradient-shift 3s linear infinite !important;
//                        ↑ Change this value
```

- `1s` = Fast
- `3s` = Default (smooth)
- `5s` = Slow

### Change Gradient Colors

Edit `scss/_base.scss` line 79:

```scss
background: linear-gradient(90deg, #ff641b 0%, #a36dee 25%, #ff641b 50%, #a36dee 75%, #ff641b 100%) !important;
//                                 ↑ orange      ↑ purple
```

### After Making Changes

Rebuild the CSS:
```bash
npm run build
```

---

## Browser Support

- ✅ Chrome/Edge (all versions)
- ✅ Firefox (all versions)
- ✅ Safari (all versions)
- ✅ Mobile browsers

Uses standard CSS animations with vendor prefixes for maximum compatibility.

---

## Troubleshooting

### Gradient not showing?
1. **Hard refresh** the browser: `Cmd + Shift + R` (Mac) or `Ctrl + Shift + R` (Windows)
2. Check if CSS is compiled: Look for the animation in `style.css`
3. Check browser console for errors

### Text showing as plain text?
- Make sure you're using the shortcode `[gradient]text[/gradient]`
- Or use the PHP function `ninjatheme_gradient_text()`
- Don't use `esc_html()` on content containing the shortcode

### Animation not smooth?
- Check browser performance
- Reduce animation duration for smoother effect
- Ensure no other CSS is overriding the animation

---

## Examples

### Hero Section
```php
<h1 class="hero-title">
    Elevate Your Team with
    <?php echo ninjatheme_gradient_text( 'World-Class eLearning Solutions' ); ?>
</h1>
```

### Card Title
```php
<h3 class="card__item-title">
    <?php echo do_shortcode( $item['title'] ); ?>
</h3>
```
*In ACF field:* `[gradient]Featured Service[/gradient]`

### Mixed Content
```php
<p>
    We specialize in <?php echo ninjatheme_gradient_text( 'custom eLearning development' ); ?>
    and instructional design.
</p>
```

---

## Functions Reference

### `ninjatheme_gradient_text( $text )`
**Purpose**: Wrap text in gradient effect
**Parameters**: `$text` (string) - Text to apply gradient to
**Returns**: HTML string with `<i>` tag
**Escaping**: Automatically escapes HTML with `esc_html()`

### `[gradient]` Shortcode
**Purpose**: WordPress shortcode for gradient text
**Usage**: `[gradient]Your text[/gradient]`
**Supports**: Nested shortcodes via `do_shortcode()`

---

## Notes

- The `<i>` tag is styled with `font-style: normal` so it won't be italicized
- All properties use `!important` to override theme defaults
- The effect works on any text length
- Gradient animates infinitely and seamlessly loops
