<?php
/**
 * Register Custom Post Types
 */

function register_custom_post_types() {
    // Register Lugares
    $labels_lugares = [
        'name'                  => _x('Lugares', 'Post Type General Name', 'kikemonk'),
        'singular_name'         => _x('Lugar', 'Post Type Singular Name', 'kikemonk'),
        'menu_name'             => __('Lugares', 'kikemonk'),
        'name_admin_bar'        => __('Lugar', 'kikemonk'),
        'all_items'             => __('Todos los Lugares', 'kikemonk'),
        'add_new_item'          => __('Añadir nuevo Lugar', 'kikemonk'),
        'add_new'               => __('Añadir nuevo', 'kikemonk'),
        'new_item'              => __('Nuevo Lugar', 'kikemonk'),
        'edit_item'             => __('Editar Lugar', 'kikemonk'),
        'update_item'           => __('Actualizar Lugar', 'kikemonk'),
        'view_item'             => __('Ver Lugar', 'kikemonk'),
        'search_items'          => __('Buscar Lugar', 'kikemonk'),
        'not_found'             => __('No encontrado', 'kikemonk'),
        'not_found_in_trash'    => __('No encontrado en la papelera', 'kikemonk'),
    ];
    $args_lugares = [
        'label'                 => __('Lugar', 'kikemonk'),
        'description'           => __('Directorio de lugares en San Cristóbal', 'kikemonk'),
        'labels'                => $labels_lugares,
        'supports'              => ['title', 'thumbnail', 'excerpt', 'page-attributes'], // Added 'page-attributes' for menu_order
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-location',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'taxonomies'            => ['post_tag'],
    ];
    register_post_type('lugares', $args_lugares);

    // Register Eventos
    $labels_eventos = [
        'name'                  => _x('Eventos', 'Post Type General Name', 'kikemonk'),
        'singular_name'         => _x('Evento', 'Post Type Singular Name', 'kikemonk'),
        'menu_name'             => __('Eventos', 'kikemonk'),
    ];
    $args_eventos = [
        'label'                 => __('Evento', 'kikemonk'),
        'labels'                => $labels_eventos,
        'supports'              => ['title', 'thumbnail', 'excerpt', 'page-attributes'], // Added 'page-attributes' for menu_order
        'public'                => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-calendar-alt',
        'has_archive'           => true,
        'show_in_rest'          => true,
        'taxonomies'            => ['post_tag'],
    ];
    register_post_type('eventos', $args_eventos);

    // Register Taxonomy for Lugares
    $labels_tax = [
        'name'              => _x('Tipos de Lugar', 'taxonomy general name', 'kikemonk'),
        'singular_name'     => _x('Tipo de Lugar', 'taxonomy singular name', 'kikemonk'),
        'search_items'      => __('Buscar Tipos', 'kikemonk'),
        'all_items'         => __('Todos los Tipos', 'kikemonk'),
        'parent_item'       => __('Tipo Padre', 'kikemonk'),
        'parent_item_colon' => __('Tipo Padre:', 'kikemonk'),
        'edit_item'         => __('Editar Tipo', 'kikemonk'),
        'update_item'       => __('Actualizar Tipo', 'kikemonk'),
        'add_new_item'      => __('Añadir nuevo Tipo', 'kikemonk'),
        'new_item_name'     => __('Nuevo nombre de Tipo', 'kikemonk'),
        'menu_name'         => __('Tipos de Lugar', 'kikemonk'),
    ];
    $args_tax = [
        'hierarchical'      => true,
        'labels'            => $labels_tax,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tipo-lugar'],
        'show_in_rest'      => true,
    ];
    register_taxonomy('tipo_lugar', ['lugares'], $args_tax);

    // Seed Terms
    if (!get_option('kikemonk_lugares_seeded')) {
        $terms = [
            '⛪Historia e Iglesias',
            '🏛 Museos',
            '📸Spots Fotograficos',
            '🌿Naturaleza y miradores',
            '🏘Mercados',
            '💧Cascadas y Rios | Ecoturismo'
        ];
        foreach ($terms as $term) {
            if (!term_exists($term, 'tipo_lugar')) {
                wp_insert_term($term, 'tipo_lugar');
            }
        }
        update_option('kikemonk_lugares_seeded', 1);
    }
}
add_action('init', 'register_custom_post_types', 0);
