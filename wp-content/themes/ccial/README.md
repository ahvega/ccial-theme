# CCIAL Theme

[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![Avada](https://img.shields.io/badge/Avada-Theme-orange.svg)](https://theme-fusion.com/avada/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.1.0-brightgreen.svg)](https://github.com/ahvega/ccial-theme/releases)
[![Magazine Modal](https://img.shields.io/badge/Magazine%20Modal-Enabled-success.svg)](#magazine-modal-system)
[![Translation](https://img.shields.io/badge/Translation-Ready-yellow.svg)](#translation)
[![Security](https://img.shields.io/badge/Security-Hardened-red.svg)](#security-features)

A custom child theme for the Avada WordPress theme, specifically designed for CCIAL.

## 📋 Table of Contents

- [Description](#description)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Customization](#customization)
- [Magazine Modal System](#magazine-modal-system)
- [File Structure](#file-structure)
- [Development](#development)
- [Security Features](#security-features)
- [Performance Features](#performance-features)
- [Support](#support)
- [License](#license)
- [Changelog](#changelog)

## Description

CCIAL Theme is a child theme based on the popular Avada theme, providing enhanced functionality and customization options while maintaining the robust foundation of the parent theme.

## Features

- **Custom Text Domain**: Uses 'ccial' text domain for easy translation management
- **Enhanced Security**: Includes security enhancements and WordPress hardening
- **Performance Optimized**: Removes unnecessary WordPress features for better performance
- **Custom Functionality**: Ready for custom post types, taxonomies, and shortcodes
- **Translation Ready**: Fully prepared for internationalization
- **Custom Admin Experience**: Enhanced WordPress admin interface
- **SVG Support**: Full SVG file handling with security and display optimizations
- **Page Excerpts**: Enables excerpt fields for pages
- **Magazine Modal System**: Interactive magazine viewer with Calameo integration

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Avada parent theme (must be installed and activated)

## Installation

1. Upload the `ccial` folder to your `/wp-content/themes/` directory
2. Activate the theme through the 'Appearance' menu in WordPress
3. The parent theme (Avada) must be installed and activated first

## Customization

### Adding Custom CSS

Edit the `style.css` file to add your custom styles. The parent theme styles are automatically imported.

**Note**: This theme includes all custom CSS that was previously in Avada Options, ensuring consistency across all languages and avoiding WPML-related CSS duplication issues.

### Adding Custom Functionality

Edit the `functions.php` file to add custom features. The file includes:

- Custom excerpt settings
- Custom body classes
- Security enhancements
- Performance optimizations
- Custom widget areas (commented examples)
- Custom post types support (commented examples)

### Translation

1. Create a `languages` folder in your theme directory
2. Use the 'ccial' text domain for all translatable strings
3. Generate .po and .mo files using tools like Poedit

### SVG Support

The theme includes comprehensive SVG support:

- **Upload Support**: SVG files can be uploaded to the media library
- **Security**: Automatic sanitization removes potentially dangerous content
- **Display**: Proper sizing and responsive behavior in frontend
- **Admin**: Correct thumbnail display in WordPress admin
- **Dimensions**: Automatic detection of SVG dimensions from viewBox or attributes

### Page Excerpts

Excerpt fields are automatically enabled for pages, allowing you to:

- Add custom excerpts to pages
- Use excerpts in page listings and widgets
- Control page preview text in search results

### Magazine Modal System

The theme includes a complete magazine viewer system that allows visitors to click on magazine cover images and view the full publication in a modal window. This system integrates with Calameo's embed service.

**Key Features:**

- Click-to-view magazine covers
- Full-screen modal viewer
- Dark mode interface
- Translatable titles
- Mobile responsive
- Secure AJAX implementation

**Documentation:**

- `MAGAZINE-MODAL-README.md` - Technical overview and setup
- `MAGAZINE-MODAL-EDITOR-GUIDE.md` - Step-by-step editor instructions
- `MAGAZINE-MODAL-QUICK-REFERENCE.md` - Quick reference for editors
- `MAGAZINE-MODAL-TECHNICAL.md` - Developer documentation

## File Structure

```bash
ccial/
├── style.css                    # Main stylesheet with theme headers
├── functions.php                # Theme functionality and customizations
├── screenshot.jpg               # Theme preview image
├── README.md                    # This file
├── MAGAZINE-MODAL-README.md            # Magazine modal technical overview
├── MAGAZINE-MODAL-EDITOR-GUIDE.md      # Step-by-step editor instructions
├── MAGAZINE-MODAL-QUICK-REFERENCE.md   # Quick reference for editors
├── MAGAZINE-MODAL-TECHNICAL.md         # Developer documentation
├── inc/
│   └── magazine-modal.php       # Magazine modal functionality
├── js/
│   └── magazine-modal.js        # Frontend JavaScript for modal
├── css/
│   └── magazine-modal.css       # Modal styling and responsive design
└── languages/
    └── ccial.pot                # Translation template
```

## Development

### Adding Custom Post Types

Uncomment and modify the relevant section in `functions.php`:

```php
// require_once get_stylesheet_directory() . '/inc/custom-post-types.php';
```

### Adding Custom Taxonomies

Uncomment and modify the relevant section in `functions.php`:

```php
// require_once get_stylesheet_directory() . '/inc/custom-taxonomies.php';
```

### Adding Custom Shortcodes

Uncomment and modify the relevant section in `functions.php`:

```php
// require_once get_stylesheet_directory() . '/inc/shortcodes.php';
```

## Security Features

- Removes WordPress version information
- Disables XML-RPC (optional)
- Removes unnecessary WordPress meta tags
- Enhanced login page customization
- SVG security: Sanitizes SVG files and prevents script execution

## Performance Features

- Removes unnecessary WordPress features
- Optimized CSS and JavaScript loading
- Custom image sizes support
- Efficient theme setup
- SVG optimization: Automatic dimension detection and responsive display

## Support

For support and customization requests, please contact the CCIAL development team.

## License

This theme is licensed under the GPL v2 or later.

## Changelog

### Version 1.1.0

- **Magazine Modal System**: Complete interactive magazine viewer
  - Calameo embed integration
  - Dark mode interface
  - Translatable titles with filename support
  - Mobile responsive design
  - Secure AJAX implementation
  - Click-to-view magazine covers
  - Professional modal styling

### Version 1.0.0

- Initial release
- Based on Avada theme
- Custom text domain implementation
- Enhanced security and performance features
- Translation ready
