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
		add_filter( 'request', array( $this, 'keep_page_with_realisation_param' ) );
		add_filter( 'redirect_canonical', array( $this, 'keep_realisation_param' ), 10, 2 );
		add_action( 'wp_footer', array( $this, 'render_realisation_modal' ) );
		add_action( 'wp_ajax_serimetal_realisations', array( $this, 'filter_realisations' ) );
		add_action( 'wp_ajax_nopriv_serimetal_realisations', array( $this, 'filter_realisations' ) );
	}

	/**
	 * Keep the current page when the modal is opened with ?realisation=slug.
	 *
	 * The post type query var would otherwise replace the main query.
	 *
	 * @param array $vars Main query vars.
	 * @return array
	 */
	public function keep_page_with_realisation_param( $vars ) {
		if ( is_admin() || empty( $vars['realisation'] ) ) {
			return $vars;
		}

		$slug = $vars['realisation'];

		if ( isset( $vars['post_type'], $vars['name'] ) && 'realisation' === $vars['post_type'] && (string) $vars['name'] === (string) $slug ) {
			unset( $vars['post_type'], $vars['name'] );
		}

		unset( $vars['realisation'] );

		return $vars;
	}

	/**
	 * Do not redirect a modal URL to a single permalink.
	 *
	 * @param string|false $redirect  Redirect target.
	 * @param string       $requested Requested URL.
	 * @return string|false
	 */
	public function keep_realisation_param( $redirect, $requested ) {
		unset( $requested );

		if ( isset( $_GET['realisation'] ) && '' !== $_GET['realisation'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return false;
		}

		return $redirect;
	}

	/**
	 * Print the empty realisation modal in the footer.
	 *
	 * Card clicks fill this model in the browser.
	 */
	public function render_realisation_modal() {
		if ( is_admin() ) {
			return;
		}

		get_template_part( 'template-parts/modal', 'realisation' );
	}

	/**
	 * Normalised map point for a realisation location field.
	 *
	 * @param mixed $location ACF google map value.
	 * @return array{lat: string, lng: string, label: string}|array{}
	 */
	public static function realisation_coordinates( $location ) {
		if ( ! is_array( $location ) || ! isset( $location['lat'], $location['lng'] ) || '' === (string) $location['lat'] || '' === (string) $location['lng'] ) {
			return array();
		}

		$lat    = (float) $location['lat'];
		$lng    = (float) $location['lng'];
		$label  = number_format( abs( $lat ), 6, '.', '' ) . ( $lat >= 0 ? 'N' : 'S' );
		$label .= ', ' . number_format( abs( $lng ), 6, '.', '' ) . ( $lng >= 0 ? 'E' : 'W' );

		return array(
			'lat'   => (string) $location['lat'],
			'lng'   => (string) $location['lng'],
			'label' => $label,
		);
	}

	/**
	 * Gallery image URLs for the modal.
	 *
	 * The featured image stays on the card background. The script reuses that
	 * URL when this list is empty.
	 *
	 * @param int $post_id Realisation ID.
	 * @return string[]
	 */
	private static function realisation_gallery_urls( $post_id ) {
		$images = get_field( 'images', $post_id );
		$urls   = array();

		if ( ! is_array( $images ) ) {
			return $urls;
		}

		foreach ( $images as $image ) {
			$image_id = is_array( $image ) ? (int) ( $image['ID'] ?? ( $image['id'] ?? 0 ) ) : (int) $image;

			if ( ! $image_id ) {
				continue;
			}

			$url = wp_get_attachment_image_url( $image_id, 'large' );

			if ( $url ) {
				$urls[] = $url;
			}
		}

		return array_slice( array_values( array_unique( $urls ) ), 0, 3 );
	}

	/**
	 * Data attributes consumed by the realisation modal.
	 *
	 * Title and excerpt are already printed in the card. Duration and date are
	 * printed on the full card, so they are added here only for the taxonomy card.
	 *
	 * @param bool $include_facts Whether to add duration and date attributes.
	 */
	public static function realisation_modal_attributes( $include_facts = false ) {
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return;
		}

		$attributes = array(
			'data-realisation' => (string) get_post_field( 'post_name', $post_id ),
		);

		$content = get_post_field( 'post_content', $post_id );
		$content = is_string( $content ) ? trim( $content ) : '';

		if ( '' !== $content ) {
			$content = trim( (string) apply_filters( 'the_content', $content ) );

			if ( '' !== trim( wp_strip_all_tags( $content ) ) ) {
				$attributes['data-text'] = $content;
			}
		}

		if ( function_exists( 'get_field' ) ) {
			$point = self::realisation_coordinates( get_field( 'location', $post_id ) );

			if ( $point ) {
				$attributes['data-lat']    = $point['lat'];
				$attributes['data-lng']    = $point['lng'];
				$attributes['data-coords'] = $point['label'];
			}

			$urls = self::realisation_gallery_urls( $post_id );

			if ( $urls ) {
				$attributes['data-images'] = wp_json_encode( $urls );
			}

			if ( $include_facts ) {
				$duration = get_field( 'duration', $post_id );
				$date     = get_field( 'date', $post_id );

				if ( $duration ) {
					$attributes['data-duration'] = (string) $duration;
				}

				if ( $date ) {
					$attributes['data-date'] = (string) $date;
				}
			}
		}

		foreach ( $attributes as $name => $value ) {
			echo ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
		}
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
