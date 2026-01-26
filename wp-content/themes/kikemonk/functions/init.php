<?php
/**
 * Initial setup and constants
 */
function monk_theme_setup() {
	// Enable plugins to manage the document title
	add_theme_support("title-tag");

	// Add post thumbnails
	add_theme_support("post-thumbnails");

	// Add Menus
	add_theme_support("menus");

	// Add HTML5 markup for captions
	add_theme_support("html5", ["caption", "comment-form", "comment-list"]);
}
add_action("after_setup_theme", "monk_theme_setup");

// Adding Theme Support
add_theme_support("custom-logo");

