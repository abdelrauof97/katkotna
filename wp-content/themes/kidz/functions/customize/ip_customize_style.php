<?php

function ideapark_theme_colors() {
	return [
		'text_color'             => $text_color = esc_attr( ideapark_mod_hex_color_norm( 'base_color_custom', '#404E65' ) ),
		'accent_color'           => $accent_color = esc_attr( ideapark_mod_hex_color_norm( 'accent_color_custom', '#56B0F2' ) ),
		'accent_color_2'         => $accent_color_2 = esc_attr( ideapark_mod_hex_color_norm( 'accent_color_2_custom', '#FF5B4B' ) ),
		'star_color'             => $star_color = esc_attr( ideapark_mod_hex_color_norm( 'star_color_custom', $accent_color ) ),
		'text_color_light'       => ideapark_hex_to_rgb_overlay( '#FFFFFF', $text_color, 0.3 ),
		'text_color_extra_light' => '#D5E0EC',
		'background_color'       => $background_color = esc_attr( ideapark_mod_hex_color_norm( 'background_color', '#ffffff' ) ),
		'sale_badge_color'       => ideapark_mod_hex_color_norm( 'sale_badge_color_custom', $accent_color ),
		'new_badge_color'        => ideapark_mod_hex_color_norm( 'new_badge_color_custom', '#93C240' ),
		'outofstock_badge_color' => ideapark_mod_hex_color_norm( 'outofstock_badge_color', $text_color ),
		'featured_badge_color'   => ideapark_mod_hex_color_norm( 'featured_badge_color', $accent_color_2 ),
		'footer_color'           => ideapark_mod_hex_color_norm( 'footer_text_color', 'currentColor' ),
	];
}

