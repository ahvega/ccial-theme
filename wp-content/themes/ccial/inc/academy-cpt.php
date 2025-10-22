<?php
/**
 * Academy Custom Post Type - Redefined from Avada Portfolio
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
 * Redefine Avada Portfolio CPT as Academy
 */
function ccial_redefine_portfolio_as_academy() {
    
    // Get the existing portfolio post type object
    $portfolio_post_type = get_post_type_object('avada_portfolio');
    
    if (!$portfolio_post_type) {
        return; // Portfolio CPT doesn't exist
    }
    
    // Redefine labels for Academy
    $labels = array(
        'name'                  => _x('Academy', 'Post type general name', 'ccial'),
        'singular_name'         => _x('Academic Resource', 'Post type singular name', 'ccial'),
        'menu_name'             => _x('Academy', 'Admin Menu text', 'ccial'),
        'name_admin_bar'        => _x('Academic Resource', 'Add New on Toolbar', 'ccial'),
        'add_new'               => _x('Add New', 'Academic Resource', 'ccial'),
        'add_new_item'          => _x('Add New Academic Resource', 'ccial'),
        'new_item'              => _x('New Academic Resource', 'ccial'),
        'edit_item'             => _x('Edit Academic Resource', 'ccial'),
        'view_item'             => _x('View Academic Resource', 'ccial'),
        'all_items'             => _x('All Academic Resources', 'ccial'),
        'search_items'          => _x('Search Academic Resources', 'ccial'),
        'parent_item_colon'     => _x('Parent Academic Resources:', 'ccial'),
        'not_found'             => _x('No academic resources found.', 'ccial'),
        'not_found_in_trash'    => _x('No academic resources found in Trash.', 'ccial'),
        'featured_image'        => _x('Featured Image', 'ccial'),
        'set_featured_image'    => _x('Set featured image', 'ccial'),
        'remove_featured_image' => _x('Remove featured image', 'ccial'),
        'use_featured_image'    => _x('Use as featured image', 'ccial'),
        'archives'              => _x('Academy Archives', 'ccial'),
        'insert_into_item'      => _x('Insert into academic resource', 'ccial'),
        'uploaded_to_this_item' => _x('Uploaded to this academic resource', 'ccial'),
        'filter_items_list'     => _x('Filter academic resources list', 'ccial'),
        'items_list_navigation' => _x('Academic resources list navigation', 'ccial'),
        'items_list'            => _x('Academic resources list', 'ccial'),
    );
    
    // Update the post type object with new labels
    $portfolio_post_type->labels = (object) $labels;
    
    // Keep original rewrite rules - we'll handle URLs manually
    // $portfolio_post_type->rewrite = array(
    //     'slug' => 'academy',
    //     'with_front' => false,
    //     'feeds' => true,
    //     'pages' => true
    // );
    
    // Update the menu icon
    $portfolio_post_type->menu_icon = 'dashicons-book-alt';
    
    // Re-register the post type with updated settings
    register_post_type('avada_portfolio', (array) $portfolio_post_type);
}
add_action('init', 'ccial_redefine_portfolio_as_academy', 20);

/**
 * Redefine Portfolio Category as Academic Category (Hierarchical)
 */
function ccial_redefine_portfolio_category_as_academic_category() {
    
    // Get existing taxonomy
    $portfolio_category = get_taxonomy('portfolio_category');
    
    if (!$portfolio_category) {
        return; // Taxonomy doesn't exist
    }
    
    // Redefine labels for Academic Category
    $labels = array(
        'name'                       => _x('Academic Categories', 'Taxonomy General Name', 'ccial'),
        'singular_name'              => _x('Academic Category', 'Taxonomy Singular Name', 'ccial'),
        'menu_name'                  => _x('Academic Categories', 'ccial'),
        'all_items'                  => _x('All Academic Categories', 'ccial'),
        'parent_item'                => _x('Parent Academic Category', 'ccial'),
        'parent_item_colon'          => _x('Parent Academic Category:', 'ccial'),
        'new_item_name'              => _x('New Academic Category Name', 'ccial'),
        'add_new_item'               => _x('Add New Academic Category', 'ccial'),
        'edit_item'                  => _x('Edit Academic Category', 'ccial'),
        'update_item'                => _x('Update Academic Category', 'ccial'),
        'view_item'                  => _x('View Academic Category', 'ccial'),
        'separate_items_with_commas' => _x('Separate academic categories with commas', 'ccial'),
        'add_or_remove_items'        => _x('Add or remove academic categories', 'ccial'),
        'choose_from_most_used'      => _x('Choose from the most used', 'ccial'),
        'popular_items'              => _x('Popular Academic Categories', 'ccial'),
        'search_items'               => _x('Search Academic Categories', 'ccial'),
        'not_found'                  => _x('Not Found', 'ccial'),
        'no_terms'                   => _x('No academic categories', 'ccial'),
        'items_list'                 => _x('Academic categories list', 'ccial'),
        'items_list_navigation'      => _x('Academic categories list navigation', 'ccial'),
    );
    
    // Update taxonomy object
    $portfolio_category->labels = (object) $labels;
    $portfolio_category->rewrite = array('slug' => 'academic-category');
    
    // Re-register taxonomy
    register_taxonomy('portfolio_category', array('avada_portfolio'), (array) $portfolio_category);
}
add_action('init', 'ccial_redefine_portfolio_category_as_academic_category', 20);

