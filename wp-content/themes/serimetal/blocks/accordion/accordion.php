<?php
/**
 * Accordion block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$additional_content = get_field( 'additional_content' );
$additional_content = is_array( $additional_content ) ? $additional_content : array();
$accordions         = get_field( 'accordions' );
$image_id           = get_field( 'image' );

$title = isset( $additional_content['titre'] ) ? $additional_content['titre'] : '';
$text  = isset( $additional_content['text'] ) ? $additional_content['text'] : '';
$cta   = isset( $additional_content['cta'] ) ? $additional_content['cta'] : array();

$has_intro = $title || $text || ! empty( $cta['url'] );
$has_media = ! empty( $image_id );

if ( empty( $accordions ) && ! empty( $is_preview ) ) {
	$accordions = array(
		array(
			'question' => 'Texte de la question',
			'answer'   => 'Texte de la réponse',
		),
	);
}

$classes = array( 'block-accordion' );

if ( $has_intro ) {
	$classes[] = 'has-intro';

}

if ( $title ) {
	$classes[] = 'has-title';
}

if ( $has_media ) {
	$classes[] = 'has-media';
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
	<?php if ( $has_intro ) : ?>
		<div class="accordion-intro">
			<?php if ( $title || $text ) : ?>
					<?php if ( $title ) : ?>
						<h2 class="heading-large"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $text ) : ?>
						<p><?php echo esc_html( $text ); ?></p>
					<?php endif; ?>
			<?php endif; ?>

			<?php if ( ! empty( $cta['url'] ) ) : ?>
				<?php
				$target   = ! empty( $cta['target'] ) ? $cta['target'] : '_self';
				$disabled = $is_preview ? 'style="pointer-events: none"' : '';
				?>
				<a class="btn-filled-primary" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( $target ); ?>" <?php echo $disabled; ?>>
					<?php echo esc_html( $cta['title'] ? $cta['title'] : 'En savoir plus' ); ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $accordions ) : ?>
		<div class="accordion-list">
			<?php foreach ( $accordions as $index => $item ) : ?>
				<?php
				$question = isset( $item['question'] ) ? $item['question'] : '';
				$answer   = isset( $item['answer'] ) ? $item['answer'] : '';
				$is_open  = 0 === (int) $index;
				?>
				<div class="accordion-item<?php echo $is_open ? ' is-active' : ''; ?>">
					<button class="accordion-title<?php echo $is_open ? ' is-active' : ''; ?>" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>">
						<span class="heading-small"><?php echo esc_html( $question ); ?></span>
						<span class="btn-outline btn-icon btn-sm ph<?php echo $is_open ? ' ph-minus' : ' ph-plus'; ?>" aria-hidden="true"></span>
					</button>
					<div class="accordion-content">
						<?php if ( $answer ) : ?>
							<p><?php echo nl2br( esc_html( $answer ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $has_media ) : ?>
		<figure class="accordion-media">
			<?php
			$img_size = ( count( $accordions ) > 2 ) ? 'large' : ( $has_intro ? 'medium' : 'large' );
			echo wp_get_attachment_image( $image_id, $img_size );
			?>
		</figure>
	<?php endif; ?>
</div>
