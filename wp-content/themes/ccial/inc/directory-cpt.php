<?php
/**
 * Directory Custom Post Type
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
 * Register Directory Custom Post Type
 */
function ccial_register_directory_cpt() {
    
    $labels = array(
        'name'                  => _x('Directory', 'Post type general name', 'ccial'),
        'singular_name'         => _x('Directory Entry', 'Post type singular name', 'ccial'),
        'menu_name'             => _x('Directory', 'Admin Menu text', 'ccial'),
        'name_admin_bar'        => _x('Directory Entry', 'Add New on Toolbar', 'ccial'),
        'add_new'               => _x('Add New', 'Directory Entry', 'ccial'),
        'add_new_item'          => _x('Add New Directory Entry', 'ccial'),
        'new_item'              => _x('New Directory Entry', 'ccial'),
        'edit_item'             => _x('Edit Directory Entry', 'ccial'),
        'view_item'             => _x('View Directory Entry', 'ccial'),
        'all_items'             => _x('All Directory Entries', 'ccial'),
        'search_items'          => _x('Search Directory Entries', 'ccial'),
        'parent_item_colon'     => _x('Parent Directory Entries:', 'ccial'),
        'not_found'             => _x('No directory entries found.', 'ccial'),
        'not_found_in_trash'    => _x('No directory entries found in Trash.', 'ccial'),
        'featured_image'        => _x('Featured Image', 'ccial'),
        'set_featured_image'    => _x('Set featured image', 'ccial'),
        'remove_featured_image' => _x('Remove featured image', 'ccial'),
        'use_featured_image'    => _x('Use as featured image', 'ccial'),
        'archives'              => _x('Directory Archives', 'ccial'),
        'insert_into_item'      => _x('Insert into directory entry', 'ccial'),
        'uploaded_to_this_item' => _x('Uploaded to this directory entry', 'ccial'),
        'filter_items_list'     => _x('Filter directory entries list', 'ccial'),
        'items_list_navigation' => _x('Directory entries list navigation', 'ccial'),
        'items_list'            => _x('Directory entries list', 'ccial'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'directory'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-location-alt',
        'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', 'fusion_builder'),
        'show_in_rest'       => false, // Disable Gutenberg editor
        'rest_base'          => 'directory',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('ccial_directory', $args);
}
add_action('init', 'ccial_register_directory_cpt');

/**
 * Register Directory Type Taxonomy (Non-hierarchical)
 */
function ccial_register_directory_type_taxonomy() {
    
    $labels = array(
        'name'                       => _x('Directory Types', 'Taxonomy General Name', 'ccial'),
        'singular_name'              => _x('Directory Type', 'Taxonomy Singular Name', 'ccial'),
        'menu_name'                  => _x('Directory Types', 'ccial'),
        'all_items'                  => _x('All Directory Types', 'ccial'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => _x('New Directory Type Name', 'ccial'),
        'add_new_item'               => _x('Add New Directory Type', 'ccial'),
        'edit_item'                  => _x('Edit Directory Type', 'ccial'),
        'update_item'                => _x('Update Directory Type', 'ccial'),
        'view_item'                  => _x('View Directory Type', 'ccial'),
        'separate_items_with_commas' => _x('Separate directory types with commas', 'ccial'),
        'add_or_remove_items'        => _x('Add or remove directory types', 'ccial'),
        'choose_from_most_used'      => _x('Choose from the most used', 'ccial'),
        'popular_items'              => _x('Popular Directory Types', 'ccial'),
        'search_items'               => _x('Search Directory Types', 'ccial'),
        'not_found'                  => _x('Not Found', 'ccial'),
        'no_terms'                   => _x('No directory types', 'ccial'),
        'items_list'                 => _x('Directory types list', 'ccial'),
        'items_list_navigation'      => _x('Directory types list navigation', 'ccial'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rest_base'                  => 'directory_type',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('directory_type', array('ccial_directory'), $args);
}
add_action('init', 'ccial_register_directory_type_taxonomy');

/**
 * Register Country/Region Taxonomy (Hierarchical)
 */
function ccial_register_country_region_taxonomy() {
    
    $labels = array(
        'name'                       => _x('Countries/Regions', 'Taxonomy General Name', 'ccial'),
        'singular_name'              => _x('Country/Region', 'Taxonomy Singular Name', 'ccial'),
        'menu_name'                  => _x('Countries/Regions', 'ccial'),
        'all_items'                  => _x('All Countries/Regions', 'ccial'),
        'parent_item'                => _x('Parent Country/Region', 'ccial'),
        'parent_item_colon'          => _x('Parent Country/Region:', 'ccial'),
        'new_item_name'              => _x('New Country/Region Name', 'ccial'),
        'add_new_item'               => _x('Add New Country/Region', 'ccial'),
        'edit_item'                  => _x('Edit Country/Region', 'ccial'),
        'update_item'                => _x('Update Country/Region', 'ccial'),
        'view_item'                  => _x('View Country/Region', 'ccial'),
        'separate_items_with_commas' => _x('Separate countries/regions with commas', 'ccial'),
        'add_or_remove_items'        => _x('Add or remove countries/regions', 'ccial'),
        'choose_from_most_used'      => _x('Choose from the most used', 'ccial'),
        'popular_items'              => _x('Popular Countries/Regions', 'ccial'),
        'search_items'               => _x('Search Countries/Regions', 'ccial'),
        'not_found'                  => _x('Not Found', 'ccial'),
        'no_terms'                   => _x('No countries/regions', 'ccial'),
        'items_list'                 => _x('Countries/regions list', 'ccial'),
        'items_list_navigation'      => _x('Countries/regions list navigation', 'ccial'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rest_base'                  => 'country_region',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('country_region', array('ccial_directory'), $args);
}
add_action('init', 'ccial_register_country_region_taxonomy');

/**
 * Setup initial Directory data
 */
function ccial_setup_directory_initial_data() {
    
    // Check if setup has already been done
    if (get_option('ccial_directory_setup_complete')) {
        return;
    }
    
    // Setup Directory Types
    $directory_types = array(
        'national_association' => __('National Association', 'ccial'),
        'camp_site' => __('Camp Site', 'ccial'),
    );
    
    foreach ($directory_types as $slug => $name) {
        if (!term_exists($slug, 'directory_type')) {
            wp_insert_term($name, 'directory_type', array(
                'slug' => $slug,
                'description' => sprintf(__('Directory entry type: %s', 'ccial'), $name)
            ));
        }
    }
    
    // Setup Countries/Regions
    $countries = array(
        'argentina' => __('Argentina', 'ccial'),
        'bolivia' => __('Bolivia', 'ccial'),
        'brazil' => __('Brazil', 'ccial'),
        'chile' => __('Chile', 'ccial'),
        'colombia' => __('Colombia', 'ccial'),
        'costa_rica' => __('Costa Rica', 'ccial'),
        'ecuador' => __('Ecuador', 'ccial'),
        'el_salvador' => __('El Salvador', 'ccial'),
        'guatemala' => __('Guatemala', 'ccial'),
        'honduras' => __('Honduras', 'ccial'),
        'mexico' => __('Mexico', 'ccial'),
        'nicaragua' => __('Nicaragua', 'ccial'),
        'panama' => __('Panama', 'ccial'),
        'paraguay' => __('Paraguay', 'ccial'),
        'peru' => __('Peru', 'ccial'),
        'uruguay' => __('Uruguay', 'ccial'),
        'venezuela' => __('Venezuela', 'ccial'),
    );
    
    foreach ($countries as $slug => $name) {
        if (!term_exists($slug, 'country_region')) {
            wp_insert_term($name, 'country_region', array(
                'slug' => $slug,
                'description' => sprintf(__('Country/Region: %s', 'ccial'), $name)
            ));
        }
    }
    
    // Mark setup as complete
    update_option('ccial_directory_setup_complete', true);
}

// Run setup on theme activation
add_action('after_switch_theme', 'ccial_setup_directory_initial_data');

/**
 * Flush rewrite rules on theme activation
 */
function ccial_directory_flush_rewrite_rules() {
    ccial_register_directory_cpt();
    ccial_register_directory_type_taxonomy();
    ccial_register_country_region_taxonomy();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ccial_directory_flush_rewrite_rules');

/**
 * Add custom columns to Directory admin list
 */
function ccial_add_directory_admin_columns($columns) {
    // Remove default columns we don't need
    unset($columns['date']);
    
    // Add custom columns
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = __('Entry Name', 'ccial');
    $new_columns['directory_type'] = __('Type', 'ccial');
    $new_columns['country_region'] = __('Country/Region', 'ccial');
    $new_columns['date'] = __('Date', 'ccial');
    
    return $new_columns;
}
add_filter('manage_ccial_directory_posts_columns', 'ccial_add_directory_admin_columns');

/**
 * Populate custom columns
 */
function ccial_populate_directory_admin_columns($column, $post_id) {
    switch ($column) {
        case 'directory_type':
            $terms = get_the_terms($post_id, 'directory_type');
            if ($terms && !is_wp_error($terms)) {
                $term_names = array();
                foreach ($terms as $term) {
                    $term_names[] = $term->name;
                }
                echo implode(', ', $term_names);
            } else {
                echo '—';
            }
            break;
            
        case 'country_region':
            $terms = get_the_terms($post_id, 'country_region');
            if ($terms && !is_wp_error($terms)) {
                $term_names = array();
                foreach ($terms as $term) {
                    $term_names[] = $term->name;
                }
                echo implode(', ', $term_names);
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_ccial_directory_posts_custom_column', 'ccial_populate_directory_admin_columns', 10, 2);

/**
 * Make columns sortable
 */
function ccial_make_directory_columns_sortable($columns) {
    $columns['directory_type'] = 'directory_type';
    $columns['country_region'] = 'country_region';
    
    return $columns;
}
add_filter('manage_edit-ccial_directory_sortable_columns', 'ccial_make_directory_columns_sortable');

/**
 * Handle sorting for custom columns
 */
function ccial_handle_directory_column_sorting($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    switch ($orderby) {
        case 'directory_type':
            $query->set('meta_key', 'directory_type');
            $query->set('orderby', 'meta_value');
            break;
        case 'country_region':
            $query->set('meta_key', 'country_region');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'ccial_handle_directory_column_sorting');

/**
 * Register REST API for Directory CPT (even though show_in_rest is false)
 * This allows API access while keeping the classic editor
 */
function ccial_register_directory_rest_api() {
    register_rest_route('wp/v2', '/directory', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ccial_get_directory_posts',
        'permission_callback' => '__return_true',
    ));
    
    register_rest_route('wp/v2', '/directory/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ccial_get_directory_post',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));
}
add_action('rest_api_init', 'ccial_register_directory_rest_api');

/**
 * Get Directory posts via REST API
 */
function ccial_get_directory_posts($request) {
    $args = array(
        'post_type' => 'ccial_directory',
        'post_status' => 'publish',
        'posts_per_page' => $request->get_param('per_page') ?: 10,
        'paged' => $request->get_param('page') ?: 1,
    );
    
    // Add taxonomy filters
    if ($request->get_param('directory_type')) {
        $args['tax_query'][] = array(
            'taxonomy' => 'directory_type',
            'field' => 'slug',
            'terms' => $request->get_param('directory_type'),
        );
    }
    
    if ($request->get_param('country_region')) {
        $args['tax_query'][] = array(
            'taxonomy' => 'country_region',
            'field' => 'slug',
            'terms' => $request->get_param('country_region'),
        );
    }
    
    $query = new WP_Query($args);
    $posts = array();
    
    foreach ($query->posts as $post) {
        $posts[] = ccial_format_directory_post_for_api($post);
    }
    
    return new WP_REST_Response($posts, 200, array(
        'X-WP-Total' => $query->found_posts,
        'X-WP-TotalPages' => $query->max_num_pages,
    ));
}

/**
 * Get single Directory post via REST API
 */
function ccial_get_directory_post($request) {
    $post_id = $request->get_param('id');
    $post = get_post($post_id);
    
    if (!$post || $post->post_type !== 'ccial_directory') {
        return new WP_Error('rest_post_invalid_id', __('Invalid post ID.', 'ccial'), array('status' => 404));
    }
    
    if ($post->post_status !== 'publish') {
        return new WP_Error('rest_post_invalid_status', __('Post is not published.', 'ccial'), array('status' => 404));
    }
    
    return new WP_REST_Response(ccial_format_directory_post_for_api($post), 200);
}

/**
 * Format Directory post for API response
 */
function ccial_format_directory_post_for_api($post) {
    $directory_types = get_the_terms($post->ID, 'directory_type');
    $country_regions = get_the_terms($post->ID, 'country_region');
    
    return array(
        'id' => $post->ID,
        'title' => array(
            'rendered' => get_the_title($post->ID),
        ),
        'content' => array(
            'rendered' => apply_filters('the_content', $post->post_content),
        ),
        'excerpt' => array(
            'rendered' => get_the_excerpt($post->ID),
        ),
        'featured_media' => get_post_thumbnail_id($post->ID),
        'directory_type' => $directory_types ? array_map(function($term) {
            return array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }, $directory_types) : array(),
        'country_region' => $country_regions ? array_map(function($term) {
            return array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }, $country_regions) : array(),
        'date' => $post->post_date,
        'date_gmt' => $post->post_date_gmt,
        'modified' => $post->post_modified,
        'modified_gmt' => $post->post_modified_gmt,
        'slug' => $post->post_name,
        'status' => $post->post_status,
        'link' => get_permalink($post->ID),
    );
}

/**
 * Force Classic Editor for Directory CPT
 */
function ccial_force_classic_editor_for_directory($use_block_editor, $post) {
    if ($post && $post->post_type === 'ccial_directory') {
        return false;
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post', 'ccial_force_classic_editor_for_directory', 10, 2);

/**
 * Add Avada Live Builder support for Directory CPT
 */
function ccial_add_avada_support_for_directory() {
    // Add Avada Live Builder support
    add_post_type_support('ccial_directory', 'fusion_builder');
    
    // Add Avada page options support
    add_post_type_support('ccial_directory', 'fusion_page_options');
}
add_action('init', 'ccial_add_avada_support_for_directory');
