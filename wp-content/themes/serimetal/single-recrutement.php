<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php get_header(); ?>

<?php $meta = array_filter(
	array(
		get_field( 'contrat' ),
		get_field( 'localisaton' ),
		get_field( 'temps' ),
	)
); ?>

<header id="recrutement-hero" class="block-small-hero">
	<div class="inside">
		<div class="content">
			<div class="content-top">
				<?php $recrutement_term = get_the_terms($post, 'recrutement-cat'); ?>
				<?php if ( is_array($recrutement_term) && isset($recrutement_term[0]) ) : ?>
					<span class="tag"><?php echo $recrutement_term[0]->name; ?></span>
				<?php endif; ?>
				<h1 class="heading-medium"><?php the_title(); ?></h1>

				<?php if ( get_the_excerpt() ) : ?>
					<div class="excerpt wysiwyg"><?php the_excerpt(); ?></div>
				<?php endif; ?>
			</div>
			<?php if ( $meta ) : ?>
			<p class="content-bottom">
				<?php foreach ( $meta as $item ) : ?>
					<span><?php echo esc_html( $item ); ?></span>
				<?php endforeach; ?>
			</p>
			<?php endif; ?>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="image">
				<?php echo get_the_post_thumbnail( $post->ID, 'small-hero' ); ?>
			</div>
		<?php endif; ?>
	</div>
</header>

<section id="recrutement-content" class="wp-block-acf-section">
    <div class="inside">
		<div class="wysiwyg">
			<?php the_content(); ?>
		</div>
		<?php if ( get_field('form') && function_exists( 'wpforms_display' ) ) : ?>
			<div class="form-panel">
				<h2 class="wp-block-heading">Postuler chez SERIMETAL</h2>
				<?php wpforms_display( get_field('form') ); ?>
			</div>
		<?php endif; ?>
    </div>
</section>

<?php
$images = get_field('images');

if ( $images ) {
	echo '<section class="wp-block-acf-section">';
	echo '<div class="inside">';
	echo render_block([
		'blockName'    => 'acf/gallery',
		'attrs'        => [
			'data' => [
				'field_6ae9f5eb65dbc' => get_field('images')
			],
		],
		'innerBlocks'  => [],
		'innerHTML'    => '',
		'innerContent' => [],
	]);
	echo '</div>';
	echo '</section>';
}
?>

<?php get_footer(); ?>