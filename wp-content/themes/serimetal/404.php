<?php
/**
 * Template 404 - Page Not Found
 * 
 * @package Serimetal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

block_template_part( '404' );

get_footer();