function ideapark_customize_css( $is_return_value = false ) {

	/**
	 * @var $text_color                string
	 * @var $accent_color              string
	 * @var $accent_color_2            string
	 * @var $star_color                string
	 * @var $text_color_light          string
	 * @var $text_color_extra_light    string
	 * @var $background_color          string
	 * @var $sale_badge_color          string
	 * @var $new_badge_color           string
	 * @var $outofstock_badge_color    string
	 * @var $featured_badge_color      string
	 * @var $footer_color              string
	 */
	extract( ideapark_theme_colors() );
	$lang_postfix = ideapark_get_lang_postfix();
	$theme_font_0 = preg_replace( '~^(custom-|system-)~', '', (string) ( ideapark_mod( 'theme_font_0' . $lang_postfix ) ?: ideapark_mod( 'theme_font_0' ) ) );
	$theme_font_1 = preg_replace( '~^(custom-|system-)~', '', (string) ( ideapark_mod( 'theme_font_1' . $lang_postfix ) ?: ideapark_mod( 'theme_font_1' ) ) );
	$theme_font_2 = preg_replace( '~^(custom-|system-)~', '', (string) ( ideapark_mod( 'theme_font_2' . $lang_postfix ) ?: ideapark_mod( 'theme_font_2' ) ) );

	$icons_count = ( ideapark_woocommerce_on() && ideapark_mod( 'wishlist_enabled' ) && ideapark_mod( 'wishlist_page' ) && class_exists( 'Ideapark_Wishlist' ) ? 1 : 0 ) + ( ideapark_mod( 'icon_search' ) ? 1 : 0 ) + ( ideapark_woocommerce_on() && ideapark_mod( 'icon_cart' ) ? 1 : 0 );

	$custom_css = '
		:root {
			--text-color: ' . $text_color . ';
			--accent-color: ' . $accent_color . ';
			--accent-color-2: ' . $accent_color_2 . ';
			--text-color-light: ' . $text_color_light . ';
			--text-color-extra-light: ' . $text_color_extra_light . ';
			--background-color: ' . $background_color . ';
			--sale-badge-color: ' . $sale_badge_color . ';
			--new-badge-color: ' . $new_badge_color . ';
			--outofstock-badge-color: ' . $outofstock_badge_color . ';
			--featured-badge-color: ' . $featured_badge_color . ';
			--footer-color: ' . $footer_color . ';
			--border-color: #EAEAEA;
			--store-notice-color: ' . esc_attr( ideapark_mod_hex_color_norm( 'store_notice_color', 'white' ) ) . ';
			--store-notice-background: ' . esc_attr( ideapark_mod_hex_color_norm( 'store_notice_background_color', 'var(--accent-color)' ) ) . ';
			
			--font-text: "' . esc_attr( $theme_font_0 ) . '", sans-serif;
			--font-header: "' . esc_attr( $theme_font_2 ) . '", sans-serif;
			--font-big-header: "' . esc_attr( $theme_font_1 ) . '", sans-serif;
			--font-big-header-weight: ' . esc_attr( ideapark_mod( 'theme_font_1_weight' . $lang_postfix ) ) . ';
			
			--image-grid-prop: ' . ( ideapark_mod( 'grid_image_fit' ) == 'contain' ? round( (float) ideapark_mod( 'grid_image_prop' ) * 100 ) : 100 ) . '%;
			--image-product-prop: ' . ( ideapark_mod( 'product_image_fit' ) == 'contain' ? round( (float) ideapark_mod( 'product_image_prop' ) * 100 ) : 100 ) . '%;
			
			--logo-max-width-desktop: ' . round( (float) ideapark_mod( 'logo_zoom' ) * 155 ) . 'px;
			--logo-max-width-mobile: min(max(105px, 100vw - ' . ( 30 * 2 + ( max( $icons_count, 1 ) * 40 ) * 2 ) . 'px), ' . round( (float) ideapark_mod( 'logo_zoom_mobile' ) * 105 ) . 'px);
			
			--mobile-menu-more-accent: url(\'data:image/svg+xml;base64,' . ideapark_b64enc( '<svg width="9" height="13" viewBox="0 0 9 13" xmlns="http://www.w3.org/2000/svg"><path d="M7.377 5.838a.916.916 0 0 1 0 1.295l-4.609 4.608a.916.916 0 0 1-1.295-1.295l4.608-4.608v1.295l-4.57-4.57a.916.916 0 0 1 1.296-1.295l4.57 4.57z" stroke="' . $accent_color . '" fill="' . $accent_color . '" fill-rule="evenodd"/></svg>' ) . '\');
			--mobile-menu-more: url(\'data:image/svg+xml;base64,' . ideapark_b64enc( '<svg width="9" height="13" viewBox="0 0 9 13" xmlns="http://www.w3.org/2000/svg"><path d="M7.377 5.838a.916.916 0 0 1 0 1.295l-4.609 4.608a.916.916 0 0 1-1.295-1.295l4.608-4.608v1.295l-4.57-4.57a.916.916 0 0 1 1.296-1.295l4.57 4.57z" stroke="' . $text_color . '" fill="' . $text_color . '" fill-rule="evenodd"/></svg>' ) . '\');
			--update-icon: url(\'data:image/svg+xml;base64,' . ideapark_b64enc( '<svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg"><path d="M13.929 7.5c-.653 0-.963.46-1.072.946-.391 1.749-2.053 4.411-5.357 4.411a5.357 5.357 0 0 1 0-10.714c1.2 0 2.3.403 3.193 1.071h-1.05a1.072 1.072 0 0 0 0 2.143h3.214c.592 0 1.072-.48 1.072-1.071V1.07a1.072 1.072 0 0 0-2.143 0v.278A7.449 7.449 0 0 0 7.5 0a7.5 7.5 0 1 0 0 15c5.346 0 7.5-5.09 7.5-6.362 0-.778-.569-1.138-1.071-1.138z" fill="' . $accent_color_2 . '" fill-rule="evenodd"/></svg>' ) . '\');
			--star-icon: url(\'data:image/svg+xml;base64,' . ideapark_b64enc( '<svg fill="' . $star_color . '" width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5T1385 1619q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5T365 1569q0-6 2-20l86-500L89 695q-25-27-25-48 0-37 56-46l502-73L847 73q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg>' ) . '\');
			--eye-icon: url(\'data:image/svg+xml;base64,' . ideapark_b64enc( '<svg fill="' . $text_color . '" width="22" height="14" viewBox="0 0 22 14" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.814 7C4.724 9.654 7.7 11.32 11 11.32S17.276 9.654 19.186 7C17.276 4.346 14.301 2.68 11 2.68 7.7 2.68 4.725 4.346 2.814 7ZM.97 6.024C3.213 2.807 6.85.674 11 .674c4.15 0 7.788 2.133 10.032 5.35a1.695 1.695 0 0 1 0 1.953c-2.244 3.216-5.883 5.35-10.032 5.35S3.213 11.192.97 7.976a1.695 1.695 0 0 1 0-1.953Z"/><ellipse cx="11.001" cy="7" rx="2.287" ry="2.183"/></svg>' ) . '\');
			
			--main-menu-font-size: ' . ideapark_mod( 'main_menu_font_size' ) . 'px;
			
			--opacity-transition: opacity 0.3s linear, visibility 0.3s linear;
			--opacity-transform-transition: opacity 0.3s linear, visibility 0.3s linear, transform 0.3s ease-out, box-shadow 0.3s ease-out;
			--hover-transition: opacity 0.15s linear, visibility 0.15s linear, color 0.15s linear, border-color 0.15s linear, background-color 0.15s linear, box-shadow 0.15s linear;
		}
	';

	$main_menu_width = (int) ideapark_mod( 'main_menu_width' );

	if ( ideapark_mod( 'main_menu_responsive' ) ) {
		$custom_css .= '
		@media (min-width: 992px) {
			.header-type-1 .main-menu .product-categories > ul > li,
			.header-type-2:not(.sticky) .main-menu .product-categories > ul > li {
			    max-width: ' . $main_menu_width . 'px !important;
			    margin: 0 20px !important;
			    width: unset !important;
			}
			
			.stickyТак:not(.sticky-type-2) .main-menu .product-categories > ul:not(.main-menu-text-only) > li {
				width: ' . $main_menu_width . 'px !important;
				max-width: unset !important;
			}
			
			.sticky.sticky-type-2 .main-menu .product-categories > ul > li {
			    max-width: ' . $main_menu_width . 'px !important;
			    margin: 0 15px !important;
			    width: unset !important;
			}
			
		}';
	} else {
		$custom_css .= '
		@media (min-width: 992px) {
			.sticky .main-menu .product-categories > ul > li,
			.header-type-1 .main-menu .product-categories > ul > li,
			.header-type-2:not(.sticky) .main-menu .product-categories > ul > li {
			    width: ' . $main_menu_width . 'px !important;
			    max-width: unset !important;
			}
			
			.header-type-1 .main-menu .product-categories ul.main-menu-text-only > li,
			.header-type-2 .main-menu .product-categories ul.main-menu-text-only > li,
			.header-type-1.sticky .main-menu .product-categories ul.main-menu-text-only > li,
			.header-type-2.sticky .main-menu .product-categories ul.main-menu-text-only > li {
				margin-left:  15px !important;
				margin-right: 15px !important;
			}
		}';
	}

	if ( $custom_css ) {
		if ( $is_return_value ) {
			return $custom_css;
		} else {
			wp_add_inline_style( 'ideapark-core-css', $custom_css );
		}
	}
}

function ideapark_uniord( $u ) {
	$k  = mb_convert_encoding( $u, 'UCS-2LE', 'UTF-8' );
	$k1 = ord( substr( $k, 0, 1 ) );
	$k2 = ord( substr( $k, 1, 1 ) );

	return $k2 * 256 + $k1;
}

function ideapark_b64enc( $input ) {

	$keyStr = "ABCDEFGHIJKLMNOP" .
	          "QRSTUVWXYZabcdef" .
	          "ghijklmnopqrstuv" .
	          "wxyz0123456789+/" .
	          "=";

	$output = "";
	$i      = 0;

	do {
		$chr1 = ord( substr( $input, $i ++, 1 ) );
		$chr2 = $i < strlen( $input ) ? ord( substr( $input, $i ++, 1 ) ) : null;
		$chr3 = $i < strlen( $input ) ? ord( substr( $input, $i ++, 1 ) ) : null;

		$enc1 = $chr1 >> 2;
		$enc2 = ( ( $chr1 & 3 ) << 4 ) | ( $chr2 >> 4 );
		$enc3 = ( ( $chr2 & 15 ) << 2 ) | ( $chr3 >> 6 );
		$enc4 = $chr3 & 63;

		if ( $chr2 === null ) {
			$enc3 = $enc4 = 64;
		} else if ( $chr3 === null ) {
			$enc4 = 64;
		}

		$output = $output .
		          substr( $keyStr, $enc1, 1 ) .
		          substr( $keyStr, $enc2, 1 ) .
		          substr( $keyStr, $enc3, 1 ) .
		          substr( $keyStr, $enc4, 1 );
		$chr1   = $chr2 = $chr3 = "";
		$enc1   = $enc2 = $enc3 = $enc4 = "";
	} while ( $i < strlen( $input ) );

	return $output;
}