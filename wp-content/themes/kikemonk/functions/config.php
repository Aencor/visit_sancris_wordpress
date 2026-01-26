<?php
/**
 * Enable theme features
 */

// Limit revisions
function monk_revision_limit($num, $post) {
	return 5;
}
add_filter("wp_revisions_to_keep", "monk_revision_limit", 10, 2);

// Add Support for SVGs
function monk_mime_types($mimes) {
	$mimes["svg"] = "image/svg+xml";
	return $mimes;
}
add_filter("upload_mimes", "monk_mime_types");


// --- ACF Local JSON Configuration ---

/**
 * Save ACF JSON to theme directory
 */
add_filter('acf/settings/save_json', function( $path ) {
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
});

/**
 * Load ACF JSON from theme directory
 */
add_filter('acf/settings/load_json', function( $paths ) {
    unset($paths[0]); // Remove original path
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});
