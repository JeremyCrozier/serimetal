<?php
/**
 * Tabs template.
 *
 * Parent block. Each panel is an acf/tab child with its own inner blocks.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-blocks-using-innerblocks-and-parent-child-relationships/
 */

$allowed_blocks = array( 'acf/tab' );

$template = array(
	array( 'acf/tab' ),
	array( 'acf/tab' ),
);

$wrapper_attributes = ! empty( $is_preview )
	? 'class="block-tabs"'
	: get_block_wrapper_attributes(
		array(
			'class' => 'block-tabs',
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<InnerBlocks
		class="tabs-content"
		allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"
		template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>"
	/>
</div>
