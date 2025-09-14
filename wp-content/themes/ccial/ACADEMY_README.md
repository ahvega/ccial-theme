# CCIAL Academy - Redefined Avada Portfolio CPT

## Overview

The Academy CPT is a complete redefinition of the Avada Portfolio Custom Post Type, transformed to manage CCIAL's comprehensive academic resources including Diplomados, Talleres, IFIs, and Textbooks. This implementation follows the detailed strategy outlined in the "Estrategia de implementación para gestionar contenidos académicos en Avada Portfolio" document.

## Academic Structure

### Four Main Academic Categories

1. **Diplomados** (Academic Degree Programs)
   - Sequential programs with levels and prerequisites
   - Four main specializations:
     - Biblical Counseling in Camps
     - Camp Program Design  
     - Biblical Curriculum Design in Camps
     - Experimental Education and Recreation with Purpose

2. **Talleres** (Elective Workshops)
   - Can be taken individually without prerequisites
   - Organized by related diplomado
   - Flexible learning modules

3. **IFIs** (Instructor Training Institutes)
   - Intensive 17-day certification events
   - Three levels: IFI-1, IFI-2, IFI-3
   - Require completion of corresponding diplomado

4. **Libros de Texto** (Textbooks)
   - Core textbooks used as "in-depth courses"
   - Five main titles covering all specializations

## Taxonomy Structure

### 1. Academic Categories (`portfolio_category`) - Hierarchical
**Purpose**: Organize content by type and specialization

**Main Categories**:
- `diplomados` → "Diplomados"
- `talleres` → "Talleres" 
- `ifis` → "Institutos de Formación de Instructores"
- `libros_texto` → "Libros de Texto"

**Subcategories**:
- **Diplomados**: Biblical Counseling, Program Design, Curriculum Design, Experimental Education
- **Talleres**: Workshops organized by diplomado specialization
- **IFIs**: IFI-1, IFI-2, IFI-3 levels
- **Libros**: Individual textbook titles

### 2. Competencies (`portfolio_skills`) - Non-hierarchical
**Purpose**: Tag resources with roles and competencies they develop

**Core Roles**:
- `confidente` → "Confidant"
- `director_programa` → "Program Director"
- `maestro_biblia` → "Bible Teacher"
- `facilitador_experiencias` → "Experience Facilitator"

**Additional Competencies**:
- Counseling, Curriculum Design, Recreation with Purpose, Personal Evangelism, Leadership, Team Development

### 3. Topics (`portfolio_tags`) - Non-hierarchical
**Purpose**: Cross-cutting themes and keywords

**Sample Topics**:
- Camp, Team Building, Evangelism, Spiritual Accompaniment, Outdoor Recreation, Leader Training, Community Development, Youth Ministry, Christian Education, Discipleship

## Internationalization

### Translation Strategy
- **Primary Language**: Spanish (WPML configured)
- **Secondary Language**: English
- **Text Domain**: `ccial`
- **All Labels**: Translatable via `__()` functions

### Key Translations
```php
// Post Type
'name' => _x('Academy', 'Post type general name', 'ccial'),
'singular_name' => _x('Academic Resource', 'Post type singular name', 'ccial'),

// Taxonomies
'Academic Categories' => _x('Academic Categories', 'Taxonomy General Name', 'ccial'),
'Competencies' => _x('Competencies', 'Taxonomy General Name', 'ccial'),
'Topics' => _x('Topics', 'Taxonomy General Name', 'ccial'),
```

## Templates

### Single Resource: `single-avada_portfolio.php`
- **Layout**: Clean, focused design for individual academic resources
- **Features**: 
  - Resource header with category and competency badges
  - Featured image display
  - Competencies and topics visualization
  - Navigation between resources
- **Responsive**: Mobile-optimized with adaptive grid

### Archive: `archive-avada_portfolio.php`
- **Layout**: Grid-based resource listing
- **Features**:
  - Advanced filtering by category, competency, and topic
  - Resource cards with metadata
  - Pagination support
  - Empty state handling
- **Responsive**: 3-column → 1-column on mobile

## Admin Features

### Custom Admin Columns
- **Resource Name**: Post title
- **Category**: Academic category taxonomy
- **Competencies**: Competencies taxonomy
- **Date**: Publication date

