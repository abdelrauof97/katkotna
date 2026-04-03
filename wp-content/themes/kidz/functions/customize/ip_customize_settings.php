<?php

global $ideapark_customize_custom_css, $ideapark_customize, $ideapark_customize_mods, $ideapark_customize_mods_def, $ideapark_customize_mods_names, $ideapark_customize_mods_images;

$ideapark_customize_custom_css  = [];
$ideapark_customize             = [];
$ideapark_customize_mods        = [];
$ideapark_customize_mods_def    = [];
$ideapark_customize_mods_names  = [];
$ideapark_customize_mods_images = [];
$ideapark_customize_mod_used    = false;

if ( ! function_exists( 'ideapark_init_theme_customize' ) ) {
	function ideapark_init_theme_customize() {
		global $ideapark_customize, $ideapark_customize_mods_def, $ideapark_customize_mods_names, $ideapark_customize_mods_images;

		if ( $ideapark_customize ) {
			return;
		}

		$version = md5( ideapark_mtime( __FILE__ ) . '-' . IDEAPARK_THEME_VERSION );
		if ( $languages = ideapark_active_languages() ) {
			$version .= '_' . implode( '_', array_keys( $languages ) );
		}

		if ( ideapark_is_file( $fn = IDEAPARK_THEME_UPLOAD_DIR . 'customizer_vars.php' ) ) {
			try {
				include( $fn );
			} catch ( \ParseError $e ) {
				unlink( $fn );
				$ideapark_customize = [];
			} catch ( \Throwable $e ) {
				unlink( $fn );
				$ideapark_customize = [];
			}
			if ( ! empty( $ideapark_customize_mods_ver ) && $ideapark_customize_mods_ver == $version && $ideapark_customize ) {
				return;
			}
		}

		if ( ( $data = get_option( 'ideapark_customize' ) ) && ! empty( $data['version'] ) && ! empty( $data['settings'] ) ) {
			if ( $data['version'] == $version ) {
				$ideapark_customize             = $data['settings'];
				$ideapark_customize_mods_def    = get_option( 'ideapark_customize_mods_def', [] );
				$ideapark_customize_mods_names  = get_option( 'ideapark_customize_mods_names', [] );
				$ideapark_customize_mods_images = get_option( 'ideapark_customize_mods_images', [] );

				return;
			} else {
				ideapark_delete_file( IDEAPARK_THEME_UPLOAD_DIR . 'customizer_vars.php' );
				delete_option( 'ideapark_customize' );
				delete_option( 'ideapark_customize_mods_def' );
				delete_option( 'ideapark_customize_mods_names' );
				delete_option( 'ideapark_customize_mods_images' );
			}
		}

		$ideapark_customize = [
			[
				'section'         => 'title_tagline',
				'refresh'         => '#header .logo-wrap',
				'refresh_wrapper' => true,
				'refresh_id'      => 'header_logo',
				'controls'        => [
					'logo'             => [
						'label'             => __( 'Logo', 'kidz' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 50,
					],
					'logo_zoom'        => [
						'label'             => __( 'Logo zoom (Desktop)', 'kidz' ),
						'default'           => 1,
						'type'              => 'slider',
						'sanitize_callback' => 'ideapark_sanitize_float',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 0.2,
						'max'               => 2,
						'step'              => 0.1,
						'priority'          => 51,
						'refresh'           => false,
						'refresh_css'       => true,
					],
					'logo_zoom_mobile' => [
						'label'             => __( 'Logo zoom (Tablet & Mobile)', 'kidz' ),
						'default'           => 1,
						'type'              => 'slider',
						'sanitize_callback' => 'ideapark_sanitize_float',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 0.2,
						'max'               => 2,
						'step'              => 0.1,
						'priority'          => 52,
						'refresh'           => false,
						'refresh_css'       => true,
					],
				],
			],
			[
				'section'  => 'background_image',
				'controls' => [
					'hide_inner_background' => [
						'label'             => __( 'Hide background on inner pages', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

				],
			],
			[
				'title'    => __( 'General Theme Settings', 'kidz' ),
				'priority' => 5,
				'controls' => [
					'home_boxed'           => [
						'label'             => __( 'Layout', 'kidz' ),
						'default'           => 'fullscreen',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'fullscreen'  => __( 'Fullscreen', 'kidz' ),
							'boxed'       => __( 'Boxed', 'kidz' ),
							'boxed-white' => __( 'Boxed with White Background', 'kidz' ),
						],
					],
					'search_type'          => [
						'label'             => __( 'Search type', 'kidz' ),
						'default'           => 'search-type-2',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'search-type-1' => __( 'Fullscreen (ajax)', 'kidz' ),
							'search-type-2' => __( 'Popup (ajax)', 'kidz' ),
							'search-type-3' => __( 'Popup (without ajax)', 'kidz' ),
						],
					],
					'ajax_search_limit'    => [
						'label'             => __( 'Number of products in the live search', 'kidz' ),
						'default'           => 8,
						'min'               => 1,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'search_type' => [ 'search-type-1', 'search-type-2' ],
						],
					],
					'search_products_only' => [
						'label'             => __( 'Search products only', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'search_type' => [ 'search-type-1', 'search-type-2' ],
						],
					],
					'search_by_sku'        => [
						'label'             => __( 'Search by SKU', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'search_type' => [ 'search-type-1', 'search-type-2' ],
						],
					],
					'search_by_category'   => [
						'label'             => __( 'Search by Category name', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'search_by_tag'        => [
						'label'             => __( 'Search by Tag name', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'search_by_brand_info' => [
						'html'              => sprintf( __( '%s Search by Brand name %s', 'kidz' ), '<a href="#" class="ideapark-control-focus" data-control="search_by_brand">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],
					'arrows_type'          => [
						'label'             => __( 'Arrows Prev & Next', 'kidz' ),
						'default'           => 'normal',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'normal'  => __( 'Normal', 'kidz' ),
							'minimal' => __( 'Minimal', 'kidz' ),
						],
					],

					'disable_block_editor' => [
						'label'             => __( 'Disable widget block editor', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'sticky_sidebar' => [
						'label'             => __( 'Sticky sidebar', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'to_top_button' => [
						'label'             => __( 'To Top Button Enable', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'to_top_button_color' => [
						'label'             => __( 'To Top Button color', 'kidz' ),
						'description'       => __( 'Default color if empty', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'dependency'        => [
							'to_top_button' => [ 'not_empty' ],
						],
					],
				],
			],
			[
				'title'       => __( 'Header / Menu Settings', 'kidz' ),
				'description' => __( 'This is a settings section to change header options and upload logo.', 'kidz' ),
				'priority'    => 90,
				'controls'    => [
					'header_settings_info'          => [
						'label'             => __( 'Header Settings', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'header_type'                   => [
						'label'             => __( 'Header type', 'kidz' ),
						'default'           => 'header-type-1',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'header-type-1' => IDEAPARK_THEME_URI . '/img/header-1.png',
							'header-type-2' => IDEAPARK_THEME_URI . '/img/header-2.png',
						],
					],
					'icon_auth'                     => [
						'label'             => __( 'Show Login / My Account icon before cart icon', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'refresh'           => true,
					],
					'icon_search'                   => [
						'label'             => __( 'Show Search button', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'refresh'           => true,
					],
					'icon_cart'                     => [
						'label'             => __( 'Show Cart button', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'refresh'           => true,
					],
					'wishlist_enabled'              => [
						'label'             => __( 'Show Wishlist button', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'refresh'           => true,
						'dependency'        => [
							'wishlist_page' => [ 'not_empty' ],
						],
					],
					'wishlist_is_disabled'          => [
						'label'             => wp_kses( __( 'Wishlist button is not shown because Wishlist Page is not set ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="wishlist_page">' . __( 'here', 'kidz' ) . '</a>', [
							'a' => [
								'href'         => true,
								'data-control' => true,
								'class'        => true
							]
						] ),
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'wishlist_page' => [ 0, '' ],
						],
					],
					'menu_settings_info'            => [
						'label'             => __( 'Main Menu Settings', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'mega_menu'                     => [
						'label'             => __( 'Main menu type', 'kidz' ),
						'default'           => true,
						'type'              => 'radio',
						'choices'           => [
							true  => __( 'Mega Menu', 'kidz' ),
							false => __( 'Product category menu', 'kidz' ),
						],
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'refresh'           => '.main-menu-container',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'main_menu',
						'refresh_callback'  => 'ideapark_mega_menu_init',
					],
					'main_menu_view'                => [
						'label'             => __( 'Main menu view', 'kidz' ),
						'default'           => 'main-menu-icons',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'main-menu-icons'     => __( 'Icons with text', 'kidz' ),
							'main-menu-text-only' => __( 'Only text', 'kidz' ),
						],
						'refresh'           => '.main-menu-container',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'main_menu',
						'refresh_callback'  => 'ideapark_mega_menu_init',
					],
					'main_menu_width'               => [
						'label'             => __( 'Menu item width', 'kidz' ),
						'description'       => __( 'Only for center logo layout with menu icons', 'kidz' ),
						'default'           => 105,
						'type'              => 'slider',
						'sanitize_callback' => 'ideapark_sanitize_abs_int',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 65,
						'max'               => 170,
						'step'              => 1,
						'refresh'           => false,
						'refresh_css'       => '.main-menu-container',
						'refresh_callback'  => 'ideapark_mega_menu_init',
					],
					'main_menu_font_size'           => [
						'label'             => __( 'Menu item font-size', 'kidz' ),
						'default'           => 12,
						'type'              => 'ideapark_sanitize_abs_int',
						'sanitize_callback' => 'sanitize_text_field',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 10,
						'max'               => 14,
						'step'              => 1,
						'refresh'           => false,
						'refresh_css'       => '.main-menu-container',
						'refresh_callback'  => 'ideapark_mega_menu_init',
					],
					'main_menu_responsive'          => [
						'label'             => __( 'Responsive menu item width', 'kidz' ),
						'description'       => __( 'Fixed menu item width by default', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'refresh'           => true,
					],
					'top_menu_settings_info'        => [
						'label'             => __( 'Top Menu Settings', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'top_menu'                      => [
						'label'             => __( 'Enable top menu', 'kidz' ),
						'refresh'           => '#home-top-menu',
						'refresh_wrapper'   => true,
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'top_menu_color'                => [
						'label'             => __( 'Text color', 'kidz' ),
						'description'       => __( 'Default color if empty', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => '#home-top-menu',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'top_menu',
					],
					'top_menu_background_color'     => [
						'label'             => __( 'Top menu background color', 'kidz' ),
						'description'       => __( 'Transparent if empty', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '#home-top-menu',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'top_menu',
						'default'           => '',
					],
					'top_menu_text'                 => [
						'label'             => __( 'Top menu text', 'kidz' ),
						'description'       => __( 'You can put contact phones or working time here', 'kidz' ),
						'type'              => 'text_editor',
						'default'           => '',
						'sanitize_callback' => 'wp_kses_post',
						'refresh'           => '#home-top-menu',
						'refresh_wrapper'   => true,
						'refresh_id'        => 'top_menu',
						'class'             => 'WP_Customize_Text_Editor_Control',
					],
					'top_menu_text_phone_clickable' => [
						'label'             => __( 'Clickable phone number in top menu text', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'top_menu_auth'                 => [
						'label'             => __( 'Show button Login / My Account ', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'refresh'           => true,
					],
					'sticky_menu_settings_info'     => [
						'label'             => __( 'Sticky Menu Settings', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'sticky_menu'                   => [
						'label'             => __( 'Sticky menu', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'sticky_type'                   => [
						'label'             => __( 'Sticky menu view', 'kidz' ),
						'default'           => 'sticky-type-1',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'sticky-type-1' => __( 'Icons only', 'kidz' ),
							'sticky-type-2' => __( 'Text only', 'kidz' ),
						],
					],
				],
			],
			[
				'title'           => __( 'Footer Settings', 'kidz' ),
				'refresh'         => '#footer',
				'refresh_wrapper' => true,
				'refresh_id'      => 'footer',
				'priority'        => 105,
				'controls'        => [

					'footer_layout' => [
						'label'             => __( 'Footer layout', 'kidz' ),
						'default'           => 'default',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'refresh'           => true,
						'choices'           => [
							'default' => __( 'Default (Contact block + 3 Widgets)', 'kidz' ),
							'widgets' => __( '4 Widgets', 'kidz' ),
							'minimal' => __( 'Minimal (Copyright and Social icons)', 'kidz' ),
						],
					],

					'logo_footer'     => [
						'label'             => __( 'Retina Logo (optional)', 'kidz' ),
						'description'       => __( 'Retina Ready Image logo. It should has width 220px (for landscape style logo) or height 220px (for portrait style logo)', 'kidz' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => '#footer .footer-logo',
						'refresh_wrapper'   => true,
						'dependency'        => [
							'footer_layout' => [ 'default' ],
						],
					],
					'footer_contacts' => [
						'label'             => __( 'Contacts', 'kidz' ),
						'description'       => __( 'Only in Widgets Footer Design', 'kidz' ),
						'type'              => 'text_editor',
						'default'           => '',
						'sanitize_callback' => 'wp_kses_post',
						'refresh'           => '#footer .contacts',
						'refresh_wrapper'   => false,
						'class'             => 'WP_Customize_Text_Editor_Control',
						'dependency'        => [
							'footer_layout' => [ 'default' ],
						],
					],

					'footer_copyright'        => [
						'label'             => __( 'Copyright', 'kidz' ),
						'description'       => __( 'Also you can paste shortcode, generated with any  plugin', 'kidz' ),
						'type'              => 'text_editor',
						'default'           => '&copy; Copyright 2022, Kidz WordPress Theme',
						'sanitize_callback' => 'wp_kses_post',
						'class'             => 'WP_Customize_Text_Editor_Control',
						'refresh'           => '#footer .copyright',
						'refresh_wrapper'   => false,
					],
					'footer_text_color'       => [
						'label'             => __( 'Text color', 'kidz' ),
						'description'       => __( 'Default color if empty', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => true,
					],
					'footer_background_color' => [
						'label'             => __( 'Background color', 'kidz' ),
						'description'       => __( 'Default color if empty', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'refresh'           => true,
					],
				],
			],
			[
				'title'           => __( 'Social Media Links', 'kidz' ),
				'description'     => __( 'Add the full url of your social media page e.g http://twitter.com/yoursite', 'kidz' ),
				'refresh'         => '.soc',
				'refresh_wrapper' => true,
				'refresh_id'      => 'soc',
				'priority'        => 130,
				'controls'        => [
					'soc_background_color' => [
						'label'             => __( 'Background color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#404E65',
						'refresh'           => true,
					],
					'soc_color'            => [
						'label'             => __( 'Icon color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '#FFFFFF',
						'refresh'           => true,
					],
					'facebook'             => [
						'label'             => __( 'Facebook url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'instagram'            => [
						'label'             => __( 'Instagram url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'vk'                   => [
						'label'             => __( 'VK url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'ok'                   => [
						'label'             => __( 'OK url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'telegram'             => [
						'label'             => __( 'Telegram url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'whatsapp'             => [
						'label'             => __( 'Whatsapp url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'twitter'              => [
						'label'             => __( 'X (Twitter) url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'youtube'              => [
						'label'             => __( 'YouTube url', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'vimeo'                => [
						'label'             => __( 'Vimeo url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'snapchat'             => [
						'label'             => __( 'Snapchat url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'tiktok'               => [
						'label'             => __( 'TikTok url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'linkedin'             => [
						'label'             => __( 'LinkedIn url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'flickr'               => [
						'label'             => __( 'Flickr url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'pinterest'            => [
						'label'             => __( 'Pinterest url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'tumblr'               => [
						'label'             => __( 'Tumblr url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'dribbble'             => [
						'label'             => __( 'Dribbble url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'github'               => [
						'label'             => __( 'Github url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'custom_soc_info' => [
						'label'             => __( 'Custom Social Icon', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'custom_soc_icon' => [
						'label'             => __( 'Icon', 'kidz' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'custom_soc_url'  => [
						'label'             => __( 'Url', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
				]
			],

			[
				'panel' => 'front_page_builder',
				'title' => __( 'General settings', 'kidz' ),

				'controls' => [

					'front_page_builder_enabled' => [
						'label'             => __( 'Enable Front Page builder', 'kidz' ),
						'description'       => __( 'If Front Page Builder is off - native page content will be shown', 'kidz' ),
						'default'           => true,
						'refresh'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'home_sections' => [
						'label'              => __( 'Sections order', 'kidz' ),
						'description'        => __( 'Drag and drop sections below to set up their order on the Front Page. You can also enable or disable any section.', 'kidz' ),
						'type'               => 'checklist',
						'default'            => 'slider=1|banner-4=1|product-tabs=1|brands=1|big-banner=0|posts=1|reviews=1|text=1|subscribe=0|html=0|shortcode=0',
						'choices'            => [
							'slider'       => __( 'Slider', 'kidz' ),
							'banner-4'     => __( 'Banner block (3/4 per row)', 'kidz' ),
							'big-banner'   => __( 'Big banner', 'kidz' ),
							'product-tabs' => __( 'Product tabs', 'kidz' ),
							'brands'       => __( 'Brands', 'kidz' ),
							'posts'        => __( 'Blog Posts', 'kidz' ),
							'reviews'      => __( 'Reviews', 'kidz' ),
							'text'         => __( 'Home page content', 'kidz' ),
							'html'         => __( 'HTML block', 'kidz' ),
							'shortcode'    => __( 'Shortcode', 'kidz' ),
							'subscribe'    => __( 'Subscribe', 'kidz' ),
						],
						'choices_edit'       => [
							'slider'       => 'home_slider_is_disabled',
							'banner-4'     => 'home_banner_4_is_disabled',
							'big-banner'   => 'home_banner_big_is_disabled',
							'product-tabs' => 'home_tab_products_is_disabled',
							'brands'       => 'home_brands_is_disabled',
							'posts'        => 'home_post_is_disabled',
							'reviews'      => 'home_reviews_is_disabled',
							'text'         => 'home_text_is_disabled',
							'html'         => 'home_html_is_disabled',
							'shortcode'    => 'home_shortcode_is_disabled',
							'subscribe'    => 'home_subscribe_is_disabled',
						],
						'can_add_block'      => [
							'product-tabs',
							'html',
							'shortcode',
						],
						'add_ajax_action'    => 'ideapark_customizer_add_section',
						'delete_ajax_action' => 'ideapark_customizer_delete_section',
						'sortable'           => true,
						'class'              => 'WP_Customize_Checklist_Control',
						'sanitize_callback'  => 'sanitize_text_field',
					],
				],
			],
			[
				'section_id'       => 'slider',
				'title'            => __( 'Slider', 'kidz' ),
				'panel'            => 'front_page_builder',
				'refresh'          => '#home-slider',
				'refresh_wrapper'  => true,
				'refresh_id'       => 'home-slider',
				'refresh_callback' => 'ideapark_init_home_slider',
				'controls'         => [

					'home_slider_is_disabled' => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=slider=1' ],
						],
					],

					'home_fullwidth_slider' => [
						'label'             => __( 'Fullwidth', 'kidz' ),
						'default'           => true,
						'refresh'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'slider_shortcode' => [
						'label'             => __( 'Third-party slider shortcode', 'kidz' ),
						'description'       => __( 'Enter shortcode, if you want to use a third-party slider instead of the theme slider', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'slider_effect' => [
						'label'             => __( 'Effect', 'kidz' ),
						'default'           => 'slide',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'fade'  => __( 'Fade', 'kidz' ),
							'slide' => __( 'Slide', 'kidz' ),
						],
					],

					'slider_items' => [
						'label'             => __( 'Slider Items', 'kidz' ),
						'default'           => 5,
						'step'              => 1,
						'min'               => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'slider_interval' => [
						'label'             => __( 'Autoplay interval (ms)', 'kidz' ),
						'description'       => __( 'Set to zero if you want to disable autoplay', 'kidz' ),
						'default'           => 5000,
						'step'              => 1,
						'min'               => 0,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'slider_hide_arrows' => [
						'label'             => __( 'Hide navigation arrows', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'slider_hide_dots' => [
						'label'             => __( 'Hide navigation dots', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'home_slider_edit' => [
						'html'              => ideapark_wp_kses( __( 'You can change the slides ', 'kidz' ) . '<a href="' . esc_url( admin_url( 'edit.php?post_type=slider' ) ) . '" >' . __( 'here', 'kidz' ) . '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			],
			[
				'section_id'      => 'banner-4',
				'title'           => __( 'Banners (3/4 per row)', 'kidz' ),
				'panel'           => 'front_page_builder',
				'refresh'         => '#home-banners',
				'refresh_wrapper' => true,
				'refresh_id'      => 'home-banners',
				'controls'        => [
					'home_banner_4_is_disabled' => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=banner-4=1' ],
						],
					],
					'home_banners_4_order'      => [
						'label'             => __( 'Sort order', 'kidz' ),
						'default'           => 'fixed',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'fixed'  => __( 'Fixed', 'kidz' ),
							'random' => __( 'Random', 'kidz' ),
						],
					],
					'home_banners_edit'         => [
						'html'              => ideapark_wp_kses( __( 'You can change the banners ', 'kidz' ) . '<a href="' . esc_url( admin_url( 'edit.php?post_type=banner' ) ) . '" >' . __( 'here', 'kidz' ) . '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			],
			[
				'section_id'           => 'big-banner',
				'title'                => __( 'Big banner', 'kidz' ),
				'panel'                => 'front_page_builder',
				'refresh'              => '#home-big-banner',
				'refresh_wrapper'      => true,
				'refresh_id'           => 'home-big-banner',
				'refresh_pre_callback' => 'ideapark_parallax_destroy',
				'refresh_callback'     => 'ideapark_parallax_init',
				'controls'             => [
					'home_banner_big_is_disabled'      => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=big-banner=1' ],
						],
					],
					'home_big_banner_header'           => [
						'label'             => __( 'Header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_subheader'        => [
						'label'             => __( 'Sub header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_button_text'      => [
						'label'             => __( 'Button text', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_text_align'       => [
						'label'             => __( 'Text align', 'kidz' ),
						'section'           => 'background_image',
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 'text-center',
						'choices'           => [
							'text-left'   => __( 'Left', 'kidz' ),
							'text-center' => __( 'Center', 'kidz' ),
							'text-right'  => __( 'Right', 'kidz' ),
						],
					],
					'home_big_banner_link'             => [
						'label'             => __( 'Link', 'kidz' ),
						'description'       => __( 'If button text empty whole banner areal would be the link', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_image'            => [
						'label'             => __( 'Image', 'kidz' ),
						'class'             => 'WP_Customize_Image_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_image_position_x' => [
						'label'             => __( 'Image position X', 'kidz' ),
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 'center',
						'choices'           => [
							'left'   => __( 'Left', 'kidz' ),
							'center' => __( 'Center', 'kidz' ),
							'right'  => __( 'Right', 'kidz' ),
						],
					],
					'home_big_banner_image_position_y' => [
						'label'             => __( 'Image position Y', 'kidz' ),
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 'center',
						'choices'           => [
							'top'    => __( 'Top', 'kidz' ),
							'center' => __( 'Center', 'kidz' ),
							'bottom' => __( 'Bottom', 'kidz' ),
						],
					],
					'home_big_banner_image_size'       => [
						'label'             => __( 'Image size', 'kidz' ),
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => 'auto',
						'choices'           => [
							'auto'    => __( 'Original', 'kidz' ),
							'contain' => __( 'Fit to banner area', 'kidz' ),
							'cover'   => __( 'Fill banner area', 'kidz' ),
						],
					],
					'home_big_banner_parallax'         => [
						'label'             => __( 'Parallax', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'home_big_banner_text_color'       => [
						'label'             => __( 'Text color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#404E65',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_button_color'     => [
						'label'             => __( 'Button color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#404E65',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_background_color' => [
						'label'             => __( 'Background color', 'kidz' ),
						'description'       => __( 'Leave empty for transparent background', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_big_banner_container'        => [
						'label'             => __( 'Boxed view', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],
			[
				'section_id'       => 'product-tabs',
				'title'            => __( 'Product Tabs', 'kidz' ),
				'panel'            => 'front_page_builder',
				'refresh'          => '#home-tabs',
				'refresh_wrapper'  => true,
				'refresh_id'       => 'home-tabs',
				'refresh_callback' => 'ideapark_init_home_tabs',
				'controls'         => [

					'home_tab_products_is_disabled' => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=product-tabs=1' ],
						],
					],

					'home_tab_products' => [
						'label'             => __( 'Products in tab', 'kidz' ),
						'description'       => __( 'The number of products in the home tabs.', 'kidz' ),
						'default'           => 12,
						'step'              => 1,
						'min'               => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'home_tab_orderby' => [
						'label'             => __( 'Order by', 'kidz' ),
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => [
							''           => 'default',
							'date'       => 'date the product was published',
							'id'         => 'post ID of the product',
							'menu_order' => 'menu order',
							'popularity' => 'number of purchases',
							'rand'       => 'random',
							'rating'     => 'average product rating',
							'title'      => 'product title',
						],
					],

					'home_tab_order' => [
						'label'             => __( 'Order', 'kidz' ),
						'default'           => 'ASC',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => [
							'ASC'  => 'ASC',
							'DESC' => 'DESC',
						],
						'dependency'        => [
							'home_tab_orderby' => [ 'not_empty' ]
						],
					],

					'home_tab_carousel' => [
						'label'             => __( 'Carousel', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'home_tab_view_more' => [
						'label'             => __( 'View more button', 'kidz' ),
						'description'       => __( 'For category tabs only', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'home_tab_view_more_item' => [
						'label'             => __( 'Show view more button as last product', 'kidz' ),
						'description'       => __( 'For category tabs with carousel only', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'home_tab_carousel' => [ 1 ],
						],
					],

					'home_product_order' => [
						'label'             => __( 'Tab list', 'kidz' ),
						'description'       => __( 'Add or delete tab, and then drag and drop tabs below to set up their order. You can also enable or disable any tab', 'kidz' ),
						'type'              => 'checklist',
						'default'           => 'featured_products=1|sale_products=1|best_selling_products=1|recent_products=1',
						'choices'           => [],
						'choices_add'       => 'ideapark_customizer_product_tab_list',
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'home_tab_shortcode' => [
						'label'             => __( 'Tab products shortcode', 'kidz' ),
						'description'       => __( 'For tab with woocommerce shortcode', 'kidz' ),
						'default'           => '',
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_product_order' => [ 'search=shortcode=1' ],
						],
					],

					'home_tab_shortcode_manual' => [
						'label'             => '<a href="https://docs.woocommerce.com/document/woocommerce-shortcodes/" target="_blank">' . __( 'Shortcodes included with WooCommerce', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Notice_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_product_order' => [ 'search=shortcode=1' ],
						],
					],

					'home_tab_shortcode_title' => [
						'label'             => __( 'Shortcode tab header', 'kidz' ),
						'default'           => __( 'Products', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_product_order' => [ 'search=shortcode=1' ],
						],
					],

					'home_featured_title' => [
						'label'             => __( 'Featured products tab header', 'kidz' ),
						'default'           => __( 'Featured', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_product_order' => [ 'search=featured_products=1' ],
						],
					],

					'home_sale_title' => [
						'label'             => __( 'Sale products tab header', 'kidz' ),
						'default'           => __( 'On a Sale', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_product_order' => [ 'search=sale_products=1' ],
						],
					],

					'home_best_selling_title' => [
						'label'             => __( 'Best-Selling products tab header', 'kidz' ),
						'default'           => __( 'Bestsellers', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_product_order' => [ 'search=best_selling_products=1' ],
						],
					],

					'home_recent_title' => [
						'label'             => __( 'Recent products tab header', 'kidz' ),
						'default'           => __( 'Latest', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_product_order' => [ 'search=recent_products=1' ],
						],
					],

					'home_tab_background_color' => [
						'label'             => __( 'Background color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'home_tab_padding_top' => [
						'label'             => __( 'Add top padding', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'home_tab_padding_bottom' => [
						'label'             => __( 'Add bottom padding', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],
			[
				'section_id'       => 'brands',
				'title'            => __( 'Brands', 'kidz' ),
				'panel'            => 'front_page_builder',
				'refresh'          => '#home-brand',
				'refresh_wrapper'  => true,
				'refresh_id'       => 'home-brands',
				'refresh_callback' => 'ideapark_init_home_brands_carousel',
				'controls'         => [
					'home_brands_is_disabled'      => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=brands=1' ],
						],
					],
					'home_brands_interval'         => [
						'label'             => __( 'Autoplay interval (ms)', 'kidz' ),
						'description'       => __( 'Set to zero if you want to disable autoplay', 'kidz' ),
						'default'           => 0,
						'step'              => 1,
						'min'               => 0,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],
					'home_brands_background_color' => [
						'label'             => __( 'Background Color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#F4F8FF',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_brands_mobile_dots'      => [
						'label'             => __( 'Show navigation dots on mobile', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'home_brands_edit'             => [
						'html'              => ideapark_wp_kses( __( 'You can change the brands ', 'kidz' ) . '<a href="' . esc_url( admin_url( 'edit.php?post_type=brand' ) ) . '" >' . __( 'here', 'kidz' ) . '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			],
			[
				'section_id'      => 'posts',
				'title'           => __( 'Blog Posts', 'kidz' ),
				'panel'           => 'front_page_builder',
				'refresh'         => '#home-post',
				'refresh_wrapper' => true,
				'refresh_id'      => 'home-posts',
				'controls'        => [
					'home_post_is_disabled'      => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=posts=1' ],
						],
					],
					'home_post_title'            => [
						'label'             => __( 'Section Header', 'kidz' ),
						'default'           => __( 'Blog Posts', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_post_category'         => [
						'label'             => __( 'Posts Category', 'kidz' ),
						'description'       => __( 'Select category if you want the posts from this category to be shown at the bottom of the home page', 'kidz' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Category_Control',
						'sanitize_callback' => 'absint',
					],
					'home_post_background_color' => [
						'label'             => __( 'Background Color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_post_count'            => [
						'label'             => __( 'Posts in section', 'kidz' ),
						'default'           => 4,
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],
				],
			],
			[
				'section_id'       => 'reviews',
				'title'            => __( 'Reviews', 'kidz' ),
				'panel'            => 'front_page_builder',
				'refresh'          => '#home-review',
				'refresh_wrapper'  => true,
				'refresh_id'       => 'home-reviews',
				'refresh_callback' => 'ideapark_init_home_review',
				'controls'         => [
					'home_reviews_is_disabled'     => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=reviews=1' ],
						],
					],
					'home_review_interval'         => [
						'label'             => __( 'Autoplay interval (ms)', 'kidz' ),
						'description'       => __( 'Set to zero if you want to disable autoplay', 'kidz' ),
						'default'           => 0,
						'step'              => 1,
						'min'               => 0,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],
					'home_review_background_color' => [
						'label'             => __( 'Background Color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_review_mobile_dots'      => [
						'label'             => __( 'Show navigation dots on mobile', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'home_review_edit'             => [
						'html'              => ideapark_wp_kses( __( 'You can change the reviews ', 'kidz' ) . '<a href="' . esc_url( admin_url( 'edit.php?post_type=review' ) ) . '" >' . __( 'here', 'kidz' ) . '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			],
			[
				'section_id'      => 'text',
				'title'           => __( 'Home page content', 'kidz' ),
				'panel'           => 'front_page_builder',
				'refresh'         => '#home-text',
				'refresh_wrapper' => true,
				'refresh_id'      => 'home-text',
				'controls'        => [
					'home_text_is_disabled'      => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=text=1' ],
						],
					],
					'home_text_background_color' => [
						'label'             => __( 'Background Color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_text_hide_header'      => [
						'label'             => __( 'Hide Header', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

				],
			],
			[
				'section_id'       => 'html',
				'title'            => __( 'HTML block', 'kidz' ),
				'panel'            => 'front_page_builder',
				'refresh'          => '#home-html',
				'refresh_wrapper'  => true,
				'refresh_id'       => 'home-html',
				'refresh_callback' => 'ideapark_third_party_reload',
				'controls'         => [
					'home_html_is_disabled' => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=html=1' ],
						],
					],
					'home_html_header'      => [
						'label'             => __( 'Header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],


					'home_html_content_type'     => [
						'label'             => __( 'Content Source', 'kidz' ),
						'default'           => 'WYSIWYG',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'WYSIWYG' => __( 'WYSIWYG editor', 'kidz' ),
							'source'  => __( 'Source Code (HTML, JavaScript)', 'kidz' ),
						],
					],
					'home_html_content'          => [
						'label'             => __( 'Content', 'kidz' ),
						'description'       => __( 'Also you can paste shortcode, generated with any  plugin (for example, Contacts Form 7)', 'kidz' ),
						'type'              => 'text_editor',
						'default'           => '',
						'sanitize_callback' => 'wp_kses_post',
						'class'             => 'WP_Customize_Text_Editor_Control',
						'dependency'        => [
							'home_html_content_type' => [ 'WYSIWYG' ],
						],
					],
					'home_html_content_source'   => [
						'label'             => __( 'Content', 'kidz' ),
						'description'       => __( 'Source Code (Shortcode, HTML, JavaScript)', 'kidz' ),
						'type'              => 'textarea',
						'default'           => '',
						'sanitize_callback' => 'ideapark_sanitize_source_code',
						'dependency'        => [
							'home_html_content_type' => [ 'source' ],
						],
					],
					'home_html_background_color' => [
						'label'             => __( 'Background Color', 'kidz' ),
						'description'       => __( 'Leave empty for transparent background', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_html_container'        => [
						'label'             => __( 'Show html inside container', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'home_html_margins'          => [
						'label'             => __( 'Add top and bottom paddings', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],
			[
				'section_id'       => 'shortcode',
				'title'            => __( 'Shortcode block', 'kidz' ),
				'panel'            => 'front_page_builder',
				'refresh'          => '#home-shortcode',
				'refresh_id'       => 'home-shortcode',
				'refresh_callback' => 'ideapark_third_party_reload',
				'refresh_wrapper'  => true,
				'controls'         => [
					'home_shortcode_is_disabled'      => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=shortcode=1' ],
						],
					],
					'home_shortcode_header'           => [
						'label'             => __( 'Header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_shortcode_content'          => [
						'label'             => __( 'Shortcode', 'kidz' ),
						'description'       => __( 'Paste shortcode, generated with any  plugin (for example, Contacts Form 7)', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_shortcode_background_color' => [
						'label'             => __( 'Background Color', 'kidz' ),
						'description'       => __( 'Leave empty for transparent background', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_shortcode_container'        => [
						'label'             => __( 'Show code inside container', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'home_shortcode_margins'          => [
						'label'             => __( 'Add top and bottom paddings', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],
			[
				'section_id'      => 'subscribe',
				'title'           => __( 'Subscribe block', 'kidz' ),
				'panel'           => 'front_page_builder',
				'refresh'         => '#home-subscribe',
				'refresh_id'      => 'home-subscribe',
				'refresh_wrapper' => true,
				'controls'        => [
					'home_subscribe_is_disabled'      => [
						'label'             => __( 'This section is not shown because it is disabled in ', 'kidz' ) . '<a href="#" class="ideapark-control-focus" data-control="home_sections">' . __( 'General settings', 'kidz' ) . '</a>',
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'home_sections' => [ 'search!=subscribe=1' ],
						],
					],
					'home_subscribe_header'           => [
						'label'             => __( 'Header', 'kidz' ),
						'type'              => 'text',
						'default'           => 'Subscribe to Newsletter',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_subscribe_content'          => [
						'label'             => __( 'Shortcode', 'kidz' ),
						'description'       => __( 'Paste subscribe, generated with any subscribe plugin (for example, MailChimp)', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_subscribe_background_color' => [
						'label'             => __( 'Background Color', 'kidz' ),
						'description'       => __( 'Leave empty for transparent background', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#EFF7FF',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'home_subscribe_container'        => [
						'label'             => __( 'Show code inside container', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'home_subscribe_margins'          => [
						'label'             => __( 'Add top and bottom paddings', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],
			[
				'title'    => __( 'Fonts', 'kidz' ),
				'priority' => 45,
				'controls' => [
					'theme_font_0'        => [
						'label'             => __( 'Content font (Google Font)', 'kidz' ),
						'default'           => 'Open Sans',
						'description'       => __( 'Default font: Open Sans', 'kidz' ),
						'sanitize_callback' => 'ideapark_sanitize_font_choice',
						'type'              => 'select',
						'choices'           => 'ideapark_get_font_choices',
					],
					'theme_font_1'        => [
						'label'             => __( 'Main Font 1 (Google Font)', 'kidz' ),
						'default'           => 'Fredoka',
						'description'       => __( 'Default font: Fredoka', 'kidz' ),
						'sanitize_callback' => 'ideapark_sanitize_font_choice',
						'type'              => 'select',
						'choices'           => 'ideapark_get_font_choices',
					],
					'theme_font_1_weight' => [
						'label'             => __( 'Font 1 Weight', 'kidz' ),
						'default'           => '600',
						'description'       => __( 'Default: 600', 'kidz' ),
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => [
							'100' => '100',
							'200' => '200',
							'300' => '300',
							'400' => '400 (normal)',
							'500' => '500',
							'600' => '600',
							'700' => '700 (bold)',
							'800' => '800',
							'900' => '900',
						],
					],
					'theme_font_2'        => [
						'label'             => __( 'Main Font 2 (Google Font)', 'kidz' ),
						'default'           => 'Montserrat',
						'description'       => __( 'Default font: Montserrat', 'kidz' ),
						'sanitize_callback' => 'ideapark_sanitize_font_choice',
						'type'              => 'select',
						'choices'           => 'ideapark_get_font_choices',
					],
					'theme_font_subsets'  => [
						'label'             => __( 'Main Fonts subset (if available)', 'kidz' ),
						'default'           => 'latin-ext',
						'description'       => __( 'Default: Latin Extended', 'kidz' ),
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => 'ideapark_get_google_font_subsets',
					],
				],
			],
			[
				'title'      => __( 'Post/Page Settings', 'kidz' ),
				'priority'   => 107,
				'section_id' => 'post_page',
				'controls'   => [

					'post_hide_featured_image' => [
						'label'             => __( 'Hide Featured Image', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_category'       => [
						'label'             => __( 'Hide Category', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_date'           => [
						'label'             => __( 'Hide Date', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_share'          => [
						'label'             => __( 'Hide Share Buttons', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_tags'           => [
						'label'             => __( 'Hide Tags', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_comment'        => [
						'label'             => __( 'Hide Comment Link', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_author'         => [
						'label'             => __( 'Hide Author Info', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_postnav'        => [
						'label'             => __( 'Hide Post Navigation', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'post_hide_related'        => [
						'label'             => __( 'Hide Related Posts', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'sidebar_info' => [
						'label'             => __( 'Show sidebar', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'sidebar_blog' => [
						'label'             => __( 'Blog', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'sidebar_post' => [
						'label'             => __( 'Post', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'sidebar_page' => [
						'label'             => __( 'Page', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],

			[
				'title'      => __( 'Contact Page Settings', 'kidz' ),
				'priority'   => 108,
				'section_id' => 'contact',
				'controls'   => [
					'contact_phones'         => [
						'label'             => __( 'Phones', 'kidz' ),
						'type'              => 'text_editor',
						'default'           => '1-800-312-2121',
						'sanitize_callback' => 'wp_kses_post',
						'refresh'           => '#contact-block-phones',
						'refresh_wrapper'   => false,
						'class'             => 'WP_Customize_Text_Editor_Control',
					],
					'contact_email'          => [
						'label'             => __( 'Email', 'kidz' ),
						'type'              => 'text_editor',
						'default'           => '<a href="mailto:example@domain.net">example@domain.net</a>',
						'sanitize_callback' => 'wp_kses_post',
						'refresh'           => '#contact-block-email',
						'refresh_wrapper'   => false,
						'class'             => 'WP_Customize_Text_Editor_Control',
					],
					'contact_address'        => [
						'label'             => __( 'Address', 'kidz' ),
						'type'              => 'text_editor',
						'default'           => '555 California str, Suite 100<br>San Francisco, CA 94107',
						'sanitize_callback' => 'wp_kses_post',
						'refresh'           => '#contact-block-address',
						'refresh_wrapper'   => false,
						'class'             => 'WP_Customize_Text_Editor_Control',
					],
					'contact_form_shortcode' => [
						'label'             => __( 'Shortcode with contact form', 'kidz' ),
						'description'       => __( 'Paste shortcode, generated with any form plugin (for example, Contacts Form 7). You can also paste any other shortcodes, changing thus the purpose of this section', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'refresh'           => '#contact-form',
						'transport'         => 'refresh',
						'refresh_wrapper'   => false,
						'sanitize_callback' => 'sanitize_text_field',
					],
					'contact_map_shortcode'  => [
						'label'             => __( 'Map code', 'kidz' ),
						'description'       => __( 'Paste embed html code or shortcode, generated with any map plugin', 'kidz' ),
						'type'              => 'textarea',
						'default'           => '',
						'refresh'           => '#contact-map',
						'transport'         => 'refresh',
						'refresh_wrapper'   => false,
						'sanitize_callback' => 'ideapark_sanitize_embed_field',
					],
				],
			],

			[
				'title'       => __( 'Performance', 'kidz' ),
				'description' => __( 'Use these options to put your theme to a high speed as well as save your server resources!', 'kidz' ),
				'priority'    => 130,
				'controls'    => [
					'use_minified_css'          => [
						'label'             => __( 'Use minified CSS', 'kidz' ),
						'description'       => __( 'Load all theme css files combined and minified into a single file', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'use_minified_js'           => [
						'label'             => __( 'Use minified JS', 'kidz' ),
						'description'       => __( 'Load all theme js files combined and minified into a single file', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'load_jquery_in_footer'     => [
						'label'             => __( 'Load jQuery in footer', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'lazyload'                  => [
						'label'             => __( 'Lazy load images', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'google_fonts_display_swap' => [
						'label'             => __( 'Use parameter display=swap for Google Fonts', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'js_delay'                  => [
						'label'             => __( 'Delay JavaScript execution', 'kidz' ),
						'description'       => __( 'Improves performance by delaying the execution of JavaScript until user interaction (e.g. scroll, click). ', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				],
			],
			[
				'section'  => 'colors',
				'controls' => [

					'accent_color_custom' => [
						'label'             => __( 'Accent Color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#56B0F2',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],

					'accent_color_2_custom' => [
						'label'             => __( 'Second Accent Color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#FF5B4B',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],

					'base_color_custom' => [
						'label'             => __( 'Base Color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '#404E65',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],

					'star_color_custom' => [
						'label'             => __( 'Star Rating Color', 'kidz' ),
						'description'       => __( 'Accent is used if nothing is specified', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],
				]
			],
			[
				'panel'    => 'woocommerce',
				'section'  => 'woocommerce_store_notice',
				'controls' => [
					'store_notice'                  => [
						'label'             => __( 'Store notice placement', 'kidz' ),
						'default'           => 'top',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'priority'          => 50,
						'choices'           => [
							'top'    => __( 'At the top of the page', 'kidz' ),
							'bottom' => __( 'At the bottom of the screen (fixed)', 'kidz' ),
						],
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
					'store_notice_button_hide'      => [
						'label'             => __( 'Hide button', 'kidz' ),
						'priority'          => 51,
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
					'store_notice_button_text'      => [
						'label'             => __( 'Custom button text', 'kidz' ),
						'description'       => __( 'Default if empty', 'kidz' ),
						'type'              => 'text',
						'priority'          => 51,
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'store_notice_button_hide' => [ 'is_empty' ],
							'woocommerce_demo_store'   => [ 'not_empty' ]
						],
					],
					'store_notice_color'            => [
						'label'             => __( 'Text color ', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'priority'          => 52,
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
					'store_notice_background_color' => [
						'label'             => __( 'Background color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => '',
						'priority'          => 53,
						'dependency'        => [
							'woocommerce_demo_store' => [ 'not_empty' ]
						],
					],
				]
			],

			[
				'panel'      => 'woocommerce',
				'title'      => __( 'General', 'kidz' ),
				'priority'   => 0,
				'section_id' => 'woocommerce',
				'controls'   => [
					'disable_purchase'              => [
						'label'             => __( 'Disable purchase', 'kidz' ),
						'description'       => __( 'Completely disables the ability to order products, turning the site into a showcase of goods.', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'hide_prices'                   => [
						'label'             => __( 'Hide prices', 'kidz' ),
						'description'       => __( 'Hide prices in the grid and on the product page.', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'hide_uncategorized'            => [
						'label'             => __( 'Hide Uncategorized (default) category', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'hidden_product_category'       => [
						'label'             => __( 'Hide category with products', 'kidz' ),
						'description'       => __( 'Products will be available only by direct link', 'kidz' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Product_Categories_Control',
						'sanitize_callback' => 'absint',
					],
					'hide_variable_price_range'     => [
						'label'             => __( 'Hide price range in the variable product', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'product_breadcrumbs_home'      => [
						'label'             => __( 'Show Home in breadcrumbs', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'product_thumbnail_cart_mobile' => [
						'label'             => __( 'Show product thumbnails on the Cart page on mobile devices', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				]
			],
			[
				'panel'      => 'woocommerce',
				'title'      => __( 'Product Grid', 'kidz' ),
				'priority'   => 2,
				'section_id' => 'woocommerce_grid',
				'controls'   => [

					'is_shop_configured' => [
						'label'             => '',
						'description'       => '',
						'type'              => 'hidden',
						'default'           => 'ideapark_is_shop_configured',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'class'             => 'WP_Customize_Hidden_Control',
					],

					'shop_is_not_configured' => [
						'label'             => wp_kses( __( 'Shop Page is not configured ', 'kidz' ) . '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=products' ) ) . '" >' . __( 'here', 'kidz' ) . '</a>', [
							'a' => [
								'href'         => true,
								'data-control' => true,
								'class'        => true
							]
						] ),
						'class'             => 'WP_Customize_Warning_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'is_shop_configured' => [ 0, '' ],
						],
					],

					'products_per_page' => [
						'label'             => __( 'Products per page', 'kidz' ),
						'default'           => 12,
						'min'               => 1,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'product_grid_pagination' => [
						'label'             => __( 'Paging', 'kidz' ),
						'default'           => 'pagination',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'pagination' => __( 'Pagination', 'kidz' ),
							'loadmore'   => __( 'Load More', 'kidz' ),
							'infinity'   => __( 'Infinity', 'kidz' ),
						],
					],

					'product_grid_load_more_text' => [
						'label'             => __( 'Load More button text', 'kidz' ),
						'type'              => 'text',
						'default'           => __( 'Load More', 'kidz' ),
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_grid_pagination' => [ 'loadmore' ],
						],
					],

					'quickview_enabled' => [
						'label'             => __( 'Quick view', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_preview_rating' => [
						'label'             => __( 'Star rating', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'shop_hide_sidebar' => [
						'label'             => __( 'Hide sidebar', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_category_bottom_description' => [
						'label'             => __( 'Show category description at the bottom of the page', 'kidz' ),
						'description'       => __( 'By default, the description is displayed at the top of the category page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'grid_image_info' => [
						'label'             => __( 'Product Image', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'grid_image_fit' => [
						'label'             => __( 'Main image fit in product grid', 'kidz' ),
						'default'           => 'cover',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'contain' => IDEAPARK_THEME_URI . '/img/thumb-contain.png',
							'cover'   => IDEAPARK_THEME_URI . '/img/thumb-cover.png',
						],
					],

					'grid_image_prop' => [
						'label'             => __( 'Image aspect ratio in grid (height / width)', 'kidz' ),
						'default'           => 1,
						'type'              => 'slider',
						'sanitize_callback' => 'ideapark_sanitize_float',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 0.4,
						'max'               => 2,
						'step'              => 0.1,
						'dependency'        => [
							'grid_image_fit' => [ 'contain' ],
						],
					],

					'switch_image_on_hover' => [
						'label'             => __( 'Switch image on hover', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'quickview_product_zoom' => [
						'label'             => __( 'Images zoom on touch or mouseover in Quick view', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'quickview_enabled' => [ 'not_empty' ],
						],
					],

					'quickview_product_zoom_mobile_hide' => [
						'label'             => __( 'Hide zoom on mobile in Quick view', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'quickview_enabled'      => [ 'not_empty' ],
							'quickview_product_zoom' => [ 'not_empty' ],
						],
					],

					'product_mobile_settings_info' => [
						'label'             => __( 'Mobile Settings', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'product_small_mobile' => [
						'label'             => __( 'Mobile Layout', 'kidz' ),
						'default'           => 'default',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'default' => __( '1 product per row', 'kidz' ),
							'compact' => __( '2 products per row', 'kidz' ),
							'small'   => __( 'Compact', 'kidz' ),
						],
					],

					'filter_button_text' => [
						'label'             => __( 'Filter button text', 'kidz' ),
						'default'           => __( 'Filter', 'kidz' ),
						'type'              => 'text',
						'sanitize_callback' => 'sanitize_text_field',
					],
				]
			],
			[
				'panel'      => 'woocommerce',
				'section_id' => 'woocommerce_product_page',
				'title'      => __( 'Product Page', 'kidz' ),
				'priority'   => 2,
				'controls'   => [
					'product_tabs' => [
						'label'             => __( 'Product Tabs (Default)', 'kidz' ),
						'description'       => __( 'Enable or disable tab, and then drag and drop tabs below to set up their order', 'kidz' ),
						'type'              => 'checklist',
						'default'           => 'description=1|additional_information=1|reviews=1',
						'choices'           => [
							'description'            => __( 'Description', 'woocommerce' ),
							'additional_information' => __( 'Additional information', 'woocommerce' ),
							'reviews'                => __( 'Reviews', 'woocommerce' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'description_tab_header' => [
						'label'             => __( 'Custom header of the "Description" tab', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_tabs' => [ 'search=description=1' ],
						],
					],

					'additional_information_tab_header' => [
						'label'             => __( 'Custom header of the "Additional information" tab', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_tabs' => [ 'search=additional_information=1' ],
						],
					],

					'product_tabs_as_sections_desktop' => [
						'label'             => __( 'Product tabs as sections on desktop', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_tabs_as_sections' => [
						'label'             => __( 'Product tabs as sections on mobile', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_page_ajax_add_to_cart' => [
						'label'             => __( 'Ajax Add to Cart', 'kidz' ),
						'description'       => __( 'This option will enable the Ajax add to cart functionality on a product page. WooCommerce doesn`t have this option built-in, so theme implementation might not be compatible with a certain plugin you`re using, so it would be best to keep it disabled.', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_hide_sidebar' => [
						'label'             => __( 'Hide sidebar', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_short_sidebar'             => [
						'label'             => __( 'Short sidebar', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_hide_sidebar' => [ 'is_empty' ],
						],
					],
					'hide_sku'                          => [
						'label'             => __( 'Hide SKU', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'hide_stock'                        => [
						'label'             => __( 'Hide stock status', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'hide_categories'                   => [
						'label'             => __( 'Hide categories', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'hide_tags'                         => [
						'label'             => __( 'Hide tags', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'shop_product_navigation_same_term' => [
						'label'             => __( 'Product navigation in same category', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_share' => [
						'label'             => __( 'Show share buttons', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_share_buttons' => [
						'label'             => __( 'Share buttons', 'kidz' ),
						'description'       => __( 'Enable or disable buttons, and then drag and drop buttons below to set up their order', 'kidz' ),
						'type'              => 'checklist',
						'default'           => 'facebook=1|twitter=1|pinterest=1|whatsapp=1',
						'choices'           => [
							'facebook'  => __( 'Facebook', 'kidz' ),
							'twitter'   => __( 'X (Twitter)', 'kidz' ),
							'pinterest' => __( 'Pinterest', 'kidz' ),
							'whatsapp'  => __( 'Whatsapp', 'kidz' ),
						],
						'sortable'          => true,
						'class'             => 'WP_Customize_Checklist_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'product_share' => [ 'not_empty' ],
						],
					],

					'product_image_info' => [
						'label'             => __( 'Product Image', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'product_image_fit' => [
						'label'             => __( 'Main image fit on product page', 'kidz' ),
						'default'           => 'contain',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'image-radio',
						'class'             => 'WP_Customize_Image_Radio_Control',
						'is_option'         => true,
						'choices'           => [
							'contain' => IDEAPARK_THEME_URI . '/img/thumb-contain.png',
							'cover'   => IDEAPARK_THEME_URI . '/img/thumb-cover.png',
						],
					],

					'product_image_prop' => [
						'label'             => __( 'Image aspect ratio on product page (height / width)', 'kidz' ),
						'default'           => 1,
						'type'              => 'slider',
						'sanitize_callback' => 'ideapark_sanitize_float',
						'class'             => 'WP_Customize_Range_Control',
						'min'               => 0.4,
						'max'               => 2,
						'step'              => 0.1,
						'dependency'        => [
							'product_image_fit' => [ 'contain' ],
						],
					],

					'shop_product_zoom' => [
						'label'             => __( 'Images zoom on touch or mouseover', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'shop_product_zoom_mobile_hide' => [
						'label'             => __( 'Hide zoom on mobile', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'shop_product_zoom' => [ 'not_empty' ],
						],
					],

					'shop_product_modal' => [
						'label'             => __( 'Images modal gallery', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'product_thumbnails' => [
						'label'             => __( 'Product thumbnails', 'kidz' ),
						'description'       => __( 'If you need thumbnails in the mobile version select the second one', 'kidz' ),
						'default'           => 'left',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'left'  => __( 'Left of the main image', 'kidz' ),
							'below' => __( 'Below the main image', 'kidz' ),
							'hide'  => __( 'Hide', 'kidz' ),
						],
					],

					'product_thumbnails_show_mobile' => [
						'label'             => __( 'Show thumbnails in the mobile version', 'kidz' ),
						'description'       => __( 'Only with `Below the main image` layout.', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_thumbnails' => [ 'below' ],
						],
					],

					'video_first' => [
						'label'             => __( 'Show the video thumbnail first', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				]
			],

			[
				'panel'      => 'woocommerce',
				'section_id' => 'woocommerce_brands',
				'title'      => __( 'Brands', 'kidz' ),
				'priority'   => 4,
				'controls'   => [

					'product_brand_attribute' => [
						'label'             => __( 'Brand attribute', 'kidz' ),
						'type'              => 'select',
						'sanitize_callback' => 'sanitize_text_field',
						'choices'           => 'ideapark_get_all_attributes',
						'is_option'         => true,
					],

					'show_product_grid_brand' => [
						'label'             => __( 'Show Brand in grid', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'show_product_page_brand' => [
						'label'             => __( 'Show Brand on product page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'show_cart_page_brand' => [
						'label'             => __( 'Show Brand on cart page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],

					'brands_page' => [
						'label'             => __( 'Brands page', 'kidz' ),
						'description'       => __( 'Page with a list of brands. Used in breadcrumbs. Add shortcode [ip-brands] to this page to display the list of brands.', 'kidz' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Page_Control',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'product_brand_attribute' => [ 'not_empty' ],
						],
					],
				]
			],
			[
				'panel'      => 'woocommerce',
				'section_id' => 'woocommerce_badges',
				'title'      => __( 'Badges', 'kidz' ),
				'priority'   => 4,
				'controls'   => [

					'outofstock_badge_info'    => [
						'label'             => __( 'Out of stock', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'outofstock_badge_color'   => [
						'label'             => __( 'Out of stock badge color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],
					'outofstock_badge_text'    => [
						'label'             => __( 'Out of stock text', 'kidz' ),
						'description'       => __( 'Disabled if empty', 'kidz' ),
						'type'              => 'text',
						'default'           => __( 'Out of stock', 'kidz' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'featured_badge_info'      => [
						'label'             => __( 'Featured', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'featured_badge_color'     => [
						'label'             => __( 'Featured badge color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],
					'featured_badge_text'      => [
						'label'             => __( 'Featured badge text', 'kidz' ),
						'description'       => __( 'Disabled if empty', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'featured_badge_text_hide' => [
						'label'             => __( 'Hide featured badges in the featured tab on the front page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'featured_badge_text' => [ 'not_empty' ],
						],
					],
					'sale_badge_info'          => [
						'label'             => __( 'Sale', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'sale_badge_color_custom'  => [
						'label'             => __( 'Sale badge color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],
					'sale_badge_text'          => [
						'label'             => __( 'Sale badge text', 'kidz' ),
						'description'       => __( 'Disabled if empty', 'kidz' ),
						'type'              => 'text',
						'default'           => __( 'Sale!', 'kidz' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'sale_badge_layout'        => [
						'label'             => __( 'Show', 'kidz' ),
						'default'           => 'percentage',
						'type'              => 'radio',
						'choices'           => [
							'percentage' => __( 'Percentage', 'kidz' ),
							'text'       => __( 'Text', 'kidz' ),
						],
						'sanitize_callback' => 'sanitize_text_field',
					],
					'new_badge_info'           => [
						'label'             => __( 'New', 'kidz' ),
						'class'             => 'WP_Customize_Info_Control',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'new_badge_color_custom'   => [
						'label'             => __( 'New badge color', 'kidz' ),
						'class'             => 'WP_Customize_Color_Control',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'refresh'           => false,
						'refresh_css'       => true,
					],
					'new_badge_text'           => [
						'label'             => __( 'New badge text', 'kidz' ),
						'description'       => __( 'Disabled if empty', 'kidz' ),
						'type'              => 'text',
						'default'           => __( 'New', 'kidz' ),
						'sanitize_callback' => 'sanitize_text_field',
					],
					'product_newness'          => [
						'label'             => __( 'Product newness', 'kidz' ),
						'description'       => __( 'Display the New badge for how many days? Set 0 for disable `NEW` badge.', 'kidz' ),
						'default'           => 5,
						'step'              => 1,
						'min'               => 0,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],
				]
			],
			[
				'panel'      => 'woocommerce',
				'section_id' => 'woocommerce_wishlist',
				'title'      => __( 'Wishlist', 'kidz' ),
				'priority'   => 4,
				'controls'   => [
					'wishlist_page' => [
						'label'             => __( 'Wishlist page', 'kidz' ),
						'description'       => __( 'Deselect to disable wishlist', 'kidz' ),
						'default'           => 0,
						'class'             => 'WP_Customize_Page_Control',
						'sanitize_callback' => 'absint',
					],

					'wishlist_share' => [
						'label'             => __( 'Wishlist share buttons', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'wishlist_page' => [ 'not_empty' ],
						],
					],
				]
			],
			[
				'panel'      => 'woocommerce',
				'section_id' => 'woocommerce_related',
				'title'      => __( 'Related products, Up-Sells, Cross-Sells', 'kidz' ),
				'priority'   => 4,
				'controls'   => [
					'related_product_number' => [
						'label'             => __( 'Number of related products', 'kidz' ),
						'default'           => 4,
						'min'               => 0,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
					],

					'related_product_header' => [
						'label'             => __( 'Related products custom header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'upsells_product_header' => [
						'label'             => __( 'Up-Sells custom header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],

					'cross_sells_product_header' => [
						'label'             => __( 'Cross-Sells custom header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					],
				]
			],
			[
				'panel'      => 'woocommerce',
				'section_id' => 'woocommerce_recently',
				'title'      => __( 'Recently viewed products', 'kidz' ),
				'priority'   => 4,
				'controls'   => [
					'recently_enabled' => [
						'label'             => __( 'Enable recently viewed products', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'recently_product_number' => [
						'label'             => __( 'Number of products', 'kidz' ),
						'default'           => 4,
						'min'               => 0,
						'step'              => 1,
						'class'             => 'WP_Customize_Number_Control',
						'type'              => 'number',
						'sanitize_callback' => 'absint',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_product_show' => [
						'label'             => __( 'Show on Product page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_shop_show' => [
						'label'             => __( 'Show on Shop page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_cart_show' => [
						'label'             => __( 'Show on Cart page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_product_header' => [
						'label'             => __( 'Custom header', 'kidz' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],

					'recently_position' => [
						'label'             => __( 'Position', 'kidz' ),
						'default'           => 'above',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'select',
						'choices'           => [
							'above' => __( 'Above other sections', 'kidz' ),
							'below' => __( 'Below other sections', 'kidz' ),
						],
						'dependency'        => [
							'recently_enabled' => [ 'not_empty' ]
						],
					],
				]
			],
			[
				'panel'      => 'woocommerce',
				'section_id' => 'woocommerce_cart',
				'title'      => __( 'Cart', 'kidz' ),
				'priority'   => 10,
				'controls'   => [
					'popup_cart_layout' => [
						'label'             => __( 'Pop-up Cart layout (Desktop)', 'kidz' ),
						'default'           => 'default',
						'sanitize_callback' => 'sanitize_text_field',
						'type'              => 'radio',
						'choices'           => [
							'default' => __( 'Default', 'kidz' ),
							'sidebar' => __( 'Sidebar', 'kidz' ),
							'disable' => __( 'Disable', 'kidz' ),
						],
					],

					'popup_cart_auto_open_desktop' => [
						'label'             => __( 'Open the pop-up cart after adding the product (Desktop)', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'popup_cart_layout' => [ 'sidebar' ]
						],
					],

					'popup_cart_auto_open_mobile' => [
						'label'             => __( 'Open the pop-up cart after adding the product (Mobile)', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
						'dependency'        => [
							'popup_cart_layout' => [ 'sidebar' ]
						],
					],

					'popup_cart_modal' => [
						'label'             => __( 'Clicking outside the sidebar closes the cart', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'cart_auto_update' => [
						'label'             => __( 'Auto update cart when quantity changed on the cart page', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],

					'expand_coupon' => [
						'label'             => __( 'Expand Coupon Code block', 'kidz' ),
						'default'           => false,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
				]
			],
			[
				'panel'    => 'woocommerce',
				'section'  => 'woocommerce_checkout',
				'priority' => 1,
				'controls' => [
					'product_two_columns_checkout' => [
						'label'             => __( 'Display form fields on the Checkout page in two columns', 'kidz' ),
						'default'           => true,
						'type'              => 'checkbox',
						'sanitize_callback' => 'ideapark_sanitize_checkbox',
					],
					'expand_coupon_info'           => [
						'html'              => sprintf( '%s' . __( 'Expand Coupon Code block', 'kidz' ) . '%s', '<a href="#" class="ideapark-control-focus" data-control="expand_coupon">', '</a>' ),
						'class'             => 'WP_Customize_HTML_Control',
						'sanitize_callback' => 'sanitize_text_field',
						'priority'          => 100,
					],
				]
			],
		];

		ideapark_parse_added_blocks();

		ideapark_add_last_control();

		$ideapark_customize_mods_def    = [];
		$ideapark_customize_mods_names  = [];
		$ideapark_customize_mods_images = [];
		foreach ( $ideapark_customize as $section ) {
			if ( ! empty( $section['controls'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					if ( isset( $control['default'] ) ) {
						$ideapark_customize_mods_def[ $control_name ] = $control['default'];
					}
					$ideapark_customize_mods_names[] = $control_name;
					if ( isset( $control['class'] ) && $control['class'] == 'WP_Customize_Image_Control' ) {
						$ideapark_customize_mods_images[] = $control_name;
					}
				}
			}
		}

		$code = "<?php\n";
		$code .= '$ideapark_customize_mods_ver = "' . $version . '";' . "\n";
		foreach ( [ 'ideapark_customize', 'ideapark_customize_mods_def', 'ideapark_customize_mods_names', 'ideapark_customize_mods_images' ] as $var_name ) {
			$code .= 'global $' . $var_name . ";\n";
			$code .= '$' . $var_name . ' = ' . ideapark_array_to_php_code( $$var_name ) . ";\n\n";
		}
		ideapark_fpc( $fn = IDEAPARK_THEME_UPLOAD_DIR . 'customizer_vars.php', $code );
		if ( ! ideapark_is_file( $fn ) ) {
			update_option( 'ideapark_customize', [
				'version'  => $version,
				'settings' => $ideapark_customize
			], false );
			update_option( 'ideapark_customize_mods_def', $ideapark_customize_mods_def, false );
			update_option( 'ideapark_customize_mods_names', $ideapark_customize_mods_names, false );
			update_option( 'ideapark_customize_mods_images', $ideapark_customize_mods_images, false );
		}
	}
}

if ( ! function_exists( 'ideapark_array_to_php_code' ) ) {
	function ideapark_array_to_php_code( $array ) {
		if ( ! is_array( $array ) ) {
			return '';
		}
		$code = '[' . "\n";
		foreach ( $array as $key => $value ) {
			$code .= '"' . $key . '" => ' . ( is_array( $value ) ? ideapark_array_to_php_code( $value ) : ( ( is_string( $value ) ? '"' : '' ) . addslashes( is_bool( $value ) ? ( $value ? 'true' : 'false' ) : $value ) . ( is_string( $value ) ? '"' : '' ) ) ) . ',' . "\n";
		}
		$code .= ']';

		return $code;
	}
}

if ( ! function_exists( 'ideapark_reset_theme_mods' ) ) {
	function ideapark_reset_theme_mods() {
		global $ideapark_customize;

		if ( ! empty( $ideapark_customize ) ) {
			foreach ( $ideapark_customize as $section ) {
				if ( ! empty( $section['controls'] ) ) {
					foreach ( $section['controls'] as $control_name => $control ) {
						if ( isset( $control['default'] ) ) {
							set_theme_mod( $control_name, $control['default'] );
							ideapark_mod_set_temp( $control_name, $control['default'] );
						}
					}
				}
			}
		}
	}
}
if ( ! function_exists( 'ideapark_fix_theme_mods' ) ) {
	function ideapark_fix_theme_mods( $old_version = '', $new_version = '' ) {
		if ( $old_version && $new_version && version_compare( $old_version, '5.18', '<=' ) && version_compare( $new_version, '5.18', '>' ) ) {
			delete_option( 'ideapark_customize' );
			delete_option( 'ideapark_google_fonts' );
		}

		if ( get_option( 'ideapark_fix_theme_mods_ver' ) != IDEAPARK_THEME_VERSION ) {
			update_option( 'ideapark_fix_theme_mods_ver', IDEAPARK_THEME_VERSION );

			if ( ( $v = get_theme_mod( 'home_hide_banners', null ) ) !== null ) {
				$s = ideapark_mod( 'home_sections' );
				$s = preg_replace( '~(banner-4)=[0,1]~', "\\1=" . ( $v ? 0 : 1 ), $s );
				set_theme_mod( 'home_sections', $s );
				ideapark_mod_set_temp( 'home_sections', $s );
				remove_theme_mod( 'home_hide_banners' );
			}
			if ( ( $v = get_theme_mod( 'home_hide_tabs', null ) ) !== null ) {
				$s = ideapark_mod( 'home_sections' );
				$s = preg_replace( '~(product-tabs)=[0,1]~', "\\1=" . ( $v ? 0 : 1 ), $s );
				set_theme_mod( 'home_sections', $s );
				ideapark_mod_set_temp( 'home_sections', $s );
				remove_theme_mod( 'home_hide_tabs' );
			}
			if ( ( $v = get_theme_mod( 'home_hide_brands', null ) ) !== null ) {
				$s = ideapark_mod( 'home_sections' );
				$s = preg_replace( '~(brands)=[0,1]~', "\\1=" . ( $v ? 0 : 1 ), $s );
				set_theme_mod( 'home_sections', $s );
				ideapark_mod_set_temp( 'home_sections', $s );
				remove_theme_mod( 'home_hide_brands' );
			}
			if ( ( $v = get_theme_mod( 'home_hide_post', null ) ) !== null ) {
				$s = ideapark_mod( 'home_sections' );
				$s = preg_replace( '~(posts)=[0,1]~', "\\1=" . ( $v ? 0 : 1 ), $s );
				set_theme_mod( 'home_sections', $s );
				ideapark_mod_set_temp( 'home_sections', $s );
				remove_theme_mod( 'home_hide_post' );
			}
			if ( ( $v = get_theme_mod( 'home_hide_reviews', null ) ) !== null ) {
				$s = ideapark_mod( 'home_sections' );
				$s = preg_replace( '~(reviews)=[0,1]~', "\\1=" . ( $v ? 0 : 1 ), $s );
				set_theme_mod( 'home_sections', $s );
				ideapark_mod_set_temp( 'home_sections', $s );
				remove_theme_mod( 'home_hide_reviews' );
			}
			if ( ( $v = get_theme_mod( 'home_hide_about', null ) ) !== null ) {
				$s = ideapark_mod( 'home_sections' );
				$s = preg_replace( '~(text)=[0,1]~', "\\1=" . ( $v ? 0 : 1 ), $s );
				set_theme_mod( 'home_sections', $s );
				ideapark_mod_set_temp( 'home_sections', $s );
				remove_theme_mod( 'home_hide_about' );
			}
			if ( ( $v = get_theme_mod( 'slider_enable', null ) ) !== null ) {
				$s = ideapark_mod( 'home_sections' );
				$s = preg_replace( '~(slider)=[0,1]~', "\\1=" . ( $v ? 1 : 0 ), $s );
				set_theme_mod( 'home_sections', $s );
				ideapark_mod_set_temp( 'home_sections', $s );
				remove_theme_mod( 'slider_enable' );
			}
			if ( ( $v = get_theme_mod( $n = 'home_featured_order', null ) ) !== null ) {
				set_theme_mod( $m = 'home_product_order', $s = preg_replace( '~(featured_products)=[0,1]~', "\\1=" . ( $v ? 1 : 0 ), ideapark_mod( $m ) ) );
				ideapark_mod_set_temp( $m, $s );
				remove_theme_mod( $n );
			}
			if ( ( $v = get_theme_mod( $n = 'home_sale_order', null ) ) !== null ) {
				set_theme_mod( $m = 'home_product_order', $s = preg_replace( '~(sale_products)=[0,1]~', "\\1=" . ( $v ? 1 : 0 ), ideapark_mod( $m ) ) );
				ideapark_mod_set_temp( $m, $s );
				remove_theme_mod( $n );
			}
			if ( ( $v = get_theme_mod( $n = 'home_best_selling_order', null ) ) !== null ) {
				set_theme_mod( $m = 'home_product_order', $s = preg_replace( '~(best_selling_products)=[0,1]~', "\\1=" . ( $v ? 1 : 0 ), ideapark_mod( $m ) ) );
				ideapark_mod_set_temp( $m, $s );
				remove_theme_mod( $n );
			}
			if ( ( $v = get_theme_mod( $n = 'home_recent_order', null ) ) !== null ) {
				set_theme_mod( $m = 'home_product_order', $s = preg_replace( '~(recent_products)=[0,1]~', "\\1=" . ( $v ? 1 : 0 ), ideapark_mod( $m ) ) );
				ideapark_mod_set_temp( $m, $s );
				remove_theme_mod( $n );
			}
			if ( ( $v = get_theme_mod( $n = 'home_brands_white_bg', null ) ) !== null ) {
				if ( $v ) {
					set_theme_mod( $m = 'home_brands_background_color', $s = '#FFFFFF' );
					ideapark_mod_set_temp( $m, $s );
					remove_theme_mod( $n );
				}
			}
			if ( $v = ideapark_mod( 'footer_minimal' ) ) {
				set_theme_mod( 'footer_layout', 'minimal' );
				ideapark_mod_set_temp( 'footer_layout', 'minimal' );
				remove_theme_mod( 'footer_minimal' );
			}
			if ( $v = ideapark_mod( 'home_sidebar' ) ) {
				if ( $v == 'disable' ) {
					set_theme_mod( 'post_hide_sidebar', true );
					ideapark_mod_set_temp( 'post_hide_sidebar', true );
				}
				remove_theme_mod( 'home_sidebar' );
			}
			if ( $v = ideapark_mod( 'logo_extra_size' ) ) {
				set_theme_mod( 'logo_zoom', 1.3 );
				ideapark_mod_set_temp( 'logo_zoom', 1.3 );
				remove_theme_mod( 'logo_extra_size' );
			}
			if ( $v = ideapark_mod( 'custom_css' ) ) {
				set_theme_mod( 'custom_css_ip', $v );
				ideapark_mod_set_temp( 'custom_css_ip', $v );
				remove_theme_mod( 'custom_css' );
			}
			if ( $v = ideapark_mod( 'sale_badge_text_always' ) ) {
				$v = ( $v ? 'text' : 'percentage' );
				set_theme_mod( 'sale_badge_layout', $v );
				ideapark_mod_set_temp( 'sale_badge_layout', $v );
				remove_theme_mod( 'sale_badge_text_always' );
			}
			if ( ( $v = get_theme_mod( $n = 'custom_css_ip', null ) ) && ( $custom_css_post_id = get_theme_mod( $c = 'custom_css_post_id', null ) ) !== null ) {
				$css = wp_get_custom_css();
				$css .= "\n\n" . $v;

				$r = wp_update_custom_css_post( trim( $css ), [
					'stylesheet' => get_stylesheet(),
				] );

				if ( ! ( $r instanceof WP_Error ) ) {
					$post_id = $r->ID;
					set_theme_mod( 'custom_css_post_id', $post_id );
					ideapark_mod_set_temp( $c, $post_id );
					remove_theme_mod( $n );
				}
			}

			if ( ( $v = get_theme_mod( $n = 'theme_font_1', null ) ) !== null ) {
				if ( $v == 'Fredoka' && get_locale() == 'ru_RU' ) {
					set_theme_mod( $n, $s = 'Rubik' );
					ideapark_mod_set_temp( $n, $s );
					set_theme_mod( $n = 'theme_font_1_weight', $s = '700' );
					ideapark_mod_set_temp( $n, $s );
				} elseif ( $v == 'Rubik' && get_locale() != 'ru_RU' ) {
					set_theme_mod( $n, $s = 'Fredoka' );
					ideapark_mod_set_temp( $n, $s );
					set_theme_mod( $n = 'theme_font_1_weight', $s = '400' );
					ideapark_mod_set_temp( $n, $s );
				} elseif ( $v == 'Fredoka One' && get_locale() != 'ru_RU' ) {
					set_theme_mod( $n, $s = 'Fredoka' );
					ideapark_mod_set_temp( $n, $s );
					set_theme_mod( $n = 'theme_font_1_weight', $s = '600' );
					ideapark_mod_set_temp( $n, $s );
				}
			}


			if ( ideapark_mod( 'front_page_builder_enabled' ) ) {
				global $ideapark_customize;
				$sections = ideapark_parse_checklist( ideapark_mod( 'home_sections' ) );
				foreach ( $sections as $section => $is_enable ) {
					if ( ! $is_enable || ! preg_match( '~^shortcode~', $section ) ) {
						continue;
					}
					foreach ( $ideapark_customize as $_section ) {
						if ( ! empty( $_section['section_id'] ) && $_section['section_id'] == $section ) {
							$postfix = '';
							if ( preg_match( '~-(\d+)$~', $section, $match ) ) {
								$postfix = '_' . $match[1];
								$index   = '-' . absint( $match[1] );
							}
						}
					}
				}
			}

			if ( ideapark_mod( 'post_hide_sidebar' ) ) {
				set_theme_mod( 'sidebar_page', '' );
				set_theme_mod( 'sidebar_post', '' );
				set_theme_mod( 'sidebar_blog', '' );
				ideapark_mod_set_temp( 'sidebar_page', '' );
				ideapark_mod_set_temp( 'sidebar_post', '' );
				ideapark_mod_set_temp( 'sidebar_blog', '' );
				remove_theme_mod( 'post_hide_sidebar' );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_init_theme_mods' ) ) {
	function ideapark_init_theme_mods() {
		global $ideapark_customize_mods, $ideapark_customize_mods_def, $ideapark_customize_mods_names, $ideapark_customize_mods_images;

		$ideapark_customize_mods = get_theme_mods();

		foreach ( $ideapark_customize_mods_names as $name ) {
			if ( ! is_array( $ideapark_customize_mods ) || ! array_key_exists( $name, $ideapark_customize_mods ) ) {
				$ideapark_customize_mods[ $name ] = apply_filters( "theme_mod_{$name}", array_key_exists( $name, $ideapark_customize_mods_def ) ? $ideapark_customize_mods_def[ $name ] : null );
			} else {
				$ideapark_customize_mods[ $name ] = apply_filters( "theme_mod_{$name}", $ideapark_customize_mods[ $name ] );
			}
		}

		if ( is_customize_preview() && $ideapark_customize_mods_images && ! IDEAPARK_THEME_IS_AJAX_HEARTBEAT ) {
			foreach ( $ideapark_customize_mods_images as $control_name ) {
				if ( ( $url = get_theme_mod( $control_name ) ) && ( $attachment_id = attachment_url_to_postid( $url ) ) ) {
					$params = wp_get_attachment_image_src( $attachment_id, 'full' );

					$ideapark_customize_mods[ $control_name . '__url' ]           = $params[0];
					$ideapark_customize_mods[ $control_name . '__attachment_id' ] = $attachment_id;
					$ideapark_customize_mods[ $control_name . '__width' ]         = $params[1];
					$ideapark_customize_mods[ $control_name . '__height' ]        = $params[2];
				} else {
					$ideapark_customize_mods[ $control_name . '__url' ]           = null;
					$ideapark_customize_mods[ $control_name . '__attachment_id' ] = null;
					$ideapark_customize_mods[ $control_name . '__width' ]         = null;
					$ideapark_customize_mods[ $control_name . '__height' ]        = null;
				}
			}
		}

		if ( is_customize_preview() && ! IDEAPARK_THEME_IS_AJAX_HEARTBEAT ) {
			if ( ideapark_is_elementor() && isset( $_POST['customized'] ) && ( $customized = json_decode( wp_unslash( $_POST['customized'] ), true ) ) ) {
				foreach ( $customized as $key => $val ) {
					if ( preg_match( '~color~', $key ) ) {
						$elementor_instance = Elementor\Plugin::instance();
						$elementor_instance->files_manager->clear_cache();
						break;
					}
				}
			}
		}


		do_action( 'ideapark_init_theme_mods' );
	}
}

if ( ! function_exists( 'ideapark_mod' ) ) {
	function ideapark_mod( $mod_name ) {
		global $ideapark_customize_mods, $ideapark_customize_mod_used;

		if ( array_key_exists( $mod_name, $ideapark_customize_mods ) ) {
			if ( is_array( $ideapark_customize_mod_used ) ) {
				$ideapark_customize_mod_used[] = $mod_name;
			}

			return $ideapark_customize_mods[ $mod_name ];
		} else {
			return null;
		}
	}
}

if ( ! function_exists( 'ideapark_mod_default' ) ) {
	function ideapark_mod_default( $mod_name ) {
		global $ideapark_customize_mods_def;

		if ( array_key_exists( $mod_name, $ideapark_customize_mods_def ) ) {
			return $ideapark_customize_mods_def[ $mod_name ];
		} else {
			return null;
		}
	}
}

if ( ! function_exists( 'ideapark_mod_set_temp' ) ) {
	function ideapark_mod_set_temp( $mod_name, $value ) {
		global $ideapark_customize_mods;
		if ( $value === null && isset( $ideapark_customize_mods[ $mod_name ] ) ) {
			unset( $ideapark_customize_mods[ $mod_name ] );
		} else {
			$ideapark_customize_mods[ $mod_name ] = $value;
		}
	}
}

if ( ! function_exists( 'ideapark_register_theme_customize' ) ) {
	function ideapark_register_theme_customize( $wp_customize ) {
		global $ideapark_customize_custom_css, $ideapark_customize;

		/**
		 * @var  WP_Customize_Manager $wp_customize
		 **/

		if ( class_exists( 'WP_Customize_Control' ) ) {

			class WP_Customize_Image_Radio_Control extends WP_Customize_Control {
				public $type = 'image-radio';

				public function render_content() {
					$input_id         = '_customize-input-' . $this->id;
					$description_id   = '_customize-description-' . $this->id;
					$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

					if ( empty( $this->choices ) ) {
						return;
					}

					$name = '_customize-radio-' . $this->id;
					?>
					<?php if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span id="<?php echo esc_attr( $description_id ); ?>"
							  class="description customize-control-description"><?php echo ideapark_wrap( $this->description ); ?></span>
					<?php endif; ?>

					<?php foreach ( $this->choices as $value => $label ) { ?>
						<span class="customize-inside-control-row">
						<label>
						<input
							id="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"
							type="radio"
							<?php echo ideapark_wrap( $describedby_attr ); ?>
							value="<?php echo esc_attr( $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							<?php $this->link(); ?>
							<?php checked( $this->value(), $value ); ?>
							/>
						<?php echo( substr( $label, 0, 4 ) == 'http' ? '<img class="ideapark-radio-img" src="' . esc_url( $label ) . '">' : esc_html( $label ) ); ?></label>
						</span><?php
					}
				}
			}

			class WP_Customize_Number_Control extends WP_Customize_Control {
				public $type = 'number';

				public function render_content() {
					?>
					<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<input type="number" name="quantity" <?php $this->link(); ?>
						       <?php if ( ! empty( $this->input_attrs['pattern'] ) ) { ?>pattern="<?php echo esc_attr( $this->input_attrs['pattern'] ); ?>"<?php } ?>
						       <?php if ( isset( $this->input_attrs['min'] ) ) { ?>min="<?php echo esc_attr( $this->input_attrs['min'] ); ?>"<?php } ?>
						       <?php if ( isset( $this->input_attrs['max'] ) ) { ?>max="<?php echo esc_attr( $this->input_attrs['max'] ); ?>"<?php } ?>
						       <?php if ( isset( $this->input_attrs['step'] ) ) { ?>step="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"<?php } ?>
							   value="<?php echo esc_textarea( $this->value() ); ?>" style="width:70px;">
					</label>
					<?php
				}
			}

			class WP_Customize_CustomCss_Control extends WP_Customize_Control {
				public $type = 'custom_css';

				public function render_content() {
					?>
					<label>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<textarea
							style="width:100%; height:150px;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
					</label>
					<?php
				}
			}

			class WP_Customize_Category_Control extends WP_Customize_Control {

				public function render_content() {
					$dropdown = wp_dropdown_categories(
						[
							'name'              => '_customize-dropdown-categories-' . $this->id,
							'echo'              => 0,
							'show_option_none'  => '&mdash; ' . esc_html__( 'Select', 'kidz' ) . ' &mdash;',
							'option_none_value' => '0',
							'selected'          => $this->value(),
						]
					);

					$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

					printf(
						'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
						$this->label,
						$dropdown
					);
				}
			}

			class WP_Customize_Page_Control extends WP_Customize_Control {

				public function render_content() {
					$dropdown = wp_dropdown_pages(
						[
							'name'              => '_customize-dropdown-pages-' . $this->id,
							'echo'              => 0,
							'show_option_none'  => '&mdash; ' . esc_html__( 'Select', 'kidz' ) . ' &mdash;',
							'option_none_value' => '0',
							'selected'          => $this->value(),
						]
					);

					$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

					printf(
						'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
						$this->label,
						$dropdown
					);
				}
			}

			class WP_Customize_Product_Categories_Control extends WP_Customize_Control {

				public function render_content() {
					$list = [ 0 => '&mdash; ' . esc_html__( 'Select', 'kidz' ) . ' &mdash;', ];

					$args = [
						'taxonomy'     => 'product_cat',
						'orderby'      => 'term_group',
						'show_count'   => 0,
						'pad_counts'   => 0,
						'hierarchical' => 1,
						'title_li'     => '',
						'hide_empty'   => 0,
						'exclude'      => ideapark_mod( 'hide_uncategorized' ) ? get_option( 'default_product_cat' ) : null,
					];
					if ( $all_categories = get_categories( $args ) ) {

						$category_name   = [];
						$category_parent = [];
						foreach ( $all_categories as $cat ) {
							$category_name[ $cat->term_id ]    = esc_html( $cat->name );
							$category_parent[ $cat->parent ][] = $cat->term_id;
						}

						$get_category = function ( $parent = 0, $prefix = ' - ' ) use ( &$list, &$category_parent, &$category_name, &$get_category ) {
							if ( array_key_exists( $parent, $category_parent ) ) {
								$categories = $category_parent[ $parent ];
								foreach ( $categories as $category_id ) {
									$list[ $category_id ] = $prefix . $category_name[ $category_id ];
									$get_category( $category_id, $prefix . ' - ' );
								}
							}
						};

						$get_category();
					}

					$dropdown = '<select ' . $this->get_link() . '>';
					foreach ( $list as $category_id => $category_name ) {
						$dropdown .= '<option value="' . esc_attr( $category_id ) . '" ' . selected( $category_id, $this->value(), false ) . '>' . esc_html( $category_name ) . '</option>';
					}

					printf(
						'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
						$this->label,
						$dropdown
					);
				}
			}

			class WP_Customize_Info_Control extends WP_Customize_Control {
				public $type = 'info';

				public function render_content() {
					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="customize-control-title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="customize-control-description description">', '</span>' ),
						'<div class="customize-control-wrap">',
						'</div>'
					);
				}
			}

			class WP_Customize_Notice_Control extends WP_Customize_Control {
				public $type = 'notice';

				public function render_content() {
					echo ideapark_wrap( $this->label, '<div>', '</div>' );
				}
			}

			class WP_Customize_Warning_Control extends WP_Customize_Control {
				public $type = 'warning';

				public function render_content() {
					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="notification-message">', '</span>' ),
						'<div class="ideapark-notice ideapark-notice--warning">',
						'</div>'
					);
				}
			}

			class WP_Customize_HTML_Control extends WP_Customize_Control {
				public $type = 'html';

				public function render_content() {
					echo isset( $this->input_attrs['html'] ) ? ideapark_wrap( $this->input_attrs['html'], '<div class="customize-control-wrap">', '</div>' ) : '';
				}
			}

			class WP_Customize_Text_Editor_Control extends WP_Customize_Control {
				public $type = 'text_editor';

				public function render_content() {

					if ( function_exists( 'wp_enqueue_editor' ) ) {
						wp_enqueue_editor();
					}
					ob_start();
					wp_editor(
						$this->value(), '_customize-text-editor-' . esc_attr( $this->id ), [
							'default_editor' => 'tmce',
							'wpautop'        => isset( $this->input_attrs['wpautop'] ) ? $this->input_attrs['wpautop'] : false,
							'teeny'          => isset( $this->input_attrs['teeny'] ) ? $this->input_attrs['teeny'] : false,
							'textarea_rows'  => isset( $this->input_attrs['rows'] ) && $this->input_attrs['rows'] > 1 ? $this->input_attrs['rows'] : 10,
							'editor_height'  => 16 * ( isset( $this->input_attrs['rows'] ) && $this->input_attrs['rows'] > 1 ? (int) $this->input_attrs['rows'] : 10 ),
							'tinymce'        => [
								'resize'             => false,
								'wp_autoresize_on'   => false,
								'add_unload_trigger' => false,
							],
						]
					);
					$editor_html = ob_get_contents();
					ob_end_clean();

					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="customize-control-title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="customize-control-description description">', '</span>' ),
						'<div class="customize-control-wrap">',
						'<span class="customize-control-field-wrap">
							<input type="hidden"' . $this->get_link() .
						( ! empty( $this->input_attrs['var_name'] ) ? ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"' : '' ) .
						' value="' . esc_textarea( $this->value() ) . '" />' .

						ideapark_wrap( $editor_html, '<div class="ideapark_text_editor">', '</div>' ) . ' 
					</span></div>'
					);

					ideapark_mod_set_temp( 'need_footer_scripts', true );
				}
			}

			class WP_Customize_Select_Control extends WP_Customize_Control {
				public $type = 'select';

				public function render_content() {
					$input_id         = '_customize-input-' . $this->id;
					$description_id   = '_customize-description-' . $this->id;
					$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
					if ( empty( $this->choices ) ) {
						return;
					}

					?>
					<?php if ( ! empty( $this->label ) ) : ?>
						<label for="<?php echo esc_attr( $input_id ); ?>"
							   class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
					<?php endif; ?>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span id="<?php echo esc_attr( $description_id ); ?>"
							  class="description customize-control-description"><?php echo ideapark_wrap( $this->description ); ?></span>
					<?php endif; ?>

					<select
						id="<?php echo esc_attr( $input_id ); ?>" <?php echo ideapark_wrap( $describedby_attr ); ?> <?php $this->link(); ?>>
						<?php
						$is_option_group = false;
						foreach ( $this->choices as $value => $label ) {
							if ( strpos( $value, '*' ) === 0 ) {
								if ( $is_option_group ) {
									echo ideapark_wrap( '</optgroup>' );
								}
								echo ideapark_wrap( '<optgroup label="' . $label . '">' );
								$is_option_group = true;
							} else {
								echo ideapark_wrap( '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>' );
							}

						}
						if ( $is_option_group ) {
							echo ideapark_wrap( '</optgroup>' );
						}
						?>
					</select>
					<?php
				}
			}

			class WP_Customize_Hidden_Control extends WP_Customize_Control {
				public $type = 'hidden';

				public function render_content() {
					?>
					<input type="hidden" name="_customize-hidden-<?php echo esc_attr( $this->id ); ?>"
						<?php
						$this->link();
						if ( ! empty( $this->input_attrs['var_name'] ) ) {
							echo ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"';
						}
						?>
						   value=""
					>
					<?php
					if ( 'last_option' == $this->id && ideapark_mod( 'need_footer_scripts' ) ) {
						ideapark_mod_set_temp( 'need_footer_scripts', false );
						do_action( 'admin_print_footer_scripts' );
					}
				}
			}

			class WP_Customize_Range_Control extends WP_Customize_Control {
				public $type = 'range';

				public function render_content() {
					$show_value = ! isset( $this->input_attrs['show_value'] ) || $this->input_attrs['show_value'];
					$output     = '';

					wp_enqueue_script( 'jquery-ui-slider', false, [ 'jquery', 'jquery-ui-core' ], null, true );
					$is_range   = 'range' == $this->input_attrs['type'];
					$field_min  = ! empty( $this->input_attrs['min'] ) ? $this->input_attrs['min'] : 0;
					$field_max  = ! empty( $this->input_attrs['max'] ) ? $this->input_attrs['max'] : 100;
					$field_step = ! empty( $this->input_attrs['step'] ) ? $this->input_attrs['step'] : 1;
					$field_val  = ! empty( $value )
						? ( $value . ( $is_range && strpos( $value, ',' ) === false ? ',' . $field_max : '' ) )
						: ( $is_range ? $field_min . ',' . $field_max : $field_min );
					$output     .= '<div id="' . esc_attr( '_customize-range-' . esc_attr( $this->id ) ) . '"'
					               . ' class="ideapark_range_slider"'
					               . ' data-range="' . esc_attr( $is_range ? 'true' : 'min' ) . '"'
					               . ' data-min="' . esc_attr( $field_min ) . '"'
					               . ' data-max="' . esc_attr( $field_max ) . '"'
					               . ' data-step="' . esc_attr( $field_step ) . '"'
					               . '>'
					               . '<span class="ideapark_range_slider_label ideapark_range_slider_label_min">'
					               . esc_html( $field_min )
					               . '</span>'
					               . '<span class="ideapark_range_slider_label ideapark_range_slider_label_max">'
					               . esc_html( $field_max )
					               . '</span>';
					$values     = explode( ',', $field_val );
					for ( $i = 0; $i < count( $values ); $i ++ ) {
						$output .= '<span class="ideapark_range_slider_label ideapark_range_slider_label_cur">'
						           . esc_html( $values[ $i ] )
						           . '</span>';
					}
					$output .= '</div>';

					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="customize-control-title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="customize-control-description description">', '</span>' ),
						'<div class="customize-control-wrap">',
						'<span class="customize-control-field-wrap">
							<input type="' . ( ! $show_value ? 'hidden' : 'number' ) . '"' . $this->get_link() .
						( $show_value ? ' class="ideapark_range_slider_value"' : '' ) .
						( ! empty( $this->input_attrs['var_name'] ) ? ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"' : '' ) . '" />' .
						$output . ' 
					</span></div>'
					);

				}
			}

			class WP_Customize_Checklist_Control extends WP_Customize_Control {
				public $type = 'checklist';

				public function render_content() {
					$output = '';
					$value  = $this->value();

					if ( ! empty( $this->input_attrs['sortable'] ) ) {
						wp_enqueue_script( 'jquery-ui-sortable', false, [
							'jquery',
							'jquery-ui-core'
						], null, true );
					}
					$output .= '<div class="ideapark_checklist ' . ( ! empty( $this->input_attrs['max-height'] ) ? 'ideapark_checklist_scroll' : '' ) . ' ideapark_checklist_' . esc_attr( ! empty( $this->input_attrs['dir'] ) ? $this->input_attrs['dir'] : 'vertical' )
					           . ( ! empty( $this->input_attrs['sortable'] ) ? ' ideapark_sortable' : '' )
					           . '"' . ( ! empty( $this->input_attrs['max-height'] ) ? ' style="max-height: ' . trim( esc_attr( $this->input_attrs['max-height'] ) ) . 'px"' : '' )
					           . ( ! empty( $this->input_attrs['add_ajax_action'] ) ? ' data-add-ajax-action="' . esc_attr( $this->input_attrs['add_ajax_action'] ) . '"' : '' )
					           . ( ! empty( $this->input_attrs['delete_ajax_action'] ) ? ' data-delete-ajax-action="' . esc_attr( $this->input_attrs['delete_ajax_action'] ) . '"' : '' )
					           . '>';
					if ( ! is_array( $value ) ) {
						if ( ! empty( $value ) ) {
							parse_str( str_replace( '|', '&', $value ), $value );
						} else {
							$value = [];
						}
					}

					if ( ! empty( $this->input_attrs['choices_add'] ) ) {
						$choices = array_filter( $this->input_attrs['choices_add'], function ( $key ) use ( $value ) {
							return isset( $value[ $key ] );
						}, ARRAY_FILTER_USE_KEY );

						$choices = ideapark_array_merge( $value, $choices );
					} else {
						if ( ! empty( $this->input_attrs['sortable'] ) && is_array( $value ) ) {
							$value = array_filter( $value, function ( $key ) {
								return array_key_exists( $key, $this->input_attrs['choices'] );
							}, ARRAY_FILTER_USE_KEY );

							$this->input_attrs['choices'] = ideapark_array_merge( $value, $this->input_attrs['choices'] );
						}
						$choices = $this->input_attrs['choices'];
					}

					foreach ( $choices as $k => $v ) {
						$output .= '<div class="ideapark_checklist_item_label'
						           . ( ! empty( $this->input_attrs['sortable'] ) ? ' ideapark_sortable_item' : '' )
						           . '"><label>'
						           . '<input type="checkbox" value="1" data-name="' . $k . '"'
						           . ( isset( $value[ $k ] ) && 1 == (int) $value[ $k ] ? ' checked="checked"' : '' )
						           . ' />'
						           . ( substr( $v, 0, 4 ) == 'http' ? '<img src="' . esc_url( $v ) . '">' : esc_html( preg_replace( '~^[ \-]+~u', '', $v ) ) )
						           . '</label>'
						           . ( ! empty( $this->input_attrs['choices_edit'][ $k ] ) ? '<button type="button" class="ideapark_checklist_item_edit" data-control="' . esc_attr( $this->input_attrs['choices_edit'][ $k ] ) . '"><span class="dashicons dashicons-admin-generic"></span></button>' : '' )
						           . ( ! empty( $this->input_attrs['choices_delete'] ) && in_array( $k, $this->input_attrs['choices_delete'] ) || ! empty( $this->input_attrs['choices_add'] ) ? '<button type="button" class="ideapark_checklist_item_delete" data-section="' . esc_attr( $k ) . '"><span class="dashicons dashicons-no-alt"></span></button>' : '' )
						           . '</div>';
					}
					$output .= '</div>';

					$output_add = '';

					if ( ! empty( $this->input_attrs['can_add_block'] ) ) {
						$output_add .= ideapark_wrap(
							ideapark_wrap( esc_html__( 'Please reload the page to see the settings of the new blocks', 'kidz' ), '<span class="notification-message">', '<br><button type="button" data-id="' . esc_attr( $this->id ) . '" class="button-primary button ideapark-customizer-reload">' . esc_html__( 'Reload', 'kidz' ) . '</button></span>' ),
							'<div class="ideapark-notice ideapark-notice--warning ideapark_checklist_add_notice">',
							'</div>'
						);
						$output_add .= '<div class="ideapark_checklist_add_wrap">';
						$output_add .= esc_html__( 'Add new block', 'kidz' );
						$output_add .= '<div class="ideapark_checklist_add_inline"><select class="ideapark_checklist_add_select">';
						$output_add .= '<option value="">' . esc_html__( '- select block -', 'kidz' ) . '</option>';
						foreach ( $this->input_attrs['can_add_block'] as $section_id ) {
							$output_add .= '<option value="' . esc_attr( $section_id ) . '">' . $this->input_attrs['choices'][ $section_id ] . '</option>';
						}
						$output_add .= '</select><button class="button ideapark_checklist_add_button" type="button">' . esc_html__( 'Add', 'kidz' ) . '</button></div>';
						$output_add .= '</div>';
					} elseif ( ! empty( $this->input_attrs['choices_add'] ) ) {
						$output_add      .= '<div class="ideapark_checklist_add_wrap">';
						$output_add      .= esc_html__( 'Add new', 'kidz' );
						$output_add      .= '<div class="ideapark_checklist_add_inline"><select class="ideapark_checklist_add_select">';
						$output_add      .= '<option value="">' . esc_html__( '- select -', 'kidz' ) . '</option>';
						$is_option_group = false;
						foreach ( $this->input_attrs['choices_add'] as $section_id => $section_name ) {
							if ( strpos( $section_id, '*' ) === 0 ) {
								if ( $is_option_group ) {
									$output_add .= '</optgroup>';
								}
								$output_add      .= '<optgroup label="' . $section_name . '">';
								$is_option_group = true;
							} else {
								$output_add .= '<option value="' . esc_attr( $section_id ) . '">' . $section_name . '</option>';
							}
						}
						if ( $is_option_group ) {
							$output_add .= '</optgroup>';
						}
						$output_add .= '</select><button class="button ideapark_checklist_add_button" type="button">' . esc_html__( 'Add', 'kidz' ) . '</button></div>';
						$output_add .= '</div>';
					}


					echo ideapark_wrap(
						ideapark_wrap( $this->label, '<span class="customize-control-title">', '</span>' ) .
						ideapark_wrap( $this->description, '<span class="customize-control-description description">', '</span>' ),
						'<div class="customize-control-wrap">',
						'<span class="customize-control-field-wrap">
							<input type="hidden" ' . $this->get_link() .
						( ! empty( $this->input_attrs['var_name'] ) ? ' data-var_name="' . esc_attr( $this->input_attrs['var_name'] ) . '"' : '' ) . ' />' .
						$output . '</span>' . $output_add . '</div>'
					);
				}
			}
		}

		$panel_priority = 1;

		$ideapark_customize_panels = [
			'front_page_builder' => [
				'priority'    => 85,
				'title'       => __( 'Front Page Builder', 'kidz' ),
				'description' => '',
			]
		];

		foreach ( $ideapark_customize_panels as $panel_name => $panel ) {
			$wp_customize->add_panel( $panel_name, [
				'capability'  => 'edit_theme_options',
				'title'       => ! empty( $panel['title'] ) ? $panel['title'] : '',
				'description' => ! empty( $panel['description'] ) ? $panel['description'] : '',
				'priority'    => isset( $panel['priority'] ) ? $panel['priority'] : $panel_priority ++,
			] );
		}

		foreach ( $ideapark_customize as $i_section => $section ) {
			if ( ! empty( $section['controls'] ) ) {

				$panel_name = ! empty( $section['panel'] ) ? $section['panel'] : '';

				if ( ! array_key_exists( 'section', $section ) ) {
					$wp_customize->add_section( $section_name = 'ideapark_section_' . ( ! empty( $section['section_id'] ) ? $section['section_id'] : $i_section ), [
						'panel'       => $panel_name,
						'title'       => ! empty( $section['title'] ) ? $section['title'] : '',
						'description' => ! empty( $section['description'] ) ? $section['description'] : '',
						'priority'    => isset( $section['priority'] ) ? $section['priority'] : 160 + $i_section,
					] );
				} else {
					$section_name = $section['section'];
				}

				$control_priority = 1;
				$control_ids      = [];
				$first_control    = '';
				foreach ( $section['controls'] as $control_name => $control ) {

					if ( ! empty( $control['type'] ) || ! empty( $control['class'] ) ) {

						if ( ! $first_control ) {
							$first_control = $control_name;
						}

						$a = [
							'transport' => isset( $control['transport'] ) ? $control['transport'] : ( ( isset( $section['refresh'] ) && ! isset( $control['refresh'] ) && true !== $section['refresh'] ) || ( isset( $control['refresh'] ) && true !== $control['refresh'] ) ? 'postMessage' : 'refresh' )
						];
						if ( isset( $control['default'] ) ) {
							if ( is_string( $control['default'] ) && function_exists( $control['default'] ) ) {
								$a['default'] = call_user_func( $control['default'] );
							} else {
								$a['default'] = $control['default'];
							}
						}
						if ( isset( $control['sanitize_callback'] ) ) {
							$a['sanitize_callback'] = $control['sanitize_callback'];
						} else {
							die( 'No sanitize_callback found!' . print_r( $control, true ) );
						}

						call_user_func( [ $wp_customize, 'add_setting' ], $control_name, $a );

						if ( ! IDEAPARK_THEME_IS_AJAX_HEARTBEAT ) {

							if ( ! empty( $control['choices'] ) && is_string( $control['choices'] ) ) {
								if ( function_exists( $control['choices'] ) ) {
									$control['choices'] = call_user_func( $control['choices'] );
								} else {
									$control['choices'] = [];
								}
							}

							if ( ! empty( $control['choices_add'] ) && is_string( $control['choices_add'] ) ) {
								if ( function_exists( $control['choices_add'] ) ) {
									$control['choices_add'] = call_user_func( $control['choices_add'] );
								} else {
									$control['choices_add'] = [];
								}
							}
						}

						if ( empty( $control['class'] ) ) {
							$wp_customize->add_control(
								new WP_Customize_Control(
									$wp_customize,
									$control_name,
									[
										'label'    => $control['label'],
										'section'  => $section_name,
										'settings' => ! empty( $control['settings'] ) ? $control['settings'] : $control_name,
										'type'     => $control['type'],
										'priority' => ! empty( $control['priority'] ) ? $control['priority'] : $control_priority + 1,
										'choices'  => ! empty( $control['choices'] ) ? $control['choices'] : null,
									]
								)
							);
						} else {

							$wp_customize->add_control(
								new $control['class'](
									$wp_customize,
									$control_name,
									[
										'label'           => ! empty( $control['label'] ) ? $control['label'] : '',
										'section'         => $section_name,
										'settings'        => ! empty( $control['settings'] ) ? $control['settings'] : $control_name,
										'type'            => ! empty( $control['type'] ) ? $control['type'] : null,
										'priority'        => ! empty( $control['priority'] ) ? $control['priority'] : $control_priority + 1,
										'choices'         => ! empty( $control['choices'] ) ? $control['choices'] : null,
										'active_callback' => ! empty( $control['active_callback'] ) ? $control['active_callback'] : '',
										'input_attrs'     => array_merge(
											$control, [
												'value'    => ideapark_mod( $control_name ),
												'var_name' => ! empty( $control['customizer'] ) ? $control['customizer'] : '',
											]
										),
									]
								)
							);
						}

						if ( ! empty( $control['description'] ) ) {
							$ideapark_customize_custom_css[ '#customize-control-' . $control_name . ( ! empty( $control['type'] ) && in_array( $control['type'], [
								'radio',
								'checkbox'
							] ) ? '' : ' .customize-control-title' ) ] = $control['description'];
						}

						$f = false;
						if ( isset( $control['refresh'] ) && is_string( $control['refresh'] )
						     &&
						     (
							     ( $is_auto_load = isset( $control['refresh_id'] ) && ideapark_customizer_check_template_part( $control['refresh_id'] ) )
							     ||
							     function_exists( $f = "ideapark_customizer_partial_refresh_" . ( isset( $control['refresh_id'] ) ? $control['refresh_id'] : $control_name ) )
						     )
						     && isset( $wp_customize->selective_refresh ) ) {
							$wp_customize->selective_refresh->add_partial(
								$control_name, [
									'selector'            => $control['refresh'],
									'settings'            => $control_name,
									'render_callback'     => $is_auto_load ? 'ideapark_customizer_load_template_part' : $f,
									'container_inclusive' => ! empty( $control['refresh_wrapper'] ),
								]
							);
						} elseif ( ! isset( $control['refresh'] ) ) {
							$control_ids[] = $control_name;
						}
					}
				}

				if ( isset( $section['refresh_id'] ) && isset( $section['refresh'] ) && is_string( $section['refresh'] )
				     &&
				     (
					     ( $is_auto_load = ideapark_customizer_check_template_part( $section['refresh_id'] ) )
					     ||
					     function_exists( "ideapark_customizer_partial_refresh_{$section['refresh_id']}" )
				     )
				     && isset( $wp_customize->selective_refresh ) ) {
					$wp_customize->selective_refresh->add_partial(
						$first_control /* first control from this section*/, [
							'selector'            => $section['refresh'],
							'settings'            => $control_ids,
							'render_callback'     => $is_auto_load ? 'ideapark_customizer_load_template_part' : "ideapark_customizer_partial_refresh_{$section['refresh_id']}",
							'container_inclusive' => ! empty( $section['refresh_wrapper'] ),
						]
					);
				}
			}
		}

		$sec = $wp_customize->get_section( 'static_front_page' );
		if ( is_object( $sec ) ) {
			$sec->priority = 87;
		}

		$sec = $wp_customize->get_panel( 'woocommerce' );
		if ( is_object( $sec ) ) {
			$sec->priority = 110;
		}

		if ( ideapark_woocommerce_on() ) {

			$wp_customize->get_panel( 'woocommerce' )->priority = 130;

			$wp_customize->remove_setting( 'woocommerce_catalog_columns' );
			$wp_customize->remove_control( 'woocommerce_catalog_columns' );
			$wp_customize->remove_setting( 'woocommerce_catalog_rows' );
			$wp_customize->remove_control( 'woocommerce_catalog_rows' );

			$wp_customize->remove_setting( 'woocommerce_thumbnail_cropping' );
			$wp_customize->remove_setting( 'woocommerce_thumbnail_cropping_custom_width' );
			$wp_customize->remove_setting( 'woocommerce_thumbnail_cropping_custom_height' );
			$wp_customize->remove_control( 'woocommerce_thumbnail_cropping' );

			$wp_customize->get_section( 'woocommerce_product_images' )->description = '';

			$wp_customize->get_control( 'woocommerce_shop_page_display' )->section        = 'ideapark_section_woocommerce_grid';
			$wp_customize->get_control( 'woocommerce_category_archive_display' )->section = 'ideapark_section_woocommerce_grid';
			$wp_customize->get_control( 'woocommerce_default_catalog_orderby' )->section  = 'ideapark_section_woocommerce_grid';

			$wp_customize->get_control( 'woocommerce_shop_page_display' )->priority        = 2;
			$wp_customize->get_control( 'woocommerce_category_archive_display' )->priority = 2;
			$wp_customize->get_control( 'woocommerce_default_catalog_orderby' )->priority  = 2;
		}
	}
}

if ( ! function_exists( 'ideapark_get_theme_dependencies' ) ) {
	function ideapark_get_theme_dependencies() {
		global $ideapark_customize;
		$result              = [
			'refresh_css'          => [],
			'dependency'           => [],
			'refresh_callback'     => [],
			'refresh_pre_callback' => []
		];
		$partial_refresh     = [];
		$css_refresh         = [];
		$css_refresh_control = [];
		foreach ( $ideapark_customize as $i_section => $section ) {
			$first_control_name = '';
			if ( ! empty( $section['controls'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					if ( ! $first_control_name ) {
						$first_control_name = $control_name;
					}
					if ( ! empty( $control['refresh_css'] ) ) {
						$result['refresh_css'][] = $control_name;
					}
					if ( ! empty( $control['refresh'] ) && is_string( $control['refresh'] ) ) {
						$result['refresh'][ $control_name ] = $control['refresh'];
						$partial_refresh[]                  = trim( $control['refresh'] );
					} elseif ( ! empty( $control['refresh_css'] ) && is_string( $control['refresh_css'] ) ) {
						$result['refresh'][ $control_name ] = $control['refresh_css'];
					}

					if ( ! empty( $control['refresh_css'] ) && is_string( $control['refresh_css'] ) ) {
						$css_refresh[] = $selector = trim( $control['refresh_css'] );
						if ( ! array_key_exists( $selector, $css_refresh_control ) ) {
							$css_refresh_control[ $selector ] = $control_name;
						}
					}

					if ( ! empty( $control['refresh_callback'] ) && is_string( $control['refresh_callback'] ) ) {
						$result['refresh_callback'][ $control_name ] = $control['refresh_callback'];
					}

					if ( ! empty( $control['refresh_pre_callback'] ) && is_string( $control['refresh_pre_callback'] ) ) {
						$result['refresh_pre_callback'][ $control_name ] = $control['refresh_pre_callback'];
					}

					if ( ! empty( $control['dependency'] ) && is_array( $control['dependency'] ) ) {
						$result['dependency'][ $control_name ] = $control['dependency'];
					}
				}
			}

			if ( ! empty( $section['refresh'] ) && is_string( $section['refresh'] ) && $first_control_name ) {
				$result['refresh'][ $first_control_name ] = $section['refresh'];
				$partial_refresh[]                        = trim( $section['refresh'] );
			}

			if ( ! empty( $section['refresh_css'] ) && is_string( $section['refresh_css'] ) && $first_control_name ) {
				$css_refresh[] = $selector = trim( $section['refresh_css'] );
				if ( ! array_key_exists( $selector, $css_refresh_control ) ) {
					$css_refresh_control[ $selector ] = $first_control_name;
				}
			}

			if ( ! empty( $section['refresh_callback'] ) && is_string( $section['refresh_callback'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					$result['refresh_callback'][ $control_name ] = $section['refresh_callback'];
				}
			}

			if ( ! empty( $section['refresh_pre_callback'] ) && is_string( $section['refresh_pre_callback'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					$result['refresh_pre_callback'][ $control_name ] = $section['refresh_pre_callback'];
				}
			}
		}

		$refresh_only_css = array_diff( array_unique( $css_refresh ), array_unique( $partial_refresh ) );

		$result['refresh_only_css'] = [];
		foreach ( $refresh_only_css as $selector ) {
			$result['refresh_only_css'][ $selector ] = $css_refresh_control[ $selector ];
		}

		return $result;
	}
}

if ( ! function_exists( 'ideapark_customizer_check_template_part' ) ) {
	function ideapark_customizer_check_template_part( $template ) {
		return ideapark_is_file( IDEAPARK_THEME_DIR . '/inc/' . $template . '.php' ) || ideapark_is_file( IDEAPARK_THEME_DIR . '/' . $template . '.php' );
	}
}

if ( ! function_exists( 'ideapark_customizer_load_template_part' ) ) {
	function ideapark_customizer_load_template_part( $_control ) {
		global $ideapark_customize;
		$is_found = false;
		foreach ( $ideapark_customize as $i_section => $section ) {
			if ( ! empty( $section['controls'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					$is_found = $control_name == $_control->id;
					if ( $is_found && ! empty( $control['refresh_id'] ) ) {
						ob_start();
						if ( ideapark_is_file( IDEAPARK_THEME_DIR . '/inc/' . $control['refresh_id'] . '.php' ) ) {
							ideapark_get_template_part( 'inc/' . $control['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
						}
						if ( ideapark_is_file( IDEAPARK_THEME_DIR . '/' . $control['refresh_id'] . '.php' ) ) {
							ideapark_get_template_part( $control['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
						}
						$output = ob_get_contents();
						ob_end_clean();

						return $output;
					}
					if ( $is_found ) {
						break;
					}
				}
			}
			if ( $is_found && ! empty( $section['refresh_id'] ) ) {
				ob_start();
				if ( ideapark_is_file( IDEAPARK_THEME_DIR . '/inc/' . $section['refresh_id'] . '.php' ) ) {
					ideapark_get_template_part( 'inc/' . $section['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
				}
				if ( ideapark_is_file( IDEAPARK_THEME_DIR . '/' . $section['refresh_id'] . '.php' ) ) {
					ideapark_get_template_part( $section['refresh_id'], ! empty( $section['section_id'] ) ? [ 'section_id' => $section['section_id'] ] : null );
				}
				$output = ob_get_contents();
				ob_end_clean();

				return $output;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_customizer_get_template_part' ) ) {
	function ideapark_customizer_get_template_part( $template ) {
		ob_start();
		get_template_part( $template );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_footer' ) ) {
	function ideapark_customizer_partial_refresh_footer() {
		return ideapark_customizer_get_template_part( 'footer' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_footer_contacts' ) ) {
	function ideapark_customizer_partial_refresh_footer_contacts() {
		return ideapark_mod( 'footer_contacts' ) ?
			make_clickable( str_replace( ']]>', ']]&gt;', ideapark_mod( 'footer_contacts' ) ) ) : '';
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_footer_copyright' ) ) {
	function ideapark_customizer_partial_refresh_footer_copyright() {
		return do_shortcode( ideapark_mod( 'footer_copyright' ) );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_logo_footer' ) ) {
	function ideapark_customizer_partial_refresh_logo_footer() {
		return ideapark_customizer_get_template_part( 'inc/footer-logo' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_soc' ) ) {
	function ideapark_customizer_partial_refresh_soc() {
		return ideapark_customizer_get_template_part( 'inc/soc' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_header_logo' ) ) {
	function ideapark_customizer_partial_refresh_header_logo() {
		return ideapark_customizer_get_template_part( 'inc/header-logo' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_main_menu' ) ) {
	function ideapark_customizer_partial_refresh_main_menu() {
		return ideapark_customizer_get_template_part( 'inc/main-menu' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_header' ) ) {
	function ideapark_customizer_partial_refresh_header() {
		return ideapark_customizer_get_template_part( 'header' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_top_menu' ) ) {
	function ideapark_customizer_partial_refresh_top_menu() {
		return ideapark_customizer_get_template_part( 'inc/home-top-menu' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_contact_phones' ) ) {
	function ideapark_customizer_partial_refresh_contact_phones() {
		return ideapark_mod( 'contact_phones' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_contact_email' ) ) {
	function ideapark_customizer_partial_refresh_contact_email() {
		return ideapark_mod( 'contact_email' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_contact_address' ) ) {
	function ideapark_customizer_partial_refresh_contact_address() {
		return ideapark_mod( 'contact_address' );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_contact_form_shortcode' ) ) {
	function ideapark_customizer_partial_refresh_contact_form_shortcode() {
		return ideapark_shortcode( ideapark_mod( 'contact_form_shortcode' ) );
	}
}

if ( ! function_exists( 'ideapark_customizer_partial_refresh_contact_map_shortcode' ) ) {
	function ideapark_customizer_partial_refresh_contact_map_shortcode() {
		return ideapark_shortcode( ideapark_mod( 'contact_map_shortcode' ) );
	}
}

if ( ! function_exists( 'ideapark_expanded_alowed_tags' ) ) {
	function ideapark_expanded_alowed_tags() {
		$my_allowed = wp_kses_allowed_html( 'post' );

		$my_allowed['iframe'] = [
			'src'             => [],
			'height'          => [],
			'width'           => [],
			'frameborder'     => [],
			'allowfullscreen' => [],
			'style'           => [],
		];

		return $my_allowed;
	}
}

if ( ! function_exists( 'ideapark_sanitize_embed_field' ) ) {
	function ideapark_sanitize_embed_field( $input ) {
		return wp_kses( $input, ideapark_expanded_alowed_tags() );
	}
}

if ( ! function_exists( 'ideapark_parse_checklist' ) ) {
	function ideapark_parse_checklist( $str ) {
		$values = [];
		if ( ! empty( $str ) ) {
			parse_str( str_replace( '|', '&', $str ), $values );
		}

		return $values;
	}
}

if ( ! function_exists( 'ideapark_sanitize_float' ) ) {
	function ideapark_sanitize_float( $input ) {
		$output = str_replace( ',', '.', $input );

		return (float) $output;
	}
}

if ( ! function_exists( 'ideapark_sanitize_abs_int' ) ) {
	function ideapark_sanitize_abs_int( $input ) {
		return abs( (int) $input );
	}
}

if ( ! function_exists( 'ideapark_sanitize_checkbox' ) ) {
	function ideapark_sanitize_checkbox( $input ) {
		if ( $input ):
			$output = true;
		else:
			$output = false;
		endif;

		return $output;
	}
}

if ( ! function_exists( 'ideapark_sanitize_source_code' ) ) {
	function ideapark_sanitize_source_code( $input ) {
		return trim( $input );
	}
}

if ( ! function_exists( 'ideapark_customize_admin_style' ) ) {
	function ideapark_customize_admin_style() {
		global $ideapark_customize_custom_css;
		if ( ! empty( $ideapark_customize_custom_css ) && is_array( $ideapark_customize_custom_css ) ) {
			?>
			<style type="text/css">
				<?php foreach ( $ideapark_customize_custom_css as $style_name => $text ) { ?>
				<?php echo esc_attr( $style_name ); ?>:after {
					content: "<?php echo esc_attr($text) ?>";
				}

				<?php } ?>
			</style>
			<?php
		}
	}
}

if ( ! function_exists( 'ideapark_customizer_preview_js' ) ) {
	add_action( 'customize_preview_init', 'ideapark_customizer_preview_js' );
	function ideapark_customizer_preview_js() {
		wp_enqueue_script(
			'ideapark-customizer-preview',
			IDEAPARK_THEME_URI . '/js/admin-customizer-preview.js',
			[ 'customize-preview' ], null, true
		);
	}
}

if ( ! function_exists( 'ideapark_get_all_atributes' ) ) {
	function ideapark_get_all_atributes() {
		$attribute_array      = [ '' => '' ];
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attribute_array[ $taxonomy ] = $tax->attribute_name;
				}
			}
		}

		return $attribute_array;
	}
}

if ( ! function_exists( 'ideapark_get_font_choices' ) ) {
	function ideapark_get_font_choices() {
		static $choices;
		if ( $choices !== null ) {
			return $choices;
		}
		$fonts   = &ideapark_get_google_fonts();
		$choices = [];

		foreach (
			[
				'Arial',
				'Tahoma',
				'Verdana',
				'Helvetica',
				'Times New Roman',
				'Trebuchet MS',
				'Georgia'
			] as $system_font
		) {
			$choices[ 'system-' . $system_font ] = __( 'System Font:', 'kidz' ) . ' ' . $system_font;
		}

		// Repackage the fonts into value/label pairs
		foreach ( $fonts as $key => $font ) {
			$choices[ $key ] = $font['label'];
		}

		return $choices;
	}
}

if ( ! function_exists( 'ideapark_get_lang_postfix' ) ) {
	function ideapark_get_lang_postfix() {
		$lang_postfix = '';
		if ( $languages = ideapark_active_languages() ) {
			if ( ideapark_current_language() != ideapark_default_language() ) {
				$lang_postfix = '_' . ideapark_current_language();
			}
		}

		return $lang_postfix;
	}
}

if ( ! function_exists( 'ideapark_get_google_font_uri' ) ) {
	function ideapark_get_google_font_uri( $fonts ) {

		if ( ! $fonts || ! is_array( $fonts ) ) {
			return '';
		}
		$fonts = array_filter( array_unique( array_filter( $fonts, function ( $item ) {
			return ! preg_match( '~^(custom-|system-)~', $item ?: '' );
		} ) ) );
		if ( ! $fonts ) {
			return '';
		}
		$hash = md5( implode( ',', $fonts ) . '--' . ideapark_mod( 'theme_font_1_weight' ) . '--' . IDEAPARK_THEME_VERSION );

		$lang_postfix = ideapark_get_lang_postfix();

		if ( ( $data = get_option( 'ideapark_google_font_uri' . $lang_postfix ) ) && ! empty( $data['version'] ) && ! empty( $data['uri'] ) ) {
			if ( $data['version'] == $hash ) {
				return $data['uri'];
			} else {
				delete_option( 'ideapark_google_font_uri' . $lang_postfix );
			}
		}

		$allowed_fonts = &ideapark_get_google_fonts();
		$family        = [];

		foreach ( $fonts as $font ) {
			$font = trim( $font );

			if ( array_key_exists( $font, $allowed_fonts ) ) {
				$filter   = array_unique( [
					'regular',
					'500',
					'700',
					'800',
					ideapark_mod( 'theme_font_1_weight' )
				] );
				$family[] = urlencode( $font . ':' . join( ',', ideapark_choose_google_font_variants( $font, $allowed_fonts[ $font ]['variants'], $filter ) ) );
			}
		}

		if ( empty( $family ) ) {
			return '';
		} else {
			$request = '//fonts.googleapis.com/css?family=' . implode( rawurlencode( '|' ), $family );
		}

		$subset = ideapark_mod( 'theme_font_subsets' . $lang_postfix );

		if ( 'all' === $subset ) {
			$subsets_available = ideapark_get_google_font_subsets();

			unset( $subsets_available['all'] );

			$subsets = array_keys( $subsets_available );
		} else {
			$subsets = [
				'latin',
				$subset,
			];
		}

		if ( ! empty( $subsets ) ) {
			$request .= urlencode( '&subset=' . join( ',', $subsets ) );
		}

		if ( ideapark_mod( 'google_fonts_display_swap' ) ) {
			$request .= '&display=swap';
		}

		add_option( 'ideapark_google_font_uri' . $lang_postfix, [
			'version' => $hash,
			'uri'     => esc_url( $request )
		], '', 'yes' );

		return esc_url( $request );
	}
}

if ( ! function_exists( 'ideapark_get_google_font_subsets' ) ) {
	function ideapark_get_google_font_subsets() {
		global $_ideapark_google_fonts_subsets;

		$list = [
			'all' => esc_html__( 'All', 'kidz' ),
		];

		foreach ( $_ideapark_google_fonts_subsets as $subset ) {
			$name = ucfirst( trim( $subset ) );
			if ( preg_match( '~-ext$~', $name ) ) {
				$name = preg_replace( '~-ext$~', ' ' . esc_html__( 'Extended', 'kidz' ), $name );
			}
			$list[ $subset ] = esc_html( $name );
		}

		return $list;
	}
}

if ( ! function_exists( 'ideapark_choose_google_font_variants' ) ) {
	function ideapark_choose_google_font_variants( $font, $variants = [], $filter = [ 'regular', '700' ] ) {
		$chosen_variants = [];
		if ( empty( $variants ) ) {
			$fonts = &ideapark_get_google_fonts();

			if ( array_key_exists( $font, $fonts ) ) {
				$variants = $fonts[ $font ]['variants'];
			}
		}

		foreach ( $filter as $var ) {
			if ( in_array( $var, $variants ) && ! array_key_exists( $var, $chosen_variants ) ) {
				$chosen_variants[] = $var;
			}
		}

		if ( empty( $chosen_variants ) ) {
			$variants[0];
		}

		return apply_filters( 'ideapark_font_variants', array_unique( $chosen_variants ), $font, $variants );
	}
}

if ( ! function_exists( 'ideapark_sanitize_font_choice' ) ) {
	function ideapark_sanitize_font_choice( $value ) {
		if ( is_int( $value ) ) {
			// The array key is an integer, so the chosen option is a heading, not a real choice
			return '';
		} else if ( array_key_exists( $value, ideapark_get_font_choices() ) ) {
			return $value;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'ideapark_customizer_product_tab_list' ) ) {
	function ideapark_customizer_product_tab_list() {
		$list = [
			'*main'                 => esc_html__( 'Main', 'kidz' ),
			'featured_products'     => esc_html__( 'Featured Products', 'kidz' ),
			'sale_products'         => esc_html__( 'Sale Products', 'kidz' ),
			'best_selling_products' => esc_html__( 'Best-Selling Products', 'kidz' ),
			'recent_products'       => esc_html__( 'Recent Products', 'kidz' ),
			'*custom'               => esc_html__( 'Custom', 'kidz' ),
			'shortcode'             => esc_html__( 'Products shortcode', 'kidz' ),
			'*categories'           => esc_html__( 'Categories', 'kidz' ),
		];

		$args = [
			'taxonomy'     => 'product_cat',
			'orderby'      => 'term_group',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 0,
			'exclude'      => ideapark_hidden_category_ids() ?: null,
		];
		if ( $all_categories = get_categories( $args ) ) {

			$category_name   = [];
			$category_parent = [];
			foreach ( $all_categories as $cat ) {
				$category_name[ $cat->term_id ]    = esc_html( $cat->name );
				$category_parent[ $cat->parent ][] = $cat->term_id;
			}

			$get_category = function ( $parent = 0, $prefix = '' ) use ( &$list, &$category_parent, &$category_name, &$get_category ) {
				if ( array_key_exists( $parent, $category_parent ) ) {
					$categories = $category_parent[ $parent ];
					foreach ( $categories as $category_id ) {
						$list[ $category_id ] = $prefix . $category_name[ $category_id ];
						$get_category( $category_id, $prefix . ' - ' );
					}
				}
			};

			$get_category();
		}

		return $list;
	}
}

if ( ! function_exists( 'ideapark_add_last_control' ) ) {
	function ideapark_add_last_control() {
		global $ideapark_customize;

		$ideapark_customize[ sizeof( $ideapark_customize ) - 1 ]['controls']['last_option'] = [
			'label'             => '',
			'description'       => '',
			'type'              => 'hidden',
			'default'           => '',
			'sanitize_callback' => 'ideapark_sanitize_checkbox',
			'class'             => 'WP_Customize_Hidden_Control',
		];
	}
}

if ( ! function_exists( 'ideapark_ajax_customizer_add_section' ) ) {
	function ideapark_ajax_customizer_add_section() {
		if ( current_user_can( 'customize' ) && ! empty( $_POST['section'] ) ) {
			if ( $section = ideapark_add_new_section( $_POST['section'] ) ) {
				wp_send_json( $section );
			} else {
				wp_send_json( [ 'error' => esc_html__( 'Something went wrong...', 'kidz' ) ] );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_ajax_customizer_delete_section' ) ) {
	function ideapark_ajax_customizer_delete_section() {
		if ( current_user_can( 'customize' ) && ! empty( $_POST['section'] ) ) {
			if ( $section = ideapark_delete_section( $_POST['section'] ) ) {
				wp_send_json( [ 'success' => 1 ] );
			} else {
				wp_send_json( [ 'error' => esc_html__( 'Something went wrong...', 'kidz' ) ] );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_parse_added_blocks' ) ) {
	function ideapark_parse_added_blocks() {
		global $ideapark_customize;
		if ( $added_blocks = get_option( 'ideapark_added_blocks' ) ) {
			foreach ( $ideapark_customize as $section_index => $section ) {
				if ( ! empty( $section['controls'] ) ) {
					foreach ( $section['controls'] as $control_name => $control ) {
						if ( ! empty( $section['panel'] ) && ! empty( $control['can_add_block'] ) && ! empty( $control['type'] ) && $control['type'] == 'checklist' && array_key_exists( $section['panel'], $added_blocks ) ) {
							foreach ( $added_blocks[ $section['panel'] ] as $item ) {
								$section_orig_id   = $item['section_id'];
								$index             = $item['index'];
								$checklist_control = &$ideapark_customize[ $section_index ]['controls'][ $control_name ];

								foreach ( $ideapark_customize as $_section ) {
									if ( ! empty( $_section['section_id'] ) && $_section['section_id'] == $section_orig_id ) {
										$section_new               = $_section;
										$section_new['is_added']   = true;
										$section_new['section_id'] .= '-' . $index;
										$section_new['title']      .= ' - ' . $index;
										if ( ! empty( $section_new['refresh'] ) ) {
											$section_new['refresh'] .= '-' . $index;
										}
										$new_controls = [];
										if ( ! empty( $section_new['controls'] ) ) {
											foreach ( $section_new['controls'] as $_control_name => $_control ) {
												if ( ! empty( $_control['dependency'] ) ) {
													foreach ( $_control['dependency'] as $key => $val ) {
														if ( $key == $control_name ) {
															$_control['dependency'][ $key ] = [ 'search!=' . $section_orig_id . '-' . $index . '=1' ];
														} elseif ( array_key_exists( $key, $_section['controls'] ) ) {
															$_control['dependency'][ $key . '_' . $index ] = $val;
															unset( $_control['dependency'][ $key ] );
														}
													}
												}
												$new_controls[ $_control_name . '_' . $index ] = $_control;
											}
											$section_new['controls'] = $new_controls;
										}
										$ideapark_customize[] = $section_new;
										break;
									}
								}

								$checklist_control['default']                                    .= '|' . $section_orig_id . '-' . $index . '=0';
								$checklist_control['choices'][ $section_orig_id . '-' . $index ] = $checklist_control['choices'][ $section_orig_id ] . ' - ' . $index;
								if ( ! empty( $checklist_control['choices_edit'][ $section_orig_id ] ) ) {
									$checklist_control['choices_edit'][ $section_orig_id . '-' . $index ] = $checklist_control['choices_edit'][ $section_orig_id ] . '_' . $index;
								}
								if ( empty( $checklist_control['choices_delete'] ) ) {
									$checklist_control['choices_delete'] = [];
								}
								$checklist_control['choices_delete'][] = $section_orig_id . '-' . $index;
							}
						}
					}
				}
			}
		}

		if ( $languages = ideapark_active_languages() ) {
			foreach ( $ideapark_customize as $section_index => &$section ) {
				if ( ! empty( $section['controls'] ) && isset( $section['controls']['theme_font_0'] ) ) {
					$orig_controls = $section['controls'];
					$default_lang  = ideapark_default_language();
					foreach ( $languages as $lang_code => $lang_name ) {
						if ( $lang_code != $default_lang ) {
							$section['controls'][ 'header_font_lang_' . $lang_code ] = [
								'label'             => __( 'Fonts for', 'kidz' ) . ' ' . $lang_name,
								'class'             => 'WP_Customize_Info_Control',
								'sanitize_callback' => 'sanitize_text_field',
								'priority'          => 101,
							];
							foreach ( $orig_controls as $control_name => $control ) {
								if ( $control_name == 'header_custom_fonts_info' ) {
									break;
								}
								$control['priority']                                     = 101;
								$section['controls'][ $control_name . '_' . $lang_code ] = $control;
							}
						}
					}
					break;
				}
			}
		}
	}
}

if ( ! function_exists( 'ideapark_delete_section' ) ) {
	function ideapark_delete_section( $section_id ) {
		$added_blocks = get_option( 'ideapark_added_blocks' );
		$is_changed   = false;
		if ( ! empty( $added_blocks ) ) {
			foreach ( $added_blocks as $panel_name => $items ) {
				foreach ( $items as $item_index => $item ) {
					if ( $item['section_id'] . '-' . $item['index'] == $section_id ) {
						unset( $added_blocks[ $panel_name ][ $item_index ] );
						$is_changed = true;
						break;
					}
				}
			}
		}
		if ( $is_changed ) {
			if ( ! empty( $added_blocks ) ) {
				update_option( 'ideapark_added_blocks', $added_blocks );
			} else {
				delete_option( 'ideapark_added_blocks' );
			}
			delete_option( 'ideapark_customize' );
			ideapark_delete_file( IDEAPARK_THEME_UPLOAD_DIR . 'customizer_vars.php' );
		}

		return $is_changed;
	}
}

if ( ! function_exists( 'ideapark_add_new_section' ) ) {
	function ideapark_add_new_section( $section_orig_id ) {
		global $ideapark_customize;
		$added_blocks = get_option( 'ideapark_added_blocks' );
		if ( empty( $added_blocks ) ) {
			$added_blocks = [];
		}
		$section_name = '';
		$section_id   = '';
		foreach ( $ideapark_customize as $section ) {
			if ( ! empty( $section['controls'] ) ) {
				foreach ( $section['controls'] as $control_name => $control ) {
					if ( ! empty( $section['panel'] ) && ! empty( $control['can_add_block'] ) && ! empty( $control['type'] ) && $control['type'] == 'checklist' && ! empty( $control['can_add_block'] ) && in_array( $section_orig_id, $control['can_add_block'] ) ) {
						if ( array_key_exists( $section['panel'], $added_blocks ) ) {
							$index = 2;
							foreach ( $added_blocks[ $section['panel'] ] as $item ) {
								if ( $item['section_id'] == $section_orig_id ) {
									$index = max( $index, $item['index'] + 1 );
								}
							}
						} else {
							$index = 2;

							$added_blocks[ $section['panel'] ] = [];
						}
						$added_blocks[ $section['panel'] ][] = [
							'section_id' => $section_orig_id,
							'index'      => $index
						];
						$section_name                        = $control['choices'][ $section_orig_id ] . ' - ' . $index;
						$section_id                          = $section_orig_id . '-' . $index;
						break;
					}
				}
			}
		}

		if ( ! empty( $added_blocks ) ) {
			update_option( 'ideapark_added_blocks', $added_blocks );
		} else {
			delete_option( 'ideapark_added_blocks' );
		}

		delete_option( 'ideapark_customize' );
		ideapark_delete_file( IDEAPARK_THEME_UPLOAD_DIR . 'customizer_vars.php' );

		return $section_name && $section_id ? [
			'name' => $section_name,
			'id'   => $section_id
		] : false;
	}
}

$_ideapark_google_fonts_cache   = false;
$_ideapark_google_fonts_subsets = [];

if ( ! function_exists( 'ideapark_get_google_fonts' ) ) {
	function & ideapark_get_google_fonts() {
		global $_ideapark_google_fonts_cache, $_ideapark_google_fonts_subsets;

		if ( $_ideapark_google_fonts_cache ) {
			return $_ideapark_google_fonts_cache;
		}

		if ( ideapark_is_file( $fn = IDEAPARK_THEME_UPLOAD_DIR . 'google_fonts_cache.php' ) ) {
			try {
				include( $fn );
			} catch ( \ParseError $e ) {
				unlink( $fn );
				$_ideapark_google_fonts_cache = [];
			} catch ( \Throwable $e ) {
				unlink( $fn );
				$_ideapark_google_fonts_cache = [];
			}
			if ( ! empty( $_ideapark_google_fonts_ver ) && $_ideapark_google_fonts_ver == IDEAPARK_THEME_VERSION && $_ideapark_google_fonts_cache ) {
				return $_ideapark_google_fonts_cache;
			}
		}

		if ( ( $data = get_option( 'ideapark_google_fonts' ) ) && ! empty( $data['version'] ) && ! empty( $data['list'] ) && ! empty( $data['subsets'] ) ) {
			if ( $data['version'] == IDEAPARK_THEME_VERSION ) {
				$_ideapark_google_fonts_cache   = $data['list'];
				$_ideapark_google_fonts_subsets = $data['subsets'];

				return $_ideapark_google_fonts_cache;
			} else {
				ideapark_delete_file( IDEAPARK_THEME_UPLOAD_DIR . 'google_fonts_cache.php' );
				delete_option( 'ideapark_google_fonts' );
			}
		}

		$decoded_google_fonts = json_decode( ideapark_fgc( IDEAPARK_THEME_DIR . '/functions/customize/webfonts.json' ), true );
		$webfonts             = [];
		foreach ( $decoded_google_fonts['items'] as $key => $value ) {
			$font_family                          = $decoded_google_fonts['items'][ $key ]['family'];
			$webfonts[ $font_family ]             = [];
			$webfonts[ $font_family ]['label']    = $font_family;
			$webfonts[ $font_family ]['variants'] = $decoded_google_fonts['items'][ $key ]['variants'];
			$webfonts[ $font_family ]['subsets']  = $decoded_google_fonts['items'][ $key ]['subsets'];
			$_ideapark_google_fonts_subsets       = array_unique( array_merge( $_ideapark_google_fonts_subsets, $decoded_google_fonts['items'][ $key ]['subsets'] ) );
		}

		sort( $_ideapark_google_fonts_subsets );
		$_ideapark_google_fonts_cache = apply_filters( 'ideapark_get_google_fonts', $webfonts );

		$code = "<?php\n";
		$code .= '$_ideapark_google_fonts_ver = "' . IDEAPARK_THEME_VERSION . '";' . "\n";
		foreach ( [ '_ideapark_google_fonts_cache', '_ideapark_google_fonts_subsets' ] as $var_name ) {
			$code .= 'global $' . $var_name . ";\n";
			$code .= '$' . $var_name . ' = ' . ideapark_array_to_php_code( $$var_name ) . ";\n\n";
		}
		ideapark_fpc( $fn = IDEAPARK_THEME_UPLOAD_DIR . 'google_fonts_cache.php', $code );
		if ( ! ideapark_is_file( $fn ) ) {
			update_option( 'ideapark_google_fonts', [
				'version' => IDEAPARK_THEME_VERSION,
				'list'    => $_ideapark_google_fonts_cache,
				'subsets' => $_ideapark_google_fonts_subsets
			], '', 'no' );
		}

		return $_ideapark_google_fonts_cache;
	}
}

if ( ! function_exists( 'ideapark_after_customizer_save' ) ) {
	function ideapark_after_customizer_save() {
		global $ideapark_customize;
		if ( ! empty( $ideapark_customize ) ) {
			foreach ( $ideapark_customize as $section ) {
				if ( ! empty( $section['controls'] ) ) {
					foreach ( $section['controls'] as $control_name => $control ) {
						if ( isset( $control['class'] ) && $control['class'] == 'WP_Customize_Image_Control' ) {
							if ( ( $url = get_theme_mod( $control_name ) ) && ( $attachment_id = attachment_url_to_postid( $url ) ) ) {
								$params = wp_get_attachment_image_src( $attachment_id, 'full' );
								set_theme_mod( $control_name . '__url', $params[0] );
								set_theme_mod( $control_name . '__attachment_id', $attachment_id );
								set_theme_mod( $control_name . '__width', $params[1] );
								set_theme_mod( $control_name . '__height', $params[2] );
							} else {
								remove_theme_mod( $control_name . '__url' );
								remove_theme_mod( $control_name . '__attachment_id' );
								remove_theme_mod( $control_name . '__width' );
								remove_theme_mod( $control_name . '__height' );
							}
						}
						if ( ! empty( $control['is_option'] ) ) {
							$val = get_theme_mod( $control_name, null );
							if ( $val === null && isset( $control['default'] ) ) {
								$val = $control['default'];
							}
							if ( $val !== null ) {
								update_option( IDEAPARK_THEME_SLUG . '_mod_' . $control_name, $val );
							} else {
								delete_option( IDEAPARK_THEME_SLUG . '_mod_' . $control_name );
							}
						}
					}
				}
			}
		}

		delete_option( 'ideapark_google_font_uri' );
		if ( $languages = ideapark_active_languages() ) {
			foreach ( $languages as $lang_code => $lang ) {
				delete_option( 'ideapark_google_font_uri' . '_' . $lang_code );
				delete_option( 'ideapark_styles_hash' . '_' . $lang_code );
			}
		}
		delete_option( 'ideapark_styles_hash' );
		delete_option( 'ideapark_editor_styles_hash' );

		if ( IDEAPARK_THEME_DEMO ) {
			ideapark_fpc( IDEAPARK_THEME_UPLOAD_DIR . 'customizer_var.css', ideapark_customize_css( true ) );
		}
	}
}

if ( ! function_exists( 'ideapark_clear_customize_cache' ) ) {
	function ideapark_clear_customize_cache() {
		ideapark_after_customizer_save();
		ideapark_delete_file( IDEAPARK_THEME_UPLOAD_DIR . 'customizer_vars.php' );
		ideapark_delete_file( IDEAPARK_THEME_UPLOAD_DIR . 'google_fonts_cache.php' );
		delete_option( 'ideapark_customize' );
		delete_option( 'ideapark_google_fonts' );
		ideapark_init_theme_customize();
	}
}

if ( ! function_exists( 'ideapark_mod_hex_color_norm' ) ) {
	function ideapark_mod_hex_color_norm( $option, $default = 'inherit' ) {
		if ( preg_match( '~^\#[0-9A-F]{3,6}$~i', $option ) ) {
			return $option;
		} elseif ( preg_match( '~^\#[0-9A-F]{3,6}$~i', $color = '#' . ltrim( ideapark_mod( $option ) ?: '', '#' ) ) ) {
			return $color;
		} else {
			if ( $default === 'inherit' ) {
				if ( $_default = ideapark_mod_default( $option ) ) {
					$default = $_default;
				}
			}

			return $default;
		}
	}
}

if ( ! function_exists( 'ideapark_hex_to_rgb_overlay' ) ) {
	function ideapark_hex_to_rgb_overlay( $hex_color_1, $hex_color_2, $alpha_2 ) {
		list( $r_1, $g_1, $b_1 ) = sscanf( $hex_color_1, "#%02x%02x%02x" );
		list( $r_2, $g_2, $b_2 ) = sscanf( $hex_color_2, "#%02x%02x%02x" );

		$r = min( round( $alpha_2 * $r_2 + ( 1 - $alpha_2 ) * $r_1 ), 255 );
		$g = min( round( $alpha_2 * $g_2 + ( 1 - $alpha_2 ) * $g_1 ), 255 );
		$b = min( round( $alpha_2 * $b_2 + ( 1 - $alpha_2 ) * $b_1 ), 255 );

		return "rgb($r, $g, $b)";
	}
}

if ( ! function_exists( 'ideapark_hex_lighting' ) ) {
	function ideapark_hex_lighting( $hex_color_1 ) {
		list( $r_1, $g_1, $b_1 ) = sscanf( $hex_color_1, "#%02x%02x%02x" );

		return 0.299 * $r_1 + 0.587 * $g_1 + 0.114 * $b_1;
	}
}

if ( ! function_exists( 'ideapark_hex_to_rgb_shift' ) ) {
	function ideapark_hex_to_rgb_shift( $hex_color, $k = 1 ) {
		list( $r, $g, $b ) = sscanf( $hex_color, "#%02x%02x%02x" );

		$r = min( round( $r * $k ), 255 );
		$g = min( round( $g * $k ), 255 );
		$b = min( round( $b * $k ), 255 );

		return "rgb($r, $g, $b)";
	}
}

if ( ! function_exists( 'ideapark_hex_to_rgba' ) ) {
	function ideapark_hex_to_rgba( $hex_color, $opacity = 1 ) {
		list( $r, $g, $b ) = sscanf( $hex_color, "#%02x%02x%02x" );

		return "rgba($r, $g, $b, $opacity)";
	}
}

if ( ! function_exists( 'ideapark_is_shop_configured' ) ) {
	function ideapark_is_shop_configured() {
		return ideapark_woocommerce_on() && wc_get_page_id( 'shop' ) > 0 ? 1 : 0;
	}
}

if ( ! function_exists( 'ideapark_fix_products_per_page' ) ) {
	function ideapark_fix_products_per_page( $old_version = '', $new_version = '' ) {
		if ( $old_version && $new_version && version_compare( $old_version, '4.25', '<=' ) && version_compare( $new_version, '4.25', '>' ) ) {
			$products_per_page = (int) get_option( 'woocommerce_catalog_columns', 0 ) * (int) get_option( 'woocommerce_catalog_rows', 0 );
			if ( ! $products_per_page ) {
				$products_per_page = 12;
			}
			set_theme_mod( 'products_per_page', $products_per_page );
		}
	}
}

if ( ! function_exists( 'ideapark_customize_scripts' ) ) {
	function ideapark_customize_scripts() {
		$assets_url    = IDEAPARK_THEME_URI . '/';
		$script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( 'select2', esc_url( $assets_url ) . 'css/select2.min.css', false, '4.1.0-beta.1', 'all' );
		wp_register_script( 'select2', esc_url( $assets_url ) . 'js/select2.full' . $script_suffix . '.js', [ 'jquery' ], '4.1.0-beta.1', true );
		wp_enqueue_style( 'select2' );
		wp_enqueue_script( 'select2' );

		wp_enqueue_script( 'ideapark-lib', IDEAPARK_THEME_URI . '/js/site-lib.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_THEME_DIR . '/js/site-lib.js' ), true );
		wp_enqueue_script( 'ideapark-admin-customizer', IDEAPARK_THEME_URI . '/js/admin.js', [
			'jquery',
			'customize-controls',
			'ideapark-lib'
		], ideapark_mtime( IDEAPARK_THEME_DIR . '/js/admin.js' ), true );
		wp_localize_script( 'ideapark-admin-customizer', 'ideapark_dependencies', ideapark_get_theme_dependencies() );

		$pages           = get_pages( [
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'page-templates/contact.php',
		] );
		$contact_url     = ! empty( $pages[0]->ID ) ? get_permalink( $pages[0]->ID ) : '';
		$post_url        = ( $latest = get_posts( [ 'numberposts' => 1 ] ) ) && ! empty( $latest[0]->ID ) ? get_permalink( $latest[0]->ID ) : '';
		$blog_url        = ( $page_for_posts = get_option( 'page_for_posts' ) ) ? get_permalink( $page_for_posts ) : get_home_url();
		$product_url     = '';
		$shop_url        = '';
		$product_cat_url = '';

		if ( ideapark_woocommerce_on() ) {
			$args = [ 'numberposts' => 1, 'post_type' => 'product', 'orderby' => 'ID' ];
			if ( ( $exclude_catalog_term = get_term_by( 'name', 'exclude-from-catalog', 'product_visibility' ) ) && ! is_wp_error( $exclude_catalog_term ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => 'product_visibility',
						'terms'    => [ $exclude_catalog_term->term_id ],
						'operator' => 'NOT IN'
					]
				];
			}
			$posts = get_posts( $args );
			if ( $posts && ! is_wp_error( $posts ) ) {
				$product_url = get_permalink( $posts[0]->ID );
			}

			$shop_url = wc_get_page_id( 'shop' ) > 0 ? wc_get_page_permalink( 'shop' ) : '';

			$terms = get_terms( 'product_cat', [ 'hide_empty' => true, 'number' => 1 ] );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$product_cat_url = get_term_link( array_shift( $terms )->term_id, 'product_cat' );
			}
		}

		wp_localize_script( 'ideapark-admin-customizer', 'ideapark_ac_vars', [
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'contactUrl'    => $contact_url,
			'postUrl'       => $post_url,
			'frontUrl'      => home_url( '/' ),
			'shopUrl'       => $shop_url,
			'productUrl'    => $product_url,
			'productCatUrl' => $product_cat_url,
			'blogUrl'       => $blog_url,
			'errorText'     => esc_html__( 'Something went wrong...', 'kidz' )
		] );
	}
}

if ( ! function_exists( 'ideapark_get_all_attributes' ) ) {
	function ideapark_get_all_attributes() {

		$attribute_array = [ '' => '' ];
		if ( ideapark_woocommerce_on() ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( $tax->attribute_public && taxonomy_exists( $taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						$attribute_array[ $taxonomy ] = $tax->attribute_name;
					}
				}
			}
		}

		return $attribute_array;
	}
}

if ( ! function_exists( 'ideapark_active_languages' ) ) {
	function ideapark_active_languages() {
		static $cache;
		if ( $cache !== null ) {
			return $cache;
		}
		$GLOBALS['ideapark_locale_to_code'] = [];
		if ( function_exists( 'pll_the_languages' ) && ( $polylang_languages = pll_the_languages( [ 'raw' => 1 ] ) ) ) {
			$languages = [];
			foreach ( $polylang_languages as $code => &$lang ) {
				$languages[ $locale = str_replace( '-', '_', $lang['locale'] ) ] = $lang['name'];

				$GLOBALS['ideapark_locale_to_code'][ $locale ] = $code;
			}
			$cache = $languages;

			return $languages;
		} elseif ( ( $wpml_languages = apply_filters( 'wpml_active_languages', [] ) ) && sizeof( $wpml_languages ) >= 2 ) {
			$languages = [];
			foreach ( $wpml_languages as $code => &$lang ) {
				if ( ! empty( $lang['default_locale'] ) ) {
					$languages[ $locale = $lang['default_locale'] ] = $lang['native_name'];

					$GLOBALS['ideapark_locale_to_code'][ $locale ] = $code;
				}
			}
			$cache = $languages;

			return $languages;
		} elseif ( class_exists( 'TRP_Translate_Press' ) ) {
			$trp           = TRP_Translate_Press::get_trp_instance();
			$settings      = $trp->get_component( 'settings' )->get_settings();
			$trp_languages = $trp->get_component( 'languages' );
			if ( ! empty( $settings['translation-languages'] ) && sizeof( $settings['translation-languages'] ) >= 2 ) {
				$published_languages = $trp_languages->get_languages();
				$languages           = [];
				foreach ( $settings['translation-languages'] as $lang_code ) {
					$languages[ $lang_code ] = array_key_exists( $lang_code, $published_languages ) ? $published_languages[ $lang_code ] : $lang_code;
				}

				$cache = $languages;

				return $languages;
			}
		}

		$cache = false;

		return false;
	}
}

if ( ! function_exists( 'ideapark_default_language' ) ) {
	function ideapark_default_language() {
		if ( function_exists( 'pll_default_language' ) && ( $default_lang = pll_default_language( 'locale' ) ) ) {
			return $default_lang;
		} elseif ( $default_lang = apply_filters( 'wpml_default_language', null ) ) {
			if ( ( $wpml_languages = apply_filters( 'wpml_active_languages', [] ) ) && array_key_exists( $default_lang, $wpml_languages ) ) {
				return $wpml_languages[ $default_lang ]['default_locale'];
			}
		}

		if ( class_exists( 'TRP_Translate_Press' ) ) {
			$trp      = TRP_Translate_Press::get_trp_instance();
			$settings = $trp->get_component( 'settings' )->get_settings();

			if ( ! empty( $settings['default-language'] ) ) {
				return $settings['default-language'];
			}
		}

		return null;
	}
}

if ( ! function_exists( 'ideapark_current_language' ) ) {
	function ideapark_current_language() {
		return get_locale();
	}
}

if ( ! function_exists( 'ideapark_query_lang' ) ) {
	function ideapark_query_lang() {
		$lang = '';
		if ( ! empty( $_REQUEST['lang'] ) ) {
			ideapark_active_languages();
			$lang = $_REQUEST['lang'];
			if ( isset( $GLOBALS['ideapark_locale_to_code'] ) && is_array( $GLOBALS['ideapark_locale_to_code'] ) && array_key_exists( $lang, $GLOBALS['ideapark_locale_to_code'] ) ) {
				$lang = $GLOBALS['ideapark_locale_to_code'][ $lang ];
			}

			if ( $lang == ideapark_current_language() ) {
				$lang = '';
			}
		}

		return $lang;
	}
}

add_action( 'init', 'ideapark_init_theme_customize', 0 );
add_action( 'wp_loaded', 'ideapark_init_theme_mods' );
add_action( 'customize_register', 'ideapark_register_theme_customize', 100 );
add_action( 'customize_controls_print_styles', 'ideapark_customize_admin_style' );
add_action( 'customize_controls_enqueue_scripts', 'ideapark_customize_scripts' );
add_action( 'customize_save_after', 'ideapark_after_customizer_save', 100 );
add_action( 'wp_ajax_ideapark_customizer_add_section', 'ideapark_ajax_customizer_add_section' );
add_action( 'wp_ajax_ideapark_customizer_delete_section', 'ideapark_ajax_customizer_delete_section' );
add_action( 'after_update_theme_late', 'ideapark_fix_products_per_page', 10, 2 );
add_action( 'after_update_theme_late', 'ideapark_fix_theme_mods', 10, 2 );
add_action( 'after_update_theme_late', 'ideapark_clear_customize_cache', 100 );