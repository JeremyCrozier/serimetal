<?php
/**
 * Chronological timeline. Slide chrome alternates on every other item.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$heading = get_field( 'heading' );
$text    = get_field( 'text' );
$slides  = get_field( 'slides' );
$slides  = is_array( $slides ) ? $slides : array();

$classes = array( 'block-timeline' );

if ( ! empty( $is_preview ) ) {
	$classes[] = 'is-preview';
}

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);

if ( ! function_exists( 'serimetal_timeline_caption' ) ) {
	/**
	 * Slide caption: label, year and description.
	 *
	 * @param string $title Slide label.
	 * @param string $year  Slide year.
	 * @param string $text  Slide description.
	 */
	function serimetal_timeline_caption( $title, $year, $text ) {
		if ( ! $title && ! $year && ! $text ) {
			return;
		}
		?>
		<div class="timeline-caption">
			<?php if ( $title || $year ) : ?>
				<div class="timeline-caption__head">
					<?php if ( $title ) : ?>
						<p class="timeline-label body-small"><?php echo esc_html( $title ); ?></p>
					<?php endif; ?>
					<?php if ( $year ) : ?>
						<p class="timeline-year"><?php echo esc_html( $year ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p class="timeline-text"><?php echo nl2br( esc_html( $text ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'serimetal_timeline_photo' ) ) {
	/**
	 * Cover image used inside a slide.
	 *
	 * @param int    $id    Attachment ID.
	 * @param string $class Photo modifier classes.
	 */
	function serimetal_timeline_photo( $id, $class ) {
		if ( ! $id ) {
			return;
		}

		echo '<div class="timeline-photo ' . esc_attr( $class ) . '">';
		echo wp_get_attachment_image( (int) $id, 'medium', false, array( 'class' => 'timeline-photo__img' ) );
		echo '</div>';
	}
}
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $heading || $text ) : ?>
		<div class="timeline-intro">
			<?php if ( $heading ) : ?>
				<h2 class="timeline-heading heading-large title-divider"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<div class="timeline-copy wysiwyg"><?php echo wp_kses_post( $text ); ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $slides ) : ?>
		<div class="timeline-runway">
			<div class="timeline-sticky">
				<div class="timeline-viewport">
					<div class="timeline-track">
						<?php $rendered = 0; ?>
						<?php foreach ( $slides as $slide ) : ?>
							<?php
							$slide = is_array( $slide ) ? $slide : array();
							$title = isset( $slide['title'] ) ? $slide['title'] : '';
							$year  = isset( $slide['year'] ) ? $slide['year'] : '';
							$copy  = isset( $slide['text'] ) ? $slide['text'] : '';
							$image = ! empty( $slide['image'] ) ? (int) $slide['image'] : 0;
							$secondary = ! empty( $slide['image_secondary'] ) ? (int) $slide['image_secondary'] : 0;

							if ( ! $title && ! $year && ! $copy && ! $image && ! $secondary ) {
								continue;
							}

							$slide_classes = array( 'timeline-slide' );

							// Open layout on every other slide.
							if ( 1 === $rendered % 2 ) {
								$slide_classes[] = 'timeline-slide--alt';
							}

							$rendered++;
							?>
							<div class="<?php echo esc_attr( implode( ' ', $slide_classes ) ); ?>">
								<?php if ( $image || $secondary ) : ?>
									<div class="timeline-media">
										<?php if ( $image && $secondary ) : ?>
											<?php serimetal_timeline_photo( $secondary, 'timeline-photo--short' ); ?>
											<?php serimetal_timeline_photo( $image, 'timeline-photo--tall' ); ?>
										<?php else : ?>
											<?php serimetal_timeline_photo( $image ? $image : $secondary, 'timeline-photo--full' ); ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>

								<?php serimetal_timeline_caption( $title, $year, $copy ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
