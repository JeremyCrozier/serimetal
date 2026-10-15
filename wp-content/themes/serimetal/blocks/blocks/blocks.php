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

$style = get_field( 'style' );
$blocks = get_field( 'blocks' );
$highlighting = get_field( 'highlighting' );
$columns = $blocks ? count( $blocks ) : 1;
$number = 1;
$classes = array(
	'columns-' . max( 3, $columns ),
	'blocks-style-' . $style,
);

if ( $highlighting ) {
	$classes[] = 'has-highlighting';
}

$styles_border = array(
	"default" => "border--yellow",
	"numbers" => "border--blue",
);

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>

	<?php if ( $highlighting['titre'] && $highlighting['text'] ) : ?>
		<div class="block-item bg-dark block-highlight">
			<?php if ( $highlighting['titre'] ) : ?>
				<h3 class="heading-medium"><?php echo esc_html( $highlighting['titre'] ); ?></h3>
			<?php endif; ?>
			<?php if ( $highlighting['text'] ) : ?>
				<p class="block-text">
					<?php echo esc_html( $highlighting['text'] ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $highlighting['cta'] ) : ?>
				<?php $target = !empty($highlighting['cta']['target']) ? $highlighting['cta']['target'] : '_self'; ?>
				<?php $disabled = $is_preview ? 'style="pointer-events: none"' : ''; ?>
				<a href="<?php echo esc_url( $highlighting['cta']['url'] ); ?>" class="btn-filled-secondary" target="<?php echo esc_attr( $target ); ?>" <?php echo $disabled ?>>
					<?php echo esc_html( $highlighting['cta']['title'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( have_rows( 'blocks' ) ) : ?>
		<?php while ( have_rows( 'blocks' ) ) : the_row(); ?>
			<div class="block-item <?php echo $styles_border[ $style ]; ?>">
				<?php if ( $style === 'numbers' ) : ?>
					<div class="block-number heading-medium">
						<?php echo esc_html( '0' . $number . '.' ); ?>
					</div>
					<?php $number++; ?>
				<?php endif; ?>
				<div class="block-item__inner">
					<?php if ( get_sub_field( 'titre' ) ) : ?>
						<h3 class="heading-small"><?php echo esc_html( get_sub_field( 'titre' ) ); ?></h3>
					<?php endif; ?>
					<?php if ( get_sub_field( 'text' ) ) : ?>
						<p class="block-text">
							<?php echo wp_kses_post( get_sub_field( 'text' ) ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>
</div>
