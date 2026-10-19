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
$get_image = get_field( 'image' );
$image = $get_image ? wp_get_attachment_image_url( $get_image, 'full' ) : get_the_post_thumbnail_url( get_the_ID(), 'full' );
$heading = get_field( 'heading' ) ? get_field( 'heading' ) : get_the_title( get_the_ID() );
$description = get_field( 'description' );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'style' => 'background: linear-gradient(180deg, rgba(11, 9, 11, 0.10) 0%, rgba(11, 9, 11, 0.80) 100%), url(' . $image . ');',
	)
);
?>

<header <?php echo $wrapper_attributes; ?>>
	<div class="inside">
		<div class="content">
			<?php if ( $description ) : ?>
				<p><?php echo $description; ?></p>
			<?php endif; ?>
			<span class="scroll-down">Scroll pour découvrir <i class="ph ph-caret-down"></i></span>
		</div>

		<h1 class="heading-xlarge"><?php echo $heading; ?></h1>
	</div>
</header>
