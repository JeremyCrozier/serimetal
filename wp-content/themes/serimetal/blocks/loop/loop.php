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

$pole_terms  = array();
$active_term = null;
$pole_color  = '';

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
} elseif ( 'realisation' === $type ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'pole',
			'hide_empty' => true,
		)
	);

	if ( ! is_wp_error( $terms ) ) {
		$pole_terms  = $terms;
		$active_term = isset( $pole_terms[0] ) ? $pole_terms[0] : null;
		$pole_color  = $active_term ? SM_Ajax::pole_color( $active_term ) : '';
	}
} else {
	$query_args['post_type'] = $type;
	$query_args['orderby']   = 'date';
	$query_args['order']     = 'DESC';
}

$disabled = ! empty( $is_preview ) ? ' style="pointer-events: none"' : '';
$classes  = array( 'block-loop', 'block-loop--' . $type );

if ( 'realisation' === $type && $pole_color ) {
	$classes[] = 'has-pole-color';
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => implode( ' ', $classes ),
		'style' => $pole_color ? '--pole-color: ' . $pole_color . ';' : '',
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( 'realisation' === $type ) : ?>
		<?php if ( $pole_terms ) : ?>
			<div class="realisation-filters"<?php echo $disabled; ?>>
				<div class="realisation-filters__tabs" role="tablist">
					<?php foreach ( $pole_terms as $term ) : ?>
						<?php
						$term_color = SM_Ajax::pole_color( $term );
						$is_active  = $active_term && (int) $active_term->term_id === (int) $term->term_id;
						?>
						<button
							type="button"
							class="realisation-filter<?php echo $is_active ? ' is-active' : ''; ?>"
							role="tab"
							data-term="<?php echo esc_attr( $term->term_id ); ?>"
							aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
							<?php echo $term_color ? 'style="--tab-color: ' . esc_attr( $term_color ) . ';"' : ''; ?>
						>
							<?php echo esc_html( $term->name ); ?>
						</button>
					<?php endforeach; ?>
				</div>
				<button type="button" class="btn-filled-secondary realisation-filter" data-term="0">
					<?php echo esc_html( 'Voir toutes les autres' ); ?>
					<i class="ph ph-caret-right" aria-hidden="true"></i>
				</button>
			</div>
		<?php endif; ?>

		<ul class="loop" <?php echo $disabled; ?>>
			<?php echo SM_Ajax::realisation_cards_html( $active_term ? $active_term->term_id : 0 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</ul>
	<?php else : ?>
		<?php
		$query = new WP_Query( $query_args );
		?>
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
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</div>
