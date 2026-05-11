<?php
/**
 * Kidz-Child functions and definitions
 *
 * @package kidz-child
 */

/** Enqueue the child theme stylesheet **/
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'kidz-child-style', get_stylesheet_directory_uri() . '/style.css', PHP_INT_MAX );
}, PHP_INT_MAX );
