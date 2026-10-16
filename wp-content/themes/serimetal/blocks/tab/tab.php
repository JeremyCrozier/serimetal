<?php
/**
 * Tab block template.
 *
 * One panel inside acf/tabs. Inner blocks are limited to content blocks.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$label = get_field( 'label' );

if ( ! is_string( $label ) || '' === $label ) {
	$label = 'Onglet';
}

// Blocks that can be inserted inside a tab panel.
$allowed_blocks = array(
	'core/paragraph',
	'core/list',
	'acf/button',
	'acf/blocks',
);

$wrapper_attributes = ! empty( $is_preview )
	? 'class="tab"'
	: get_block_wrapper_attributes(
		array(
			'class' => 'tab',
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<button class="tab-item" type="button" role="tab" aria-selected="false"><?php echo esc_html( $label ); ?></button>
	<InnerBlocks class="tab-content-item" allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />
</div>
