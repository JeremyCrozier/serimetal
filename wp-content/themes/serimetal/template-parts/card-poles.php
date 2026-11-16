<?php
/**
 * Pole card.
 *
 * The photo stays a background. The excerpt panel unfolds over it.
 *
 * @package Serimetal
 */
$color      = sanitize_hex_color( (string) get_field( 'page_color', get_the_ID() ) );
$background = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
?>

<li class="pole-card"<?php echo $color ? ' style="border-bottom-color: ' . esc_attr( $color ) . ';"' : ''; ?>>
	<a href="<?php the_permalink(); ?>"<?php echo $background ? ' style="background-image: url(' . esc_url( $background ) . ');"' : ''; ?>>
		<span class="know-more btn-outline">en savoir plus <i class="ph ph-caret-right"></i></span>
		<div class="pole-card__content">
			<h3 class="heading-small">
				<?php the_title(); ?>
			</h3>
			<div class="pole-card__excerpt">
				<?php the_excerpt(); ?>
			</div>
		</div>
	</a>
</li>
