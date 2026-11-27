<?php
/**
 * Image & Texte Block template.
 *
 * Container that accepts native Gutenberg blocks and ACF blocks via InnerBlocks.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-blocks-using-innerblocks-and-parent-child-relationships/
 */

$image = get_field( 'image' );
$text  = get_field( 'text' );


$classes = array(
	'image-texte-block',
);

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>

	<?php if ( $image ) : ?>
		<div class="image-texte-block__image">
			<?php echo wp_get_attachment_image( (int) $image, 'medium' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $text ) : ?>
		<div class="image-texte-block__text wysiwyg">
			<?php echo wp_kses_post( $text ); ?>
		</div>
	<?php endif; ?>
</div>
