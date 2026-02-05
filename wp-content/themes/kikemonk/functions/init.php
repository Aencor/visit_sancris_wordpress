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


// Hide Admin Bar for non-admins
add_filter('show_admin_bar', function($show) {
    if (!current_user_can('administrator')) {
        return false;
    }
    return $show;
});

/**
 * Helpher function to check if a place is open based on "HH:MM - HH:MM" string.
 * Returns: true (Open), false (Closed), or null (Unparseable/Always Open).
 */
function kikemonk_is_open($schedule_text) {
    if (empty($schedule_text)) return null;

    // Normalize text suitable for parsing
    $text = strtolower(trim($schedule_text));
    
    // Check if it matches "HH:MM - HH:MM" pattern
    if (preg_match('/(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})/', $text, $matches)) {
        $start_time = $matches[1];
        $end_time = $matches[2];
        
        // Get current time in WordPress timezone
        $current_timestamp = current_time('timestamp');
        $current_time_str = date('H:i', $current_timestamp);
        
        // Convert to comparable integers (minutes from midnight not needed if simple string compare works for HH:MM 24h)
        // Better to compare timestamps for the current day
        $today = date('Y-m-d', $current_timestamp);
        $start_ts = strtotime("$today $start_time");
        $end_ts = strtotime("$today $end_time");
        $now_ts = strtotime("$today $current_time_str");

        // Handle overnight hours (e.g. 21:00 - 03:00)
        if ($end_ts < $start_ts) {
            $end_ts += 86400; // Add 24 hours
            if ($now_ts < $start_ts) {
                 $now_ts += 86400; // If now is early morning (e.g. 01:00) and open (21:00), adjust now to next day frame locally
            }
        }

        return ($now_ts >= $start_ts && $now_ts <= $end_ts);
    }

    return null; // Could not parse, maybe "Abierto 24 horas" or complex text
}
