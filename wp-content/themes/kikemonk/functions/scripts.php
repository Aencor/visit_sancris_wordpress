<?php


function theme_scripts() {

	if (is_single() && comments_open() && get_option("thread_comments")) {
		wp_enqueue_script("comment-reply");
	}

	wp_register_script(
		"theme-defer",
		get_template_directory_uri() . "/assets/build/main.js",
		['jquery'], // Dependencia de jQuery
		'1.0.1',
		false
	);
	wp_enqueue_script("theme-defer");

	// Get Places for Map
	$args = array(
		'post_type' => 'lugares',
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);
	$places_query = new WP_Query($args);
	$places_data = array();

	if ($places_query->have_posts()) {
		while ($places_query->have_posts()) {
			$places_query->the_post();
			$location = get_field('ubicacion');
			
			// Default fallback image if none exists
			$image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
			if (!$image_url) {
				$image_url = get_template_directory_uri() . '/assets/img/logo.jpg';
			}

			if ($location && isset($location['lat']) && isset($location['lng'])) {
				$custom_desc = get_field('descripcion_personalizada');
                
                // Determine Category, Label and Icon
                $terms = get_the_terms(get_the_ID(), 'tipo_lugar');
                $category = 'restaurant'; // default fallback key
                $label = 'Restaurante'; // default fallback label
                $icon = null;

                if ($terms && !is_wp_error($terms)) {
                    $term = reset($terms); // Get first term
                    $category = $term->slug;
                    $label = $term->name;
                    // Extract Emoji (First char)
                    $icon = mb_substr($term->name, 0, 1, 'UTF-8');
                } else {
                    // Fallback to ACF
                    $acf_cat = get_field('categoria');
                    if ($acf_cat) {
                        $category = $acf_cat;
                        $label = ucfirst($acf_cat); // Basic formatting
                    }
                }

				$places_data[] = array(
					'id' => get_the_ID(),
					'name' => get_the_title(),
					'category' => $category,
                    'label' => $label,
                    'icon' => $icon,
					'description' => $custom_desc ?: get_the_excerpt(), // Use custom field or fallback to excerpt
					'image' => $image_url,
					'url' => get_permalink(),
					'lat' => floatval($location['lat']),
					'lng' => floatval($location['lng'])
				);
			}
		}
		wp_reset_postdata();
	}

	// Get Events
	$args_events = array(
		'post_type' => 'eventos',
		'posts_per_page' => 10,
		'post_status' => 'publish',
		'meta_key' => 'fecha',
		'orderby' => 'meta_value',
		'order' => 'ASC'
	);
	$events_query = new WP_Query($args_events);
	$events_data = array();

	if ($events_query->have_posts()) {
		while ($events_query->have_posts()) {
			$events_query->the_post();
			$location_map = get_field('ubicacion_mapa');
			$events_data[] = array(
				'id' => get_the_ID(),
				'title' => get_the_title(),
				'link' => get_permalink(),
				'lat' => $location_map ? floatval($location_map['lat']) : null,
				'lng' => $location_map ? floatval($location_map['lng']) : null,
				'acf' => array(
					'fecha' => get_field('fecha'),
					'hora' => get_field('hora'),
					'ubicacion' => get_field('ubicacion')
				)
			);
		}
		wp_reset_postdata();
	}

	wp_localize_script('theme-defer', 'wpData', array(
		'center' => array('lat' => 16.737, 'lng' => -92.637),
		'places' => $places_data,
		'events' => $events_data,
        'ajax_url' => admin_url('admin-ajax.php'),
        'favorite_nonce' => wp_create_nonce('favorite_nonce'),
        'is_logged_in' => is_user_logged_in()
	));

	// Leaflet JS
	wp_enqueue_script("leaflet", "https://unpkg.com/leaflet@1.9.4/dist/leaflet.js", [], "1.9.4", true);
	
	// GSAP
	wp_enqueue_script("gsap", "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js", [], "3.12.2", true);

    // Font Awesome
    wp_enqueue_style("font-awesome", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css", [], "6.4.0");
	
	// Main Style
	wp_enqueue_style(
		"master",
		get_template_directory_uri() . "/assets/build/style.css",
		[],
		"1.1",
		"all"
	);
}

add_action("wp_enqueue_scripts", "theme_scripts", 9999);
