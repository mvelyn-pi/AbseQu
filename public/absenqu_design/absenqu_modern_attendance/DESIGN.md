---
name: AbsenQu Modern Attendance
colors:
  surface: '#fcf9f8'
  surface-dim: '#dcd9d9'
  surface-bright: '#fcf9f8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f6f3f2'
  surface-container: '#f0eded'
  surface-container-high: '#eae7e7'
  surface-container-highest: '#e5e2e1'
  on-surface: '#1b1c1c'
  on-surface-variant: '#41493e'
  inverse-surface: '#303030'
  inverse-on-surface: '#f3f0ef'
  outline: '#717a6d'
  outline-variant: '#c0c9bb'
  surface-tint: '#2a6b2c'
  primary: '#00450d'
  on-primary: '#ffffff'
  primary-container: '#1b5e20'
  on-primary-container: '#90d689'
  inverse-primary: '#91d78a'
  secondary: '#006e1c'
  on-secondary: '#ffffff'
  secondary-container: '#91f78e'
  on-secondary-container: '#00731e'
  tertiary: '#363d33'
  on-tertiary: '#ffffff'
  tertiary-container: '#4d5449'
  on-tertiary-container: '#c1c8ba'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#acf4a4'
  primary-fixed-dim: '#91d78a'
  on-primary-fixed: '#002203'
  on-primary-fixed-variant: '#0c5216'
  secondary-fixed: '#94f990'
  secondary-fixed-dim: '#78dc77'
  on-secondary-fixed: '#002204'
  on-secondary-fixed-variant: '#005313'
  tertiary-fixed: '#dee5d6'
  tertiary-fixed-dim: '#c2c9bb'
  on-tertiary-fixed: '#171d14'
  on-tertiary-fixed-variant: '#42493e'
  background: '#fcf9f8'
  on-background: '#1b1c1c'
  surface-variant: '#e5e2e1'
typography:
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  label-bold:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.05em
  button-text:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 8px
  sm: 12px
  md: 16px
  lg: 24px
  xl: 32px
  container-margin: 24px
  gutter: 16px
---

## Brand & Style

This design system is built on a foundation of **High-Contrast Minimalism** tailored for the educational sector. It departs from the cluttered, legacy aesthetic of traditional school software in favor of a "Flat-Graphic" approach. The personality is disciplined yet approachable, utilizing a clear hierarchy and ample whitespace to reduce cognitive load for students and staff.

By removing all shadows and gradients, the design system relies on **precision linework (1px borders)** and a curated green-centric palette to define structure. The emotional response should be one of efficiency, freshness, and digital native confidence—moving school administration into a contemporary, professional space.

## Colors

The palette is rooted in a "Botanical Professional" spectrum. The **Deep Green (#1B5E20)** provides the authoritative anchor for primary actions and navigation headers. The **Light Green (#4CAF50)** serves as a vibrant accent for secondary interactions and interactive states. 

The background uses **Very Light Green (#F1F8E9)** instead of pure white to reduce eye strain during extended use while maintaining a clean, "minty" freshness. Status colors are saturated and highly distinct to ensure immediate recognition of attendance patterns (Present, Late, Alpha).

## Typography

The design system utilizes **Inter** exclusively to ensure a systematic and utilitarian feel. The type scale is optimized for legibility at a glance. Headlines use a tight letter-spacing and heavy weights to create a sense of importance without needing decorative elements. Label styles are set in uppercase with slight tracking to clearly distinguish metadata from body content.

## Layout & Spacing

The layout follows a **Fluid Grid** model with strict 4px increments. 
- **Desktop:** 12-column grid with 24px margins.
- **Tablet:** 8-column grid with 24px margins.
- **Mobile:** 4-column grid with 16px margins.

Spacing is used to group related information rather than borders wherever possible. Content containers should utilize "safe-area" padding of 24px (lg) to maintain an airy, modern feel.

## Elevation & Depth

This design system employs a **zero-shadow policy**. Depth is conveyed through **Tonal Layering** and **Outline Definition**:
1.  **Level 0 (Base):** The #F1F8E9 background.
2.  **Level 1 (Cards/Containers):** White (#FFFFFF) surfaces with a 1px border (#E0E0E0 or #1B5E20 at low opacity).
3.  **Interactive State:** Elements do not "lift" on hover; instead, they change fill color or increase border thickness to 2px.

This approach creates a tactile, "paper-like" feel that is predictable and lightweight.

## Shapes

The shape language is "Soft-Modern." A consistent **8px to 12px corner radius** (defined as `rounded` and `rounded-lg`) is applied to all cards, inputs, and buttons. This softening of the strict flat design ensures the application feels friendly and accessible to junior high students. Icons must always be **Outline Style** with a 1.5pt or 2pt stroke weight to match the 1px component borders.

## Components

### Buttons
- **Primary:** Solid Deep Green (#1B5E20) fill, white text, no border.
- **Secondary:** 1px Deep Green border, transparent fill, Deep Green text.
- **States:** Hover triggers a slight darken of the fill; Active/Pressed triggers a 2px stroke.

### Input Fields
- **Default:** 1px light gray border (#D1D1D1), white fill.
- **Focus:** 1px Deep Green border. Labels should be small and positioned above the field, never inside as placeholders only.

### Cards
- **Base:** White background, 1px border (#E0E0E0), 12px radius.
- **Header Section:** Optional light green (#F1F8E9) top-strip to categorize content.

### Badges (Status)
- **Present:** Solid Light Green (#4CAF50) with dark green text.
- **Late:** Solid Yellow (#FBC02D) with dark brown text.
- **Alpha:** Solid Red (#D32F2F) with white text.
- **Shape:** Fully rounded (pill) with `label-bold` typography.

### Lists
- Attendance lists should use 1px horizontal dividers only, removing vertical lines to keep the view clean. Each row should have a subtle hover state change to #F9F9F9.