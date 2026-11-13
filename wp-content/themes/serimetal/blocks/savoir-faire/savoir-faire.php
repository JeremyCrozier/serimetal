<?php
/**
 * Savoir-faire block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$title    = get_field( 'title' );
$text     = get_field( 'text' );
$image_id = get_field( 'image' );
$cta      = get_field( 'cta' );
$list     = get_field( 'list' );
$cta      = is_array( $cta ) ? $cta : array();

$has_main  = $title || $text || ! empty( $cta['url'] );
$has_aside = $image_id || $list;

$classes = array( 'block-savoir-faire' );

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $has_main ) : ?>
		<div class="savoir-faire-main">
			<?php if ( $title ) : ?>
				<h2 class="heading-large"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<div class="wysiwyg savoir-faire-text"><?php echo wp_kses_post( $text ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $cta['url'] ) ) : ?>
				<a class="btn-filled-primary" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( ! empty( $cta['target'] ) ? $cta['target'] : '_self' ); ?>"<?php echo ! empty( $is_preview ) ? ' style="pointer-events: none"' : ''; ?>>
					<?php echo esc_html( ! empty( $cta['title'] ) ? $cta['title'] : 'En savoir plus' ); ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $has_aside ) : ?>
		<div class="savoir-faire-aside">
			<?php if ( $image_id ) : ?>
				<figure class="savoir-faire-media">
					<?php echo wp_get_attachment_image( $image_id, 'large' ); ?>
				</figure>
			<?php endif; ?>

			<?php if ( $list ) : ?>
				<div class="wysiwyg savoir-faire-list"><?php echo wp_kses_post( $list ); ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
