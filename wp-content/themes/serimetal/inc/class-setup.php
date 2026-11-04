<?php
/**
 * Setup theme
 *
 * @package Serimetal
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

class SM_Setup {
    /**
     * Instance unique de la classe
     *
     * @var SM_Setup
     */
    private static $instance = null;

    /**
     * Obtenir l'instance unique de la classe
     *
     * @return SM_Setup
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
        $this->setup_hooks();
    }

    /**
     * Configurer les hooks
     */
    private function setup_hooks() {
        // Configuration du thème
        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('after_setup_theme', array($this, 'add_image_sizes'));
        add_filter('wpcf7_autop_or_not', '__return_false');
        
        // Support SVG
        add_filter('upload_mimes', array($this, 'add_svg_support'));

        // Page color as a CSS custom property
        add_action('wp_head', array($this, 'output_page_color'));
        add_filter('block_editor_settings_all', array($this, 'add_page_color_editor_style'), 10, 2);

        // For dev
        if ( wp_get_environment_type() === 'local' ) {
            // Only allow fields to be edited on development
            //add_filter( 'acf/settings/show_admin', '__return_false' );
            do_action( 'qm/info', wp_get_registered_image_subsizes() );
        }
    }

    /**
     * Configuration du thème
     */
    public function theme_setup() {
        /* menu */
        add_theme_support('menus');
        /* tag-title */
        add_theme_support('title-tag');
        /* Thumbnails */
        add_theme_support('post-thumbnails');
        /* styles de blocs */
        add_theme_support('wp-block-styles');
        // Ajouter le support pour l'éditeur de template parts
        add_theme_support('block-template-parts');
        // Ajouter le support pour l'édition complète du site
        add_theme_support('template-editing');

        add_theme_support('block-patterns');

        remove_theme_support('core-block-patterns');
    }

    /**
     * Ajouter le support SVG
     *
     * @param array $mimes Types MIME.
     * @return array
     */
    public function add_svg_support($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Resolved page color, falling back to the theme blue.
     *
     * @param int $post_id Post ID. 0 uses the current post.
     * @return string Hex color.
     */
    private function get_page_color($post_id = 0) {
        $color = '#0874f9';

        if (!function_exists('get_field')) {
            return $color;
        }

        $page_color = $post_id ? get_field('page_color', $post_id) : get_field('page_color');

        if (is_string($page_color)) {
            $page_color = sanitize_hex_color($page_color);

            if ($page_color) {
                $color = $page_color;
            }
        }

        return $color;
    }

    /**
     * Print the ACF page color as --page-color.
     */
    public function output_page_color() {
        printf(
            "<style>:root{--page-color:%s;}</style>\n",
            esc_attr($this->get_page_color())
        );
    }

    /**
     * Expose --page-color inside the block editor canvas.
     *
     * @param array                   $settings Editor settings.
     * @param WP_Block_Editor_Context $context  Editor context.
     * @return array
     */
    public function add_page_color_editor_style($settings, $context) {
        $post_id = 0;

        if ($context instanceof WP_Block_Editor_Context && isset($context->post->ID)) {
            $post_id = (int) $context->post->ID;
        }

        if (!isset($settings['styles']) || !is_array($settings['styles'])) {
            $settings['styles'] = array();
        }

        $settings['styles'][] = array(
            'css' => sprintf(':root{--page-color:%s;}', $this->get_page_color($post_id)),
        );

        return $settings;
    }

    public function add_image_sizes() {
        add_image_size('icon', 24, 24, true);
        // add_image_size('vertical-slider', 536, 536, true);
        // add_image_size('featured-post', 636, 440, true);
        // add_image_size('thumbnail-post', 400, 200, true);
    }
}