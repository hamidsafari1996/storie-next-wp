<?php
/**
 * Plugin Name: Story Post Type Plugin
 * Description: A plugin to create a custom post type for stories with gallery settings.
 * Version: 1.0
 * Author: Hamid Safari
 * Text Domain: text-domain
 * Domain Path: /languages
 *
 * @package StoryPostTypePlugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

// Define plugin constants for easy access to plugin paths and URLs
define('STORY_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('STORY_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required autoload files
require_once STORY_PLUGIN_PATH . "autoload.php";

// Include CMB2
require_once STORY_PLUGIN_PATH . "cmb2/init.php";

// Include required helper files if they exist
$helpers_file = STORY_PLUGIN_PATH . "helpers/helpers.php";
if (file_exists($helpers_file)) {
    require_once $helpers_file;
}

/**
 * Initialize the plugin components
 */
function storie_plugin_init() {
    // Initialize post type
    if (class_exists('Storie\\Storie_Post_Type')) {
        Storie\Storie_Post_Type::init();
    }
    
    // Initialize metaboxes
    if (class_exists('Storie\\Metabox')) {
        Storie\Metabox::init();
    }
    
    // Initialize REST API
    if (class_exists('Storie\\REST_API')) {
        Storie\REST_API::init();
    }
}

// Hook into WordPress init to initialize our plugin components
add_action('init', 'storie_plugin_init', 0);

/**
 * Register plugin activation hook
 */
register_activation_hook(__FILE__, 'storie_plugin_activate');

/**
 * Plugin activation function
 */
function storie_plugin_activate() {
    // Make sure post types are registered
    if (class_exists('Storie\\Storie_Post_Type')) {
        Storie\Storie_Post_Type::register_story_post_type();
    }
    
    // Flush rewrite rules to make custom post type URLs work
    flush_rewrite_rules();
}

/**
 * Register plugin deactivation hook
 */
register_deactivation_hook(__FILE__, 'storie_plugin_deactivate');

/**
 * Plugin deactivation function
 */
function storie_plugin_deactivate() {
    // Flush rewrite rules to remove custom post type URLs
    flush_rewrite_rules();
}