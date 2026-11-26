<?php
/**
 * Template 404 - Page Not Found
 * 
 * @package Serimetal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

<section id="page-404" class="block-section bg--default wp-block-acf-section">
    <div class="inside">
        <h1 class="heading-xlarge">Erreur 404</h1>
        <p class="heading-medium">page non trouvée</p>
		<a href="<?php echo home_url(); ?>" class="btn-filled-secondary">Retourner à l'accueil</a>
    </div>
</section>

<?php get_footer(); ?>
