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
