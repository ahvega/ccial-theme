# CCIAL Directory Custom Post Type

## Overview

The Directory CPT is designed to manage National Associations and Camp Sites across Latin America. It provides a clean, translatable structure optimized for WPML multilingual support.

## Features

### Custom Post Type: `ccial_directory`

- **Slug**: `/directory/`
- **Supports**: Title, Editor, Excerpt, Thumbnail, Revisions, Page Attributes
- **REST API**: Enabled for headless/API usage
- **Menu Position**: 20 (after Pages)
- **Menu Icon**: Location icon (`dashicons-location-alt`)

### Taxonomies

#### 1. Directory Type (`directory_type`) - Non-hierarchical

- **Purpose**: Categorize entries by type
- **Default Terms**:
  - `national_association` → "National Association"
  - `camp_site` → "Camp Site"

#### 2. Country/Region (`country_region`) - Hierarchical

- **Purpose**: Organize by geographical location
- **Default Countries**: Argentina, Bolivia, Brazil, Chile, Colombia, Costa Rica, Ecuador, El Salvador, Guatemala, Honduras, Mexico, Nicaragua, Panama, Paraguay, Peru, Uruguay, Venezuela

## Internationalization

All labels are translatable using WordPress `__()` functions with the `'ccial'` text domain:

```php
// Example
'name' => _x('Directory', 'Post type general name', 'ccial'),
```

### Translation Priority

- **Primary Language**: Spanish (as configured in WPML)
- **Secondary Language**: English
- **Text Domain**: `ccial`

## Templates

### Single Entry: `single-ccial_directory.php`

- Clean, focused layout for individual directory entries
- Displays type, country/region, and content
- Responsive design with mobile optimization

### Archive: `archive-ccial_directory.php`

- Grid layout with filtering capabilities
- Filter by Directory Type and Country/Region
- Pagination support
- Responsive grid (3 columns → 1 column on mobile)

## Admin Features

### Custom Admin Columns

- **Entry Name**: Post title
- **Type**: Directory type taxonomy
- **Country/Region**: Country/region taxonomy
- **Date**: Publication date

### Sortable Columns

- All custom columns are sortable
- Proper query handling for taxonomy sorting

## Setup Process

1. **Automatic Setup**: Runs on theme activation
2. **Initial Data**: Creates default taxonomy terms
3. **Rewrite Rules**: Flushes permalinks automatically

## Usage

### Adding Directory Entries

1. Go to **WordPress Admin → Directory**
2. Click **"Add New Directory Entry"**
3. Fill in title, content, and select taxonomies
4. Add featured image if desired

### Custom Fields

- **No custom fields included** - use ACF Pro as needed
- **Clean foundation** for adding specific fields later

## File Structure

```bash
wp-content/themes/ccial/
├── inc/
│   └── directory-cpt.php          # CPT registration and setup
├── single-ccial_directory.php      # Single entry template
├── archive-ccial_directory.php    # Archive template
└── DIRECTORY_README.md            # This documentation
```

## Future Enhancements

- **ACF Integration**: Add custom fields as needed
- **Advanced Filtering**: Extend filter capabilities
- **Search Integration**: Add directory-specific search
- **Map Integration**: Add geographical mapping
- **Export/Import**: Bulk management tools

## WPML Compatibility

- **Fully Compatible**: All strings are translatable
- **Taxonomy Translation**: Both taxonomies support WPML
- **Content Translation**: Posts can be translated
- **URL Structure**: Maintains proper multilingual URLs

## Version History

- **1.0.0** - Initial implementation
  - Basic CPT with two taxonomies
  - Translatable labels
  - Admin columns and sorting
  - Basic templates
  - Automatic setup
