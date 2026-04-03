<?php
/*
 * Plugin Name: Kidz Wishlist
 * Version: 2.0
 * Description: Wishlist plugin for the Kidz theme.
 * Author: parkofideas.com
 * Author URI: https://parkofideas.com
 * Text Domain: ideapark-wishlist
 * Domain Path: /lang/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IDEAPARK_WISHLIST_VERSION', '2.0' );

$theme_obj = wp_get_theme();

if ( empty( $theme_obj ) || strtolower( $theme_obj->get( 'TextDomain' ) ) != 'kidz' && strtolower( $theme_obj->get( 'TextDomain' ) ) != 'kidz-child' ) {
	add_filter( 'plugin_row_meta', function ( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = array(
				'warning' => '<b>' . esc_html__( 'This plugin works only with Kidz theme', 'ideapark-wishlist' ) . '</b>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}, 10, 2 );

	return;
}

require_once( dirname( __FILE__ ) . '/includes/class-ideapark-wishlist.php' );

if ( ! function_exists( 'Ideapark_Wishlist' ) ) {
	function Ideapark_Wishlist() {
		return Ideapark_Wishlist::instance( __FILE__, IDEAPARK_WISHLIST_VERSION );
	}

	Ideapark_Wishlist();
}

if ( ! function_exists( 'ideapark_is_wishlist_page' ) ) {
	function ideapark_is_wishlist_page() {
		global $post;

		return ( is_page() && ideapark_mod( 'wishlist_page' ) && ideapark_mod( 'wishlist_enabled' ) && class_exists( 'Ideapark_Wishlist' ) && apply_filters( 'wpml_object_id', ideapark_mod( 'wishlist_page' ), 'any' ) == $post->ID );
	}
}