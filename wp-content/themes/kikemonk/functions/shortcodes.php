<?php
// [button text="text" size="large" url="url" target="target"]
function button_func($atts) {
	$a = shortcode_atts(
		[
			"text" => "Button Text",
			"url" => "/",
			"target" => "_self",
			"size" => "large"
		],
		$atts
	);

	$s = "<a class='btn btn-primary btn-{$a["size"]}' href='{$a["url"]}' target='{$a["target"]}'>{$a["text"]}</a>";

		return $s;
	}
	add_shortcode("button", "button_func");

	function carousel_func($atts) {
		$a = shortcode_atts(
			[
					"id" => "1",
					"size" => "normal"
			],
			$atts
	);

	$args = array(
			'post_type' => 'carousel',
			'p'         => $a['id'],
	);

	$query = new WP_Query($args);
	if ($query->have_posts()) {
			$output = '<div class="inline-carousel carousels">';
			while ($query->have_posts()) {
					$query->the_post();
					
					// Check if the repeater field has rows of data
					if (have_rows('carousel_images', $a['id'])) { // Replace 'carousel_images' with your actual repeater field name
							while (have_rows('carousel_images', $a['id'])) {
									the_row();
									
									// Get subfield value
									$image = get_sub_field('image'); // Replace 'image' with your actual subfield name
									
									if ($image) {
											$output .= '<div class="carousel-item carousel-' . $a['size'] . '" style="background-image:url(' . esc_url($image['url']) . ')">';
											$output .= '</div>';
									}
							}
					}
			}
			$output .= '</div>';
	} else {
			$output = '<p>No carousels found</p>';
	}

	wp_reset_postdata();
	return $output;

}
add_shortcode("carousel", "carousel_func");
