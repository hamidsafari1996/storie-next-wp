<?php
/**
 * Custom Metaboxes
 *
 * This file defines custom metaboxes for the Storie plugin using CMB2.
 * It adds gallery functionality to stories and subtitle/logo fields to posts.
 *
 * @package Storie
 * @since 1.0.0
 * @requires CMB2
 */

namespace Storie;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Metabox
 *
 * Handles registration and configuration of custom metaboxes using CMB2.
 * This class provides methods to create metaboxes for stories and posts.
 *
 * @package Storie
 */
class Metabox {
	/**
	 * Initialize the metaboxes
	 *
	 * Hooks into WordPress to register custom metaboxes using CMB2.
	 * This method is called from the main plugin file.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {
		add_action( 'cmb2_admin_init', array( __CLASS__, 'add_story_gallery_metabox' ) );
	}

	/**
	 * Add gallery metabox to the Story post type
	 *
	 * Creates a metabox with a file list field for uploading multiple images
	 * to create a gallery for Story posts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_story_gallery_metabox() {
		// Create a new metabox for the story gallery
		$cmb = new_cmb2_box( array(
			'id'           => 'story_gallery_metabox',    // Unique ID for the metabox
			'title'        => __( 'Story Gallery', 'cmb2' ), // Title displayed on the metabox
			'object_types' => array( 'story' ),           // Post type this metabox applies to
			'context'      => 'normal',                   // Where the metabox appears (normal, side, advanced)
			'priority'     => 'high',                     // Priority within the context
			'show_names'   => true,                       // Show field names on the left
		));

		// Add a file list field for gallery images
		$cmb->add_field( array(
			'name'         => __( 'Gallery Images', 'cmb2' ), // Field label
			'desc'         => __( 'Upload or select images for the gallery', 'cmb2' ), // Field description
			'id'           => 'story_gallery',            // Field ID used to save/retrieve data
			'type'         => 'file_list',                // Field type for multiple files
			'preview_size' => array(100, 100),            // Size of image previews
			'query_args'   => array( 'type' => 'image' ), // Limit to image files only
			'text'         => array(
				'add_upload_files_text' => __( 'Add or Upload Images', 'cmb2' ), // Custom button text
			),
		));
	}
}