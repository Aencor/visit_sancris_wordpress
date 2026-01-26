<?php
// Force import from JSON to DB to fix missing fields issue
if( function_exists('acf_import_field_group') && !get_option('kikemonk_acf_force_import_v2') ):

    // Load and import Lugares
    $json_lugares = file_get_contents(get_stylesheet_directory() . '/acf-json/group_lugares.json');
    if ($json_lugares) {
        $group_lugares = json_decode($json_lugares, true);
        acf_import_field_group($group_lugares);
    }

    // Load and import Eventos
    $json_eventos = file_get_contents(get_stylesheet_directory() . '/acf-json/group_eventos.json');
    if ($json_eventos) {
        $group_eventos = json_decode($json_eventos, true);
        acf_import_field_group($group_eventos);
    }

    update_option('kikemonk_acf_force_import_v2', true);
endif;
