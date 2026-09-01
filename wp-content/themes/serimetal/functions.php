<?php
/**
 * Fichier principal du thème Serimetal
 *
 * @package Serimetal
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

define('SM_THEME_VERSION', wp_get_theme()->get('Version'));
define('SM_THEME_DIR', get_template_directory());
define('SM_THEME_URL', get_template_directory_uri());

/**
 * Initialiser le thème
 */

require_once SM_THEME_DIR . '/inc/class-setup.php';
require_once SM_THEME_DIR . '/inc/class-assets.php';
require_once SM_THEME_DIR . '/inc/class-blocks.php';
require_once SM_THEME_DIR . '/inc/class-menu.php';

// Initialiser le thème
SM_Setup::get_instance();
SM_Assets::get_instance();
SM_Blocks::get_instance();
SM_Menu::get_instance();

function get_file_icon($file_url) {
    // Create cache key based on file URL
    $cache_key = 'SM_icon_' . md5($file_url);
    
    // Try to get cached content
    $cached_content = get_transient($cache_key);
    
    if ($cached_content !== false) {
        return $cached_content;
    }
    
    // Cache not found, fetch the icon
    $icon_content = '';
    
    if (WP_ENVIRONMENT_TYPE !== 'staging') {
        $icon_content = @file_get_contents((string) $file_url);
    } else {
        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode(USERNAME . ":" . PASSWORD)
            )
        );
        $response = wp_remote_get($file_url, $args);

        if (is_wp_error($response)) {
            error_log($response->get_error_message());
            $icon_content = '';
        } else {
            $icon_content = wp_remote_retrieve_body($response);
        }
    }
    
    // Cache the content for 7 days (604800 seconds)
    // Only cache if we got valid content
    if (!empty($icon_content)) {
        set_transient($cache_key, $icon_content, 7 * DAY_IN_SECONDS);
    }
    
    return $icon_content;
}

// Désactiver l'API XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );
