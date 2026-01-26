<?php
if( function_exists('acf_add_local_field_group') ):

    // Lugares Field Group
    acf_add_local_field_group(array(
        'key' => 'group_65abc12345678',
        'title' => 'Datos del Lugar',
        'fields' => array(
            array(
                'key' => 'field_65abc12345679',
                'label' => 'Categoría',
                'name' => 'categoria',
                'type' => 'select',
                'choices' => array(
                    'restaurant' => 'Restaurante',
                    'park' => 'Parque',
                    'museum' => 'Museo',
                    'shop' => 'Tienda',
                    'bar' => 'Bar',
                    'cafe' => 'Cafetería',
                    'hotel' => 'Hotel'
                ),
                'default_value' => 'restaurant',
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_65abc12345680',
                'label' => 'Puntuación',
                'name' => 'puntuacion',
                'type' => 'text',
                'default_value' => '4.8',
            ),
            array(
                'key' => 'field_65abc12345681',
                'label' => 'Horario',
                'name' => 'horario',
                'type' => 'textarea',
                'default_value' => 'Abierto 09:00 - 21:00',
            ),
            array(
                'key' => 'field_65abc12345682',
                'label' => 'Ubicación (Mapa)',
                'name' => 'ubicacion',
                'type' => 'leaflet_map',
                'instructions' => 'Selecciona la ubicación en el mapa.',
                'center_lat' => '16.7371',
                'center_lng' => '-92.6376',
                'zoom' => 14,
            ),
            // Contact Fields
            array(
                'key' => 'field_65abc12345686',
                'label' => 'WhatsApp',
                'name' => 'whatsapp',
                'type' => 'text',
                'instructions' => 'Número con lada (ej: 529671234567)',
            ),
            array(
                'key' => 'field_65abc12345687',
                'label' => 'Email de contacto',
                'name' => 'email',
                'type' => 'email',
            ),
            array(
                'key' => 'field_65abc12345688',
                'label' => 'Teléfono',
                'name' => 'telefono',
                'type' => 'text',
            ),
            // Custom Messages
            array(
                'key' => 'field_65abc12345690',
                'label' => 'Descripción Personalizada',
                'name' => 'descripcion_personalizada',
                'type' => 'textarea',
                'instructions' => 'Descripción adicional que aparece debajo del título.',
            ),
            array(
                'key' => 'field_65abc12345691',
                'label' => 'Mensaje WhatsApp',
                'name' => 'mensaje_whatsapp',
                'type' => 'text',
                'default_value' => 'Hola, vi su lugar en Visita San Cris y quisiera más información.',
                'instructions' => 'Mensaje predeterminado al abrir WhatsApp.',
            ),
            array(
                'key' => 'field_65abc12345692',
                'label' => 'Mensaje Email',
                'name' => 'mensaje_email',
                'type' => 'text',
                'default_value' => 'Hola, solicito información sobre sus servicios.',
                'instructions' => 'Asunto/Cuerpo predeterminado al abrir el correo.',
            ),
            array(
                'key' => 'field_65abc12345685',
                'label' => 'Galería',
                'name' => 'galeria',
                'type' => 'gallery',
                'return_format' => 'array',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'lugares',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));



endif;
