<?php
/**
 * Gestion des blocs Gutenberg
 *
 * @package Serimetal
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Classe de gestion des blocs
 */
class SM_Blocks {
    /**
     * Instance unique de la classe
     *
     * @var SM_Blocks
     */
    private static $instance = null;

    /**
     * Obtenir l'instance unique de la classe
     *
     * @return SM_Blocks
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
        // Enregistrer les blocs ACF
        add_action('init', array($this, 'register_acf_blocks'), 5);
        // add_action('init', array($this, 'register_patterns'), 10);
        // add_action('init', array($this, 'register_pattern_categories'), 15);

        // Filtrer les blocs autorisés
        //add_filter('allowed_block_types_all', array($this, 'allowed_block_types'), 5, 2);

        //add_filter( 'render_block', array($this, 'render_block'), 5, 2);
        // Filtrer les arguments des blocs
        //add_filter('register_block_tySM_args', array($this, 'filter_block_tySM_args'), 10, 2);
    
    }

    /**
     * Enregistrer les blocs ACF
     */
    public function register_acf_blocks() {
        // register_block_type(__DIR__ . '/../blocks/icon');

        // Enregistrer le style pour l'image
        // register_block_style(
        //     'core/image',
        //     [
        //         'name'  => 'full-width',
        //         'label' => 'Largeur 100%'
        //     ]
        // );
    }

    /**
     * Enregistrer patterns
     */
    public function register_patterns() {
        $patterns_base = __DIR__ . '/../patterns';

        // register_block_pattern( 'Serimetal/hero-primary-hero', array(
        //     'title'       => 'Primary Hero',
        //     'description' => 'Primary Hero pour la page d\'accueil',
        //     'categories'  => array( 'hero' ),
        //     'keywords'    => array( 'hero', 'banner', 'header', 'en tête' ),
        //     'content'     => @file_get_contents( $patterns_base . '/hero/primary-hero.php' ),
        // ) );
    }

    /**
     * Enregistrer les catégories de patterns
     */
    function register_pattern_categories() {
        // register_block_pattern_category( 'hero', array(
        //     'label' => 'Hero',
        // ) );
    }

    /**
     * Définir les blocs autorisés
     *
     * @param array $allowed_blocks Liste des blocs autorisés.
     * @param object $editor_context Contexte de l'éditeur.
     * @return array
     */
    public function allowed_block_types($allowed_blocks, $editor_context) {

        return array(
            'wpforms/form-selector',
            'acf/icon',
            // 'core/legacy-widget',
            // 'core/widget-group',
            // 'core/archives',
            // 'core/avatar',
            // 'core/block',
            // 'core/button',
            // 'core/calendar',
            // 'core/categories',
            // 'core/comment-author-name',
            // 'core/comment-content',
            // 'core/comment-date',
            // 'core/comment-edit-link',
            // 'core/comment-reply-link',
            // 'core/comment-template',
            // 'core/comments',
            // 'core/comments-pagination',
            // 'core/comments-pagination-next',
            // 'core/comments-pagination-numbers',
            // 'core/comments-pagination-previous',
            // 'core/comments-title',
            // 'core/cover',
            // 'core/file',
            // 'core/footnotes',
            // 'core/gallery',
             'core/heading',
            // 'core/home-link',
            'core/image',
            // 'core/latest-comments',
            // 'core/latest-posts',
             'core/list',
            // 'core/loginout',
            // 'core/media-text',
            // 'core/navigation',
            // 'core/navigation-link',
            // 'core/navigation-submenu',
            // 'core/page-list',
            // 'core/page-list-item',
            'core/pattern',
            // 'core/post-author',
            // 'core/post-author-biography',
            // 'core/post-author-name',
            // 'core/post-comments-form',
            // 'core/post-content',
            // 'core/post-date',
            // 'core/post-excerpt',
            // 'core/post-featured-image',
            // 'core/post-navigation-link',
            // 'core/post-template',
            // 'core/post-terms',
            // 'core/post-title',
            // 'core/query',
            // 'core/query-no-results',
            // 'core/query-pagination',
            // 'core/query-pagination-next',
            // 'core/query-pagination-numbers',
            // 'core/query-pagination-previous',
            // 'core/query-title',
            // 'core/read-more',
            // 'core/rss',
            // 'core/search',
            // 'core/shortcode',
            // 'core/site-logo',
            // 'core/site-tagline',
            // 'core/site-title',
            //'core/social-link',
            // 'core/tag-cloud',
            // 'core/term-description',
            // 'core/audio',
            // 'core/buttons',
            // 'core/code',
            'core/column',
            'core/columns',
            // 'core/details',
            'core/embed',
            // 'core/freeform',
             'core/group',
            // 'core/html',
             'core/list-item',
            // 'core/missing',
            // 'core/more',
            // 'core/nextpage',
             'core/paragraph',
            // 'core/preformatted',
            // 'core/pullquote',
            // 'core/quote',
            // 'core/separator',
            // 'core/social-links',
             'core/spacer',
            // 'core/table',
            // 'core/text-columns',
            // 'core/verse',
            // 'core/video',
            // 'contact-form-7/contact-form-selector',
            // 'core/post-comments'
        );
    }

    public function filter_block_tySM_args($args, $block_type) {
        if ( 'core/image' === $block_type ) {
            unset($args['styles']);
        }
        return $args;
    }

    public function render_block($content, $block) {

        // Security
        if ( empty( $block['blockName'] ) || empty( $block['attrs'] ) ) {
            return $content;
        }

        return $content;
    }

}
