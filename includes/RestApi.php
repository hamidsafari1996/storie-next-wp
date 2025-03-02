<?php
/**
 * REST API Extensions
 *
 * This file extends the WordPress REST API with custom fields and endpoints
 * for the Storie plugin. It adds support for custom fields, images, and CORS.
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
 * Class REST_API
 *
 * Extends the WordPress REST API with custom fields and functionality
 * for the Storie plugin. Handles registration of custom fields, image support,
 * and cross-origin resource sharing (CORS) headers.
 *
 * @package Storie
 */
class REST_API {
	/**
	 * Initialize the REST API extensions
	 *
	 * Registers all hooks needed for extending the WordPress REST API.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_api_fields' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'add_thumbnail_to_rest_api' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'add_excerpt_to_rest_api' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'add_category_name_to_rest_api' ) );
		add_action( 'send_headers', array( __CLASS__, 'add_cors_http_header' ) );
	}

	/**
	 * Register custom fields for the REST API
	 *
	 * Adds custom fields to various object types in the REST API.
	 * Each field is registered with a get callback to retrieve the data.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register_api_fields() {
		// Define fields to register in format: [object_type, field_name, callback_method]
		$fields = array(
			array( 'story', 'gallery_images', 'get_story_gallery_images' ),
			array( 'post', 'author_name', 'get_post_author_name' ),
			array( 'post', 'author_avatar', 'get_post_author_avatar' ),
			array( 'user', 'full_name', 'get_user_full_name' ),
		);

		// Register each field with the REST API
		foreach ( $fields as $field ) {
			register_rest_field( $field[0], $field[1], array(
				'get_callback'    => array( __CLASS__, $field[2] ),
				'update_callback' => null,
				'schema'          => null,
			));
		}
	}

	/**
	 * Get gallery images for a story
	 *
	 * Retrieves the gallery images associated with a story post.
	 *
	 * @since 1.0.0
	 * @param array $object The object data from the REST API
	 * @return array Array of gallery images or empty array if none found
	 */
	public static function get_story_gallery_images( $object ) {
		$gallery = get_post_meta( $object['id'], 'story_gallery', true );
		return !empty( $gallery ) ? $gallery : [];
	}

	/**
	 * Get author name for a post
	 *
	 * Retrieves the display name of the post author.
	 *
	 * @since 1.0.0
	 * @param array $object The object data from the REST API
	 * @return string The author display name or empty string if not found
	 */
	public static function get_post_author_name( $object ) {
		if (!isset($object['author'])) {
			return '';
		}
		$author_id = $object['author'];
		return get_the_author_meta( 'display_name', $author_id );
	}

	/**
	 * Get author avatar URL for a post
	 *
	 * Retrieves the avatar URL for the post author.
	 *
	 * @since 1.0.0
	 * @param array $object The object data from the REST API
	 * @return string The avatar URL or empty string if not found
	 */
	public static function get_post_author_avatar( $object ) {
		if (!isset($object['author'])) {
			return '';
		}
		return get_avatar_url( $object['author'], array('size' => 96) );
	}

	/**
	 * Get full name for a user
	 *
	 * Combines the first and last name of a user.
	 *
	 * @since 1.0.0
	 * @param array $object The object data from the REST API
	 * @return string The user's full name or empty string if not found
	 */
	public static function get_user_full_name( $object ) {
		if (!isset($object['id'])) {
			return '';
		}
		$first_name = get_user_meta( $object['id'], 'first_name', true );
		$last_name  = get_user_meta( $object['id'], 'last_name', true );
		return trim( "$first_name $last_name" );
	}

	/**
	 * Add featured image URL to REST API response
	 *
	 * Registers a custom field to include the featured image URL in the REST API.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_thumbnail_to_rest_api() {
		$types = array('story', 'post');
		foreach ( $types as $type ) {
			register_rest_field( $type, 'featured_image_src', array(
				'get_callback'    => function( $post ) {
					if (!isset($post['id'])) {
						return null;
					}
					$thumbnail_id = get_post_thumbnail_id( $post['id'] );
					if (!$thumbnail_id) return null;
					
					$image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
					return $image ? $image[0] : null;
				},
				'update_callback' => null,
				'schema'          => null,
			));
		}
	}

	/**
	 * Add excerpt to REST API response
	 *
	 * Registers a custom field to include the post excerpt in the REST API.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_excerpt_to_rest_api() {
		register_rest_field( 'post', 'excerpt', array(
			'get_callback'    => function( $post ) {
				if (!isset($post['id'])) {
					return '';
				}
				$excerpt = get_the_excerpt( $post['id'] );
				return $excerpt ? $excerpt : '';
			},
			'update_callback' => null,
			'schema'          => null,
		));
	}

	/**
	 * Add category name to REST API response
	 *
	 * Registers a custom field to include the primary category name in the REST API.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_category_name_to_rest_api() {
		register_rest_field( 'post', 'category_name', array(
			'get_callback'    => function( $post ) {
				if (!isset($post['id'])) {
					return '';
				}
				$categories = get_the_category( $post['id'] );
				return !empty( $categories ) ? $categories[0]->name : '';
			},
			'update_callback' => null,
			'schema'          => null,
		));
	}

	/**
	 * Add CORS headers to allow cross-origin requests
	 *
	 * Sets appropriate headers to enable cross-origin resource sharing (CORS).
	 * This allows the REST API to be accessed from different domains.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_cors_http_header() {
		// Check if the function exists before calling it
		if (!function_exists('get_http_origin')) {
			header("Access-Control-Allow-Origin: *");
			return;
		}
		
		// Get the origin of the request
		$origin = get_http_origin();
		if ($origin) {
			// Set CORS headers for specific origin
			header("Access-Control-Allow-Origin: $origin");
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
			header("Access-Control-Allow-Credentials: true");
			header("Access-Control-Allow-Headers: Authorization, Content-Type");
		} else {
			// Allow all origins if specific origin not detected
			header("Access-Control-Allow-Origin: *");
		}
		
		// Handle preflight OPTIONS requests
		if (isset($_SERVER['REQUEST_METHOD']) && 'OPTIONS' === $_SERVER['REQUEST_METHOD']) {
			if (function_exists('status_header')) {
				status_header(200);
			} else {
				header("HTTP/1.1 200 OK");
			}
			exit();
		}
	}
}