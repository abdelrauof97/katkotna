<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! function_exists( 'ideapark_get_wvs_get_option' ) ) {
	function ideapark_get_wvs_get_option( $option, $default = null ) {
		$options = get_option( 'woo_variation_swatches' );

		if ( current_theme_supports( 'woo_variation_swatches' ) ) {
			$theme_support = get_theme_support( 'woo_variation_swatches' );
			$default       = isset( $theme_support[0][ $option ] ) ? $theme_support[0][ $option ] : $default;
		}

		return isset( $options[ $option ] ) ? $options[ $option ] : $default;
	}
}

if ( ! function_exists( 'ideapark_swatches_plugin_on' ) ) {
	function ideapark_swatches_plugin_on() {
		return function_exists( 'woo_variation_swatches' ) ? 1 : 0;
	}
}

if ( ! function_exists( 'ideapark_get_taxonomy_type' ) ) {
	function ideapark_get_taxonomy_type( $taxonomy ) {
		if ( ideapark_swatches_plugin_on() && ideapark_woocommerce_on() ) {
			$get_attribute  = woo_variation_swatches()->get_frontend()->get_attribute_taxonomy_by_name( $taxonomy );
			$attribute_type = ( $get_attribute ) ? $get_attribute->attribute_type : '';

			return $attribute_type;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'ideapark_get_product_attribute_color' ) ) {
	function ideapark_get_product_attribute_color( $term, $data = [] ) {
		$term_id = 0;
		if ( is_numeric( $term ) ) {
			$term_id = $term;
		}
		if ( is_object( $term ) ) {
			$term_id = $term->term_id;
		}

		return get_term_meta( $term_id, 'product_attribute_color', true );
	}
}

if ( ! function_exists( 'ideapark_get_product_attribute_color_secondary' ) ) {
	function ideapark_get_product_attribute_color_secondary( $term, $data = [] ) {
		$term_id = 0;
		if ( is_numeric( $term ) ) {
			$term_id = $term;
		}
		if ( is_object( $term ) ) {
			$term_id = $term->term_id;
		}

		return wc_string_to_bool( get_term_meta( $term_id, 'is_dual_color', true ) ) ? get_term_meta( $term_id, 'secondary_color', true ) : '';
	}
}

if ( ! function_exists( 'ideapark_get_product_attribute_image' ) ) {
	function ideapark_get_product_attribute_image( $term, $data = [] ) {
		$term_id = 0;
		if ( is_numeric( $term ) ) {
			$term_id = $term;
		}
		if ( is_object( $term ) ) {
			$term_id = $term->term_id;
		}

		return get_term_meta( $term_id, 'product_attribute_image', true );
	}
}


function ideapark_woocommerce_functions() {
	if ( ! function_exists( 'wc_query_string_form_fields' ) ) {
		function wc_query_string_form_fields( $values = null, $exclude = [], $current_key = '', $return = false ) {
			if ( is_null( $values ) ) {
				$values = $_GET; // WPCS: input var ok, CSRF ok.
			}
			$html = '';

			foreach ( $values as $key => $value ) {
				if ( in_array( $key, $exclude, true ) ) {
					continue;
				}
				if ( $current_key ) {
					$key = $current_key . '[' . $key . ']';
				}
				if ( is_array( $value ) ) {
					$html .= wc_query_string_form_fields( $value, $exclude, $key, true );
				} else {
					$html .= '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( wp_unslash( $value ) ) . '" />';
				}
			}

			if ( $return ) {
				return $html;
			} else {
				echo ideapark_wrap( $html );
			}
		}
	}

	if ( ! function_exists( 'wc_get_cart_remove_url' ) ) {
		function wc_get_cart_remove_url( $cart_item_key ) {
			$cart_page_url = wc_get_page_permalink( 'cart' );

			return apply_filters( 'woocommerce_get_remove_url', $cart_page_url ? wp_nonce_url( add_query_arg( 'remove_item', $cart_item_key, $cart_page_url ), 'woocommerce-cart' ) : '' );
		}
	}
}

add_filter( 'woocommerce_layered_nav_count', function ( $html, $count ) {
	return '<span class="count">' . absint( $count ) . '</span>';
}, 10, 2 );

add_action( 'init', 'ideapark_woocommerce_functions' );