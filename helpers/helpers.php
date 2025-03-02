<?php
/**
 * Helper Functions
 *
 * This file contains standalone helper functions for the Storie plugin.
 * These functions provide utility methods that can be used throughout the plugin.
 *
 * @package StoryPostTypePlugin
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Add CORS headers to allow cross-origin requests
 *
 * Sets appropriate headers to enable cross-origin resource sharing (CORS).
 * This allows the REST API to be accessed from different domains.
 *
 * Note: This function should be hooked to 'send_headers' action, not 'init'.
 * The current implementation uses 'init' which is not ideal for header modifications.
 *
 * @since 1.0.0
 * @return void
 */
function add_cors_http_header() {
	// Get the origin of the request if available
	$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
	
	if ($origin) {
		// Set CORS headers for specific origin
		// For production, you should restrict to specific origins:
		// $allowed_origins = array('https://example.com', 'https://www.example.com');
		// if (in_array($origin, $allowed_origins)) {
		header("Access-Control-Allow-Origin: $origin");
		// }
	} else {
		// Allow all origins if specific origin not detected
		// Note: In production, it's more secure to specify allowed origins
		header("Access-Control-Allow-Origin: *");
	}
	
	// Add additional headers for better CORS support
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Headers: Authorization, Content-Type");
	
	// Handle preflight OPTIONS requests
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		status_header(200);
		exit;
	}
}

/**
 * Register the CORS header function with WordPress
 * 
 * Note: This should use the 'send_headers' hook instead of 'init'
 * for proper header handling in the WordPress request lifecycle.
 */
add_action('init', 'add_cors_http_header');