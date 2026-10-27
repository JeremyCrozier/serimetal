<?php
/**
 * Form block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$background = get_field( 'background' );
$form_id    = get_field( 'form' );
$content    = get_field( 'content' );
$content    = is_array( $content ) ? $content : array();

$title = isset( $content['titre'] ) ? $content['titre'] : '';
$text  = isset( $content['text'] ) ? $content['text'] : '';
$image = ! empty( $content['image'] ) ? (int) $content['image'] : 0;
$has_content = $title || $text || $image;

$classes = array( 'block-form' );

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<section <?php echo $wrapper_attributes; ?>>
	<?php if ( $has_content ) : ?>
		<div class="form-content">
			<?php if ( $title || $text ) : ?>
				<div class="form-intro">
					<?php if ( $title ) : ?>
						<h2 class="heading-large"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $text ) : ?>
						<p><?php echo nl2br( esc_html( $text ) ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $image ) : ?>
				<figure class="form-media">
					<?php echo wp_get_attachment_image( $image, 'medium' ); ?>
				</figure>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $form_id && function_exists( 'wpforms_display' ) ) : ?>
		<div class="form-panel bg-<?php echo $background; ?>">
			<?php wpforms_display( $form_id ); ?>
		</div>
	<?php endif; ?>
</section>
