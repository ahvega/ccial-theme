# CCIAL Theme Typescale System

## Overview

This theme implements a modern, responsive typescale system using CSS `clamp()` values, based on recommendations from [ClampGenerator's typescale tool](https://clampgenerator.com/tools/font-size-typescale/). This creates fluid typography that scales beautifully from mobile to desktop without needing media queries.

## How It Works

The typescale uses CSS `clamp()` function with three values:

- **Minimum**: Smallest font size (mobile)
- **Preferred**: Fluid scaling based on viewport width
- **Maximum**: Largest font size (desktop)

## Viewport Range

- **Mobile**: 320px (minimum font sizes)
- **Desktop**: 1440px (maximum font sizes)
- **Fluid**: Smooth scaling between these breakpoints

## Typography Scale

### Headings

| Element | Mobile | Desktop | Weight | Line Height |
|---------|--------|---------|---------|-------------|
| H1 | 32px | 56px | 700 | 1.1 |
| H2 | 28px | 44px | 700 | 1.2 |
| H3 | 24px | 36px | 600 | 1.3 |
| H4 | 20px | 30px | 600 | 1.4 |
| H5 | 18px | 24px | 500 | 1.4 |
| H6 | 16px | 20px | 500 | 1.5 |

### Body Text

| Element | Mobile | Desktop | Weight | Line Height |
|---------|--------|---------|---------|-------------|
| Body/Paragraph | 16px | 20px | 400 | 1.6 |
| Small | 14px | 18px | 400 | 1.5 |
| Tiny | 12px | 16px | 400 | 1.4 |
| Lead | 18px | 24px | 400 | 1.6 |
| Code | 14px | 18px | 400 | 1.5 |

### Display Text

| Element | Mobile | Desktop | Weight | Line Height |
|---------|--------|---------|---------|-------------|
| Display-1 | 40px | 64px | 800 | 1.1 |
| Display-2 | 36px | 56px | 800 | 1.1 |

## Usage Examples

### Basic Usage

```html
<h1>Main Heading</h1>
<h2>Section Heading</h2>
<p>Body text that scales fluidly</p>
<small>Smaller text for captions</small>
```

### Utility Classes

```html
<!-- Font sizes -->
<p class="text-xs">Extra small text</p>
<p class="text-sm">Small text</p>
<p class="text-base">Base text</p>
<p class="text-lg">Large text</p>
<p class="text-xl">Extra large text</p>
<p class="text-2xl">2X large text</p>
<p class="text-3xl">3X large text</p>
<p class="text-4xl">4X large text</p>

<!-- Font weights -->
<p class="font-light">Light text</p>
<p class="font-normal">Normal text</p>
<p class="font-medium">Medium text</p>
<p class="font-semibold">Semibold text</p>
<p class="font-bold">Bold text</p>
<p class="font-extrabold">Extrabold text</p>

<!-- Line heights -->
<p class="leading-tight">Tight line height</p>
<p class="leading-snug">Snug line height</p>
<p class="leading-normal">Normal line height</p>
<p class="leading-relaxed">Relaxed line height</p>
<p class="leading-loose">Loose line height</p>
```

### Special Classes

```html
<!-- Lead paragraph -->
<p class="lead">This is a lead paragraph with larger text</p>

<!-- Display text for hero sections -->
<h1 class="display-1">Hero Display Text</h1>
<h2 class="display-2">Secondary Hero Text</h2>

<!-- Code text -->
<code>Inline code example</code>
<pre>Block code example</pre>
```

## CSS Variables (Optional)

You can also use CSS custom properties for consistent spacing:

```css
:root {
    --text-xs: clamp(0.75rem, calc(0.6875rem + 0.3125vw), 1rem);
    --text-sm: clamp(0.875rem, calc(0.8125rem + 0.3125vw), 1.125rem);
    --text-base: clamp(1rem, calc(0.875rem + 0.625vw), 1.25rem);
    --text-lg: clamp(1.125rem, calc(1rem + 0.625vw), 1.5rem);
    --text-xl: clamp(1.25rem, calc(1.125rem + 0.625vw), 1.875rem);
    --text-2xl: clamp(1.5rem, calc(1.25rem + 1.25vw), 2.25rem);
    --text-3xl: clamp(1.75rem, calc(1.375rem + 1.875vw), 2.75rem);
    --text-4xl: clamp(2rem, calc(1.5rem + 2.5vw), 3.5rem);
}
```

## Benefits

✅ **No Media Queries**: Typography scales automatically  
✅ **Fluid Scaling**: Smooth transitions between screen sizes  
✅ **Accessibility**: Maintains minimum readable sizes  
✅ **Performance**: CSS-only solution, no JavaScript required  
✅ **Future-Proof**: Works on all modern devices  
✅ **Consistent**: Maintains visual hierarchy across all screen sizes  

## Browser Support

CSS `clamp()` is supported in:

- Chrome 79+
- Firefox 75+
- Safari 13.1+
- Edge 79+

For older browsers, consider adding fallbacks or using a polyfill.

## Customization

To adjust the typescale, modify the `clamp()` values in `style.css`. The formula is:

```css
font-size: clamp(minimum, calc(preferred), maximum);
```

Where:

- `minimum` = smallest acceptable size
- `preferred` = fluid scaling formula
- `maximum` = largest acceptable size

## Examples of Different Scales

### Minor Second (1.067)

```css
h1 { font-size: clamp(2rem, calc(1.5rem + 2.5vw), 3.2rem); }
```

### Major Third (1.25)

```css
h1 { font-size: clamp(2rem, calc(1.5rem + 2.5vw), 3.5rem); }
```

### Perfect Fourth (1.333)

```css
h1 { font-size: clamp(2rem, calc(1.5rem + 2.5vw), 3.75rem); }
```

### Golden Ratio (1.618)

```css
h1 { font-size: clamp(2rem, calc(1.5rem + 2.5vw), 4.5rem); }
```

## Resources

- [ClampGenerator Typescale Tool](https://clampgenerator.com/tools/font-size-typescale/)
- [CSS clamp() MDN Documentation](https://developer.mozilla.org/en-US/docs/Web/CSS/clamp)
- [Fluid Typography Guide](https://css-tricks.com/simplified-fluid-typography/)

---

*This typescale system is based on modern web design principles and provides an excellent foundation for responsive typography across all devices.*
