<?php
/**
 * WordPress User Export REST API
 * 
 * Provides REST API endpoints to export WordPress users with their roles and metadata
 * in CSV format for administrative purposes.
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
 * Register User Export REST API endpoints
 */
function ccial_register_user_export_api() {
    // Main user export endpoint
    register_rest_route('ccial/v1', '/users/export', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ccial_export_users_csv',
        'permission_callback' => 'ccial_check_user_export_permissions',
        'args' => array(
            'format' => array(
                'description' => 'Export format (csv, json)',
                'type' => 'string',
                'enum' => array('csv', 'json'),
                'default' => 'csv',
            ),
            'roles' => array(
                'description' => 'Comma-separated list of user roles to filter',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'include_meta' => array(
                'description' => 'Include user meta fields',
                'type' => 'boolean',
                'default' => true,
            ),
            'fields' => array(
                'description' => 'Comma-separated list of specific fields to include',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));
    
    // User list endpoint (JSON only)
    register_rest_route('ccial/v1', '/users/list', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'ccial_get_users_list',
        'permission_callback' => 'ccial_check_user_export_permissions',
        'args' => array(
            'per_page' => array(
                'description' => 'Number of users per page',
                'type' => 'integer',
                'default' => 50,
                'minimum' => 1,
                'maximum' => 100,
            ),
            'page' => array(
                'description' => 'Page number',
                'type' => 'integer',
                'default' => 1,
                'minimum' => 1,
            ),
            'roles' => array(
                'description' => 'Comma-separated list of user roles to filter',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'search' => array(
                'description' => 'Search term for users',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));
}
add_action('rest_api_init', 'ccial_register_user_export_api');

/**
 * Check permissions for user export
 */
function ccial_check_user_export_permissions($request) {
    // Only administrators and users with 'export' capability can access
    if (current_user_can('export') || current_user_can('manage_options')) {
        return true;
    }
    
    return new WP_Error('rest_forbidden', 
        __('Sorry, you are not allowed to export users.', 'ccial'), 
        array('status' => 403)
    );
}

/**
 * Export users as CSV
 */
function ccial_export_users_csv($request) {
    $format = $request->get_param('format');
    $roles = $request->get_param('roles');
    $include_meta = $request->get_param('include_meta');
    $fields = $request->get_param('fields');
    
    // Build query arguments
    $args = array(
        'number' => -1, // Get all users
        'orderby' => 'display_name',
        'order' => 'ASC',
    );
    
    // Filter by roles if specified
    if (!empty($roles)) {
        $role_array = array_map('trim', explode(',', $roles));
        $args['role__in'] = $role_array;
    }
    
    // Get users
    $users = get_users($args);
    
    if (empty($users)) {
        return new WP_Error('no_users_found', 
            __('No users found matching the criteria.', 'ccial'), 
            array('status' => 404)
        );
    }
    
    // Get available user roles
    $wp_roles = wp_roles();
    $role_names = $wp_roles->get_names();
    
    // Prepare data
    $user_data = array();
    $headers = array(
        'ID',
        'Username',
        'Email',
        'First Name',
        'Last Name',
        'Display Name',
        'Roles',
        'Registered Date',
        'Last Login',
        'Status'
    );
    
    // Add meta fields if requested
    if ($include_meta) {
        $meta_fields = array('phone', 'mobile', 'address', 'city', 'state', 'country', 'postal_code');
        $headers = array_merge($headers, $meta_fields);
    }
    
    // Add custom fields if specified
    if (!empty($fields)) {
        $custom_fields = array_map('trim', explode(',', $fields));
        $headers = array_merge($headers, $custom_fields);
    }
    
    foreach ($users as $user) {
        $user_roles = array();
        foreach ($user->roles as $role) {
            $user_roles[] = isset($role_names[$role]) ? $role_names[$role] : $role;
        }
        
        $row = array(
            $user->ID,
            $user->user_login,
            $user->user_email,
            $user->first_name,
            $user->last_name,
            $user->display_name,
            implode(', ', $user_roles),
            $user->user_registered,
            get_user_meta($user->ID, 'last_login', true) ?: 'Never',
            $user->user_status == 0 ? 'Active' : 'Inactive'
        );
        
        // Add meta fields
        if ($include_meta) {
            foreach ($meta_fields as $field) {
                $row[] = get_user_meta($user->ID, $field, true) ?: '';
            }
        }
        
        // Add custom fields
        if (!empty($fields)) {
            foreach ($custom_fields as $field) {
                $row[] = get_user_meta($user->ID, $field, true) ?: '';
            }
        }
        
        $user_data[] = $row;
    }
    
    if ($format === 'json') {
        // Return JSON format
        $json_data = array();
        $json_data['headers'] = $headers;
        $json_data['users'] = $user_data;
        $json_data['total'] = count($users);
        $json_data['exported_at'] = current_time('mysql');
        
        return new WP_REST_Response($json_data, 200);
    }
    
    // Return CSV format
    return ccial_generate_csv_response($headers, $user_data, 'users_export_' . date('Y-m-d_H-i-s') . '.csv');
}

/**
 * Get users list (JSON format)
 */
function ccial_get_users_list($request) {
    $per_page = $request->get_param('per_page');
    $page = $request->get_param('page');
    $roles = $request->get_param('roles');
    $search = $request->get_param('search');
    
    // Build query arguments
    $args = array(
        'number' => $per_page,
        'offset' => ($page - 1) * $per_page,
        'orderby' => 'display_name',
        'order' => 'ASC',
    );
    
    // Filter by roles if specified
    if (!empty($roles)) {
        $role_array = array_map('trim', explode(',', $roles));
        $args['role__in'] = $role_array;
    }
    
    // Add search if specified
    if (!empty($search)) {
        $args['search'] = '*' . $search . '*';
        $args['search_columns'] = array('user_login', 'user_email', 'display_name', 'first_name', 'last_name');
    }
    
    // Get users with total count
    $users_query = new WP_User_Query($args);
    $users = $users_query->get_results();
    $total_users = $users_query->get_total();
    
    // Get available user roles
    $wp_roles = wp_roles();
    $role_names = $wp_roles->get_names();
    
    // Prepare response data
    $user_data = array();
    
    foreach ($users as $user) {
        $user_roles = array();
        foreach ($user->roles as $role) {
            $user_roles[] = array(
                'slug' => $role,
                'name' => isset($role_names[$role]) ? $role_names[$role] : $role
            );
        }
        
        $user_data[] = array(
            'id' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'display_name' => $user->display_name,
            'roles' => $user_roles,
            'registered_date' => $user->user_registered,
            'last_login' => get_user_meta($user->ID, 'last_login', true) ?: null,
            'status' => $user->user_status == 0 ? 'active' : 'inactive',
            'url' => rest_url('ccial/v1/users/' . $user->ID)
        );
    }
    
    $response = array(
        'users' => $user_data,
        'total' => $total_users,
        'pages' => ceil($total_users / $per_page),
        'current_page' => $page,
        'per_page' => $per_page
    );
    
    return new WP_REST_Response($response, 200);
}

/**
 * Generate CSV response
 */
function ccial_generate_csv_response($headers, $data, $filename = 'export.csv') {
    // Set headers for CSV download
    $headers_array = array(
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'no-cache, must-revalidate',
        'Expires' => '0'
    );
    
    // Start output buffering
    ob_start();
    
    // Open output stream
    $output = fopen('php://output', 'w');
    
    // Add BOM for UTF-8 compatibility with Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Write headers
    fputcsv($output, $headers);
    
    // Write data
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    // Close output stream
    fclose($output);
    
    // Get output content
    $csv_content = ob_get_clean();
    
    // Return response
    $response = new WP_REST_Response($csv_content, 200);
    
    // Set headers
    foreach ($headers_array as $key => $value) {
        $response->header($key, $value);
    }
    
    return $response;
}

/**
 * Add user export capability to administrator role
 */
function ccial_add_export_capability() {
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('export');
    }
}
add_action('admin_init', 'ccial_add_export_capability');

/**
 * Track user login for last login meta
 */
function ccial_track_user_login($user_login, $user) {
    update_user_meta($user->ID, 'last_login', current_time('mysql'));
}
add_action('wp_login', 'ccial_track_user_login', 10, 2);

/**
 * Add admin menu for user export
 */
function ccial_add_user_export_menu() {
    add_users_page(
        __('Export Users', 'ccial'),
        __('Export Users', 'ccial'),
        'export',
        'ccial-user-export',
        'ccial_user_export_page'
    );
}
add_action('admin_menu', 'ccial_add_user_export_menu');

/**
 * User export admin page
 */
function ccial_user_export_page() {
    if (!current_user_can('export')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'ccial'));
    }
    
    $site_url = get_site_url();
    $api_endpoint = $site_url . '/wp-json/ccial/v1/users/export';
    ?>
    <div class="wrap">
        <h1><?php _e('Export Users', 'ccial'); ?></h1>
        
        <div class="card">
            <h2><?php _e('REST API Endpoints', 'ccial'); ?></h2>
            <p><?php _e('Use these endpoints to export user data via REST API:', 'ccial'); ?></p>
            
            <h3><?php _e('CSV Export', 'ccial'); ?></h3>
            <code><?php echo esc_url($api_endpoint); ?></code>
            
            <h3><?php _e('JSON Export', 'ccial'); ?></h3>
            <code><?php echo esc_url($api_endpoint); ?>?format=json</code>
            
            <h3><?php _e('User List (JSON)', 'ccial'); ?></h3>
            <code><?php echo esc_url($site_url); ?>/wp-json/ccial/v1/users/list</code>
        </div>
        
        <div class="card">
            <h2><?php _e('Parameters', 'ccial'); ?></h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Parameter', 'ccial'); ?></th>
                        <th><?php _e('Type', 'ccial'); ?></th>
                        <th><?php _e('Description', 'ccial'); ?></th>
                        <th><?php _e('Example', 'ccial'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>format</code></td>
                        <td>string</td>
                        <td><?php _e('Export format (csv or json)', 'ccial'); ?></td>
                        <td><code>?format=csv</code></td>
                    </tr>
                    <tr>
                        <td><code>roles</code></td>
                        <td>string</td>
                        <td><?php _e('Comma-separated user roles', 'ccial'); ?></td>
                        <td><code>?roles=administrator,editor</code></td>
                    </tr>
                    <tr>
                        <td><code>include_meta</code></td>
                        <td>boolean</td>
                        <td><?php _e('Include user meta fields', 'ccial'); ?></td>
                        <td><code>?include_meta=true</code></td>
                    </tr>
                    <tr>
                        <td><code>fields</code></td>
                        <td>string</td>
                        <td><?php _e('Custom meta fields to include', 'ccial'); ?></td>
                        <td><code>?fields=phone,address</code></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="card">
            <h2><?php _e('Authentication', 'ccial'); ?></h2>
            <p><?php _e('This endpoint requires authentication. Use one of these methods:', 'ccial'); ?></p>
            <ul>
                <li><?php _e('Application Passwords (WordPress 5.6+)', 'ccial'); ?></li>
                <li><?php _e('Basic Authentication plugin', 'ccial'); ?></li>
                <li><?php _e('JWT Authentication plugin', 'ccial'); ?></li>
                <li><?php _e('OAuth plugin', 'ccial'); ?></li>
            </ul>
        </div>
        
        <div class="card">
            <h2><?php _e('Quick Export Links', 'ccial'); ?></h2>
            <p>
                <a href="<?php echo esc_url($api_endpoint); ?>" class="button button-primary" target="_blank">
                    <?php _e('Download CSV Export', 'ccial'); ?>
                </a>
                <a href="<?php echo esc_url($api_endpoint . '?format=json'); ?>" class="button" target="_blank">
                    <?php _e('View JSON Export', 'ccial'); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Add export capability to other roles if needed
 */
function ccial_add_export_to_editor_role() {
    $role = get_role('editor');
    if ($role && !$role->has_cap('export')) {
        $role->add_cap('export');
    }
}
add_action('admin_init', 'ccial_add_export_to_editor_role');
