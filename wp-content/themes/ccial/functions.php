<?php
/**
 * CCIAL Child Theme Functions
 * 
 * @package CCIAL
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
 * Add custom functionality for CCIAL theme
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
    // Add custom body class for CCIAL theme
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
    echo '<span id="footer-thankyou">' . __('Developed with ❤️ for CCIAL', 'ccial') . '</span>';
}
add_filter('admin_footer_text', 'ccial_admin_footer');

/**
 * Add custom widgets areas if needed
 */
function ccial_widgets_init() {
    // Example: Add a custom widget area
    /*
    register_sidebar(array(
        'name'          => __('CCIAL Sidebar', 'ccial'),
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

// Include additional functionality files
// require_once get_stylesheet_directory() . '/inc/custom-post-types.php';
// require_once get_stylesheet_directory() . '/inc/custom-taxonomies.php';
require_once get_stylesheet_directory() . '/inc/magazine-modal.php';
