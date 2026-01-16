<?php
/**
 * CCI AL Child Theme Functions
 * 
 * @package CCI AL
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function ccial_enqueue_styles() {
    // Enqueue parent theme styles
    wp_enqueue_style('avada-style', get_template_directory_uri() . '/style.css', array(), '1.0.0');
    
    // Enqueue child theme styles
    wp_enqueue_style('ccial-style', get_stylesheet_directory_uri() . '/style.css', array('avada-style'), '1.0.0');
}
add_action('wp_enqueue_scripts', 'ccial_enqueue_styles', 20);

/**
 * Load child theme text domain
 */
function ccial_lang_setup() {
    $lang = get_stylesheet_directory() . '/languages';
    load_child_theme_textdomain('ccial', $lang);
}
add_action('after_setup_theme', 'ccial_lang_setup');

/**
 * Add custom functionality for CCI AL theme
 */
function ccial_theme_setup() {
    // Add theme support for various features
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Add custom image sizes if needed
    // add_image_size('ccial-featured', 800, 600, true);
}
add_action('after_setup_theme', 'ccial_theme_setup');

/**
 * Custom excerpt length
 */
function ccial_excerpt_length($length) {
    return 25; // Customize this number as needed
}
add_filter('excerpt_length', 'ccial_excerpt_length');

/**
 * Custom excerpt more text
 */
function ccial_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'ccial_excerpt_more');

/**
 * Add custom body classes
 */
function ccial_body_classes($classes) {
    // Add custom body class for CCI AL theme
    $classes[] = 'ccial-theme';
    
    return $classes;
}
add_filter('body_class', 'ccial_body_classes');

/**
 * Custom login logo (if needed)
 */
function ccial_custom_login_logo() {
    // Uncomment and customize if you want a custom login logo
    /*
    echo '<style type="text/css">
        #login h1 a { 
            background-image: url(' . get_stylesheet_directory_uri() . '/images/custom-login-logo.png) !important; 
            background-size: contain !important;
            width: 300px !important;
            height: 100px !important;
        }
    </style>';
    */
}
add_action('login_head', 'ccial_custom_login_logo');

/**
 * Add custom admin styles
 */
