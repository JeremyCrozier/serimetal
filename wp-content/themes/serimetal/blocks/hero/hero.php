<?php
/**
 * Hero block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$image_id      = get_field( 'image' );
$image_url     = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : get_the_post_thumbnail_url( get_the_ID(), 'full' );
$video_desktop = get_field( 'video_desktop' );
$video_mobile  = get_field( 'video_mobile' );
$heading       = get_field( 'heading' ) ? get_field( 'heading' ) : get_the_title( get_the_ID() );
$overtitle     = get_field( 'overtitle' );
$description   = get_field( 'description' );
$link          = get_field( 'cta' );
$link          = is_array( $link ) ? $link : array();

$video_desktop = is_string( $video_desktop ) ? $video_desktop : '';
$video_mobile  = is_string( $video_mobile ) ? $video_mobile : '';
$classes       = array( 'block-hero' );

if ( $video_mobile ) {
	$classes[] = 'has-mobile-video';
}

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);

$media_style = $image_url ? ' style="background-image: url(' . esc_url( $image_url ) . ');"' : '';
$poster      = $image_url ? ' poster="' . esc_url( $image_url ) . '"' : '';
?>

<header <?php echo $wrapper_attributes; ?>>
	<div class="hero-media"<?php echo $media_style; ?>>
		<?php if ( $video_desktop ) : ?>
			<video class="hero-video hero-video--desktop" autoplay muted loop playsinline<?php echo $poster; ?>>
				<source src="<?php echo esc_url( $video_desktop ); ?>">
			</video>
		<?php endif; ?>

		<?php if ( $video_mobile ) : ?>
			<video class="hero-video hero-video--mobile" autoplay muted loop playsinline<?php echo $poster; ?>>
				<source src="<?php echo esc_url( $video_mobile ); ?>">
			</video>
		<?php endif; ?>

		<span class="hero-overlay" aria-hidden="true"></span>
	</div>

	<div class="inside">
		<div class="heading-container">
			<?php if ( $overtitle ) : ?>
				<p class="heading-small"><?php echo esc_html( $overtitle ); ?></p>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h1 class="heading-xlarge"><?php echo esc_html( $heading ); ?></h1>
			<?php endif; ?>
		</div>

		<?php if ( $description || ! empty( $link['url'] ) ) : ?>
			<div class="content">
				<?php if ( $description ) : ?>
					<div class="hero-description"><?php echo wp_kses_post( $description ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $link['url'] ) ) : ?>
					<a href="<?php echo esc_url( $link['url'] ); ?>" aria-label="<?php echo esc_html( ! empty( $link['title'] ) ? $link['title'] : 'En savoir plus' ); ?>" aria-hidden="true" class="scroll-down-container">
						<span class="scroll-down">
							<?php echo esc_html( ! empty( $link['title'] ) ? $link['title'] : 'En savoir plus' ); ?> 
							<i class="ph ph-caret-down" aria-hidden="true"></i>
						</span>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</header>
