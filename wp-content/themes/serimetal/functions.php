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
require_once SM_THEME_DIR . '/inc/class-ajax.php';
require_once SM_THEME_DIR . '/inc/class-menu.php';

// Initialiser le thème
SM_Setup::get_instance();
SM_Assets::get_instance();
SM_Blocks::get_instance();
SM_Ajax::get_instance();
SM_Menu::get_instance();

// Désactiver l'API XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );
