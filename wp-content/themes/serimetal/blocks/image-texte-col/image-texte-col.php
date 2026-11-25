<?php
/**
 * Two-column image and text. The first column leads with the title.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$column_1 = get_field( 'col1' );
$column_2 = get_field( 'col2' );
$column_1 = is_array( $column_1 ) ? $column_1 : array();
$column_2 = is_array( $column_2 ) ? $column_2 : array();

$title   = isset( $column_1['title'] ) ? $column_1['title'] : '';
$text    = isset( $column_1['text'] ) ? $column_1['text'] : '';
$image   = ! empty( $column_1['image'] ) ? (int) $column_1['image'] : 0;
$text_2  = isset( $column_2['text'] ) ? $column_2['text'] : '';
$image_2 = ! empty( $column_2['image'] ) ? (int) $column_2['image'] : 0;
$cta     = isset( $column_1['cta'] ) ? $column_1['cta'] : '';
$cta_2   = isset( $column_2['cta'] ) ? $column_2['cta'] : '';

$classes = array( 'image-texte-col-block' );

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<div class="image-texte-col__grid">
		<?php if ( $title || $text || $image ) : ?>
			<div class="image-texte-col__col col-1">
				<?php if ( $title ) : ?>
					<h2 class="image-texte-col__heading heading-large title-divider"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<div class="image-texte-col__text wysiwyg"><?php echo wp_kses_post( $text ); ?></div>
				<?php endif; ?>

				<?php if ( $cta ) : ?>
					<a class="btn-filled-primary" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( $cta['target'] ); ?>">
						<?php echo esc_html( $cta['title'] ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $image ) : ?>
					<div class="image-texte-col__media">
						<?php echo wp_get_attachment_image( $image, 'medium' ); ?>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>

		<?php if ( $text_2 || $image_2 ) : ?>
			<div class="image-texte-col__col col-2">
				<?php if ( $image_2 ) : ?>
					<div class="image-texte-col__media">
						<?php echo wp_get_attachment_image( $image_2, 'medium' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $text_2 ) : ?>
					<div class="image-texte-col__text wysiwyg"><?php echo wp_kses_post( $text_2 ); ?></div>
				<?php endif; ?>

				<?php if ( $cta_2 ) : ?>
					<a class="btn-filled-primary" href="<?php echo esc_url( $cta_2['url'] ); ?>" target="<?php echo esc_attr( $cta_2['target'] ); ?>">
						<?php echo esc_html( $cta_2['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
