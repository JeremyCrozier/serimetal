<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php get_header(); ?>

<section class="section-hero">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Welcome to our website</h1>
            </div>
        </div>
    </div>
</section>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
get_template_part( the_content(), get_post_format() );
endwhile; endif; ?>

<?php  get_footer(); ?>