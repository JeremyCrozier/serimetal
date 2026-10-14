<?php
/**
 * Button Block template.
 *
 * @param array $block The block settings and attributes.
 * @param boolean $is_preview True during backend preview render, i.e., when rendering inside the block editor’s content, or rendered inside the block editor when adding a new block, showing a preview when hovering over the new block. This variable is only set to true when is_admin() and current screen is_block_editor() both return true.
 * @param integer $post_id The Post ID
 * @param array $context The context provided to the block by the post or its parent block.
 * 
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/
 * 
 */

// Load values
$link   = get_field( 'button' );
$icon   = get_field( 'icon' );
$target = !empty($link['target']) ? $link['target'] : '_self';
$icon_file = $icon ? get_file_icon( $icon ) : null;
$disabled = $is_preview ? 'style="pointer-events: none"' : '';

$wrapper_attributes = $is_preview ? ' ' : get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes ?>>
	<a href="<?php echo esc_url($link['url'] ?? '#') ?>" class="cta" target="<?php echo esc_attr($target) ?>" <?php echo $disabled ?> title="Accéder à la page">
		<?php echo esc_html($link['title'] ?? 'Titre du bouton') ?>
		<?php if ( $icon_file ): ?>
			<span class="button-icon">
				<?php echo $icon_file; ?>
			</span>
		<?php endif; ?>
	</a>
</div>