<?php
require_once('wp-load.php');

$username = 'testuser';
$password = 'password123';
$email = 'testuser@example.com';

if (!username_exists($username)) {
    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        echo "Error creating user: " . $user_id->get_error_message();
    } else {
        $user = new WP_User($user_id);
        $user->set_role('subscriber');
        echo "User created: $username / $password";
    }
} else {
    echo "User $username already exists.";
}
