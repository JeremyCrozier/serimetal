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
        add_filter('nav_menu_item_title', array($this, 'blog_menu_item_title'), 30, 4);
        // Add class to menu item if icon or description is present
        add_filter('nav_menu_css_class', array($this, 'add_menu_item_class'), 10, 4);
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

        if ($depth !== 1) {
            return $title;
        }

        if (in_array('menu-item-type-post_type', $item->classes)) {
            $post_id = $item->object_id;

            $thumbnail = get_the_post_thumbnail($post_id, array('128', '84'), array('class' => 'menu-item-thumb'));
            $post_title = get_the_title($post_id);

            $output  = '';
            if ($thumbnail) {
                $output .= $thumbnail;
            }
            $output .= '<span class="menu-item-title">' . esc_html($post_title) . '</span>';
            $output .= '<i class="ph ph-caret-right"></i>';

            return $output;
        }

        return $title;
    }
}

