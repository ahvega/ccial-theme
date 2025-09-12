<?php
/**
 * Example: Magazine Modal functionality in functions.php
 * 
 * This shows how the magazine modal code could be organized
 * directly in the child theme's functions.php file
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Magazine Modal Functionality - All in functions.php
 */

// Register shortcode
add_shortcode('modalview', 'ccial_modalview_shortcode');
function ccial_modalview_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 'auto'
    ), $atts, 'modalview');
    
    $attachment_id = 'auto';
    if ($atts['id'] !== 'auto') {
        $attachment_id = intval($atts['id']);
        if ($attachment_id <= 0) {
            $attachment_id = 'auto';
        } else {
            $attachment = get_post($attachment_id);
            if (!$attachment || $attachment->post_type !== 'attachment') {
                $attachment_id = 'auto';
            }
        }
    }
    
    return 'magazine-modal://' . $attachment_id;
}

// AJAX handlers
add_action('wp_ajax_get_magazine_embed', 'ccial_ajax_get_magazine_embed');
add_action('wp_ajax_nopriv_get_magazine_embed', 'ccial_ajax_get_magazine_embed');
function ccial_ajax_get_magazine_embed() {
    if (!wp_verify_nonce($_POST['nonce'], 'magazine_modal_nonce')) {
        wp_die('Security check failed');
    }
    
    $attachment_id = intval($_POST['attachment_id']);
    if ($attachment_id <= 0) {
        wp_send_json_error('Invalid attachment ID');
    }
    
    $attachment = get_post($attachment_id);
    if (!$attachment || $attachment->post_type !== 'attachment') {
        wp_send_json_error('Attachment not found');
    }
    
    if (!current_user_can('read_post', $attachment_id)) {
        wp_send_json_error('Access denied');
    }
    
    $embed_html = $attachment->post_content;
    if (empty($embed_html)) {
        wp_send_json_error('No embed HTML found for this attachment');
    }
    
    $allowed_html = array(
        'iframe' => array(
            'src' => array(),
            'width' => array(),
            'height' => array(),
            'frameborder' => array(),
            'allow' => array(),
            'allowfullscreen' => array(),
            'loading' => array(),
            'style' => array(),
            'class' => array(),
            'id' => array(),
            'title' => array(),
            'sandbox' => array(),
            'referrerpolicy' => array(),
        ),
        'div' => array(
            'class' => array(),
            'id' => array(),
            'style' => array(),
        ),
        'script' => array(
            'src' => array(),
            'type' => array(),
            'async' => array(),
            'defer' => array(),
        ),
    );
    
    $sanitized_html = wp_kses($embed_html, $allowed_html);
    
    wp_send_json_success(array(
        'embed_html' => $sanitized_html,
        'attachment_title' => $attachment->post_title
    ));
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'ccial_magazine_modal_scripts');
function ccial_magazine_modal_scripts() {
    if (is_admin()) {
        return;
    }
    
    wp_enqueue_script(
        'ccial-magazine-modal',
        get_stylesheet_directory_uri() . '/js/magazine-modal.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    wp_enqueue_style(
        'ccial-magazine-modal',
        get_stylesheet_directory_uri() . '/css/magazine-modal.css',
        array(),
        '1.0.0'
    );
    
    wp_localize_script('ccial-magazine-modal', 'ccialMagazineModal', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('magazine_modal_nonce'),
        'modalSelector' => '.fusion-modal.magazine',
        'modalBodySelector' => '.fusion-modal.magazine .modal-body',
        'modalTargetSelector' => '#magazine-modal-target',
        'allowedIframeAttrs' => array(
            'src', 'width', 'height', 'frameborder', 'allow', 
            'allowfullscreen', 'loading', 'style', 'class', 'id', 
            'title', 'sandbox', 'referrerpolicy'
        )
    ));
}

// Add data-attachment-id attribute to images
add_filter('wp_get_attachment_image_attributes', 'ccial_add_attachment_id_attribute', 10, 3);
function ccial_add_attachment_id_attribute($attr, $attachment, $size) {
    if ($attachment && is_object($attachment)) {
        $attr['data-attachment-id'] = $attachment->ID;
    }
    return $attr;
}

// Process shortcodes in Avada Image Link field
add_filter('fusion_attr_imageframe-link', 'ccial_process_link_shortcodes', 10, 2);
function ccial_process_link_shortcodes($attr, $args) {
    if (strpos($attr, '[modalview') !== false) {
        $attr = do_shortcode($attr);
    }
    return $attr;
}

// Process shortcodes in content areas
add_filter('the_content', 'ccial_process_content_shortcodes', 20);
function ccial_process_content_shortcodes($content) {
    if (!is_admin() && strpos($content, '[modalview') !== false) {
        $content = do_shortcode($content);
    }
    return $content;
}
