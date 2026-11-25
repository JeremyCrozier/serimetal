<?php
/**
 * Team block: intro column, skills card and closing text.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$intro   = get_field( 'intro' );
$block_1 = get_field( 'block_1' );
$block_2 = get_field( 'block_2' );
$intro   = is_array( $intro ) ? $intro : array();
$block_1 = is_array( $block_1 ) ? $block_1 : array();
$block_2 = is_array( $block_2 ) ? $block_2 : array();

$title      = isset( $intro['title'] ) ? $intro['title'] : '';
$intro_text = isset( $intro['text'] ) ? $intro['text'] : '';
$intro_image = ! empty( $intro['image'] ) ? (int) $intro['image'] : 0;
$list       = isset( $block_1['text'] ) ? $block_1['text'] : '';
$card_image = ! empty( $block_1['image'] ) ? (int) $block_1['image'] : 0;
$closing    = isset( $block_2['text'] ) ? $block_2['text'] : '';
$cta        = isset( $block_2['cta'] ) && is_array( $block_2['cta'] ) ? $block_2['cta'] : array();

$has_intro = $title || $intro_text || $intro_image;
$has_panel = $list || $card_image;
$has_close = $closing || ! empty( $cta['url'] );

$classes = array( 'block-team' );

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<div class="team-main">
		<?php if ( $has_intro ) : ?>
			<div class="team-intro">
				<?php if ( $title || $intro_text ) : ?>
					<div class="team-copy">
						<?php if ( $title ) : ?>
							<h2 class="team-heading heading-large title-divider"><?php echo esc_html( $title ); ?></h2>
						<?php endif; ?>
						<?php if ( $intro_text ) : ?>
							<div class="team-text wysiwyg"><?php echo wp_kses_post( $intro_text ); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $intro_image ) : ?>
					<div class="team-media team-intro__media">
						<?php echo wp_get_attachment_image( $intro_image, 'medium' ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_panel || $has_close ) : ?>
			<div class="team-content">
				<?php if ( $has_panel ) : ?>
					<div class="team-panel">
						<?php if ( $list ) : ?>
							<div class="team-card wysiwyg"><?php echo wp_kses_post( $list ); ?></div>
						<?php endif; ?>

						<?php if ( $card_image ) : ?>
							<div class="team-media team-panel__media">
								<?php echo wp_get_attachment_image( $card_image, 'medium' ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $has_close ) : ?>
					<div class="team-footer">
						<?php if ( $closing ) : ?>
							<div class="team-text wysiwyg"><?php echo wp_kses_post( $closing ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $cta['url'] ) ) : ?>
							<a class="btn-filled-primary" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( ! empty( $cta['target'] ) ? $cta['target'] : '_self' ); ?>">
								<?php echo esc_html( ! empty( $cta['title'] ) ? $cta['title'] : 'En savoir plus' ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
