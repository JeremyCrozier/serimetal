<?php
/**
 * Classe Menu pour personnaliser les menus
 *
 * @package Serimetal
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Classe SM_Menu
 */
class SM_Menu {
    /**
     * Instance unique de la classe
     *
     * @var SM_Menu
     */
    private static $instance = null;

    /**
     * Obtenir l'instance unique de la classe
     *
     * @return SM_Menu
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
        // Add icon and description to menu items
        add_filter('nav_menu_item_title', array($this, 'add_menu_item_icon_and_description'), 10, 4);
        add_filter('nav_menu_item_title', array($this, 'add_menu_item_icon_cta_button'), 20, 4);
        add_filter('nav_menu_item_title', array($this, 'blog_menu_item_title'), 30, 4);
        // Add class to menu item if icon or description is present
        add_filter('nav_menu_css_class', array($this, 'add_menu_item_class'), 10, 4);
    }

    /**
     * Ajoute l'icône et la description à un élément de menu
     * Appliqué uniquement pour le menu 'menu'
     *
     * @param string $title Le titre de l'élément de menu
     * @param object $item L'objet de l'élément de menu
     * @param array $args Les arguments du menu
     * @param int $depth La profondeur de l'élément dans le menu
     * 
     * @return string Le titre modifié avec l'icône et la description
     */
    public function add_menu_item_icon_and_description($title, $item, $args, $depth) {

        if (!$depth == 2) {
            return $title;
        }

        $icon = get_field('icon', $item->ID);
        $description = !empty($item->description) ? $item->description : '';

        if (!$icon && !$description) {
            return $title;
        }

        $output = '';

        if (!empty($icon)) {
            $icon_file = get_file_icon( $icon );
            
            if ($icon_file) {
                $output .= '<div class="menu-item-icon">';
                $output .= $icon_file;
                $output .= '</div>';
            }
        }

        $output .= '<div class="menu-item-content">';
        $output .= '<span class="menu-item-title">' . $title . '</span>';

        if (!empty($description)) {
            $output .= '<span class="menu-item-description body-2">' . esc_html($description) . '</span>';
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Ajoute une classe CSS à l'item de menu si l'icône ou la description est présente
     *
     * @param array $classes Les classes CSS de l'item de menu
     * @param object $item L'objet de l'élément de menu
     * @param array $args Les arguments du menu
     * @param int $depth La profondeur de l'élément dans le menu
     * 
     * @return array Les classes CSS modifiées
     */
    public function add_menu_item_class($classes, $item, $args, $depth) {
        // Apply only to depth 2 (as per the title filter condition)
        if ($depth != 2) {
            return $classes;
        }

        // Get icon and description
        $icon = get_field('icon', $item->ID);
        $description = !empty($item->description) ? $item->description : '';

        // Add class if icon or description is present
        if (!empty($icon) || !empty($description)) {
            $classes[] = 'menu-item-has-icon-or-description';
        }

        return $classes;
    }

    /**
     * Ajoute l'icône et la description à un élément de menu
     * Appliqué uniquement pour le menu 'menu'
     *
     * @param string $title Le titre de l'élément de menu
     * @param object $item L'objet de l'élément de menu
     * @param array $args Les arguments du menu
     * @param int $depth La profondeur de l'élément dans le menu
     * 
     * @return string Le titre modifié avec l'icône et la description
     */
    public function add_menu_item_icon_cta_button($title, $item, $args, $depth) {

        // only if the item has the class is-style-default or is-style-outline
        if (!in_array('is-style-default', $item->classes) && !in_array('is-style-outline', $item->classes)) {
            return $title;
        }

        $icon = get_field('icon', $item->ID);

        if (!$icon) {
            return $title;
        }

        $output = $title;

        if (!empty($icon)) {
            $icon_file = get_file_icon( $icon );
            
            if ($icon_file) {
                $output .= '<span class="button-icon">';
                $output .= $icon_file;
                $output .= '</span>';
            }
        }

        return $output;
    }

    /**
     * Ajoute le titre de l'élément de menu pour le blog
     *
     * @param string $title Le titre de l'élément de menu
     * @param object $item L'objet de l'élément de menu
     * @param array $args Les arguments du menu
     * @param int $depth La profondeur de l'élément dans le menu
     * 
     * @return string Le titre modifié
     */
    public function blog_menu_item_title($title, $item, $args, $depth) {

        if ($depth != 2) {
            return $title;
        }

        if (in_array('menu-item-type-post_type', $item->classes)) {
            $post_id = $item->object_id;

            if ($item->object !== 'post') {
                return $title;
            }

            $thumbnail = get_the_post_thumbnail($post_id, array('139', '93'), array('class' => 'menu-item-thumb'));
            $post_title = get_the_title($post_id);
            $reading_time = get_post_meta($post_id, '_yoast_wpseo_estimated-reading-time-minutes', true);

            $meta_html = '';
            if (!empty($reading_time)) {
                $meta_html = '<span class="menu-item-description body-2">'.esc_html($reading_time).' min de lecture</span>';
            }

            $output  = '';
            if ($thumbnail) {
                $output .= '<span class="menu-item-thumb-wrap">'.$thumbnail.'</span>';
            }
            $output .= '<div class="menu-item-content">';
            $output .= '<span class="menu-item-title">' . esc_html($post_title) . '</span>';
            if ($meta_html) {
                $output .= $meta_html;
            }
            $output .= '</div>';

            return $output;
        }

        return $title;
    }
}

