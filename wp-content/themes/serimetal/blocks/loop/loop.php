<?php
/**
 * Loop block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */

$allowed_types = array( 'poles', 'realisation', 'recrutement' );
$type          = get_field( 'type' );

if ( ! in_array( $type, $allowed_types, true ) ) {
	$type = 'realisation';
}

$query_args = array(
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( 'poles' === $type ) {
	$query_args['post_type'] = 'page';
	$query_args['orderby']   = array(
		'menu_order' => 'ASC',
		'title'      => 'ASC',
	);
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => 'pole',
			'operator' => 'EXISTS',
		),
	);
} else {
	$query_args['post_type'] = $type;
	$query_args['orderby']   = 'date';
	$query_args['order']     = 'DESC';
}

$query       = new WP_Query( $query_args );
$disabled   = $is_preview ? 'style="pointer-events: none"' : '';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'block-loop block-loop--' . $type,
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $query->have_posts() ) : ?>
		<ul class="loop" <?php echo $disabled; ?>>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part(
					'template-parts/card',
					$type,
					array(
						'is_preview' => ! empty( $is_preview ),
					)
				);
			endwhile;
			?>
		</ul>
	<?php endif; ?>
</div>

<?php
wp_reset_postdata();
