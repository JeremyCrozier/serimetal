<?php
/**
 * Small Hero block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$image_id = get_field( 'image' );
if ( ! $image_id ) {
	$image_id = get_post_thumbnail_id( get_the_ID() );
}
$image_id = $image_id ? (int) $image_id : 0;

$heading = get_field( 'heading' );
if ( ! $heading ) {
	$heading = get_the_title( get_the_ID() );
}

$description = get_field( 'description' );
$cta         = get_field( 'cta' );
$cta         = is_array( $cta ) ? $cta : array();
$cta_url     = ! empty( $cta['url'] ) ? $cta['url'] : '';
$cta_title   = ! empty( $cta['title'] ) ? $cta['title'] : '';
$target      = ! empty( $cta['target'] ) ? $cta['target'] : '_self';
$disabled    = $is_preview ? 'style="pointer-events: none"' : '';

$wrapper_attributes = ! empty( $is_preview )
	? 'class="block-small-hero"'
	: get_block_wrapper_attributes(
		array(
			'class' => 'block-small-hero',
		)
	);
?>

<header <?php echo $wrapper_attributes; ?>>
	<div class="inside">
		<div class="content">
			<div class="hero-copy">
				<?php if ( $heading ) : ?>
					<h1 class="heading-large"><?php echo esc_html( $heading ); ?></h1>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<div class="wysiwyg"><?php echo wp_kses_post( $description ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( $cta_url ) : ?>
				<a href="<?php echo esc_url( $cta_url ); ?>" class="btn-filled-primary" target="<?php echo esc_attr( $target ); ?>" <?php echo $disabled; ?>>
					<?php echo esc_html( $cta_title ? $cta_title : 'Texte du bouton' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( $image_id ) : ?>
			<div class="image">
				<?php echo wp_get_attachment_image( $image_id, 'large' ); ?>
			</div>
		<?php endif; ?>
	</div>
</header>