### Enhanced Functionality
- **Sortable Columns**: All custom columns support sorting
- **Menu Integration**: Uses book icon (`dashicons-book-alt`)
- **Slug Structure**: `/academy/` for clean URLs

## Implementation Details

### File Structure
```
wp-content/themes/ccial/
├── inc/
│   └── academy-cpt.php              # CPT redefinition and setup
├── single-avada_portfolio.php       # Single resource template
├── archive-avada_portfolio.php      # Archive template
└── ACADEMY_README.md                # This documentation
```

### Key Functions
- `ccial_redefine_portfolio_as_academy()` - Redefines CPT labels and settings
- `ccial_redefine_portfolio_category_as_academic_category()` - Academic categories
- `ccial_redefine_portfolio_skills_as_competencies()` - Competencies taxonomy
- `ccial_redefine_portfolio_tags_as_topics()` - Topics taxonomy
- `ccial_setup_academy_initial_data()` - Initial taxonomy setup

## Academic Resource Types

### Diplomados
- **Structure**: Sequential programs with levels
- **Requirements**: Prerequisites between levels
- **Materials**: Required textbooks and workshops
- **Outcome**: Comprehensive specialization

### Talleres
- **Structure**: Individual, elective workshops
- **Requirements**: No prerequisites (flexible)
- **Association**: Can be linked to diplomados
- **Outcome**: Specific skill development

### IFIs
- **Structure**: Intensive 17-day events
- **Requirements**: Complete diplomado levels
- **Certification**: Instructor certification
- **Outcome**: Qualified instructors

### Libros de Texto
- **Structure**: Core textbooks
- **Usage**: "In-depth courses" for diplomados
- **Content**: Comprehensive subject coverage
- **Outcome**: Deep knowledge foundation

## Future Enhancements

### ACF Pro Integration
- **Common Fields**: Duration, modality, promotional video, downloadable materials
- **Diplomado Fields**: Levels, prerequisites, required materials, learning objectives
- **Taller Fields**: Related diplomados, scheduled dates, costs, certificates
- **IFI Fields**: Certification level, requirements, location, capacity, instructors
- **Libro Fields**: Authors, publication year, index, pedagogical value

### Advanced Features
- **Relationship Fields**: Link resources to show dependencies
- **Conditional Fields**: Show relevant fields based on category
- **Lead Capture**: CTA forms for contacting National Associations
- **Search Integration**: Enhanced filtering and search capabilities

## WPML Compatibility

### Full Multilingual Support
- **Post Translation**: All academic resources can be translated
- **Taxonomy Translation**: Categories, competencies, and topics support WPML
- **URL Structure**: Maintains proper multilingual permalinks
- **Admin Interface**: Translatable admin labels and descriptions

## Usage Guidelines

### Content Management
1. **Create Resources**: Go to Academy → Add New Academic Resource
2. **Categorize**: Assign appropriate academic category and subcategory
3. **Tag Competencies**: Select relevant roles and competencies
4. **Add Topics**: Include cross-cutting themes and keywords
5. **Content**: Add detailed descriptions and featured images

### Navigation Flow
- **Student Journey**: Diplomado → Related Talleres → Required Libros → IFI
- **Flexible Learning**: Talleres can be taken independently
- **Progressive Development**: Sequential levels within diplomados
- **Certification Path**: Complete diplomado → Apply for IFI

## Version History

- **1.0.0** - Initial Academy implementation
  - Complete CPT redefinition from Avada Portfolio
  - Three-taxonomy structure (Categories, Competencies, Topics)
  - Comprehensive initial data setup
  - Custom templates with advanced filtering
  - Full internationalization support
  - Admin enhancements and sorting

## Technical Notes

### Avada Integration
- **Maintains Compatibility**: Uses existing Avada Portfolio infrastructure
- **Custom Class**: `CCIAL_Academy` replaces `Avada_Portfolio` functionality
- **Template Override**: Child theme templates take precedence
- **Styling**: Inherits Avada design system with custom enhancements

### Performance Considerations
- **Efficient Queries**: Optimized taxonomy queries and filtering
- **Caching**: Compatible with WordPress caching plugins
- **Images**: Responsive image handling with proper sizing
- **Pagination**: Efficient pagination for large resource collections
