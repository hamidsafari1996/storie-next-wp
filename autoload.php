<?php
/**
 * Custom autoloader for the Storie plugin
 *
 * This autoloader handles classes in the Storie namespace and loads them
 * from the includes directory, supporting multiple file naming conventions.
 *
 * @package Storie
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Autoloader function for Storie plugin classes
 *
 * @param string $class_name The fully qualified class name to load
 * @return bool True if the class was loaded, false otherwise
 */
function storie_autoloader($class_name) {
    // Only handle classes in the Storie namespace
    if (strpos($class_name, 'Storie\\') !== 0) {
        return false;
    }

    // Remove the namespace prefix
    $bare_class_name = str_replace('Storie\\', '', $class_name);
    
    // Debug information
    $debug = defined('WP_DEBUG') && WP_DEBUG;
    if ($debug) {
        error_log("Storie Autoloader: Attempting to load class {$bare_class_name}");
    }

    // Try multiple file naming conventions
    $file_paths = [
        // 1. Exact match (Storie_Post_Type.php)
        STORY_PLUGIN_PATH . 'includes/' . $bare_class_name . '.php',
        
        // 2. No underscores (StoriePostType.php)
        STORY_PLUGIN_PATH . 'includes/' . str_replace('_', '', $bare_class_name) . '.php',
        
        // 3. Lowercase with hyphens (storie-post-type.php)
        STORY_PLUGIN_PATH . 'includes/' . str_replace('_', '-', strtolower($bare_class_name)) . '.php',
        
        // 4. Lowercase with underscores (storie_post_type.php)
        STORY_PLUGIN_PATH . 'includes/' . strtolower($bare_class_name) . '.php'
    ];

    // Try each possible file path
    foreach ($file_paths as $file_path) {
        if (file_exists($file_path)) {
            if ($debug) {
                error_log("Storie Autoloader: Found and loading {$file_path}");
            }
            require_once $file_path;
            return true;
        }
    }

    if ($debug) {
        error_log("Storie Autoloader: Could not find any file for class {$bare_class_name}");
        error_log("Storie Autoloader: Tried the following paths: " . implode(', ', $file_paths));
    }
    
    return false;
}

// Register the autoloader
spl_autoload_register('storie_autoloader');

/**
 * Helper function to check if all required classes are available
 *
 * @return bool True if all required classes are available, false otherwise
 */
function storie_check_required_classes() {
    $required_classes = [
        'Storie\\Storie_Post_Type',
        'Storie\\Metabox',
        'Storie\\REST_API'
    ];
    
    $missing_classes = [];
    
    foreach ($required_classes as $class) {
        if (!class_exists($class)) {
            $missing_classes[] = $class;
        }
    }
    
    if (!empty($missing_classes)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("Storie Plugin: Missing required classes: " . implode(', ', $missing_classes));
        }
        return false;
    }
    
    return true;
}