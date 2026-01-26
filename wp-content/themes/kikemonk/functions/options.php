<?php
if (function_exists("acf_add_options_page")) {
	acf_add_options_page([
		"page_title" => "Global Settings",
		"menu_title" => "Global Settings",
		"menu_slug" => "theme-general-options",
		"capability" => "edit_posts",
		"redirect" => true,
		"icon_url" => "dashicons-admin-site",
		"position" => 58 // End of the menu, before the separator at 59
	]);

	acf_add_options_sub_page([
		"page_title" => "Menu Options",
		"menu_title" => "Menu Options",
		"parent_slug" => "theme-general-options"
	]);

	acf_add_options_sub_page([
		"page_title" => "Global Settings",
		"menu_title" => "Global Settings",
		"parent_slug" => "theme-general-options"
	]);

	acf_add_options_sub_page([
		"page_title" => "External Scripts",
		"menu_title" => "External Scripts",
		"parent_slug" => "theme-general-options"
	]);

	acf_add_options_sub_page([
		"page_title" => "404 Page",
		"menu_title" => "404 Page",
		"parent_slug" => "theme-general-options"
	]);
}
