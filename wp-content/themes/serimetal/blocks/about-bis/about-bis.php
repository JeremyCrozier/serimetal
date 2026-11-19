<?php
/**
 * About bis block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$heading       = get_field( 'heading' );
$text          = get_field( 'text' );
$cta           = get_field( 'cta' );
$cta           = is_array( $cta ) ? $cta : array();
$image         = get_field( 'image' );
$key_elements  = get_field( 'key_elements' );
$key_elements  = is_array( $key_elements ) ? $key_elements : array();
$has_copy      = $heading || $text || ! empty( $cta['url'] );

$classes = array( 'about-bis-block' );

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $has_copy || $image ) : ?>
		<div class="about-bis__main">
			<?php if ( $has_copy ) : ?>
				<div class="about-bis__copy">
					<?php if ( $heading || $text ) : ?>
						<div class="about-bis__intro">
							<?php if ( $heading ) : ?>
								<h2 class="about-bis__heading heading-small title-divider"><?php echo esc_html( $heading ); ?></h2>
							<?php endif; ?>
							<?php if ( $text ) : ?>
								<p class="about-bis__text"><?php echo nl2br( esc_html( $text ) ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $cta['url'] ) ) : ?>
						<a class="btn-ghost-primary" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( ! empty( $cta['target'] ) ? $cta['target'] : '_self' ); ?>"<?php echo ! empty( $is_preview ) ? ' style="pointer-events: none"' : ''; ?>>
							<?php echo esc_html( ! empty( $cta['title'] ) ? $cta['title'] : 'En savoir plus' ); ?>
							<i class="ph ph-caret-right" aria-hidden="true"></i>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $image ) : ?>
				<figure class="about-bis__media">
					<?php echo wp_get_attachment_image( (int) $image, 'small-hero', false, array( 'class' => 'parallax' ) ); ?>
				</figure>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $key_elements ) : ?>
		<ul class="about-bis__stats">
			<?php foreach ( $key_elements as $item ) : ?>
				<?php
				$stat = isset( $item['main_text'] ) ? $item['main_text'] : '';

				if ( ! $stat ) {
					continue;
				}
				?>
				<li class="about-bis__stat">
					<p class="heading-small"><?php echo esc_html( $stat ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