/**
 * Redefine Portfolio Skills as Competencies (Non-hierarchical)
 */
function ccial_redefine_portfolio_skills_as_competencies() {
    
    // Get existing taxonomy
    $portfolio_skills = get_taxonomy('portfolio_skills');
    
    if (!$portfolio_skills) {
        return; // Taxonomy doesn't exist
    }
    
    // Redefine labels for Competencies
    $labels = array(
        'name'                       => _x('Competencies', 'Taxonomy General Name', 'ccial'),
        'singular_name'              => _x('Competency', 'Taxonomy Singular Name', 'ccial'),
        'menu_name'                  => _x('Competencies', 'ccial'),
        'all_items'                  => _x('All Competencies', 'ccial'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => _x('New Competency Name', 'ccial'),
        'add_new_item'               => _x('Add New Competency', 'ccial'),
        'edit_item'                  => _x('Edit Competency', 'ccial'),
        'update_item'                => _x('Update Competency', 'ccial'),
        'view_item'                  => _x('View Competency', 'ccial'),
        'separate_items_with_commas' => _x('Separate competencies with commas', 'ccial'),
        'add_or_remove_items'        => _x('Add or remove competencies', 'ccial'),
        'choose_from_most_used'      => _x('Choose from the most used', 'ccial'),
        'popular_items'              => _x('Popular Competencies', 'ccial'),
        'search_items'               => _x('Search Competencies', 'ccial'),
        'not_found'                  => _x('Not Found', 'ccial'),
        'no_terms'                   => _x('No competencies', 'ccial'),
        'items_list'                 => _x('Competencies list', 'ccial'),
        'items_list_navigation'      => _x('Competencies list navigation', 'ccial'),
    );
    
    // Update taxonomy object
    $portfolio_skills->labels = (object) $labels;
    $portfolio_skills->rewrite = array('slug' => 'competency');
    
    // Re-register taxonomy
    register_taxonomy('portfolio_skills', array('avada_portfolio'), (array) $portfolio_skills);
}
add_action('init', 'ccial_redefine_portfolio_skills_as_competencies', 20);

/**
 * Redefine Portfolio Tags as Topics/Tags (Non-hierarchical)
 */
function ccial_redefine_portfolio_tags_as_topics() {
    
    // Get existing taxonomy
    $portfolio_tags = get_taxonomy('portfolio_tags');
    
    if (!$portfolio_tags) {
        return; // Taxonomy doesn't exist
    }
    
    // Redefine labels for Topics/Tags
    $labels = array(
        'name'                       => _x('Topics', 'Taxonomy General Name', 'ccial'),
        'singular_name'              => _x('Topic', 'Taxonomy Singular Name', 'ccial'),
        'menu_name'                  => _x('Topics', 'ccial'),
        'all_items'                  => _x('All Topics', 'ccial'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => _x('New Topic Name', 'ccial'),
        'add_new_item'               => _x('Add New Topic', 'ccial'),
        'edit_item'                  => _x('Edit Topic', 'ccial'),
        'update_item'                => _x('Update Topic', 'ccial'),
        'view_item'                  => _x('View Topic', 'ccial'),
        'separate_items_with_commas' => _x('Separate topics with commas', 'ccial'),
        'add_or_remove_items'        => _x('Add or remove topics', 'ccial'),
        'choose_from_most_used'      => _x('Choose from the most used', 'ccial'),
        'popular_items'              => _x('Popular Topics', 'ccial'),
        'search_items'               => _x('Search Topics', 'ccial'),
        'not_found'                  => _x('Not Found', 'ccial'),
        'no_terms'                   => _x('No topics', 'ccial'),
        'items_list'                 => _x('Topics list', 'ccial'),
        'items_list_navigation'      => _x('Topics list navigation', 'ccial'),
    );
    
    // Update taxonomy object
    $portfolio_tags->labels = (object) $labels;
    $portfolio_tags->rewrite = array('slug' => 'topic');
    
    // Re-register taxonomy
    register_taxonomy('portfolio_tags', array('avada_portfolio'), (array) $portfolio_tags);
}
add_action('init', 'ccial_redefine_portfolio_tags_as_topics', 20);

/**
 * Setup initial Academy data
 */
function ccial_setup_academy_initial_data() {
    
    // Check if setup has already been done
    if (get_option('ccial_academy_setup_complete')) {
        return;
    }
    
    // Setup Academic Categories (Hierarchical)
    $academic_categories = array(
        // Main Categories
        'diplomados' => array(
            'name' => __('Diplomas', 'ccial'),
            'description' => __('Academic degree programs with sequential levels', 'ccial'),
            'children' => array(
                'consejeria_biblica' => __('Biblical Counseling in Camps', 'ccial'),
                'diseno_programas' => __('Camp Program Design', 'ccial'),
                'diseno_curricular' => __('Biblical Curriculum Design in Camps', 'ccial'),
                'educacion_experimental' => __('Experimental Education and Recreation with Purpose', 'ccial'),
            )
        ),
        'talleres' => array(
            'name' => __('Workshops', 'ccial'),
            'description' => __('Elective workshops that can be taken individually', 'ccial'),
            'children' => array(
                'talleres_consejeria' => __('Workshops - Counseling', 'ccial'),
                'talleres_diseno_programas' => __('Workshops - Program Design', 'ccial'),
                'talleres_diseno_curricular' => __('Workshops - Curriculum Design', 'ccial'),
                'talleres_educacion_experimental' => __('Workshops - Experimental Education', 'ccial'),
            )
        ),
        'ifis' => array(
            'name' => __('Instructor Training Institutes', 'ccial'),
            'description' => __('Intensive 17-day instructor certification events', 'ccial'),
            'children' => array(
                'ifi_1' => __('IFI-1', 'ccial'),
                'ifi_2' => __('IFI-2', 'ccial'),
                'ifi_3' => __('IFI-3', 'ccial'),
            )
        ),
        'libros_texto' => array(
            'name' => __('Textbooks', 'ccial'),
            'description' => __('Core textbooks used as "in-depth courses"', 'ccial'),
            'children' => array(
                'construyendo_relaciones' => __('Building Relationships', 'ccial'),
                'facilitando_crecimiento' => __('Facilitating Growth', 'ccial'),
                'programando_campamentos' => __('Programming Camps', 'ccial'),
                'creando_encuentros_biblicos' => __('Creating Biblical Encounters in Community', 'ccial'),
                'capacitando_facilitadores' => __('Training Puzzle Facilitators', 'ccial'),
            )
        ),
    );
    
    foreach ($academic_categories as $slug => $category_data) {
        // Create parent category
        $parent_term = wp_insert_term($category_data['name'], 'portfolio_category', array(
            'slug' => $slug,
            'description' => $category_data['description']
        ));
        
        if (!is_wp_error($parent_term) && isset($category_data['children'])) {
            // Create child categories
            foreach ($category_data['children'] as $child_slug => $child_name) {
                wp_insert_term($child_name, 'portfolio_category', array(
                    'slug' => $child_slug,
                    'parent' => $parent_term['term_id']
                ));
            }
        }
    }
    
    // Setup Competencies (Non-hierarchical)
    $competencies = array(
        'confidente' => __('Confidant', 'ccial'),
        'director_programa' => __('Program Director', 'ccial'),
        'maestro_biblia' => __('Bible Teacher', 'ccial'),
        'facilitador_experiencias' => __('Experience Facilitator', 'ccial'),
        'consejeria' => __('Counseling', 'ccial'),
        'diseno_curricular' => __('Curriculum Design', 'ccial'),
        'recreacion_proposito' => __('Recreation with Purpose', 'ccial'),
        'evangelismo_personal' => __('Personal Evangelism', 'ccial'),
        'liderazgo' => __('Leadership', 'ccial'),
        'desarrollo_equipo' => __('Team Development', 'ccial'),
    );
    
    foreach ($competencies as $slug => $name) {
        if (!term_exists($slug, 'portfolio_skills')) {
            wp_insert_term($name, 'portfolio_skills', array(
                'slug' => $slug,
                'description' => sprintf(__('Competency: %s', 'ccial'), $name)
            ));
        }
    }
    
    // Setup Topics/Tags (Non-hierarchical)
    $topics = array(
        'campamento' => __('Camp', 'ccial'),
        'team_building' => __('Team Building', 'ccial'),
        'evangelismo' => __('Evangelism', 'ccial'),
        'acompanamiento_espiritual' => __('Spiritual Accompaniment', 'ccial'),
        'recreacion_aire_libre' => __('Outdoor Recreation', 'ccial'),
        'formacion_lideres' => __('Leader Training', 'ccial'),
        'desarrollo_comunidad' => __('Community Development', 'ccial'),
        'ministerio_juvenil' => __('Youth Ministry', 'ccial'),
        'educacion_cristiana' => __('Christian Education', 'ccial'),
        'discipulado' => __('Discipleship', 'ccial'),
    );
    
    foreach ($topics as $slug => $name) {
        if (!term_exists($slug, 'portfolio_tags')) {
            wp_insert_term($name, 'portfolio_tags', array(
                'slug' => $slug,
                'description' => sprintf(__('Topic: %s', 'ccial'), $name)
            ));
        }
    }
    
    // Mark setup as complete
    update_option('ccial_academy_setup_complete', true);
}

// Run setup on theme activation
add_action('after_switch_theme', 'ccial_setup_academy_initial_data');

/**
 * Flush rewrite rules on theme activation
 */
function ccial_academy_flush_rewrite_rules() {
    ccial_redefine_portfolio_as_academy();
    ccial_redefine_portfolio_category_as_academic_category();
    ccial_redefine_portfolio_skills_as_competencies();
    ccial_redefine_portfolio_tags_as_topics();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ccial_academy_flush_rewrite_rules');

/**
 * Add custom rewrite rules for Academy (multilingual support)
 */
function ccial_add_academy_rewrite_rules() {
    // Add rewrite rule for academy archive (English)
    add_rewrite_rule(
        '^academy/?$',
        'index.php?post_type=avada_portfolio',
        'top'
    );
    
    // Add rewrite rule for academia archive (Spanish)
    add_rewrite_rule(
        '^academia/?$',
        'index.php?post_type=avada_portfolio',
        'top'
    );
    
    // Add rewrite rule for academy pagination (English)
    add_rewrite_rule(
        '^academy/page/([0-9]+)/?$',
        'index.php?post_type=avada_portfolio&paged=$matches[1]',
        'top'
    );
    
    // Add rewrite rule for academia pagination (Spanish)
    add_rewrite_rule(
        '^academia/page/([0-9]+)/?$',
        'index.php?post_type=avada_portfolio&paged=$matches[1]',
        'top'
    );
    
    // Add rewrite rule for academy category archives (English)
    add_rewrite_rule(
        '^academy/([^/]+)/?$',
        'index.php?post_type=avada_portfolio&portfolio_category=$matches[1]',
        'top'
    );
    
    // Add rewrite rule for academia category archives (Spanish)
    add_rewrite_rule(
        '^academia/([^/]+)/?$',
        'index.php?post_type=avada_portfolio&portfolio_category=$matches[1]',
        'top'
    );
    
    // Add rewrite rule for academy category pagination (English)
    add_rewrite_rule(
        '^academy/([^/]+)/page/([0-9]+)/?$',
        'index.php?post_type=avada_portfolio&portfolio_category=$matches[1]&paged=$matches[2]',
        'top'
    );
    
    // Add rewrite rule for academia category pagination (Spanish)
    add_rewrite_rule(
        '^academia/([^/]+)/page/([0-9]+)/?$',
        'index.php?post_type=avada_portfolio&portfolio_category=$matches[1]&paged=$matches[2]',
        'top'
    );
}
add_action('init', 'ccial_add_academy_rewrite_rules', 20);

/**
 * Add custom columns to Academy admin list
 */
function ccial_add_academy_admin_columns($columns) {
    // Remove default columns we don't need
    unset($columns['date']);
    
    // Add custom columns
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = __('Resource Name', 'ccial');
    $new_columns['academic_category'] = __('Category', 'ccial');
    $new_columns['competencies'] = __('Competencies', 'ccial');
    $new_columns['date'] = __('Date', 'ccial');
    
    return $new_columns;
}
add_filter('manage_avada_portfolio_posts_columns', 'ccial_add_academy_admin_columns');

/**
 * Populate custom columns
 */
function ccial_populate_academy_admin_columns($column, $post_id) {
    switch ($column) {
        case 'academic_category':
            $terms = get_the_terms($post_id, 'portfolio_category');
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
            
        case 'competencies':
            $terms = get_the_terms($post_id, 'portfolio_skills');
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
add_action('manage_avada_portfolio_posts_custom_column', 'ccial_populate_academy_admin_columns', 10, 2);

/**
 * Make columns sortable
 */
function ccial_make_academy_columns_sortable($columns) {
    $columns['academic_category'] = 'academic_category';
    $columns['competencies'] = 'competencies';
    
    return $columns;
}
add_filter('manage_edit-avada_portfolio_sortable_columns', 'ccial_make_academy_columns_sortable');

/**
 * Handle sorting for custom columns
 */
function ccial_handle_academy_column_sorting($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    switch ($orderby) {
        case 'academic_category':
            $query->set('meta_key', 'academic_category');
            $query->set('orderby', 'meta_value');
            break;
        case 'competencies':
            $query->set('meta_key', 'competencies');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'ccial_handle_academy_column_sorting');

/**
 * Add Academy-specific functionality
 */
function ccial_add_academy_functionality() {
    // Add Academy-specific CSS classes
    add_filter('body_class', 'ccial_academy_body_classes');
    
    // Modify academy archive query
    add_action('pre_get_posts', 'ccial_academy_query_modifications');
}

function ccial_academy_body_classes($classes) {
    if (is_singular('avada_portfolio')) {
        $classes[] = 'academy-single';
    }
    if (is_post_type_archive('avada_portfolio')) {
        $classes[] = 'academy-archive';
    }
    if (is_tax('portfolio_category')) {
        $classes[] = 'academy-category-archive';
        
        // Add specific category class
        $term = get_queried_object();
        if ($term) {
            $classes[] = 'academy-category-' . $term->slug;
        }
    }
    return $classes;
}

function ccial_academy_query_modifications($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive('avada_portfolio') || is_tax('portfolio_category')) {
            // Set posts per page for academy archive and category archives
            $query->set('posts_per_page', 12);
            
            // Only add language filtering if we have a specific language request
            $request_uri = $_SERVER['REQUEST_URI'];
            if (strpos($request_uri, '/academia/') !== false) {
                // This is a Spanish request - let WPML handle the filtering
                if (function_exists('icl_object_id')) {
                    // WPML will handle language filtering automatically
                    // We don't need to add meta_query here
                }
            }
            // For English requests, let the default behavior work
        }
    }
}

add_action('init', 'ccial_add_academy_functionality');

/**
 * Custom URL generation for Academy in both languages
 */
function ccial_academy_post_type_link($post_link, $post) {
    if ($post->post_type === 'avada_portfolio') {
        // Get current language
        $current_lang = get_locale();
        
        // Determine the slug based on language
        if (strpos($current_lang, 'es') === 0) {
            // Spanish
            $slug = 'academia';
        } else {
            // English (default)
            $slug = 'academy';
        }
        
        // Generate the new URL
        $post_link = home_url($slug . '/' . $post->post_name . '/');
    }
    return $post_link;
}
add_filter('post_type_link', 'ccial_academy_post_type_link', 10, 2);

/**
 * Custom archive URL generation for Academy
 */
function ccial_academy_archive_link($link, $post_type) {
    if ($post_type === 'avada_portfolio') {
        // Get current language
        $current_lang = get_locale();
        
        // Determine the slug based on language
        if (strpos($current_lang, 'es') === 0) {
            // Spanish
            $slug = 'academia';
        } else {
            // English (default)
            $slug = 'academy';
        }
        
        // Generate the new archive URL
        $link = home_url($slug . '/');
    }
    return $link;
}
add_filter('post_type_archive_link', 'ccial_academy_archive_link', 10, 2);

/**
 * Custom taxonomy archive URL generation for Academy categories
 */
function ccial_academy_taxonomy_link($termlink, $term, $taxonomy) {
    if ($taxonomy === 'portfolio_category') {
        // Get current language
        $current_lang = get_locale();
        
        // Determine the slug based on language
        if (strpos($current_lang, 'es') === 0) {
            // Spanish
            $slug = 'academia';
        } else {
            // English (default)
            $slug = 'academy';
        }
        
        // Generate the new taxonomy archive URL
        $termlink = home_url($slug . '/' . $term->slug . '/');
    }
    return $termlink;
}
add_filter('term_link', 'ccial_academy_taxonomy_link', 10, 3);

/**
 * Add custom query vars for Academy URLs
 */
function ccial_academy_query_vars($vars) {
    $vars[] = 'academy_page';
    $vars[] = 'academia_page';
    return $vars;
}
add_filter('query_vars', 'ccial_academy_query_vars');

/**
 * Handle Academy URL parsing and language detection
 */
function ccial_academy_parse_request($wp) {
    // Check if this is an academy/academia request
    if (isset($wp->query_vars['academy_page']) || isset($wp->query_vars['academia_page'])) {
        $wp->query_vars['post_type'] = 'avada_portfolio';
        $wp->query_vars['is_archive'] = true;
        $wp->query_vars['is_post_type_archive'] = true;
    }
}
add_action('parse_request', 'ccial_academy_parse_request');

/**
 * Detect language from URL and set context (simplified)
 */
function ccial_academy_detect_language_from_url() {
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Only set language context for WPML if it's active
    if (function_exists('icl_object_id')) {
        // Check if this is an academia (Spanish) request
        if (strpos($request_uri, '/academia/') !== false) {
            // Set WPML language context
            add_filter('wpml_current_language', function() {
                return 'es';
            });
        }
        
        // Check if this is an academy (English) request
        if (strpos($request_uri, '/academy/') !== false) {
            // Set WPML language context
            add_filter('wpml_current_language', function() {
                return 'en';
            });
        }
    }
}
add_action('init', 'ccial_academy_detect_language_from_url', 5);

/**
 * Alternative approach: Use WP_Query to get posts by language
 * This works regardless of the multilingual plugin being used
 */
function ccial_academy_get_posts_by_language($language = 'en') {
    $args = array(
        'post_type' => 'avada_portfolio',
        'post_status' => 'publish',
        'posts_per_page' => 12,
        'meta_query' => array()
    );
    
    // Check for different multilingual plugins
    if (function_exists('icl_object_id')) {
        // WPML
        $args['meta_query'][] = array(
            'key' => 'wpml_language',
            'value' => $language,
            'compare' => '='
        );
    } elseif (function_exists('pll_get_post_language')) {
        // Polylang
        $args['lang'] = $language;
    } else {
        // Fallback: check for custom language meta
        $args['meta_query'][] = array(
            'relation' => 'OR',
            array(
                'key' => 'language',
                'value' => $language,
                'compare' => '='
            ),
            array(
                'key' => 'language',
                'compare' => 'NOT EXISTS'
            )
        );
    }
    
    return new WP_Query($args);
}

/**
 * Debug function to check what posts are being returned (disabled by default)
 */
function ccial_academy_debug_posts() {
    // Debug function disabled to prevent interference
    // Uncomment the code below if you need to debug
    /*
    if (current_user_can('administrator') && isset($_GET['debug_academy'])) {
        $request_uri = $_SERVER['REQUEST_URI'];
        $language = (strpos($request_uri, '/academia/') !== false) ? 'es' : 'en';
        
        echo '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 1px solid #ccc;">';
        echo '<h3>Academy Debug Info</h3>';
        echo '<p><strong>Request URI:</strong> ' . $request_uri . '</p>';
        echo '<p><strong>Detected Language:</strong> ' . $language . '</p>';
        
        $query = ccial_academy_get_posts_by_language($language);
        echo '<p><strong>Posts Found:</strong> ' . $query->found_posts . '</p>';
        
        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li>' . get_the_title() . ' (ID: ' . get_the_ID() . ')</li>';
            }
            echo '</ul>';
        }
        echo '</div>';
    }
    */
}
// add_action('wp_footer', 'ccial_academy_debug_posts');
