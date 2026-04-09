# Mega Menu Setup Guide

## Overview
This guide explains how to create a 3-column mega menu with images for each category.

## WordPress Admin Setup

### Step 1: Enable CSS Classes and Descriptions
1. Go to **Appearance → Menus**
2. Click **Screen Options** (top right corner)
3. Check both:
   - ✅ CSS Classes
   - ✅ Description

### Step 2: Create Menu Structure

Create your menu with this structure:

```
Services (Parent Item)
├── Instructional Design Services (Category 1)
│   ├── Mobile Learning
│   ├── Curriculum
│   ├── Blueprint/Roadmap
│   ├── Training Needs Analysis
│   ├── Content Monetization
│   ├── Blended Learning
│   └── Instructional Design Consulting
├── Custom eLearning Development (Category 2)
│   ├── Video Training Production
│   ├── Live Action Host Video
│   ├── Video Animation
│   ├── Microlearning Solutions
│   ├── Video Storytelling
│   └── Video Training Strategy
└── LMS Support Services (Category 3)
    ├── LMS Implementation Consultant
    ├── LMS Administration
    └── LMS Management Services
```

### Step 3: Add Mega Menu Class to Parent

1. Find the parent menu item (e.g., "Services")
2. In the **CSS Classes** field, add: `mega-menu`

### Step 4: Add Images to Category Items

For each category (Instructional Design Services, Custom eLearning Development, LMS Support Services):

1. In the **Description** field, add your image HTML (just the `<img>` tag or `<svg>`):

```html
<img src="/wp-content/uploads/icons/instructional-design.svg" alt="">
```

Or use inline SVG:

```html
<svg width="64" height="64" viewBox="0 0 24 24">
  <path d="..." fill="#ff3b3b"/>
</svg>
```

**Important:** The icon will appear ABOVE the category title automatically.

### Step 5: Save Menu

Click **Save Menu**

## Image Requirements

- **Recommended size:** 60x60px
- **Formats:** SVG (preferred), PNG, or JPG
- **Location:** Upload to Media Library or use `/wp-content/uploads/` directory

## Example Screenshot

Your menu should look like this in the admin:

```
☑ Services
  CSS Classes: mega-menu

  ☑ Instructional Design Services
    Description: <img src="/path/to/icon.svg" alt="">

    ☑ Mobile Learning
    ☑ Curriculum
    ☑ Blueprint/Roadmap
    ...

  ☑ Custom eLearning Development
    Description: <img src="/path/to/icon2.svg" alt="">

    ☑ Video Training Production
    ...
```

## Customization

### Change Number of Columns

Edit `style.css` line with `grid-template-columns`:

```css
.clean-menu .has-mega-menu > .sub-menu {
  grid-template-columns: repeat(3, 1fr); /* Change 3 to desired columns */
}
```

### Change Icon Size

Edit the `.mega-menu-icon` styles:

```css
.mega-menu-icon {
  width: 80px;  /* Change from 60px */
  height: 80px;
}
```

### Change Colors

```css
.clean-menu .has-mega-menu > .sub-menu > li > a {
  color: #2c2c2c; /* Category title color */
}

.clean-menu .has-mega-menu > .sub-menu > li .sub-menu li a:hover {
  color: #ff3b3b; /* Hover color */
}
```

## Troubleshooting

**Images not showing?**
- Check the image path is correct
- Make sure images are uploaded to Media Library
- Verify Description field is enabled in Screen Options

**Menu not in 3 columns?**
- Ensure parent item has `mega-menu` CSS class
- Check that you have exactly 3 category items
- Clear browser cache

**Category titles are clickable (shouldn't be)?**
- This is by design - they're not clickable (pointer-events: none)
- Only sub-items should be clickable

## Need Help?

Check your browser console for errors and ensure:
1. CSS is loaded correctly
2. Menu structure matches the guide
3. `mega-menu` class is added to parent item
