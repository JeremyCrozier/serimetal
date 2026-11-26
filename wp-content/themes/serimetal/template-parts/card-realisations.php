<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Realisation card.
 *
 * The photo is the link background. The active pole color is the bottom border.
 *
 * @package Serimetal
 */

$content    = get_the_content();
$date       = get_field( 'date', get_the_ID() );
$duration   = get_field( 'duration', get_the_ID() );
$location   = get_field( 'location', get_the_ID() );
$background = get_the_post_thumbnail_url( get_the_ID(), 'large' );
$point      = SM_Ajax::realisation_coordinates( $location );
$coords     = $point ? $point['label'] : '';
$map_url    = '';

if ( $coords ) {
	$map_url = 'https://maps.google.com/maps?width=100%&amp;height=100%&amp;hl=en&amp;q=' . rawurlencode( $coords ) . '&amp;t=&amp;z=8&amp;ie=UTF8&amp;iwloc=B&amp;output=embed&amp;size=400x170';
}

$has_facts = $duration || $date;
$has_content = $content && strlen( trim( $content ) ) > 0;
?>

<li class="realisation-card">
	<a class="realisation-card__link" 
		href="<?php echo esc_url( get_permalink() ); ?>"
		<?php SM_Ajax::realisation_modal_attributes(); ?>
		<?php echo $background ? ' style="background-image: url(' . esc_url( $background ) . ');"' : ''; ?>
	>
		<span class="realisation-card__overlay" aria-hidden="true"></span>

		<div class="realisation-card__main">
			<div class="realisation-card__intro">
				<h3 class="heading-medium"><?php the_title(); ?></h3>
				<?php if ( $has_content ) : ?>
					<p class="realisation-card__content"><?php echo wp_kses_post( $content ); ?></p>
				<?php endif; ?>
			</div>
			<span class="btn-ghost-secondary">
				<?php echo esc_html( 'En savoir plus' ); ?>
				<i class="ph ph-plus" aria-hidden="true"></i>
			</span>
		</div>

		<?php if ( $has_facts || $coords ) : ?>
			<div class="realisation-card__aside">
				<?php if ( $has_facts ) : ?>
					<div class="realisation-card__facts">
						<?php if ( $duration ) : ?>
							<p class="realisation-card__fact">
								<span class="realisation-card__label">Durée</span>
								<span class="realisation-card__value heading-small"><?php echo esc_html( $duration ); ?></span>
							</p>
						<?php endif; ?>
						<?php if ( $date ) : ?>
							<p class="realisation-card__fact">
								<span class="realisation-card__label">Livré le</span>
								<span class="realisation-card__value heading-small"><?php echo esc_html( $date ); ?></span>
							</p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $coords ) : ?>
					<div class="realisation-card__map">
						<div class="map-overlay" aria-hidden="true"></div>
						<iframe id="map"
						title="Carte"
						class="map_part"
						width="400"
						height="170"
						loading="lazy"
						frameborder="0"
						scrolling="no"
						marginheight="0"
						marginwidth="0"
						src="<?php echo esc_url( $map_url ); ?>"></iframe>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</a>
</li>
