<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <?php wp_head(); ?>

    <?php if (!current_user_can('administrator') && WP_ENVIRONMENT_TYPE != 'local') : ?>
        <!-- Google Tag Manager -->
        <!-- <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-K8DVSQRL');</script> -->
        <!-- End Google Tag Manager -->
    <?php endif; ?> 

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<header id="header">
    <div class="inside">
        <div class="header-left">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" aria-label="<?php bloginfo('name'); ?>" title="Accueil">
                <img src="/wp-content/uploads/icons/logo-Serimetal.svg" alt="<?php bloginfo('name'); ?>" width="130" height="35" class="logo-desktop">
                <img src="/wp-content/uploads/icons/logo-Serimetal-mobile.svg" alt="<?php bloginfo('name'); ?>" width="32" height="32" class="logo-mobile">
                <span class="screen-reader-text"><?php bloginfo('name'); ?></span>
            </a>
            
            <nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'Serimetal'); ?>">
                <?php wp_nav_menu( array(  'menu' => 'menu', 'container' => '', 'menu_class' => 'menu menu-header' ) ); ?>
            </nav>
        </div>
        
        <div class="header-right">
            <?php wp_nav_menu( array(  'menu' => 'cta', 'container' => '', 'menu_class' => 'menu menu-cta' ) ); ?>
        </div>
        <button class="burger-menu-button" aria-label="Menu">
            <img src="/wp-content/uploads/icons/menu.svg" alt="Ouvrir le menu" width="40" height="40" class="burger-menu-icon">
            <img src="/wp-content/uploads/icons/menu-close.svg" alt="Fermer le menu" width="40" height="40" class="burger-menu-icon close-menu">
            <span class="screen-reader-text">Menu</span>
        </button>
    </div>
</header>

<main id="main">