function ccial_admin_styles() {
    wp_enqueue_style('ccial-admin-style', get_stylesheet_directory_uri() . '/admin-style.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'ccial_admin_styles');

/**
 * Customize WordPress admin footer
 */
function ccial_admin_footer() {
    echo '<span id="footer-thankyou">' . __('Developed with ❤️ for CCI AL', 'ccial') . '</span>';
}
add_filter('admin_footer_text', 'ccial_admin_footer');

/**
 * Add custom widgets areas if needed
 */
function ccial_widgets_init() {
    // Example: Add a custom widget area
    /*
    register_sidebar(array(
        'name'          => __('CCI AL Sidebar', 'ccial'),
        'id'            => 'ccial-sidebar',
        'description'   => __('Add widgets here to appear in your sidebar.', 'ccial'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    */
}
add_action('widgets_init', 'ccial_widgets_init');

/**
 * Security enhancements
 */
function ccial_security_enhancements() {
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');
    
    // Remove WordPress version from RSS feeds
    add_filter('the_generator', '__return_empty_string');
    
    // Disable XML-RPC if not needed
    // add_filter('xmlrpc_enabled', '__return_false');
}
add_action('init', 'ccial_security_enhancements');

/**
 * Performance optimizations
 */
function ccial_performance_optimizations() {
    // Remove unnecessary WordPress features
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'ccial_performance_optimizations');

/**
 * Enable excerpt fields for pages
 */
function ccial_add_excerpt_to_pages() {
    add_post_type_support('page', 'excerpt');
}
add_action('init', 'ccial_add_excerpt_to_pages');

/**
 * Enhanced SVG handling for WordPress
 */
function ccial_svg_upload_mimes($mimes) {
    // Add SVG support
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    
    return $mimes;
}
add_filter('upload_mimes', 'ccial_svg_upload_mimes');

/**
 * Sanitize SVG files on upload
 */
function ccial_sanitize_svg($file) {
    if ($file['type'] === 'image/svg+xml') {
        if (!function_exists('simplexml_load_file')) {
            return $file;
        }
        
        // Check if we can load the SVG
        $file_content = file_get_contents($file['tmp_name']);
        if ($file_content === false) {
            return $file;
        }
        
        // Remove any script tags and other potentially dangerous content
        $file_content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $file_content);
        $file_content = preg_replace('/<on\w+\s*=\s*["\'][^"\']*["\']/i', '', $file_content);
        $file_content = preg_replace('/javascript:/i', '', $file_content);
        $file_content = preg_replace('/vbscript:/i', '', $file_content);
        $file_content = preg_replace('/data:/i', '', $file_content);
        
        // Write the sanitized content back
        file_put_contents($file['tmp_name'], $file_content);
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'ccial_sanitize_svg');

/**
 * Add SVG support in media library - ONLY for media library thumbnails
 */
function ccial_fix_svg_thumb_display() {
    echo '
    <style>
        /* Only target media library SVG thumbnails, not all admin SVG elements */
        .media-frame .media-icon img[src$=".svg"],
        .media-frame .attachment-266x266 img[src$=".svg"],
        .media-frame .thumbnail img[src$=".svg"],
        .wp-list-table .media-icon img[src$=".svg"] {
            width: 100% !important;
            height: auto !important;
        }
    </style>
    ';
}
add_action('admin_head', 'ccial_fix_svg_thumb_display');

/**
 * Fix SVG dimensions in frontend
 */
function ccial_fix_svg_dimensions($attr, $attachment, $size) {
    // Only process if we have a valid attachment
    if (!$attachment || !is_object($attachment)) {
        return $attr;
    }
    
    $image_url = wp_get_attachment_url($attachment->ID);
    if (!$image_url) {
        return $attr;
    }
    
    $file_type = wp_check_filetype($image_url);
    
    if ($file_type['type'] === 'image/svg+xml') {
        if (empty($attr['width']) || empty($attr['height'])) {
            $svg_path = get_attached_file($attachment->ID);
            if ($svg_path && file_exists($svg_path)) {
                $svg_content = file_get_contents($svg_path);
                if ($svg_content !== false) {
                    // Try to get dimensions from SVG viewBox or width/height attributes
                    if (preg_match('/viewBox=["\']([^"\']+)["\']/', $svg_content, $matches)) {
                        $viewbox = explode(' ', $matches[1]);
                        if (count($viewbox) >= 4) {
                            $attr['width'] = $viewbox[2];
                            $attr['height'] = $viewbox[3];
                        }
                    } elseif (preg_match('/width=["\']([^"\']+)["\']/', $svg_content, $matches)) {
                        $attr['width'] = $matches[1];
                    } elseif (preg_match('/height=["\']([^"\']+)["\']/', $svg_content, $matches)) {
                        $attr['height'] = $matches[1];
                    }
                    
                    // Set default dimensions if none found
                    if (empty($attr['width'])) $attr['width'] = '100';
                    if (empty($attr['height'])) $attr['height'] = '100';
                }
            }
        }
        
        // Ensure SVG is responsive
        $attr['style'] = 'max-width: 100%; height: auto;';
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'ccial_fix_svg_dimensions', 10, 3);

/**
 * Add SVG support for customizer
 */
function ccial_customizer_svg_support($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'ccial_customizer_svg_support');

/**
 * Security: Disable SVG execution
 */
function ccial_disable_svg_execution() {
    // Remove any potential execution capabilities
    remove_action('wp_head', 'wp_generator');
    
    // Add security headers for SVG files
    if (isset($_GET['svg']) || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '.svg') !== false)) {
        header('Content-Security-Policy: default-src \'none\'');
        header('X-Content-Type-Options: nosniff');
    }
}
add_action('init', 'ccial_disable_svg_execution');

/**
 * ACF Field: Load countries list for 'pais' field
 * Provides a comprehensive list of countries for Directory and User contact info
 * Supports bilingual site (English/Spanish)
 */
function acf_load_latam_countries($field) {
    $field['choices'] = array(
        // Latin America and Caribbean
        'AR' => __('Argentina', 'ccial'),
        'BO' => __('Bolivia', 'ccial'),
        'BR' => __('Brazil', 'ccial'),
        'BZ' => __('Belize', 'ccial'),
        'CL' => __('Chile', 'ccial'),
        'CO' => __('Colombia', 'ccial'),
        'CR' => __('Costa Rica', 'ccial'),
        'CU' => __('Cuba', 'ccial'),
        'DO' => __('Dominican Republic', 'ccial'),
        'EC' => __('Ecuador', 'ccial'),
        'SV' => __('El Salvador', 'ccial'),
        'GT' => __('Guatemala', 'ccial'),
        'GY' => __('Guyana', 'ccial'),
        'HT' => __('Haiti', 'ccial'),
        'HN' => __('Honduras', 'ccial'),
        'JM' => __('Jamaica', 'ccial'),
        'MX' => __('Mexico', 'ccial'),
        'NI' => __('Nicaragua', 'ccial'),
        'PA' => __('Panama', 'ccial'),
        'PY' => __('Paraguay', 'ccial'),
        'PE' => __('Peru', 'ccial'),
        'PR' => __('Puerto Rico', 'ccial'),
        'SR' => __('Suriname', 'ccial'),
        'TT' => __('Trinidad and Tobago', 'ccial'),
        'UY' => __('Uruguay', 'ccial'),
        'VE' => __('Venezuela', 'ccial'),
        
        // North America
        'CA' => __('Canada', 'ccial'),
        'US' => __('United States', 'ccial'),
        
        // Europe
        'ES' => __('Spain', 'ccial'),
        'FR' => __('France', 'ccial'),
        'IT' => __('Italy', 'ccial'),
        'PT' => __('Portugal', 'ccial'),
        'DE' => __('Germany', 'ccial'),
        'GB' => __('United Kingdom', 'ccial'),
        'NL' => __('Netherlands', 'ccial'),
        'BE' => __('Belgium', 'ccial'),
        'CH' => __('Switzerland', 'ccial'),
        'AT' => __('Austria', 'ccial'),
        'IE' => __('Ireland', 'ccial'),
        'SE' => __('Sweden', 'ccial'),
        'NO' => __('Norway', 'ccial'),
        'DK' => __('Denmark', 'ccial'),
        'FI' => __('Finland', 'ccial'),
        
        // Asia
        'CN' => __('China', 'ccial'),
        'JP' => __('Japan', 'ccial'),
        'KR' => __('South Korea', 'ccial'),
        'IN' => __('India', 'ccial'),
        'TH' => __('Thailand', 'ccial'),
        'SG' => __('Singapore', 'ccial'),
        'MY' => __('Malaysia', 'ccial'),
        'PH' => __('Philippines', 'ccial'),
        'ID' => __('Indonesia', 'ccial'),
        'VN' => __('Vietnam', 'ccial'),
        'TW' => __('Taiwan', 'ccial'),
        'HK' => __('Hong Kong', 'ccial'),
        
        // Africa
        'ZA' => __('South Africa', 'ccial'),
        'EG' => __('Egypt', 'ccial'),
        'NG' => __('Nigeria', 'ccial'),
        'KE' => __('Kenya', 'ccial'),
        'MA' => __('Morocco', 'ccial'),
        'TN' => __('Tunisia', 'ccial'),
        'GH' => __('Ghana', 'ccial'),
        'ET' => __('Ethiopia', 'ccial'),
        
        // Oceania
        'AU' => __('Australia', 'ccial'),
        'NZ' => __('New Zealand', 'ccial'),
        'FJ' => __('Fiji', 'ccial'),
        
        // Other important countries
        'RU' => __('Russia', 'ccial'),
        'TR' => __('Turkey', 'ccial'),
        'IL' => __('Israel', 'ccial'),
        'SA' => __('Saudi Arabia', 'ccial'),
        'AE' => __('United Arab Emirates', 'ccial'),
        'QA' => __('Qatar', 'ccial'),
        'KW' => __('Kuwait', 'ccial'),
        'BH' => __('Bahrain', 'ccial'),
        'OM' => __('Oman', 'ccial'),
        'JO' => __('Jordan', 'ccial'),
        'LB' => __('Lebanon', 'ccial'),
        
        // Generic option
        'XX' => __('Other Country', 'ccial')
    );
    
    return $field;
}
add_filter('acf/load_field/name=pais', 'acf_load_latam_countries');

/**
 * ACF Field: Load academic levels for Academy resources
 */
function acf_load_niveles_academia($field) {
    $field['choices'] = array(
        __('Diploma', 'ccial') => __('Diploma', 'ccial'),
        __('Workshop', 'ccial') => __('Workshop', 'ccial'),
        __('IFI Level 1', 'ccial') => __('IFI Level 1', 'ccial'),
        __('IFI Level 2', 'ccial') => __('IFI Level 2', 'ccial'),
        __('IFI Level 3', 'ccial') => __('IFI Level 3', 'ccial'),
        __('Textbook Course', 'ccial') => __('Textbook Course', 'ccial'),
        __('Certification', 'ccial') => __('Certification', 'ccial'),
        __('Specialization', 'ccial') => __('Specialization', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=nivel_academia', 'acf_load_niveles_academia');

/**
 * ACF Field: Load academic competencies
 */
function acf_load_competencias_academia($field) {
    $field['choices'] = array(
        __('Biblical Counseling', 'ccial') => __('Biblical Counseling', 'ccial'),
        __('Program Design', 'ccial') => __('Program Design', 'ccial'),
        __('Curriculum Design', 'ccial') => __('Curriculum Design', 'ccial'),
        __('Experimental Education', 'ccial') => __('Experimental Education', 'ccial'),
        __('Leadership Development', 'ccial') => __('Leadership Development', 'ccial'),
        __('Team Building', 'ccial') => __('Team Building', 'ccial'),
        __('Personal Evangelism', 'ccial') => __('Personal Evangelism', 'ccial'),
        __('Camp Ministry', 'ccial') => __('Camp Ministry', 'ccial'),
        __('Youth Ministry', 'ccial') => __('Youth Ministry', 'ccial'),
        __('Christian Education', 'ccial') => __('Christian Education', 'ccial'),
        __('Discipleship', 'ccial') => __('Discipleship', 'ccial'),
        __('Community Development', 'ccial') => __('Community Development', 'ccial'),
        __('Spiritual Accompaniment', 'ccial') => __('Spiritual Accompaniment', 'ccial'),
        __('Outdoor Recreation', 'ccial') => __('Outdoor Recreation', 'ccial'),
        __('Recreation with Purpose', 'ccial') => __('Recreation with Purpose', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=competencias_academia', 'acf_load_competencias_academia');

/**
 * ACF Field: Load academic topics
 */
function acf_load_temas_academia($field) {
    $field['choices'] = array(
        __('Camp Ministry', 'ccial') => __('Camp Ministry', 'ccial'),
        __('Team Building', 'ccial') => __('Team Building', 'ccial'),
        __('Evangelism', 'ccial') => __('Evangelism', 'ccial'),
        __('Spiritual Accompaniment', 'ccial') => __('Spiritual Accompaniment', 'ccial'),
        __('Outdoor Recreation', 'ccial') => __('Outdoor Recreation', 'ccial'),
        __('Leader Training', 'ccial') => __('Leader Training', 'ccial'),
        __('Community Development', 'ccial') => __('Community Development', 'ccial'),
        __('Youth Ministry', 'ccial') => __('Youth Ministry', 'ccial'),
        __('Christian Education', 'ccial') => __('Christian Education', 'ccial'),
        __('Discipleship', 'ccial') => __('Discipleship', 'ccial'),
        __('Biblical Counseling', 'ccial') => __('Biblical Counseling', 'ccial'),
        __('Program Design', 'ccial') => __('Program Design', 'ccial'),
        __('Curriculum Design', 'ccial') => __('Curriculum Design', 'ccial'),
        __('Experimental Education', 'ccial') => __('Experimental Education', 'ccial'),
        __('Leadership', 'ccial') => __('Leadership', 'ccial'),
        __('Team Development', 'ccial') => __('Team Development', 'ccial'),
        __('Personal Evangelism', 'ccial') => __('Personal Evangelism', 'ccial'),
        __('Recreation with Purpose', 'ccial') => __('Recreation with Purpose', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=temas_academia', 'acf_load_temas_academia');

/**
 * ACF Field: Load academic resource types
 */
function acf_load_tipos_recurso_academia($field) {
    $field['choices'] = array(
        __('Diploma Program', 'ccial') => __('Diploma Program', 'ccial'),
        __('Workshop', 'ccial') => __('Workshop', 'ccial'),
        __('IFI Training', 'ccial') => __('IFI Training', 'ccial'),
        __('Textbook Course', 'ccial') => __('Textbook Course', 'ccial'),
        __('Online Course', 'ccial') => __('Online Course', 'ccial'),
        __('Seminar', 'ccial') => __('Seminar', 'ccial'),
        __('Conference', 'ccial') => __('Conference', 'ccial'),
        __('Retreat', 'ccial') => __('Retreat', 'ccial'),
        __('Certification Program', 'ccial') => __('Certification Program', 'ccial'),
        __('Specialization Course', 'ccial') => __('Specialization Course', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=tipo_recurso_academia', 'acf_load_tipos_recurso_academia');

/**
 * ACF Field: Load AN positions/roles
 */
function acf_load_cargos_an($field) {
    $field['choices'] = array(
        __('President', 'ccial') => __('President', 'ccial'),
        __('Vice President', 'ccial') => __('Vice President', 'ccial'),
        __('Secretary', 'ccial') => __('Secretary', 'ccial'),
        __('Treasurer', 'ccial') => __('Treasurer', 'ccial'),
        __('Executive Director', 'ccial') => __('Executive Director', 'ccial'),
        __('Program Director', 'ccial') => __('Program Director', 'ccial'),
        __('Board Member', 'ccial') => __('Board Member', 'ccial'),
        __('Regional Coordinator', 'ccial') => __('Regional Coordinator', 'ccial'),
        __('Camp Director', 'ccial') => __('Camp Director', 'ccial'),
        __('Training Coordinator', 'ccial') => __('Training Coordinator', 'ccial'),
        __('Youth Coordinator', 'ccial') => __('Youth Coordinator', 'ccial'),
        __('Development Director', 'ccial') => __('Development Director', 'ccial'),
        __('Communications Director', 'ccial') => __('Communications Director', 'ccial'),
        __('Other', 'ccial') => __('Other', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=cargo_an', 'acf_load_cargos_an');

/**
 * ACF Field: Load organization types
 */
function acf_load_tipos_organizacion($field) {
    $field['choices'] = array(
        __('National Association', 'ccial') => __('National Association', 'ccial'),
        __('Regional Office', 'ccial') => __('Regional Office', 'ccial'),
        __('Local Chapter', 'ccial') => __('Local Chapter', 'ccial'),
        __('Camp Site', 'ccial') => __('Camp Site', 'ccial'),
        __('Training Center', 'ccial') => __('Training Center', 'ccial'),
        __('Headquarters', 'ccial') => __('Headquarters', 'ccial'),
        __('Field Office', 'ccial') => __('Field Office', 'ccial'),
        __('Partner Organization', 'ccial') => __('Partner Organization', 'ccial'),
        __('Affiliate', 'ccial') => __('Affiliate', 'ccial'),
        __('Other', 'ccial') => __('Other', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=tipo_organizacion', 'acf_load_tipos_organizacion');

/**
 * ACF Field: Load member status
 */
function acf_load_estado_miembro($field) {
    $field['choices'] = array(
        __('Active', 'ccial') => __('Active', 'ccial'),
        __('Inactive', 'ccial') => __('Inactive', 'ccial'),
        __('Honorary', 'ccial') => __('Honorary', 'ccial'),
        __('Emeritus', 'ccial') => __('Emeritus', 'ccial'),
        __('Suspended', 'ccial') => __('Suspended', 'ccial'),
        __('Pending', 'ccial') => __('Pending', 'ccial'),
        __('Probationary', 'ccial') => __('Probationary', 'ccial'),
        __('Retired', 'ccial') => __('Retired', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=estado_miembro', 'acf_load_estado_miembro');

/**
 * ACF Field: Load contact types
 */
function acf_load_tipos_contacto($field) {
    $field['choices'] = array(
        __('Email', 'ccial') => __('Email', 'ccial'),
        __('Phone', 'ccial') => __('Phone', 'ccial'),
        __('Mobile', 'ccial') => __('Mobile', 'ccial'),
        __('WhatsApp', 'ccial') => __('WhatsApp', 'ccial'),
        __('Telegram', 'ccial') => __('Telegram', 'ccial'),
        __('Skype', 'ccial') => __('Skype', 'ccial'),
        __('Zoom', 'ccial') => __('Zoom', 'ccial'),
        __('Facebook', 'ccial') => __('Facebook', 'ccial'),
        __('Instagram', 'ccial') => __('Instagram', 'ccial'),
        __('LinkedIn', 'ccial') => __('LinkedIn', 'ccial'),
        __('Twitter', 'ccial') => __('Twitter', 'ccial'),
        __('Website', 'ccial') => __('Website', 'ccial'),
        __('Office Address', 'ccial') => __('Office Address', 'ccial'),
        __('Home Address', 'ccial') => __('Home Address', 'ccial'),
        __('Other', 'ccial') => __('Other', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=tipo_contacto', 'acf_load_tipos_contacto');

/**
 * ACF Field: Load academic achievement status
 */
function acf_load_estado_logro_academia($field) {
    $field['choices'] = array(
        __('Completed', 'ccial') => __('Completed', 'ccial'),
        __('In Progress', 'ccial') => __('In Progress', 'ccial'),
        __('Enrolled', 'ccial') => __('Enrolled', 'ccial'),
        __('Certified', 'ccial') => __('Certified', 'ccial'),
        __('Graduated', 'ccial') => __('Graduated', 'ccial'),
        __('Pending Review', 'ccial') => __('Pending Review', 'ccial'),
        __('Failed', 'ccial') => __('Failed', 'ccial'),
        __('Withdrawn', 'ccial') => __('Withdrawn', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=estado_logro_academia', 'acf_load_estado_logro_academia');

/**
 * ACF Field: Load priority levels
 */
function acf_load_niveles_prioridad($field) {
    $field['choices'] = array(
        __('Low', 'ccial') => __('Low', 'ccial'),
        __('Medium', 'ccial') => __('Medium', 'ccial'),
        __('High', 'ccial') => __('High', 'ccial'),
        __('Critical', 'ccial') => __('Critical', 'ccial'),
        __('Urgent', 'ccial') => __('Urgent', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=nivel_prioridad', 'acf_load_niveles_prioridad');

/**
 * ACF Field: Load visibility options
 */
function acf_load_opciones_visibilidad($field) {
    $field['choices'] = array(
        __('Public', 'ccial') => __('Public', 'ccial'),
        __('Private', 'ccial') => __('Private', 'ccial'),
        __('Members Only', 'ccial') => __('Members Only', 'ccial'),
        __('Staff Only', 'ccial') => __('Staff Only', 'ccial'),
        __('Administrators Only', 'ccial') => __('Administrators Only', 'ccial'),
        __('Hidden', 'ccial') => __('Hidden', 'ccial')
    );
    return $field;
}
add_filter('acf/load_field/name=opcion_visibilidad', 'acf_load_opciones_visibilidad');

// Include additional functionality files Andry Javier  88036789
// require_once get_stylesheet_directory() . '/inc/custom-post-types.php';
// require_once get_stylesheet_directory() . '/inc/custom-taxonomies.php';
require_once get_stylesheet_directory() . '/inc/directory-cpt.php';
require_once get_stylesheet_directory() . '/inc/academy-cpt.php';
require_once get_stylesheet_directory() . '/inc/magazine-modal.php';
require_once get_stylesheet_directory() . '/inc/user-export-api.php';

/**
 * ACF Image Shortcode
 * 
 * Displays ACF image fields with flexible options for size, linking, and styling.
 * Particularly useful for Avada theme nested columns where direct ACF field insertion fails.
 * Supports both post fields and user profile fields.
 * 
 * Usage: 
 * - Default (post author's profile): [acf_image field="foto" size="medium" class="custom-class"]
 * - Specific user profile: [acf_image field="foto" user_id="123" size="medium"]
 * - Current user profile: [acf_image field="foto" user_id="current" size="medium"]
 * - Post fields only: [acf_image field="foto" post_id="123" size="medium"]
 * - Return URL only (for Avada image elements): [acf_image field="foto" return="url"]
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output or URL string depending on 'return' parameter
 */
function ccial_acf_image_shortcode($atts) {
    $a = shortcode_atts([
        'field'     => '',       // ACF field name or key
        'post_id'   => '',       // defaults to current post. Use "option" for ACF Options
        'user_id'   => '',       // user ID for user profile fields. Use "current" for current user
        'size'      => 'full',   // any registered size (thumbnail, medium, large, full, custom)
        'class'     => '',       // extra classes for <img>
        'link'      => 'none',   // none|file|attachment
        'alt_field' => '',       // optional: ACF text field to override alt text
        'fallback'  => '',       // URL to a fallback image if empty
        'lazy'      => 'true',   // lazy load attribute
        'return'    => 'html',   // html|url - return full HTML img tag or just the URL
    ], $atts, 'acf_image');

    if (empty($a['field'])) {
        return '<!-- ACF Image Shortcode: No field specified -->';
    }

    // Determine context: user profile or post
    if (!empty($a['user_id'])) {
        // User profile context (explicit user_id provided)
        if ($a['user_id'] === 'current') {
            $user_id = get_current_user_id();
        } else {
            $user_id = intval($a['user_id']);
        }
        
        if (!$user_id) {
            return '<!-- ACF Image Shortcode: Invalid user ID -->';
        }
        
        $img = get_field($a['field'], 'user_' . $user_id);
        $alt_field_context = 'user_' . $user_id;
    } else {
        // Default: try post author's user profile first, then post fields
        $post_id = $a['post_id'] ?: get_queried_object_id();
        $post = get_post($post_id);
        
        if ($post && $post->post_author) {
            // Try to get field from post author's user profile first
            $img = get_field($a['field'], 'user_' . $post->post_author);
            $alt_field_context = 'user_' . $post->post_author;
            
            // If no image found in user profile, try post fields
            if (empty($img)) {
                $img = get_field($a['field'], $post_id);
                $alt_field_context = $post_id;
            }
        } else {
            // Fallback to post fields only
            $img = get_field($a['field'], $post_id);
            $alt_field_context = $post_id;
        }
    }

    // Normalize to attachment ID + alt
    $attachment_id = null;
    $alt = '';

    if ($a['alt_field']) {
        $custom_alt = get_field($a['alt_field'], $alt_field_context);
        if (!empty($custom_alt)) $alt = $custom_alt;
    }

    if (is_array($img)) {
        // image array (ACF return = array)
        $attachment_id = isset($img['ID']) ? intval($img['ID']) : 0;
        if (!$alt && !empty($img['alt'])) $alt = $img['alt'];
    } elseif (is_numeric($img)) {
        // image id (ACF return = id)
        $attachment_id = intval($img);
    } elseif (is_string($img) && filter_var($img, FILTER_VALIDATE_URL)) {
        // image url (ACF return = url)
        $url = esc_url($img);
        
        // If return="url", just return the URL
        if ($a['return'] === 'url') {
            return $url;
        }
        
        $alt = $alt ?: '';
        $loading = ($a['lazy'] === 'false') ? '' : ' loading="lazy"';
        $class   = $a['class'] ? ' class="'.esc_attr($a['class']).'"' : '';
        $img_html = '<img src="'.$url.'" alt="'.esc_attr($alt).'"'.$class.$loading.' />';
        return $img_html;
    }

    // If we have an attachment id, use wp_get_attachment_image()
    if ($attachment_id) {
        // If return="url", get the image URL instead of HTML
        if ($a['return'] === 'url') {
            $image_url = wp_get_attachment_image_url($attachment_id, $a['size']);
            if ($image_url) {
                return esc_url($image_url);
            }
        }
        
        $attrs = [
            'class'   => $a['class'],
            'loading' => ($a['lazy'] === 'false') ? 'eager' : 'lazy',
        ];
        if ($alt !== '') $attrs['alt'] = $alt;

        $img_html = wp_get_attachment_image($attachment_id, $a['size'], false, $attrs);

        // Optional wrapping link
        if ($a['link'] === 'file') {
            $href = wp_get_attachment_url($attachment_id);
            $img_html = '<a href="'.esc_url($href).'" target="_blank" rel="noopener">'.$img_html.'</a>';
        } elseif ($a['link'] === 'attachment') {
            $href = get_attachment_link($attachment_id);
            $img_html = '<a href="'.esc_url($href).'">'.$img_html.'</a>';
        }

        return $img_html;
    }

    // Fallback URL if no image set
    if (!empty($a['fallback'])) {
        $url = esc_url($a['fallback']);
        
        // If return="url", just return the URL
        if ($a['return'] === 'url') {
            return $url;
        }
        
        $loading = ($a['lazy'] === 'false') ? '' : ' loading="lazy"';
        $class   = $a['class'] ? ' class="'.esc_attr($a['class']).'"' : '';
        return '<img src="'.$url.'" alt="'.esc_attr($alt).'"'.$class.$loading.' />';
    }

    return '<!-- ACF Image Shortcode: No image found for field "'.$a['field'].'" -->';
}
// Register the shortcode
add_shortcode('acf_image', 'ccial_acf_image_shortcode');

/**
 * Prevent escaping of acf_image shortcode output
 * This ensures the HTML is not double-encoded by themes/plugins
 * Fixes issue where shortcode output is being HTML-escaped
 */
add_filter('do_shortcode_tag', function($output, $tag, $attr) {
    if ($tag === 'acf_image') {
        // Decode any HTML entities that might have been added by escaping
        // This fixes the issue where <img> tags are being escaped to &lt;img&gt;
        $decoded = html_entity_decode($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // If decoding changed something, return decoded version
        if ($decoded !== $output) {
            return $decoded;
        }
        return $output;
    }
    return $output;
}, 99, 3); // High priority to run after other filters

/**
 * Ensure shortcode output is not escaped in content
 * Process shortcodes and decode any escaped HTML from acf_image shortcode
 */
add_filter('the_content', function($content) {
    // Process shortcodes early to prevent escaping
    if (has_shortcode($content, 'acf_image')) {
        // Ensure shortcodes are processed
        $content = do_shortcode($content);
        
        // Fix double-encoded HTML from acf_image shortcode
        // Pattern: &lt;img src=&quot;...&quot; ... /&gt; should become <img src="..." ... />
        $content = preg_replace_callback(
            '/&lt;img\s+([^&]*?src=&quot;([^&]*?)&quot;[^&]*?)\s*\/&gt;/i',
            function($matches) {
                // Decode the attributes
                $attrs = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                return '<img ' . $attrs . ' />';
            },
            $content
        );
    }
    return $content;
}, 8); // Priority 8 to run before most other filters

/**
 * Additional filter to prevent escaping in widget text and other contexts
 */
add_filter('widget_text', function($content) {
    if (has_shortcode($content, 'acf_image')) {
        $content = do_shortcode($content);
    }
    return $content;
}, 8);

/**
 * Fix escaped acf_image shortcode output in final HTML
 * Uses output buffering to catch and fix escaped HTML before it's sent to browser
 */
add_action('template_redirect', function() {
    if (!is_admin()) {
        ob_start(function($buffer) {
            // Fix escaped img tags from acf_image shortcode that appear in src attributes
            // Pattern: <img ... src="&lt;img src=&quot;...&quot; ... /&gt;" ...>
            $buffer = preg_replace_callback(
                '/<img([^>]*?)\s+src=["\'](&lt;img\s+[^&]*?src=&quot;([^&]*?)&quot;[^&]*?\/&gt;)["\']([^>]*?)>/i',
                function($matches) {
                    // Extract the actual image URL
                    $url = html_entity_decode($matches[3], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $before_attrs = $matches[1];
                    $after_attrs = $matches[4];
                    // Return proper img tag with the actual URL
                    return '<img' . $before_attrs . ' src="' . esc_url($url) . '"' . $after_attrs . '>';
                },
                $buffer
            );
            
            // Also fix cases where escaped img tags appear as content (not in attributes)
            $buffer = preg_replace_callback(
                '/&lt;img\s+([^&]*?src=&quot;([^&]*?)&quot;[^&]*?)\s*\/&gt;/i',
                function($matches) {
                    $attrs = html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    return '<img ' . $attrs . ' />';
                },
                $buffer
            );
            
            return $buffer;
        });
    }
}, 1);