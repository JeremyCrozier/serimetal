<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <?php wp_head(); ?>
    
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<header id="header">
    <div class="inside">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" aria-label="<?php bloginfo('name'); ?>" title="Accueil">
            <img src="/wp-content/uploads/icons/logo-serimetal-blue-white.svg" alt="<?php bloginfo('name'); ?>" width="220" height="32" class="logo-desktop">
            <span class="screen-reader-text"><?php bloginfo('name'); ?></span>
        </a>
        
        <nav id="main-nav" class="main-nav" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'Serimetal'); ?>">
            <?php wp_nav_menu( array(  'menu' => 'menu', 'container' => '', 'menu_class' => 'menu menu-header' ) ); ?>
        </nav>
        <button class="burger-menu-button" type="button" aria-label="Menu" aria-expanded="false" aria-controls="main-nav">
            <svg class="burger-menu-icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
            <path d="M15 10V12H1V10H15ZM15 5V7H1V5H15Z" fill="currentColor"/>
            </svg>
            <svg class="burger-menu-icon close-menu" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
            <path d="M13.626 3.413L9.227 7.813 13.313 11.898 11.898 13.313 7.813 9.227 3.727 13.313 2.313 11.898 6.398 7.813 1.999 3.413 3.413 1.999 7.813 6.398 12.212 1.999 13.626 3.413Z" fill="currentColor"/>
            </svg>
            <span class="screen-reader-text">Menu</span>
        </button>
    </div>
</header>

<main id="main">
<?php
if ( function_exists('yoast_breadcrumb') && ! is_front_page() ) {
  yoast_breadcrumb('<nav id="breadcrumbs" class="body-small" aria-label="breadcrumb">','</nav>');
}
?>
