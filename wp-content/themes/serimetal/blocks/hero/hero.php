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
$overtitle = get_field( 'overtitle' );
$description = get_field( 'description' );
$link = get_field( 'cta' );
$target = !empty($link['target']) ? $link['target'] : '_self';
$front_page = is_front_page() || intval( get_option( 'page_on_front' ) ) === get_the_ID();

$class = $front_page ? 'home-hero' : '';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'style' => 'background: linear-gradient(180deg, rgba(11, 9, 11, 0.10) 0%, rgba(11, 9, 11, 0.80) 100%), url(' . $image . ');',
		'class' => $class,
	)
);
?>

<header <?php echo $wrapper_attributes; ?>>
	<div class="inside">
		<?php if ( $front_page ) : ?>
			<div class="content">
				<?php if ( $description ) : ?>
					<?php echo wp_kses_post( wpautop( $description ) ); ?>
				<?php endif; ?>
				<span class="scroll-down">Scroll pour découvrir <i class="ph ph-caret-down"></i></span>
			</div>
			<div class="heading-container">
				<?php if ( $overtitle ) : ?>
					<p class="heading-small"><?php echo $overtitle; ?></p>
				<?php endif; ?>
				<h1 class="heading-xlarge"><?php echo $heading; ?></h1>
			</div>
		<?php else : ?>
			<div class="heading-container">
				<?php if ( $overtitle ) : ?>
					<p class="heading-small"><?php echo $overtitle; ?></p>
				<?php endif; ?>
				<h1 class="heading-xlarge"><?php echo $heading; ?></h1>
			</div>
			<div class="content">
				<?php if ( $description ) : ?>
					<?php echo wp_kses_post( wpautop( $description ) ); ?>
				<?php endif; ?>
				<?php if ( $link ) : ?>
					<a href="<?php echo esc_url($link['url'] ?? '#') ?>" class="btn-outline" target="<?php echo esc_attr($target) ?>" title="Accéder à la page">
						<?php echo esc_html($link['title'] ?? 'Texte du bouton') ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</header>
