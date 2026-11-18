<?php
/**
 * Ajax requests for the theme.
 *
 * @package Serimetal
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class SM_Ajax
 */
class SM_Ajax {
	/**
	 * Unique instance.
	 *
	 * @var SM_Ajax
	 */
	private static $instance = null;

	/**
	 * Get the unique instance.
	 *
	 * @return SM_Ajax
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Register ajax actions.
	 */
	private function setup_hooks() {
		add_action( 'wp_ajax_serimetal_realisations', array( $this, 'filter_realisations' ) );
		add_action( 'wp_ajax_nopriv_serimetal_realisations', array( $this, 'filter_realisations' ) );
	}

	/**
	 * Pole term color used by the realisation filters and cards.
	 *
	 * @param WP_Term|int $term Term object or term ID.
	 * @return string
	 */
	public static function pole_color( $term ) {
		$term_id = $term instanceof WP_Term ? $term->term_id : absint( $term );

		if ( ! $term_id || ! function_exists( 'get_field' ) ) {
			return '';
		}

		$color = get_field( 'page_color', 'pole_' . $term_id );

		return sanitize_hex_color( (string) $color ) ?: '';
	}

	/**
	 * Render every realisation card for a pole term.
	 *
	 * A term ID of 0 returns every realisation.
	 *
	 * @param int $term_id Pole term ID.
	 * @return string
	 */
	public static function realisation_cards_html( $term_id ) {
		$term_id = absint( $term_id );
		$args    = array(
			'post_type'           => 'realisation',
			'post_status'         => 'publish',
			'posts_per_page'      => -1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
		);

		if ( $term_id ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'pole',
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			);
		}

		$query = new WP_Query( $args );

		ob_start();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/card', 'realisations' );
			}
		} else {
			echo '<li class="realisation-card realisation-card--empty"><p>Aucune réalisation pour ce pôle.</p></li>';
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Return the realisation cards for the requested pole.
	 */
	public function filter_realisations() {
		check_ajax_referer( 'serimetal_realisations', 'nonce' );

		$term_id = isset( $_POST['term'] ) ? absint( wp_unslash( $_POST['term'] ) ) : 0;

		if ( $term_id && ! term_exists( $term_id, 'pole' ) ) {
			wp_send_json_error(
				array(
					'message' => 'Terme invalide',
				),
				400
			);
		}

		wp_send_json_success(
			array(
				'html'  => self::realisation_cards_html( $term_id ),
				'color' => $term_id ? self::pole_color( $term_id ) : '',
			)
		);
	}
}
