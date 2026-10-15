<?php
/**
 * Section Block template.
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

$background = get_field( 'background' );
$classes = array(
	'bg-' . $background,
);

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section__inner">
		<InnerBlocks />
	</div>
</section>
