<?php
/**
 * Magazine Modal Functionality
 * 
 * Implements "click cover → open modal with embedded Calameo viewer" flow
 * for magazine archive presentation on WordPress site using Avada theme.
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
 * Magazine Modal Class
 */
class CCI_AL_Magazine_Modal {
    
    /**
     * Track if shortcode is used on current page
     */
    private $shortcode_used = false;
    
    /**
     * Initialize the magazine modal functionality
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    /**
     * Initialize hooks and filters
     */
    public function init() {
        // Register shortcode
        add_shortcode('modalview', array($this, 'modalview_shortcode'));
        
        // Add AJAX handlers
        add_action('wp_ajax_get_magazine_embed', array($this, 'ajax_get_magazine_embed'));
        add_action('wp_ajax_nopriv_get_magazine_embed', array($this, 'ajax_get_magazine_embed'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add data-attachment-id attribute to images
        add_filter('wp_get_attachment_image_attributes', array($this, 'add_attachment_id_attribute'), 10, 3);
        
        // Process shortcodes in Avada Image Link field if needed
        add_filter('fusion_attr_imageframe-link', array($this, 'process_link_shortcodes'), 10, 2);
        
        // Alternative: Process shortcodes in content areas
        add_filter('the_content', array($this, 'process_content_shortcodes'), 20);
        
        // Track if shortcode is used on current page
        add_action('wp', array($this, 'check_shortcode_usage'));
    }
    
    /**
     * Modalview shortcode handler
     * 
     * @param array $atts Shortcode attributes
     * @return string Special URL for frontend JS to intercept
     */
    public function modalview_shortcode($atts) {
        // Mark that shortcode is being used so scripts get enqueued
        $this->shortcode_used = true;
        
        $atts = shortcode_atts(array(
            'id' => 'auto'
        ), $atts, 'modalview');
        
        // Store attachment ID in a data attribute for JavaScript to use
        $attachment_id = 'auto';
        if ($atts['id'] !== 'auto') {
            $attachment_id = intval($atts['id']);
            if ($attachment_id <= 0) {
                $attachment_id = 'auto';
            } else {
                // Verify attachment exists
                $attachment = get_post($attachment_id);
                if (!$attachment || $attachment->post_type !== 'attachment') {
                    $attachment_id = 'auto';
                }
            }
        }
        
        // Return a special URL that JavaScript can intercept
        return 'magazine-modal://' . $attachment_id;
    }
    
    /**
     * AJAX handler to get magazine embed HTML
     */
    public function ajax_get_magazine_embed() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'magazine_modal_nonce')) {
            wp_die('Security check failed');
        }
        
        $attachment_id = intval($_POST['attachment_id']);
        
        if ($attachment_id <= 0) {
            wp_send_json_error('Invalid attachment ID');
        }
        
        // Get attachment
        $attachment = get_post($attachment_id);
        if (!$attachment || $attachment->post_type !== 'attachment') {
            wp_send_json_error('Attachment not found');
        }
        
        // Check if attachment is publicly accessible
        // For magazine viewing, we allow access to attachments that are not private
        if ($attachment->post_status !== 'inherit' && $attachment->post_status !== 'publish') {
            wp_send_json_error('Attachment not publicly accessible');
        }
        
        // Get embed HTML from description
        $embed_html = $attachment->post_content;
        
        if (empty($embed_html)) {
            wp_send_json_error('No embed HTML found for this attachment');
        }
        
