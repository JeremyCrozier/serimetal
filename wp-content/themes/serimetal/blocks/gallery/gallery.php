<?php
/**
 * Gallery block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$images = get_field( 'images' );
$images = is_array( $images ) ? $images : array();
$ids    = array();

foreach ( $images as $image ) {
	if ( is_array( $image ) ) {
		$id = $image['ID'] ?? ( $image['id'] ?? 0 );
	} else {
		$id = $image;
	}

	$id = (int) $id;

	if ( $id ) {
		$ids[] = $id;
	}
}

$count  = count( $ids );
$layout = min( $count, 7 );
$extra  = $count > 7 ? array_slice( $ids, 7 ) : array();
$ids    = $count > 7 ? array_slice( $ids, 0, 7 ) : $ids;

$classes = array( 'block-gallery' );

if ( $layout ) {
	$classes[] = 'gallery--' . $layout;
}

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $ids ) : ?>
		<div class="gallery">
			<?php foreach ( $ids as $id ) : ?>
				<figure class="gallery-item">
					<?php echo wp_get_attachment_image( $id, 'medium' ); ?>
				</figure>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $extra ) : ?>
		<div class="gallery-more">
			<?php foreach ( $extra as $id ) : ?>
				<figure class="gallery-item">
					<?php echo wp_get_attachment_image( $id, 'medium' ); ?>
				</figure>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
