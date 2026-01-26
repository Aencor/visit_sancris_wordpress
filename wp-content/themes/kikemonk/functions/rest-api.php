<?php
/**
 * Expose ACF fields in REST API
 */
add_action('rest_api_init', function () {
    // Register ACF fields for 'lugares' post type
    register_rest_field('lugares', 'acf', [
        'get_callback' => function ($post) {
            return get_fields($post['id']);
        },
        'schema' => null,
    ]);

    // Register ACF fields for 'eventos' post type
    register_rest_field('eventos', 'acf', [
        'get_callback' => function ($post) {
            return get_fields($post['id']);
        },
        'schema' => null,
    ]);
});
