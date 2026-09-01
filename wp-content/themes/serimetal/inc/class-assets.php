<?php
/**
 * Gestion des assets du thème
 *
 * @package Serimetal
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Classe de gestion des assets
 */
class SM_Assets {
    /**
     * Instance unique de la classe
     *
     * @var SM_Assets
     */
    private static $instance = null;

    /**
     * Obtenir l'instance unique de la classe
     *
     * @return SM_Assets
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructeur
     */
    private function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_front_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Enqueue des assets front
     */
    public function enqueue_front_assets() {
        wp_enqueue_style('main', SM_THEME_URL . '/assets/main.min.css', array(), SM_THEME_VERSION);
        wp_enqueue_style('blocks', SM_THEME_URL . '/assets/blocks.min.css', array(), SM_THEME_VERSION);
        wp_enqueue_script('bundle', SM_THEME_URL . '/assets/main.js', array('jquery'), SM_THEME_VERSION, true);

        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
    }

    /**
     * Enqueue des assets admin
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style('admin', SM_THEME_URL . '/assets/adminStyles.min.css', array(), SM_THEME_VERSION);
        wp_enqueue_style('blocks', SM_THEME_URL . '/assets/blocks.min.css', array(), SM_THEME_VERSION);
        wp_enqueue_script('adminScript', SM_THEME_URL . '/assets/adminScript.js', array('wp-blocks', 'wp-dom-ready'), SM_THEME_VERSION, true);
    }
}
