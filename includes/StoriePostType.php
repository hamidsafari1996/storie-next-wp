<?php
/**
 * Story Post Type Registration
 *
 * This file defines the custom post type 'story' for the Storie plugin.
 * It handles registration of the post type with appropriate labels and settings.
 *
 * @package Storie
 * @since 1.0.0
 */

namespace Storie;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Storie_Post_Type
 *
 * Handles registration and configuration of the 'story' custom post type.
 * This class provides methods to initialize and register the post type with WordPress.
 *
 * @package Storie
 */
class Storie_Post_Type {
	/**
	 * Initialize the post type
	 *
	 * Hooks into WordPress to register the custom post type.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_story_post_type' ) );
	}

	/**
	 * Register the 'story' custom post type
	 *
	 * Creates a new post type with custom labels, supports, and settings.
	 * The post type is public, has archives, and supports REST API.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register_story_post_type() {
		// Define custom labels for the post type
		$labels = array(
			'name'               => __('Stories', 'text-domain'),               // General name for the post type
			'singular_name'      => __('Story', 'text-domain'),                 // Name for one object of this post type
			'add_new'            => __('Add New', 'text-domain'),               // The add new text
			'add_new_item'       => __('Add New Story', 'text-domain'),         // The add new item text
			'edit_item'          => __('Edit Story', 'text-domain'),            // The edit item text
			'new_item'           => __('New Story', 'text-domain'),             // The new item text
			'view_item'          => __('View Story', 'text-domain'),            // The view item text
			'search_items'       => __('Search Stories', 'text-domain'),        // The search items text
			'not_found'          => __('No stories found', 'text-domain'),      // The not found text
			'not_found_in_trash' => __('No stories found in Trash', 'text-domain'), // The not found in trash text
			'menu_name'          => __('Stories', 'text-domain'),               // The menu name text
		);

		// Register the custom post type with WordPress
		register_post_type(
			'story',                                 // Post type name/key
			array(
				'labels'            => $labels,      // Custom labels from above
				'public'            => true,         // Make it publicly accessible
				'has_archive'       => true,         // Enable post type archives
				'rewrite'           => array( 'slug' => 'stories' ), // Custom permalink structure
				'supports'          => array(        // Features this post type supports
					'title',                         // Post title
					'editor',                        // Post content
					'thumbnail',                     // Featured images
					'excerpt'                        // Post excerpts
				),
				'show_in_rest'      => true,         // Enable Gutenberg editor & REST API
				'menu_icon'         => 'dashicons-book-alt', // Icon in admin menu
				'menu_position'     => 5,            // Position in admin menu (below Posts)
				'capability_type'   => 'post',       // Use same capabilities as regular posts
				'publicly_queryable' => true,        // Allow queries on the frontend
				'show_in_menu'      => true,         // Show in admin menu
			)
		);
	}
}

// Don't call init() here - it should be called from the main plugin file
// This prevents duplicate initialization and allows the autoloader to work properly
// Storie_Post_Type::init();