        // Sanitize the embed HTML
        $allowed_html = array(
            'iframe' => array(
                'src' => array(),
                'width' => array(),
                'height' => array(),
                'frameborder' => array(),
                'allow' => array(),
                'allowfullscreen' => array(),
                'allowtransparency' => array(),
                'scrolling' => array(),
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
            'a' => array(
                'href' => array(),
                'target' => array(),
                'class' => array(),
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
        
        // Create translatable title with filename (without extension)
        $filename = basename(get_attached_file($attachment_id));
        $filename_without_ext = pathinfo($filename, PATHINFO_FILENAME);
        $title_prefix = __('Hoguera Magazine vol: ', 'ccial');
        $formatted_title = $title_prefix . $filename_without_ext;
        
        wp_send_json_success(array(
            'embed_html' => $sanitized_html,
            'attachment_title' => $attachment->post_title,
            'formatted_title' => $formatted_title
        ));
    }
    
    /**
     * Check if shortcode is used on current page
     */
    public function check_shortcode_usage() {
        global $post;
        
        if (!$post) {
            return;
        }
        
        // Check if shortcode is present in post content
        if (has_shortcode($post->post_content, 'modalview')) {
            $this->shortcode_used = true;
            return;
        }
        
        // Check if shortcode is present in Avada elements (fusion_builder)
        if (strpos($post->post_content, '[fusion_') !== false) {
            // Check for shortcode in Avada elements
            if (strpos($post->post_content, 'modalview') !== false) {
                $this->shortcode_used = true;
                return;
            }
        }
        
        // Check in page meta fields (for Avada page options)
        $page_options = get_post_meta($post->ID, 'pyre_page_options', true);
        if ($page_options && is_array($page_options)) {
            foreach ($page_options as $key => $value) {
                if (is_string($value) && strpos($value, 'modalview') !== false) {
                    $this->shortcode_used = true;
                    return;
                }
            }
        }
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only enqueue on frontend
        if (is_admin()) {
            return;
        }
        
        // Check if shortcode is used on current page (fallback check)
        if (!$this->shortcode_used) {
            global $post;
            if ($post && (
                has_shortcode($post->post_content, 'modalview') ||
                strpos($post->post_content, 'modalview') !== false
            )) {
                $this->shortcode_used = true;
            }
        }
        
        // Check if magazine modal exists on the page (reliable fallback)
        if (!$this->shortcode_used) {
            // Check if there's a magazine modal element in the page content
            global $post;
            if ($post && strpos($post->post_content, 'fusion_modal') !== false && 
                strpos($post->post_content, 'magazine') !== false) {
                $this->shortcode_used = true;
            }
        }
        
        // Only enqueue if shortcode is used or modal exists
        if (!$this->shortcode_used) {
            return;
        }
        
        
        // Enqueue the magazine modal script
        wp_enqueue_script(
            'ccial-magazine-modal',
            get_stylesheet_directory_uri() . '/js/magazine-modal.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Enqueue the magazine modal styles
        wp_enqueue_style(
            'ccial-magazine-modal',
            get_stylesheet_directory_uri() . '/css/magazine-modal.css',
            array(),
            '1.0.0'
        );
        
        // Localize script with AJAX data
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
    
    /**
     * Add data-attachment-id attribute to images
     * 
     * @param array $attr Image attributes
     * @param WP_Post $attachment Attachment post object
     * @param string $size Image size
     * @return array Modified attributes
     */
    public function add_attachment_id_attribute($attr, $attachment, $size) {
        if ($attachment && is_object($attachment)) {
            $attr['data-attachment-id'] = $attachment->ID;
        }
        return $attr;
    }
    
    /**
     * Process shortcodes in Avada Image Link field
     * 
     * @param string $attr Link attribute value
     * @param array $args Element arguments
     * @return string Processed link
     */
    public function process_link_shortcodes($attr, $args) {
        // Check if the link contains our shortcode
        if (strpos($attr, '[modalview') !== false) {
            $attr = do_shortcode($attr);
        }
        return $attr;
    }
    
    /**
     * Process shortcodes in content areas
     * 
     * @param string $content Post content
     * @return string Processed content
     */
    public function process_content_shortcodes($content) {
        // Only process if we're not in admin and content contains our shortcode
        if (!is_admin() && strpos($content, '[modalview') !== false) {
            $content = do_shortcode($content);
        }
        return $content;
    }
}

// Initialize the magazine modal functionality
new CCI_AL_Magazine_Modal();
