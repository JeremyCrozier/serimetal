<?php
/**
 * Simpler page hero: photo, title and text.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$image_id    = get_field( 'image' );
$image_url   = $image_id ? wp_get_attachment_image_url( (int) $image_id, 'full' ) : get_the_post_thumbnail_url( get_the_ID(), 'full' );
$heading     = get_field( 'heading' ) ? get_field( 'heading' ) : get_the_title( get_the_ID() );
$description = get_field( 'description' );
$link        = get_field( 'cta' );
$link        = is_array( $link ) ? $link : array();

$classes = array( 'block-hero-bis', 'bg--dark' );

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);

$media_style = $image_url ? ' style="background-image: url(' . esc_url( $image_url ) . ');"' : '';
?>

<header <?php echo $wrapper_attributes; ?>>
	<div class="hero-media"<?php echo $media_style; ?>>
		<span class="hero-overlay" aria-hidden="true"></span>
	</div>

	<div class="inside">
		<?php if ( $heading ) : ?>
			<h1 class="heading-xlarge"><?php echo esc_html( $heading ); ?></h1>
		<?php endif; ?>

		<?php if ( $description || ! empty( $link['url'] ) ) : ?>
			<div class="hero-bis__copy">
				<?php if ( $description ) : ?>
					<div class="wysiwyg"><?php echo wp_kses_post( $description ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $link['url'] ) ) : ?>
					<a class="btn-filled-secondary" href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( ! empty( $link['target'] ) ? $link['target'] : '_self' ); ?>">
						<?php echo esc_html( ! empty( $link['title'] ) ? $link['title'] : 'En savoir plus' ); ?>
						<i class="ph ph-caret-down" aria-hidden="true"></i>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</header>
