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

$type = get_field( 'type' );
$type = ( 'poles' === $type ) ? 'poles' : 'realisation';

$query_args = array(
	'post_status'         => 'publish',
	'posts_per_page'      => -1,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( 'poles' === $type ) {
	// Pages assigned to at least one term of the pole taxonomy.
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
	$query_args['post_type'] = 'realisation';
	$query_args['orderby']   = 'date';
	$query_args['order']     = 'DESC';
}

$query = new WP_Query( $query_args );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'block-loop block-loop--' . $type,
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $query->have_posts() ) : ?>
		<ul class="loop columns-3">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();

				$excerpt  = wp_strip_all_tags( get_the_excerpt() );
				$disabled = ! empty( $is_preview ) ? 'style="pointer-events: none"' : '';
				?>
				<li class="loop-item">
					<a class="loop-item__link" href="<?php the_permalink(); ?>" <?php echo $disabled; ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="loop-item__media">
								<?php the_post_thumbnail( 'medium' ); ?>
							</figure>
						<?php endif; ?>

						<h3 class="loop-item__title heading-small"><?php the_title(); ?></h3>

						<?php if ( $excerpt ) : ?>
							<p class="loop-item__excerpt"><?php echo esc_html( $excerpt ); ?></p>
						<?php endif; ?>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php endif; ?>
</div>

<?php
wp_reset_postdata();
