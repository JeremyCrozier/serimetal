<?php
/**
 * About block template.
 *
 * @param array   $block      The block settings and attributes.
 * @param string  $content    The block inner HTML.
 * @param boolean $is_preview True during backend preview render.
 * @param integer $post_id    The Post ID.
 * @param array   $context    The context provided to the block by the post or its parent block.
 */


/**
* Render a text card and its image.
*
* @param mixed   $panel      ACF group.
* @param boolean $is_preview True in the editor preview.
*/

if ( ! function_exists( 'serimetal_about_panel' ) ) {
	function serimetal_about_panel( $panel, $is_preview ) {
		$panel   = is_array( $panel ) ? $panel : array();
		$heading = isset( $panel['heading'] ) ? $panel['heading'] : '';
		$text    = isset( $panel['text'] ) ? $panel['text'] : '';
		$cta     = isset( $panel['cta'] ) && is_array( $panel['cta'] ) ? $panel['cta'] : array();
		$image   = ! empty( $panel['image'] ) ? (int) $panel['image'] : 0;

		if ( ! $heading && ! $text && ! $image && empty( $cta['url'] ) ) {
			return;
		}
		?>
		<div class="about-panel<?php echo $image ? '' : ' about-panel--text'; ?>">
			<div class="about-card">
				<?php if ( $heading || $text ) : ?>
					<div class="about-card__content">
						<?php if ( $heading ) : ?>
							<h3 class="heading-small"><?php echo esc_html( $heading ); ?></h3>
						<?php endif; ?>
						<?php if ( $text ) : ?>
							<?php echo wp_kses_post( $text ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $cta['url'] ) ) : ?>
					<?php
					$target   = ! empty( $cta['target'] ) ? $cta['target'] : '_self';
					?>
					<a class="btn-filled-primary" href="<?php echo esc_url( $cta['url'] ); ?>" target="<?php echo esc_attr( $target ); ?>">
						<?php echo esc_html( ! empty( $cta['title'] ) ? $cta['title'] : 'En savoir plus' ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( $image ) : ?>
				<figure class="about-card__media">
					<?php echo wp_get_attachment_image( $image, 'medium', false, array( 'class' => 'parallax' ) ); ?>
				</figure>
			<?php endif; ?>
		</div>
		<?php
	}
}

$intro         = get_field( 'intro' );
$block_1       = get_field( 'block_1' );
$block_2       = get_field( 'block_2' );
$key_elements  = get_field( 'key_elements' );
$intro         = is_array( $intro ) ? $intro : array();
$intro_heading = isset( $intro['heading'] ) ? $intro['heading'] : '';
$intro_text    = isset( $intro['text'] ) ? $intro['text'] : '';
$has_intro     = $intro_heading || $intro_text;
$block_1       = is_array( $block_1 ) ? $block_1 : array();
$block_2       = is_array( $block_2 ) ? $block_2 : array();
$block_1_cta   = isset( $block_1['cta'] ) && is_array( $block_1['cta'] ) ? $block_1['cta'] : array();
$block_2_cta   = isset( $block_2['cta'] ) && is_array( $block_2['cta'] ) ? $block_2['cta'] : array();
$has_block_1   = ! empty( $block_1['heading'] ) || ! empty( $block_1['text'] ) || ! empty( $block_1['image'] ) || ! empty( $block_1_cta['url'] );
$has_block_2   = ! empty( $block_2['heading'] ) || ! empty( $block_2['text'] ) || ! empty( $block_2['image'] ) || ! empty( $block_2_cta['url'] );
$key_elements  = is_array( $key_elements ) ? $key_elements : array();
$stats         = array();

foreach ( $key_elements as $item ) {
	if ( ! is_array( $item ) ) {
		continue;
	}

	$stat_main  = isset( $item['main_text'] ) ? $item['main_text'] : '';
	$stat_extra = isset( $item['secondary_text'] ) ? $item['secondary_text'] : '';

	if ( ! $stat_main && ! $stat_extra ) {
		continue;
	}

	$stats[] = array(
		'main'  => $stat_main,
		'extra' => $stat_extra,
	);
}

$has_stats = ! empty( $stats );
// Side-by-side layout: intro and stats on the left, block 1 on the right.
$solo      = $has_block_1 && ! $has_block_2;

$classes   = array( 'about-block' );

$wrapper_attributes = ! empty( $is_preview )
	? 'class="' . esc_attr( implode( ' ', $classes ) ) . '"'
	: get_block_wrapper_attributes(
		array(
			'class' => implode( ' ', $classes ),
		)
	);
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php if ( $solo ) : ?>
		<div class="about-row about-row--solo">
			<?php if ( $has_intro || $has_stats ) : ?>
				<div class="about-solo">
					<?php if ( $has_intro ) : ?>
						<div class="about-intro">
							<?php if ( $intro_heading ) : ?>
								<h2 class="heading-large title-divider"><?php echo esc_html( $intro_heading ); ?></h2>
							<?php endif; ?>
							<?php echo wp_kses_post( $intro_text ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $has_stats ) : ?>
						<ul class="about-stats about-stats--compact">
							<?php foreach ( $stats as $stat ) : ?>
								<li class="about-stat">
									<div class="about-stat__copy">
										<?php if ( $stat['main'] ) : ?>
											<p class="heading-small"><?php echo esc_html( $stat['main'] ); ?></p>
										<?php endif; ?>
										<?php if ( $stat['extra'] ) : ?>
											<p class="about-stat__caption"><?php echo esc_html( $stat['extra'] ); ?></p>
										<?php endif; ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php serimetal_about_panel( $block_1, $is_preview ); ?>
		</div>
	<?php else : ?>
		<?php if ( $has_intro || $has_block_1 ) : ?>
			<div class="about-row">
				<?php if ( $has_intro ) : ?>
					<div class="about-intro">
						<?php if ( $intro_heading ) : ?>
							<h2 class="heading-large title-divider"><?php echo esc_html( $intro_heading ); ?></h2>
						<?php endif; ?>
						<?php if ( $intro_text ) : ?>
							<?php echo wp_kses_post( $intro_text ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php serimetal_about_panel( $block_1, $is_preview ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $has_block_2 || $has_stats ) : ?>
			<div class="about-row about-row--media">
				<?php serimetal_about_panel( $block_2, $is_preview ); ?>

				<?php if ( $has_stats ) : ?>
					<div class="about-stats">
						<?php foreach ( $stats as $stat ) : ?>
							<div class="about-stat">
								<div class="about-stat__copy">
									<?php if ( $stat['main'] ) : ?>
										<p class="heading-medium"><?php echo esc_html( $stat['main'] ); ?></p>
									<?php endif; ?>
									<?php if ( $stat['extra'] ) : ?>
										<p class="about-stat__caption"><?php echo esc_html( $stat['extra'] ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
