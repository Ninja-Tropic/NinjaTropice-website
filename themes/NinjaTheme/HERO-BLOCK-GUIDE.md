# Main Hero Block - Usage Guide

## Updated Design Features

The main hero block has been updated to match the modern SaaS design with:

1. **Gradient Background**: Beautiful mint/cyan to white gradient
2. **Orange/Red Color Scheme**: Using your brand colors (#ff681b orange, #ef4444 red)
3. **Video Support**: Native video display instead of image
4. **Modern Typography**: Bold, impactful headlines

## How to Use the Title Highlight

To highlight text in red (like "with SaaS solutions" in the design), wrap it with a span:

```html
Revolutionizing businesses <span class="main-hero__title-highlight">with SaaS solutions</span>
```

This will display "with SaaS solutions" in red (#ef4444).

## Adding a Video

In your WordPress ACF fields for the Main Hero block:

1. **Video Field**: Add your video URL or embed code
   - Supports YouTube, Vimeo (oEmbed)
   - Or direct video file URLs
   - Or custom embed code

2. **Image Field**: This will be used as fallback if no video is provided

The video will display inline on the right side of the hero, just like the dashboard preview in the design.

## Color Palette

- **Primary Orange**: #ff681b (buttons, accents)
- **Red Highlight**: #ef4444 (title highlights)
- **Dark Text**: #111827 (headings)
- **Light Text**: #4b5563 (body text)
- **Gradient**: Mint (#d0f4f1) to White

## Example Content Structure

**Title:**
```
Revolutionizing businesses <span class="main-hero__title-highlight">with SaaS solutions</span>
```

**Content:**
```
Empower your business with cutting-edge SaaS tools designed for modern teams.
```

**Button Link**: Add your CTA link

**Video**: Paste your video embed URL or code

---

## Notes

- The video will be embedded directly (no modal/lightbox)
- The gradient background creates a modern, fresh look
- Floating cards are still supported for additional visual interest
- Fully responsive design
