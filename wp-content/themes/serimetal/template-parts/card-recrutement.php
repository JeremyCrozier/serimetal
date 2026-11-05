<?php
/**
 * Recrutement card.
 *
 * @package Serimetal
 */

$terms      = get_the_terms( get_the_ID(), 'recrutement-cat' );
$term       = ( is_array( $terms ) && isset( $terms[0] ) ) ? $terms[0] : null;
$meta       = array_filter(
	array(
		get_field( 'contrat', get_the_ID() ),
		get_field( 'localisaton', get_the_ID() ),
		get_field( 'temps', get_the_ID() ),
	)
);
?>

<li class="job-card">
	<div class="job-card__content">
		<?php if ( $term ) : ?>
			<p class="job-card__category"><?php echo esc_html( $term->name ); ?></p>
		<?php endif; ?>

		<h3 class="heading-small">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $meta ) : ?>
			<p class="job-card__meta">
				<?php foreach ( $meta as $item ) : ?>
					<span><?php echo esc_html( $item ); ?></span>
				<?php endforeach; ?>
			</p>
		<?php endif; ?>
	</div>

	<a class="btn-filled-primary" href="<?php the_permalink(); ?>">
		<?php echo esc_html( 'En savoir plus' ); ?>
	</a>
</li>
