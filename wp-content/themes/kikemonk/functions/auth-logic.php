<?php
/**
 * Auth Logic: Login and Registration Handlers
 */

// Handle Custom Login
function handle_custom_login() {
    if (isset($_POST['custom_login_nonce']) && wp_verify_nonce($_POST['custom_login_nonce'], 'custom_login_action')) {
        
        $creds = array(
            'user_login'    => sanitize_text_field($_POST['log']),
            'user_password' => $_POST['pwd'],
            'remember'      => isset($_POST['rememberme']) ? true : false,
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            // Redirect back with error
            wp_redirect(add_query_arg('login_error', 'invalid_creds', wp_get_referer()));
            exit;
        } else {
            // Redirect to home or profile
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action('admin_post_nopriv_custom_login', 'handle_custom_login');
add_action('admin_post_custom_login', 'handle_custom_login');

// Handle Custom Registration
function handle_custom_registration() {
    if (isset($_POST['custom_register_nonce']) && wp_verify_nonce($_POST['custom_register_nonce'], 'custom_register_action')) {
        
        $username = sanitize_user($_POST['username']);
        $email    = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $confirm  = $_POST['confirm_password'];

        // Validation
        if ($password !== $confirm) {
            wp_redirect(add_query_arg('register_error', 'password_mismatch', wp_get_referer()));
            exit;
        }

        if (username_exists($username) || email_exists($email)) {
            wp_redirect(add_query_arg('register_error', 'user_exists', wp_get_referer()));
            exit;
        }

        // Create User
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            wp_redirect(add_query_arg('register_error', 'generic', wp_get_referer()));
            exit;
        }

        // Auto Login user after registration
        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => true,
        );
        wp_signon($creds, false);

        // Redirect
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_post_nopriv_custom_register', 'handle_custom_registration');
add_action('admin_post_custom_register', 'handle_custom_registration');

// Handle Profile Update
function handle_profile_update() {
    if (!is_user_logged_in()) {
        wp_redirect(home_url());
        exit;
    }

    if (isset($_POST['profile_update_nonce']) && wp_verify_nonce($_POST['profile_update_nonce'], 'profile_update_action')) {
        
        $user_id = get_current_user_id();
        $email = sanitize_email($_POST['email']);
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);

        // Update User
        $user_data = array(
            'ID' => $user_id,
            'user_email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
        );

        $result = wp_update_user($user_data);

        // Handle File Upload
        if (!is_wp_error($result) && !empty($_FILES['profile_photo']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('profile_photo', 0);

            if (!is_wp_error($attachment_id)) {
                update_user_meta($user_id, 'custom_avatar', $attachment_id);
            }
        }

        if (is_wp_error($result)) {
            // Redirect with error
            wp_redirect(add_query_arg('profile_error', 'update_failed', wp_get_referer()));
            exit;
        } else {
            // Redirect with success
            wp_redirect(add_query_arg('profile_updated', 'true', wp_get_referer()));
            exit;
        }
    }
}
// AJAX Profile Update
function ajax_handle_profile_update() {
    check_ajax_referer('profile_update_action', 'profile_update_nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'No autorizado'));
    }

    $user_id = get_current_user_id();
    $email = sanitize_email($_POST['email']);
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);

    // Update User Info
    $user_data = array(
        'ID' => $user_id,
        'user_email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name, // Update display name too if desired
    );

    // If display name is empty (user cleared name fields), fallback to username? 
    // Usually display_name shouldn't be empty. If both empty, maybe keep username.
    if (empty(trim($first_name . ' ' . $last_name))) {
        unset($user_data['display_name']);
    }

    $result = wp_update_user($user_data);

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }

    $avatar_url = '';

    // Handle File Upload
    if (!empty($_FILES['profile_photo']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('profile_photo', 0);

        if (is_wp_error($attachment_id)) {
            // Can send warning but typically we just fail the image part
            // wp_send_json_error(array('message' => $attachment_id->get_error_message()));
        } else {
            update_user_meta($user_id, 'custom_avatar', $attachment_id);
            $avatar_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
        }
    }

    // Return new data
    // Fetch fresh user data to ensure we return what's in DB
    $user = get_user_by('id', $user_id);
    
    wp_send_json_success(array(
        'message' => 'Perfil actualizado correctamente',
        'avatar_url' => $avatar_url,
        'full_name' => $user->first_name . ' ' . $user->last_name, // Send back full name for dynamic UI update
        'display_name' => $user->display_name
    ));
}
add_action('wp_ajax_update_profile', 'ajax_handle_profile_update');

// AJAX Toggle Favorite
function toggle_user_favorite() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'favorite_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
    }

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Not logged in'));
    }

    $post_id = intval($_POST['post_id']);
    $user_id = get_current_user_id();
    
    // Get existing favorites
    $favorites = get_user_meta($user_id, 'user_favorites', true);
    if (!is_array($favorites)) {
        $favorites = array();
    }

    $action = '';
    
    // Toggle
    if (in_array($post_id, $favorites)) {
        // Remove
        $favorites = array_diff($favorites, array($post_id));
        $action = 'removed';
    } else {
        // Add
        $favorites[] = $post_id;
        $action = 'added';
    }

    // Update meta
    $favorites = array_values($favorites); // Reindex
    update_user_meta($user_id, 'user_favorites', $favorites);

    wp_send_json_success(array('action' => $action, 'count' => count($favorites)));
}
add_action('wp_ajax_toggle_favorite', 'toggle_user_favorite');
