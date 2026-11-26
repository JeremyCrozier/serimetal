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
$background = get_the_post_thumbnail_url( get_the_ID(), 'large' );
$has_content = $content && strlen( trim( $content ) ) > 0;
?>

<li class="realisation-card swiper-slide">
	<a class="realisation-card__link" 
		href="<?php echo esc_url( get_permalink() ); ?>"
		<?php SM_Ajax::realisation_modal_attributes( true ); ?>
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
	</a>
</li>
