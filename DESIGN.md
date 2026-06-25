---
name: Verdant Growth
colors:
  surface: '#f8f9fa'
  surface-dim: '#d9dadb'
  surface-bright: '#f8f9fa'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f4f5'
  surface-container: '#edeeef'
  surface-container-high: '#e7e8e9'
  surface-container-highest: '#e1e3e4'
  on-surface: '#191c1d'
  on-surface-variant: '#3c4a42'
  inverse-surface: '#2e3132'
  inverse-on-surface: '#f0f1f2'
  outline: '#6c7a71'
  outline-variant: '#bbcabf'
  surface-tint: '#006c49'
  primary: '#006c49'
  on-primary: '#ffffff'
  primary-container: '#10b981'
  on-primary-container: '#00422b'
  inverse-primary: '#4edea3'
  secondary: '#944a23'
  on-secondary: '#ffffff'
  secondary-container: '#fd9e70'
  on-secondary-container: '#76340e'
  tertiary: '#1b6b51'
  on-tertiary: '#ffffff'
  tertiary-container: '#67b193'
  on-tertiary-container: '#00422f'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#6ffbbe'
  primary-fixed-dim: '#4edea3'
  on-primary-fixed: '#002113'
  on-primary-fixed-variant: '#005236'
  secondary-fixed: '#ffdbcc'
  secondary-fixed-dim: '#ffb693'
  on-secondary-fixed: '#351000'
  on-secondary-fixed-variant: '#76330d'
  tertiary-fixed: '#a6f2d1'
  tertiary-fixed-dim: '#8bd6b6'
  on-tertiary-fixed: '#002116'
  on-tertiary-fixed-variant: '#00513b'
  background: '#f8f9fa'
  on-background: '#191c1d'
  surface-variant: '#e1e3e4'
typography:
  display-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Be Vietnam Pro
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
  headline-md:
    fontFamily: Be Vietnam Pro
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Be Vietnam Pro
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-md:
    fontFamily: Be Vietnam Pro
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Be Vietnam Pro
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  container-max: 1280px
  gutter: 20px
---

## Brand & Style

The brand personality is nurturing, intelligent, and organic. It bridges the gap between high-tech "smart" automation and the tactile, grounded experience of gardening. The UI should evoke a sense of calm productivity, making the user feel like a capable steward of their environment rather than a data analyst.

The design style is **Modern Organic**. It utilizes soft, generous whitespace and a clean, systematic layout inherited from Minimalism, but softens the edges with high-radius corners and natural color transitions. The interface should feel "breathable," avoiding dense clusters of information in favor of card-based modularity that mimics garden plots. Subtle photographic textures and illustrative accents provide the "farming game" warmth requested, ensuring the app feels like a hobbyist's companion.

## Colors

The palette is rooted in the natural world. **Emerald Green** serves as the primary action color, symbolizing active growth and vitality. **Forest Green** provides a grounded contrast for deep headers and navigation elements. **Earthy Brown** is used sparingly for structural accents, iconography related to soil/roots, and secondary buttons to maintain the organic metaphor.

The background uses a soft **Off-white** to reduce eye strain and provide a clean canvas for colorful plant photography. Status colors are high-chroma to ensure critical alerts (like dehydration or pests) are immediately visible against the predominantly green UI.

## Typography

The design system utilizes **Poppins** (selected for its friendly, contemporary, and approachable qualities which align perfectly with the "Poppins" request style) across all levels. 

Headlines are set with tight letter-spacing and bold weights to convey authority and structure. Body text prioritizes legibility with a generous line height (1.5x) to ensure gardening guides and plant care instructions are easy to read while outdoors. Labels utilize medium and semi-bold weights to remain distinct even at smaller sizes in dense data views.

## Layout & Spacing

The layout follows a **Fluid Grid** model with a soft 8px spacing system. 
- **Mobile:** 4-column grid with 16px margins. Content is primarily stacked in vertical cards.
- **Tablet:** 8-column grid with 24px margins. Introduction of side-by-side card layouts for garden metrics.
- **Desktop:** 12-column grid with a maximum container width of 1280px. Sidebar navigation is fixed, while the main stage utilizes a "masonry" style layout for different garden plots.

Internal component padding should be generous (min 16px) to maintain the "airy" feel of a nature-inspired app. Components should use `lg` (24px) or `xl` (32px) gaps to prevent the UI from feeling cluttered.

## Elevation & Depth

This design system uses **Ambient Shadows** to create a sense of layering without harshness. Shadows are extremely soft, using a slight Green-Gray tint (`#065F46` at 5-8% opacity) instead of pure black to maintain the color story.

- **Level 0 (Base):** Off-white background.
- **Level 1 (Cards):** White surface with a 4px Y-offset blur. Used for primary garden stats.
- **Level 2 (Modals/Pop-overs):** White surface with a 12px Y-offset blur. Used for adding new plants or detailed sensor settings.
- **Tonal Layers:** Secondary information (like empty garden slots) should use a very light sage-tinted background (`#F1F5F2`) with no shadow to appear "recessed" into the earth.

## Shapes

The shape language is defined by **Organic Rounding**. Following the "2xl" request, the standard corner radius for cards and containers is **1rem (16px)**, scaling up to **1.5rem (24px)** for large featured dashboard elements. 

Interactive elements like buttons and input fields should feel soft to the touch. Icons should be encased in circular or highly rounded backgrounds to mimic pebbles or seeds.

## Components

### Buttons
- **Primary:** Forest Green background with White text. 24px corner radius (Pill-shaped).
- **Secondary:** Earthy Brown border (2px) with Earthy Brown text. Clear background.
- **Tertiary/Ghost:** Emerald Green text, no border. Used for "Cancel" or "Learn More".

### Cards (Garden Plots)
- White background, 16px padding, 24px corner radius.
- Must include a slot for a high-quality plant image at the top with a bottom-clip radius.
- Progress bars for water/sun levels should use rounded caps and thick 8px tracks.

### Chips (Plant Tags)
- Small, pill-shaped indicators.
- Use light tints of the status colors (e.g., Light Red background with Dark Red text for "Urgent Water").

### Inputs
- Background: Very light sage.
- Border: 1px solid Gray-Green, turning Emerald Green on focus.
- 12px corner radius.

### Garden Map Elements
- Map nodes should be "squishy" and interactive.
- Use illustrative, thick-line botanical icons (2px stroke) with rounded ends to maintain the friendly, "farming game" aesthetic.