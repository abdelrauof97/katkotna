<?php

/*------------------------------------*\
	Constants & Globals
\*------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'IDEAPARK_THEME_IS_AJAX', function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) );
define( 'IDEAPARK_THEME_IS_XML_HTTP_REQUEST', ( $n = '_SERVER' ) && ( 'xmlhttprequest' === strtolower( isset( $$n['HTTP_X_REQUESTED_WITH'] ) ? $$n['HTTP_X_REQUESTED_WITH'] : '' ) ) );
define( 'IDEAPARK_THEME_IS_AJAX_HEARTBEAT', IDEAPARK_THEME_IS_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] == 'heartbeat' ) );
define( 'IDEAPARK_THEME_IS_AJAX_SEARCH', IDEAPARK_THEME_IS_AJAX && ! empty( $_POST['action'] ) && ( $_POST['action'] == 'ideapark_ajax_search' ) );
define( 'IDEAPARK_THEME_IS_AJAX_FRAGMENTS', ! empty( $_REQUEST['wc-ajax'] ) && ( $_REQUEST['wc-ajax'] == 'get_refreshed_fragments' || $_REQUEST['wc-ajax'] == 'add_to_cart' ) );
define( 'IDEAPARK_THEME_IS_AJAX_INFINITY', IDEAPARK_THEME_IS_XML_HTTP_REQUEST && ! empty( $_POST['ideapark_infinity_loading'] ) );
define( 'IDEAPARK_THEME_NAME', 'Kidz' );
define( 'IDEAPARK_THEME_SLUG', 'kidz' );
define( 'IDEAPARK_THEME_DOMAIN', IDEAPARK_THEME_SLUG );
define( 'IDEAPARK_THEME_DIR', get_template_directory() );
define( 'IDEAPARK_THEME_URI', get_template_directory_uri() );
define( 'IDEAPARK_THEME_DEMO', false );
define( 'IDEAPARK_THEME_MANUAL', 'https://parkofideas.com/kidz/manual/' );
define( 'IDEAPARK_THEME_SUPPORT', 'https://parkofideas.ticksy.com/' );
define( 'IDEAPARK_THEME_CHANGELOG', 'https://themeforest.net/item/kidz-baby-store-woocommerce-theme/17688768#item-description__kidz-wordpress-theme-changelog' );
define( 'IDEAPARK_THEME_VERSION', '5.25' );

$wp_upload_arr = wp_get_upload_dir();

if ( preg_match( '~^https~i', IDEAPARK_THEME_URI ) && ! preg_match( '~^https~i', $wp_upload_arr['baseurl'] ) ) {
	$wp_upload_arr['baseurl'] = preg_replace( '~^http:~i', 'https:', $wp_upload_arr['baseurl'] );
}

define( "IDEAPARK_THEME_UPLOAD_DIR", $wp_upload_arr['basedir'] . "/" . strtolower( sanitize_file_name( IDEAPARK_THEME_NAME ) ) . "/" );
define( "IDEAPARK_THEME_UPLOAD_URL", $wp_upload_arr['baseurl'] . "/" . strtolower( sanitize_file_name( IDEAPARK_THEME_NAME ) ) . "/" );

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

$ideapark_theme_scripts = [];
$ideapark_theme_styles  = [];

if ( ! function_exists( 'ideapark_is_requset' ) ) {
	function ideapark_is_requset( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) || ( is_admin() && ! empty( $_GET['action'] ) && ( $_GET['action'] == 'elementor' ) ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
		}
	}
}

if ( ! function_exists( 'ideapark_is_rtl' ) ) {
	function ideapark_is_rtl() {
		return apply_filters( 'ideapark_is_rtl', is_rtl() );
	}
}

if ( ! function_exists( 'ideapark_setup' ) ) {

	function ideapark_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'custom-background' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'woocommerce', [
			'thumbnail_image_width'         => 210, // woocommerce_thumbnail
			'gallery_thumbnail_image_width' => 70,  // woocommerce_gallery_thumbnail
			'single_image_width'            => 360, // woocommerce_single
		] );

		add_theme_support( 'woo_variation_swatches', [
			'shape_style'          => 'rounded',
			'show_variation_label' => false,
			'show_on_archive'      => false,
		] );

		add_image_size( 'ideapark-home-brands', 142, 160 );
		add_image_size( 'ideapark-home-banners', '', 250 );
		add_image_size( 'ideapark-category-thumb', '', 53 );

		add_image_size( 'woocommerce_thumbnail-2x', 420, 420, true );
		add_image_size( 'ideapark-gallery-thumb-2x', 140, 140, true );
		add_image_size( 'ideapark-large-2x', '', 1180 );

		remove_image_size( 'medium_large' );
		remove_image_size( '1536x1536' );
		remove_image_size( '2048x2048' );

		load_theme_textdomain( 'kidz', IDEAPARK_THEME_DIR . '/languages' );

		add_action( 'load_textdomain_mofile', 'ideapark_correct_tgmpa_mofile', 10, 2 );
		load_theme_textdomain( 'tgmpa', IDEAPARK_THEME_DIR . '/plugins/languages' );
		remove_action( 'load_textdomain_mofile', 'ideapark_correct_tgmpa_mofile', 10 );

		register_nav_menus( [
			'primary'  => esc_html__( 'Top Menu', 'kidz' ),
			'megamenu' => esc_html__( 'Mega Menu (Primary)', 'kidz' ),
		] );

	}
}

if ( ! function_exists( 'ideapark_check_version' ) ) {
	function ideapark_check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && ideapark_is_requset( 'admin' ) && ( ( $current_version = get_option( IDEAPARK_THEME_SLUG . '_theme_version', '' ) ) || ! $current_version ) && ( version_compare( $current_version, IDEAPARK_THEME_VERSION, '!=' ) ) ) {
			do_action( 'after_update_theme', $current_version, IDEAPARK_THEME_VERSION );
			add_action( 'init', function () use ( $current_version ) {
				do_action( 'after_update_theme_late', $current_version, IDEAPARK_THEME_VERSION );
			}, 999 );
			update_option( IDEAPARK_THEME_SLUG . '_theme_version', IDEAPARK_THEME_VERSION );
			update_option( IDEAPARK_THEME_SLUG . '_about_page', 1 );
		}
	}
}

if ( ! function_exists( 'ideapark_set_image_dimensions' ) ) {
	function ideapark_set_image_dimensions() {

		update_option( 'woocommerce_thumbnail_cropping', '1:1' );

		update_option( 'thumbnail_size_w', 70 );
		update_option( 'thumbnail_size_h', 70 );

		update_option( 'medium_size_w', 360 );
		update_option( 'medium_size_h', '' );

		update_option( 'medium_large_size_w', 360 );
		update_option( 'medium_large_size_h', '' );

		update_option( 'large_size_w', '' );
		update_option( 'large_size_h', 590 );

	}
}

// Maximum width for media
if ( ! isset( $content_width ) ) {
	$content_width = 1220; // Pixels
}

/*------------------------------------*\
	Include files
\*------------------------------------*/
require_once( IDEAPARK_THEME_DIR . '/functions/customize/ip_customize_settings.php' );
require_once( IDEAPARK_THEME_DIR . '/functions/customize/ip_customize_style.php' );

if ( ! class_exists( 'Ideaperk_Mega_Menu' ) ) {
	if ( ! is_admin() ) {
		require_once( IDEAPARK_THEME_DIR . '/functions/megamenu/custom_walker.php' );
	} else {
		require_once( IDEAPARK_THEME_DIR . '/functions/megamenu/edit_custom_walker.php' );
	}
	require_once( IDEAPARK_THEME_DIR . '/functions/megamenu/mega-menu.php' );
}

if ( is_admin() && ! IDEAPARK_THEME_IS_AJAX_SEARCH ) {
	require_once IDEAPARK_THEME_DIR . '/plugins/class-tgm-plugin-activation.php';
	add_action( 'tgmpa_register', 'ideapark_register_required_plugins' );
}

if ( is_admin() ) {
	require_once IDEAPARK_THEME_DIR . '/functions/theme-about/theme-about.php';
}

if ( ! function_exists( 'ideapark_core_plugin_on' ) ) {
	function ideapark_core_plugin_on() {
		return function_exists( 'Ideapark_Theme_Functionality' );
	}
}

function ideapark_woocommerce_on() {
	return class_exists( 'WooCommerce' ) ? 1 : 0;
}

if ( ideapark_woocommerce_on() ) {
	require_once( IDEAPARK_THEME_DIR . '/functions/woocommerce/woocommerce-func.php' );
	require_once( IDEAPARK_THEME_DIR . '/functions/woocommerce/woocommerce-quickview.php' );

	if ( is_admin() ) {
		if ( ! IDEAPARK_THEME_IS_AJAX ) {
			require_once( IDEAPARK_THEME_DIR . '/functions/woocommerce/woocommerce-tax-extra-fields.php' );
		}
	}
}

if ( ! function_exists( 'ideapark_get_required_plugins' ) ) {
	function ideapark_get_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = [
			[
				'name'     => esc_html__( 'Kidz Core', 'kidz' ),
				'slug'     => 'ideapark-theme-functionality',
				'source'   => IDEAPARK_THEME_DIR . '/plugins/ideapark-theme-functionality.zip',
				'required' => true,
				'version'  => IDEAPARK_THEME_VERSION,
			],

			[
				'name'     => esc_html__( 'Kidz Wishlist', 'kidz' ),
				'slug'     => 'ideapark-wishlist',
				'source'   => IDEAPARK_THEME_DIR . '/plugins/ideapark-wishlist.zip',
				'required' => true,
				'version'  => '2.0',
			],

			[
				'name'     => esc_html__( 'WooCommerce', 'kidz' ),
				'slug'     => 'woocommerce',
				'required' => true
			],

			[
				'name'     => esc_html__( 'Contact Form 7', 'kidz' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			],

			[
				'name'           => esc_html__( 'Variation Swatches for WooCommerce', 'kidz' ),
				'slug'           => 'woo-variation-swatches',
				'required'       => false,
				'notice_disable' => true,
			],

			[
				'name'           => esc_html__( 'MailChimp for WP', 'kidz' ),
				'slug'           => 'mailchimp-for-wp',
				'required'       => false,
				'notice_disable' => true,
			],

			[
				'name'           => esc_html__( 'Revolution Slider', 'kidz' ),
				'slug'           => 'revslider',
				'type'           => 'api',
				"source"         => 'revslider/revslider.php',
				'version'        => '6.7.35',
				'tooltip'        => esc_html__( 'This plugin is not used in the demo (except demo4). Install it only if you are going to create custom slides, because it slows down the site and negatively affects PageSpeed', 'kidz' ),
				'required'       => false,
				'notice_disable' => true,
			],

		];

		if ( ( $code = ideapark_get_purchase_code() ) && $code !== IDEAPARK_THEME_SKIP_REGISTER ) {
			return $plugins;
		}

		return array_filter( $plugins, function ( $plugin ) {
			return empty( $plugin['type'] ) || $plugin['type'] != 'api';
		} );
	}
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */

if ( ! function_exists( 'ideapark_register_required_plugins' ) ) {
	function ideapark_register_required_plugins() {
		$plugins = ideapark_get_required_plugins();

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = [
			'id'           => 'kidz',
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',
			// Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins',
			// Menu slug.
			'parent_slug'  => 'themes.php',
			// Parent menu slug.
			'capability'   => 'edit_theme_options',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,
			// Automatically activate plugins after installation or not.
			'message'      => '',
			// Message to output right before the plugins table.
		];

		tgmpa( $plugins, $config );
	}
}

if ( ! function_exists( 'ideapark_scripts_disable_cf7' ) ) {
	function ideapark_scripts_disable_cf7() {
		if ( ! is_singular() || is_front_page() ) {
			add_filter( 'wpcf7_load_js', '__return_false' );
			add_filter( 'wpcf7_load_css', '__return_false' );
		}
	}
}

if ( ! function_exists( 'ideapark_scripts' ) ) {
	function ideapark_scripts() {
		global $post;

		if ( $GLOBALS['pagenow'] != 'wp-login.php' && ! is_admin() ) {

			if ( ideapark_woocommerce_on() ) {
				wp_enqueue_script( 'wc-cart-fragments' );
				if ( ideapark_mod( 'load_jquery_in_footer' ) ) {
					wp_scripts()->add_data( 'wc-add-to-cart', 'group', 1 );
					wp_scripts()->add_data( 'woocommerce', 'group', 1 );
					wp_scripts()->add_data( 'js-cookie', 'group', 1 );
					wp_scripts()->add_data( 'wc-cart-fragments', 'group', 1 );
				}
			}

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply', false, [], false, true );
			}

			if ( is_archive() || is_home() ) {
				wp_enqueue_script( 'jquery-masonry', [ 'jquery' ] );
			}

			if ( ideapark_woocommerce_on() ) {
				if ( ideapark_mod( 'disable_block_editor' ) ) {
					wp_dequeue_style( 'wc-block-style' );
					wp_dequeue_style( 'wc-blocks-style' );
				}
				wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
				wp_dequeue_script( 'prettyPhoto' );
				wp_dequeue_script( 'prettyPhoto-init' );

				if ( ideapark_mod( 'shop_product_modal' ) && ( is_product() || ! empty( $post ) && ! empty( $post->post_content ) && has_shortcode( $post->post_content, 'product_page' ) ) ) {
					wp_enqueue_style( 'photoswipe', IDEAPARK_THEME_URI . '/css/photoswipe/photoswipe.css', [], '4.1.1', 'all' );
					wp_enqueue_style( 'photoswipe-skin', IDEAPARK_THEME_URI . '/css/photoswipe/default-skin.css', [], '4.1.1', 'all' );
					wp_enqueue_script( 'photoswipe', IDEAPARK_THEME_URI . '/js/photoswipe/photoswipe.min.js', [ 'jquery' ], '4.1.1', true );
					wp_enqueue_script( 'photoswipe-ui', IDEAPARK_THEME_URI . '/js/photoswipe/photoswipe-ui-default.min.js', [ 'jquery' ], '4.1.1', true );
				}

				if ( ideapark_mod( 'product_thumbnails' ) != 'hide' && ( is_product() || ! empty( $post ) && ! empty( $post->post_content ) && has_shortcode( $post->post_content, 'product_page' ) ) ) {
					wp_enqueue_style( 'slick-slider', IDEAPARK_THEME_URI . '/css/slick.css', [], '1.8.1', 'all' );
					wp_enqueue_script( 'slick-slider', IDEAPARK_THEME_URI . '/js/slick.min.js', [ 'jquery' ], '1.8.1', true );
				}
			}

			if ( ideapark_mod( 'disable_block_editor' ) ) {
				wp_dequeue_style( 'wp-block-library' );
				wp_dequeue_style( 'wp-block-library-theme' );
			}

			ideapark_add_style( 'bootstrap', IDEAPARK_THEME_URI . '/css/bootstrap.min.css', [], '3.3.5', 'all' );
			ideapark_add_style( 'magnific-popup', IDEAPARK_THEME_URI . '/css/magnific-popup.css', [], '1.1.0', 'all' );
			ideapark_add_style( 'ideapark-core-css', IDEAPARK_THEME_URI . '/style.css', [], ideapark_mtime( IDEAPARK_THEME_DIR . '/style.css' ), 'all' );
			if ( ideapark_is_rtl() ) {
				ideapark_add_style( 'ideapark-rtl', IDEAPARK_THEME_URI . '/css/rtl.css', [], ideapark_mtime( IDEAPARK_THEME_DIR . '/css/rtl.css' ), 'all' );
			}

			if ( is_customize_preview() ) {
				wp_enqueue_style( 'ideapark-customize-preview', IDEAPARK_THEME_URI . '/css/admin-customizer-preview.css', [], IDEAPARK_THEME_VERSION . '.1', 'all' );
			}

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply', false, [], false, true );
			}
			ideapark_add_script( 'imagesloaded', IDEAPARK_THEME_URI . '/js/imagesloaded.min.js', '4.1.4', true );
			ideapark_add_script( 'zoom', IDEAPARK_THEME_URI . '/js/jquery.zoom.min.js', [ 'jquery' ], '1.7.21', true );
			ideapark_add_script( 'owl-carousel', IDEAPARK_THEME_URI . '/js/owl.carousel.min.js', [ 'jquery' ], '2.3.4', true );
			ideapark_add_script( 'fitvids', IDEAPARK_THEME_URI . '/js/jquery.fitvids.js', [ 'jquery' ], '1.1', true );
			ideapark_add_script( 'customselect', IDEAPARK_THEME_URI . '/js/jquery.customSelect.min.js', [ 'jquery' ], '0.5.1', true );
			ideapark_add_script( 'magnific-popup', IDEAPARK_THEME_URI . '/js/jquery.magnific-popup.min.js', [ 'jquery' ], '0.9.9', true );
			ideapark_add_script( 'simple-parallax', IDEAPARK_THEME_URI . '/js/simpleParallax.min.js', [ 'jquery' ], '5.2.0', true );
			ideapark_add_script( 'body-scroll-lock', IDEAPARK_THEME_URI . '/js/bodyScrollLock.js', [ 'jquery' ], '1.0', true );
			ideapark_add_script( 'ideapark-lib', IDEAPARK_THEME_URI . '/js/site-lib.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_THEME_DIR . '/js/site-lib.js' ), true );
			ideapark_add_script( 'ideapark-core', IDEAPARK_THEME_URI . '/js/site.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_THEME_DIR . '/js/site.js' ), true );
		}
	}
}

if ( ! function_exists( 'ideapark_scripts_load' ) ) {
	function ideapark_scripts_load() {
		ideapark_enqueue_style();
		ideapark_enqueue_script();

		if ( ideapark_mod( 'load_jquery_in_footer' ) ) {
			wp_scripts()->add_data( 'jquery', 'group', 1 );
			wp_scripts()->add_data( 'jquery-core', 'group', 1 );
			wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
		}

		wp_localize_script( 'ideapark-core', 'ideapark_wp_vars', ideapark_localize_vars() );

		global $wp_scripts;
		if ( ideapark_woocommerce_on() ) {
			foreach ( $wp_scripts->registered as $handler => $script ) {
				if ( $handler == 'wc-add-to-cart-variation' ) {
					wp_enqueue_script( 'wc-add-to-cart-variation-3-fix', IDEAPARK_THEME_URI . '/js/woocommerce/add-to-cart-variation-3-fix.js', [
						'wc-add-to-cart-variation'
					], IDEAPARK_THEME_VERSION, true );

					wp_localize_script( 'wc-add-to-cart-variation-3-fix', 'ideapark_wc_add_to_cart_variation_vars', [
						'in_stock_message' => esc_html__( 'In stock', 'kidz' ),
						'in_stock_svg'     => esc_js( '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-check" /></svg>' ),
						'out_of_stock_svg' => esc_js( '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-close" /></svg>' )
					] );

					if ( ideapark_mod( 'load_jquery_in_footer' ) ) {
						wp_scripts()->add_data( 'wc-add-to-cart-variation', 'group', 1 );
						wp_scripts()->add_data( 'wc-add-to-cart-variation-fix', 'group', 1 );
					}
					break;
				}
			}
		}

		$inline_css = '';

		if ( ideapark_woocommerce_on() && ! ideapark_mod( 'disable_block_editor' ) ) {
			$font_path = str_replace( [
					'http:',
					'https:'
				], '', WC()->plugin_url() ) . '/assets/fonts/';

			$inline_css .= "
@font-face {
font-family: 'star';
src: url('{$font_path}star.eot');
src: url('{$font_path}star.eot?#iefix') format('embedded-opentype'),
	url('{$font_path}star.woff') format('woff'),
	url('{$font_path}star.ttf') format('truetype'),
	url('{$font_path}star.svg#star') format('svg');
font-weight: normal;
font-style: normal;
}";
		}
		if ( $inline_css ) {
			wp_add_inline_style( 'ideapark-core', $inline_css );
		}
	}
};

if ( ! function_exists( 'ideapark_sprite_loader' ) ) {
	function ideapark_sprite_loader() { ?>
		<script>
			var ideapark_svg_content = "";
			var ajax = new XMLHttpRequest();
			ajax.open("GET", "<?php echo esc_url( ideapark_get_sprite_url() ); ?>", true);
			ajax.send();
			ajax.onload = function (e) {
				ideapark_svg_content = ajax.responseText;
				ideapark_download_svg_onload();
			};
			
			function ideapark_download_svg_onload() {
				if (typeof document.body != "undefined" && document.body != null && typeof document.body.childNodes != "undefined" && typeof document.body.childNodes[0] != "undefined") {
					var div = document.createElement("div");
					div.className = "svg-sprite-container";
					div.innerHTML = ideapark_svg_content;
					document.body.insertBefore(div, document.body.childNodes[0]);
				} else {
					setTimeout(ideapark_download_svg_onload, 100);
				}
			}

		</script>
	<?php }
}

/*------------------------------------*\
	Widgets
\*------------------------------------*/

if ( ! function_exists( 'ideapark_widgets_init' ) ) {
	function ideapark_widgets_init() {

		register_sidebar( [
			'name'          => esc_html__( 'Sidebar', 'kidz' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );

		register_sidebar( [
			'name'          => esc_html__( 'Footer', 'kidz' ),
			'id'            => 'footer-sidebar',
			'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			'description'   => esc_html__( 'Maximum 3 widgets', 'kidz' ),
		] );

		register_sidebar( [
			'name'          => esc_html__( 'Product list', 'kidz' ),
			'id'            => 'shop-sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );

		register_sidebar( [
			'name'          => esc_html__( 'Product page', 'kidz' ),
			'id'            => 'product-sidebar',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		] );

	}
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

if ( ! function_exists( 'ideapark_custom_excerpt_length' ) ) {
	function ideapark_custom_excerpt_length( $length ) {
		return 84;
	}
}

if ( ! function_exists( 'ideapark_excerpt_more' ) ) {
	function ideapark_excerpt_more( $more ) {
		return '&hellip;';
	}
}

if ( ! function_exists( 'ideapark_ajax_search' ) ) {
	function ideapark_ajax_search() {
		global $post, $product, $wp_query, $wp_the_query;

		if ( strlen( ( $s = trim( preg_replace( '~[\s\t\r\n]+~', ' ', $_POST['s'] ) ) ) ) > 0 ) {

			$lang = ideapark_query_lang();

			$query_args = [
				's'              => $s,
				'posts_per_page' => ideapark_mod( 'ajax_search_limit' ) ? ideapark_mod( 'ajax_search_limit' ) : 1,
				'post_status'    => 'publish',
				'lang'           => $lang
			];

			add_filter( 'trp_force_search', '__return_true', 999 );

			if ( $lang ) {
				do_action( 'wpml_switch_language', $lang );
				if ( ! empty( $_REQUEST['lang'] ) && ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
					switch_to_locale( $_REQUEST['lang'] );
				}
			}

			if ( ideapark_mod( 'search_products_only' ) && ideapark_woocommerce_on() || isset( $_POST['post_type'] ) && $_POST['post_type'] == 'product' ) {
				$query_args['post_type'] = [ 'product' ];
			}
			if ( ideapark_woocommerce_on() ) {
				add_action( 'pre_get_posts', [ WC()->query, 'product_query' ] );
			}
			$wp_query = $wp_the_query = new WP_Query();

			$wp_query->query( $query_args );

			if ( is_a( $wp_query, 'WP_Query' ) ) {
				$wp_query->is_search = true;
			}
			?>
			<ul>
				<?php
				if ( $wp_query->have_posts() ) {
					while ( $wp_query->have_posts() ) {
						$wp_query->the_post();
						$post_type = get_post_type();
						if ( $post_type == 'product' ) {
							$product = wc_get_product( get_the_ID() );
						}
						?>
						<li <?php post_class( 'ajax-search-row', get_the_ID() ); ?>>
							<?php if ( has_post_thumbnail() ) { ?>
								<a href="<?php the_permalink(); ?>">
									<div class="post-img">
										<?php the_post_thumbnail( 'thumbnail' ); ?>
									</div>
								</a>
							<?php } ?>
							<div class="post-content">
								<a href="<?php the_permalink(); ?>">
									<h4><?php the_title() ?></h4>
								</a>
								<?php if ( ideapark_woocommerce_on() && ( $product = wc_get_product( $post->ID ) ) ) { ?>
									<?php echo ideapark_wrap( $product->get_price_html(), '<span class="price">', '</span>' ); ?>
									<div class="actions">
										<?php woocommerce_template_loop_add_to_cart(); ?>
									</div>

								<?php } elseif ( get_post_type( $post->ID ) != 'page' && ! ideapark_mod( 'post_hide_date' ) ) { ?>
									<div class="meta-date">
										<div class="post-meta post-date">
											<?php echo the_date(); ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</li>
					<?php } ?>
					<li class="view-all-li">
						<a href="javascript:jQuery('#ajax-search form').submit()"
						   class="view-all"><?php echo esc_html__( 'View all results', 'kidz' ); ?> &nbsp;<i
								class="fa fa-chevron-right"></i></a>
					</li>
				<?php } else { ?>
					<li class="no-results"><?php echo esc_html__( 'No results found', 'kidz' ); ?></li>
				<?php } ?>
			</ul>
			<?php
		} else {
			echo '';
		}
		die();
	}
}

if ( ! function_exists( 'ideapark_category' ) ) {
	function ideapark_category( $separator, $cat = null, $a_calss = '' ) {
		$catetories = [];

		if ( ! $cat ) {
			$cat = get_the_category();
		}
		foreach ( $cat as $category ) {
			$catetories[] = '<a ' . ( $a_calss ? 'class="' . esc_attr( $a_calss ) . '"' : '' ) . ' href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( esc_attr__( "View all posts in %s", 'kidz' ), $category->name ) . '" ' . '>' . esc_html( $category->name ) . '</a>';
		}

		if ( $catetories ) {
			echo implode( $separator, $catetories );
		}
	}
}

if ( ! function_exists( 'ideapark_corenavi' ) ) {
	function ideapark_corenavi( $custom_query = null ) {
		global $wp_query;
		$pages = '';
		if ( ! $custom_query ) {
			$custom_query = $wp_query;
		}
		$max = $custom_query->max_num_pages;
		if ( ! $current = get_query_var( 'paged' ) ) {
			$current = 1;
		}
		$a['base']    = str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) );
		$a['total']   = $max;
		$a['current'] = $current;

		$total          = 0; // 1 - echo "Page N from N", 0 - without
		$a['mid_size']  = 3;
		$a['end_size']  = 1;
		$a['prev_text'] = esc_html__( '&larr;', 'kidz' );
		$a['next_text'] = esc_html__( '&rarr;', 'kidz' );

		if ( $total == 1 && $max > 1 ) {
			$pages = '<span class="pages">' . esc_html__( 'Page', 'kidz' ) . ' ' . $current . ' ' . esc_html__( 'from', 'kidz' ) . ' ' . $max . '</span>' . "\r\n";
		}

		$pages .= paginate_links( $a );

		echo ideapark_wrap( $pages, '<div class="navigation">', '</div>' );
	}
}

if ( ! function_exists( 'ideapark_post_nav' ) ) {
	function ideapark_post_nav() {
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}

		?>
		<nav class="post-navigation" role="navigation">
			<div class="nav-links">
				<?php
				if ( is_attachment() ) :
					previous_post_link( '%link', '<span class="meta-nav">' . esc_html__( 'Published In', 'kidz' ) . '</span>%title' );
				else :
					previous_post_link( '<div class="nav-previous"><span>' . esc_html__( 'Previous Post', 'kidz' ) . '</span>%link</div>', '<span class="meta-nav"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-angle-left" /></svg></span> %title' );
					next_post_link( '<div class="nav-next"><span>' . esc_html__( 'Next Post', 'kidz' ) . '</span>%link</div>', '%title <span class="meta-nav"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-angle-right" /></svg></span></span>' );
				endif;
				?>
			</div>
			<!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'ideapark_html5_comment' ) ) {
	function ideapark_html5_comment( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" class="comment">
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<header class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) {
						echo '<div class="author-img">' . get_avatar( $comment, $args['avatar_size'] ) . '</div>';
					} ?>
					<?php printf( '%s <span class="says">' . esc_html__( 'says:', 'kidz' ) . '</span>', sprintf( '<strong class="author-name">%s</strong>', get_comment_author_link() ) ); ?>
				</div>
				<!-- .comment-author -->

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'kidz' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a>

					<?php comment_reply_link( array_merge( $args, [
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth']
					] ) ); ?>

					<!-- .reply -->
					<?php edit_comment_link( esc_html__( 'Edit', 'kidz' ), '<span class="edit-link">', '</span>' ); ?>
				</div>
				<!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'kidz' ); ?></p>
				<?php endif; ?>
			</header>
			<!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div>
			<!-- .comment-content -->

		</article><!-- .comment-body -->
		<?php
	}
}

if ( ! function_exists( 'ideapark_body_class' ) ) {
	function ideapark_body_class( $classes ) {
		$is_front_page    = is_front_page() && ideapark_mod( 'front_page_builder_enabled' ) && get_option( 'show_on_front' ) == 'page';
		$post_has_sidebar = ( is_home() && ideapark_mod( 'sidebar_blog' ) || is_page() && ideapark_mod( 'sidebar_page' ) || ! is_page() && ideapark_mod( 'sidebar_post' ) ) && is_active_sidebar( 'sidebar-1' );
		if ( ideapark_woocommerce_on() ) {
			$is_list = ( is_shop() || is_product_tag() || is_product_category() || is_product_taxonomy() );

			$product_list_has_sidebar = ! ideapark_mod( 'shop_hide_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
			$product_has_sidebar      = ! ideapark_mod( 'product_hide_sidebar' ) && is_active_sidebar( 'product-sidebar' );

			$classes[] = $is_front_page && ! $is_list || ( is_product() && ( ! $product_has_sidebar || ideapark_mod( 'product_short_sidebar' ) ) ) || ( $is_list && ! $product_list_has_sidebar ) || ( ! is_product() && ! $is_list && ! $post_has_sidebar ) || is_cart() || is_checkout() ? 'sidebar-disable' : ( $is_list ? 'sidebar-left' : 'sidebar-right' );
			if ( is_product() && ideapark_mod( 'product_short_sidebar' ) ) {
				$classes[] = 'sidebar-short';
			}
		} else {
			$classes[] = $is_front_page ? 'sidebar-disable' : ( ! $post_has_sidebar ? 'sidebar-disable' : 'sidebar-right' );
		}

		$classes[] = ideapark_mod( 'header_type' );
		$classes[] = ideapark_mod( 'sticky_type' );
		$classes[] = 'layout-' . ideapark_mod( 'home_boxed' );
		$classes[] = ideapark_mod( 'home_fullwidth_slider' ) && ideapark_mod( 'home_boxed' ) == 'fullscreen' ? 'fullwidth-slider' : 'fixed-slider';
		$classes[] = ideapark_mod( 'mega_menu' ) ? 'mega-menu' : '';
		$classes[] = ideapark_woocommerce_on() ? 'woocommerce-on' : 'woocommerce-off';
		$classes[] = ideapark_is_rtl() ? 'h-rtl' : 'h-ltr';

		if ( class_exists( 'Ideapark_Wishlist' ) && ideapark_is_wishlist_page() ) {
			$classes[] = 'wishlist-page';
		}

		$classes[] = 'preload';

		return $classes;
	}
}

if ( ! function_exists( 'ideapark_empty_menu' ) ) {
	function ideapark_empty_menu() {
	}
}

if ( ! function_exists( 'ideapark_category_menu' ) ) {
	function ideapark_category_menu() {
		global $wp_query;

		$current_cat_id = ( is_tax( 'product_cat' ) ) ? $wp_query->queried_object->term_id : '';

		$categories = get_categories( [
			'type'         => 'post',
			'hierarchical' => 1,
			'taxonomy'     => 'product_cat',
			'exclude'      => ideapark_hidden_category_ids() ?: null,
		] );

		$output    = '';
		$count     = 0;
		$all_count = 0;
		$with_icon = ideapark_mod( 'main_menu_view' ) == 'main-menu-icons';

		foreach ( $categories as $category ) {
			if ( $category->parent == '0' ) {
				$all_count ++;
			}
		}

		foreach ( $categories as $category ) {
			if ( $category->parent == '0' ) {
				$count ++;
				if ( ideapark_mod( 'header_type' ) == 'header-type-1' && $count > 6 ) {
					continue;
				}

				$is_has_icon = false;

				if ( $with_icon ) {
					if ( $product_cat_svg_id = function_exists( 'get_term_meta' ) ? get_term_meta( $category->term_id, 'product_cat_svg_id', true ) : get_metadata( 'woocommerce_term', $category->term_id, 'product_cat_svg_id', true ) ) {
						$icon        = '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#' . esc_attr( $product_cat_svg_id ) . '" /></svg>';
						$is_has_icon = true;
					} elseif ( $thumbnail_id = function_exists( 'get_term_meta' ) ? get_term_meta( $category->term_id, 'thumbnail_id', true ) : get_metadata( 'woocommerce_term', $category->term_id, 'thumbnail_id', true ) ) {
						$image        = wp_get_attachment_image_src( $thumbnail_id, 'ideapark-category-thumb', true );
						$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, 'ideapark-category-thumb' ) : false;
						$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, 'ideapark-category-thumb' ) : false;
						$icon         = '<img src="' . esc_url( $image[0] ) . '" alt="' . esc_attr( $category->name ) . '"' . ( $image_srcset ? ' srcset="' . esc_attr( $image_srcset ) . '"' : '' ) . ( $image_sizes ? ' sizes="' . esc_attr( $image_sizes ) . '"' : '' ) . '/>';
						$is_has_icon  = true;
					} else {
						$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"/>';
					}
				} else {
					$icon = '';
				}

				$submenu_ouput = '';
				foreach ( $categories as $category_submenu ) {
					if ( $category_submenu->parent == $category->term_id ) {
						$sub_submenu_ouput = '';
						foreach ( $categories as $category_sub_submenu ) {
							if ( $category_sub_submenu->parent == $category_submenu->term_id ) {
								$sub_submenu_ouput .= '<li' . ( $current_cat_id == $category_sub_submenu->term_id ? " class='current'" : "" ) . '><a href="' . esc_url( get_term_link( (int) $category_sub_submenu->term_id, 'product_cat' ) ) . '">' . esc_html( $category_sub_submenu->name ) . '</a>';
							}
						}
						$submenu_ouput .= '<li class="' . ( $current_cat_id == $category_submenu->term_id ? " current" : "" ) . ( $sub_submenu_ouput ? " has-children" : "" ) . '"><a href="' . esc_url( get_term_link( (int) $category_submenu->term_id, 'product_cat' ) ) . '">' . esc_html( $category_submenu->name ) . '</a><a class="js-more" href="#"><i class="more"></i></a>';
						$submenu_ouput .= ( $sub_submenu_ouput ? '<ul class="sub-menu sub-menu__inner">' . $sub_submenu_ouput . '</ul>' : '' ) . '</li>';
					}
				}

				$output .= '<li class="' . ( $is_has_icon ? ' with-icon' : ' without-icon' ) . ( $current_cat_id == $category->term_id ? " current" : "" ) . ( $submenu_ouput ? " has-children" : "" ) . ' items-' . ( $all_count > 12 ? 12 : ( $all_count < 6 ? 6 : $all_count ) ) . '"><a href="' . esc_url( get_term_link( (int) $category->term_id, 'product_cat' ) ) . '">' . $icon . '<span>' . esc_html( $category->name ) . '</span></a><a class="js-more" href="#"><i class="more"></i></a>' . ( $submenu_ouput ? '<ul class="sub-menu">' . $submenu_ouput . '</ul>' : '' ) . '</li>';
			}
		}
		echo ideapark_wrap( $output, '<ul class="menu main-menu-container ' . ideapark_mod( 'main_menu_view' ) . ( ideapark_mod( 'main_menu_responsive' ) ? ' main-menu-responsive' : ' main-menu-fixed' ) . '">', '</ul>' );
	}
}

if ( ! function_exists( 'ideapark_search_form' ) ) {
	function ideapark_search_form( $form ) {
		ob_start();
		do_action( 'wpml_add_language_form_field' );
		$lang   = ob_get_clean();
		$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';
		$format = apply_filters( 'search_form_format', $format );

		if ( 'html5' == $format ) {
			$form = '<form role="search" method="get" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
				<div>
				<label>
					<span class="screen-reader-text">' . esc_html_x( 'Search for:', 'label', 'kidz' ) . '</span>
					<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'kidz' ) . '" value="' . get_search_query() . '" name="s" />' .
			        ( ideapark_woocommerce_on() && ideapark_mod( 'search_products_only' ) ? '<input type="hidden" name="post_type" value="product">' : '' ) .
			        '</label>
				<button type="submit" class="search-submit"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-search" /></svg></button>
				</div>' . $lang . '
			</form>';
		} else {
			$form = '<form role="search" method="get" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
				<div>
					<label class="screen-reader-text" for="s">' . esc_html_x( 'Search for:', 'label', 'kidz' ) . '</label>
					<input type="text" value="' . get_search_query() . '" name="s" id="s" />' .
			        ( ideapark_woocommerce_on() && ideapark_mod( 'search_products_only' ) ? '<input type="hidden" name="post_type" value="product">' : '' ) .
			        '<button type="submit" class="search-submit"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-search" /></svg></button>
				</div>' . $lang . '
			</form>';
		}

		return $form;
	}
}

if ( ! function_exists( 'ideapark_search_form_ajax' ) ) {
	function ideapark_search_form_ajax( $form ) {
		ob_start();
		do_action( 'wpml_add_language_form_field' );
		$lang = ob_get_clean();
		$form = '
	<form role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '">
		<input id="ajax-search-input" autocomplete="off" type="text" name="s" placeholder="' . esc_attr__( 'search products...', 'kidz' ) . '" value="' . esc_attr( get_search_query() ) . '" />' .
		        ( ideapark_woocommerce_on() && ideapark_mod( 'search_products_only' ) ? '<input type="hidden" name="post_type" value="product">' : '' ) .
		        '<a id="search-close" href="#">
			<svg>
				<use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-close" />
			</svg>
		</a>
		<button type="submit" class="search" aria-label="' . esc_attr__( 'Search', 'kidz' ) . '">
			<svg>
				<use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-search" />
			</svg>
		</button>' . $lang . '
	</form>';

		return $form;
	}
}

if ( ! function_exists( 'ideapark_svg_url' ) ) {
	function ideapark_svg_url() {
		return is_customize_preview() ? IDEAPARK_THEME_URI . '/img/sprite.svg' : '';
	}
}

if ( ! function_exists( 'ideapark_get_account_link' ) ) {
	function ideapark_get_account_link() {
		$link_title = ( is_user_logged_in() ) ? ( '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-user" /></svg><span>' . esc_html__( 'My Account', 'kidz' ) . '</span>' ) : ( '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-user" /></svg><span>' . esc_html__( 'Login', 'kidz' ) . '</span>' );

		return '<a href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" rel="nofollow">' . $link_title . '</a>';
	}
}

if ( ! function_exists( 'ideapark_header_add_to_cart_hash' ) ) {
	function ideapark_header_add_to_cart_hash( $hash ) {
		return $hash . ( function_exists( 'ideapark_wishlist' ) ? substr( implode( ',', Ideapark_Wishlist()->ids() ), 0, 8 ) : '' );
	}
}

if ( ! function_exists( 'ideapark_header_add_to_cart_fragment' ) ) {
	function ideapark_header_add_to_cart_fragment( $fragments ) {
		$fragments['.ip-cart-count']     = ideapark_cart_info();
		$fragments['.ip-wishlist-count'] = ideapark_wishlist_info();
		if ( ideapark_mod( '_get_notice' ) ) {
			ob_start();
			wc_print_notices();
			$fragments['ideapark_notice'] = ob_get_clean();
		}

		return $fragments;
	}
}

if ( ! function_exists( 'ideapark_cart_info' ) ) {
	function ideapark_cart_info() {
		$old_count = isset( $_COOKIE['ip-cart-count'] ) ? (int) $_COOKIE['ip-cart-count'] : 0;
		$count     = isset( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		if ( IDEAPARK_THEME_IS_AJAX_FRAGMENTS ) {
			wc_setcookie( 'ip-cart-count', $count, time() + 60 * 60 * 24 * 30, false );
		}

		return '<span class="ip-cart-count' . ( $old_count != $count ? ' animate' : '' ) . '">' . esc_html( $count ? $count : '' ) . '</span>';
	}
}

if ( ! function_exists( 'ideapark_wishlist_info' ) ) {
	function ideapark_wishlist_info() {

		$old_count = isset( $_COOKIE['ip-wishlist-count'] ) ? (int) $_COOKIE['ip-wishlist-count'] : 0;
		if ( ideapark_woocommerce_on() && ideapark_mod( 'wishlist_enabled' ) && ideapark_mod( 'wishlist_page' ) && class_exists( 'Ideapark_Wishlist' ) ) {
			$count = sizeof( Ideapark_Wishlist()->ids() );
		} else {
			$count = 0;
		}
		if ( IDEAPARK_THEME_IS_AJAX_FRAGMENTS || IDEAPARK_THEME_IS_AJAX ) {
			wc_setcookie( 'ip-wishlist-count', $count, time() + 60 * 60 * 24 * 30, false );
		}

		return '<span class="ip-wishlist-count' . ( $old_count != $count ? ' animate' : '' ) . '">' . esc_html( $count ? $count : '' ) . '</span>';
	}
}

if ( ! function_exists( 'ideapark_woocommerce_show_product_loop_new_badge' ) ) {
	function ideapark_woocommerce_show_product_loop_new_badge() {

		/**
		 * @var $product WC_Product
		 **/
		global $product;

		$badges = '';


		if ( $title = get_post_meta( $product->get_id(), 'ideapark_custom_badge_tab_title', true ) ) {
			$color    = get_post_meta( $product->get_id(), 'ideapark_custom_badge_tab_color', true );
			$bg_color = get_post_meta( $product->get_id(), 'ideapark_custom_badge_tab_bg_color', true );
			$badges   .= '<span class="ip-shop-loop-custom-badge" ' . ( $color || $bg_color ? ( ' style="' . ( $color ? 'color:' . esc_attr( $color ) . ';' : '' ) . ( $bg_color ? 'background-color:' . esc_attr( $bg_color ) . ';' : '' ) . '"' ) : '' ) . '>' . esc_html( $title ) . '</span>';
		}

		if ( ideapark_mod( 'featured_badge_text' ) && $product->is_featured() ) {
			$badges .= '<span class="ip-shop-loop-featured-badge">' . esc_html( ideapark_mod( 'featured_badge_text' ) ) . '</span>';
		}

		$postdate      = get_the_time( 'Y-m-d' );
		$postdatestamp = strtotime( $postdate );
		$newness       = (int) ideapark_mod( 'product_newness' );

		if ( $newness > 0 && ideapark_mod( 'new_badge_text' ) ) {
			if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) {
				$badges .= '<span class="ip-shop-loop-new-badge">' . esc_html( ideapark_mod( 'new_badge_text' ) ) . '</span>';
			}
		}

		echo ideapark_wrap( $badges, '<span class="ip-shop-loop-badges">', '</span>' );
	}
}

if ( ! function_exists( 'ideapark_woocommerce_product_tabs' ) ) {
	function ideapark_woocommerce_product_tabs( $tabs ) {
		$theme_tabs = ideapark_parse_checklist( ideapark_mod( 'product_tabs' ) );
		$priority   = 10;
		foreach ( $theme_tabs as $theme_tab_index => $enabled ) {
			if ( array_key_exists( $theme_tab_index, $tabs ) ) {
				if ( $enabled ) {
					$tabs[ $theme_tab_index ]['priority'] = $priority;
				} else {
					unset( $tabs[ $theme_tab_index ] );
				}
			}
			$priority += 10;
		}

		return $tabs;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_breadcrumbs' ) ) {
	function ideapark_woocommerce_breadcrumbs() {
		return [
			'delimiter'   => '',
			'wrap_before' => '<nav class="woocommerce-breadcrumb"><ul>',
			'wrap_after'  => '</ul></nav>',
			'before'      => '<li>',
			'after'       => '</li>',
			'home'        => ideapark_mod( 'product_breadcrumbs_home' ) ? esc_html__( 'Home', 'kidz' ) : '',
		];
	}
}

if ( ! function_exists( 'ideapark_custom_woocommerce_thumbnail' ) ) {
	function ideapark_custom_woocommerce_thumbnail() {

		add_filter( 'woocommerce_placeholder_img_src', 'ideapark_custom_woocommerce_placeholder_img_src' );

		function ideapark_custom_woocommerce_placeholder_img_src( $src ) {
			$upload_dir = wp_upload_dir();
			$uploads    = untrailingslashit( $upload_dir['baseurl'] );
			$src        = IDEAPARK_THEME_URI . '/img/placeholder.png';

			return $src;
		}
	}
}

if ( ! function_exists( 'ideapark_woocommerce_account_menu_items' ) ) {
	function ideapark_woocommerce_account_menu_items( $items ) {
		unset( $items['customer-logout'] );

		return $items;
	}
}

if ( ! function_exists( 'ideapark_single_product_summary_break' ) ) {
	function ideapark_single_product_summary_break() {
		echo '</div><div class="col-md-12 col-xs-6 ip-buttons-block break">';
	}
}

if ( ! function_exists( 'ideapark_single_product_summary_availability' ) ) {
	function ideapark_single_product_summary_availability() {
		global $product;
		/**
		 * @var $product WC_Product
		 */

		if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
			if ( $product->get_type() == 'variable' ) { ?>
				<span class="ip-stock"></span>
			<?php } else {
				$availability = $product->get_availability();
				if ( $product->is_in_stock() || $product->is_on_backorder() ) {
					$availability_html = '<span class="ip-stock ip-in-stock ' . esc_attr( $availability['class'] ) . '"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-check" /></svg>' . ( $availability['availability'] ? esc_html( $availability['availability'] ) : esc_html__( 'In stock', 'kidz' ) ) . '</span>';
				} else {
					$availability_html = '<span class="ip-stock ip-out-of-stock ' . esc_attr( $availability['class'] ) . '"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-close" /></svg>' . esc_html( $availability['availability'] ) . '</span>';
				}
				echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_cut_product_categories' ) ) {
	function ideapark_cut_product_categories( $links ) {
		if ( ideapark_woocommerce_on() && is_product() ) {
			$links = array_slice( $links, 0, 2 );
		}

		return $links;
	}
}

if ( ! function_exists( 'ideapark_remove_product_description_heading' ) ) {
	function ideapark_remove_product_description_heading() {
		return '';
	}
}

if ( ! function_exists( 'ideapark_woocommerce_archive_description' ) ) {
	function ideapark_woocommerce_archive_description() {
		if ( is_search() ) {
			$old = ideapark_mod( 'search_products_only' );
			ideapark_mod_set_temp( 'search_products_only', true );
			get_search_form();
			ideapark_mod_set_temp( 'search_products_only', $old );
		}
	}
}

if ( ! function_exists( 'ideapark_woocommerce_max_srcset_image_width_768' ) ) {
	function ideapark_woocommerce_max_srcset_image_width_768( $max_width, $size_array ) {
		return 768;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_max_srcset_image_width_360' ) ) {
	function ideapark_woocommerce_max_srcset_image_width_360( $max_width, $size_array ) {
		return 360;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_max_srcset_image_width_140' ) ) {
	function ideapark_woocommerce_max_srcset_image_width_140( $max_width, $size_array ) {
		return 140;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_srcset' ) ) {
	function ideapark_woocommerce_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		foreach ( $sources as $width => $data ) {

			if ( ideapark_mod( 'product_small_mobile' ) == 'compact' ) {
				$min_width = 140;
			} elseif ( ideapark_mod( 'product_small_mobile' ) == 'small' ) {
				$min_width = 100;
			} else {
				$min_width = 210;
			}

			if ( $width < $min_width ) {
				unset( $sources[ $width ] );
			}
		}

		return $sources;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_srcset_single' ) ) {
	function ideapark_woocommerce_srcset_single( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		foreach ( $sources as $width => $data ) {
			if ( $width < 360 ) {
				unset( $sources[ $width ] );
			}
		}

		return $sources;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_srcset_retina' ) ) {
	function ideapark_woocommerce_srcset_retina( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		foreach ( $sources as $width => $data ) {
			if ( $width != $size_array[0] && $width != $size_array[0] * 2 ) {
				unset( $sources[ $width ] );
			}
		}

		return $sources;
	}
}

if ( ! function_exists( 'ideapark_subcategory_archive_thumbnail_size' ) ) {
	function ideapark_subcategory_archive_thumbnail_size( $thumbnail_size ) {
		return 'woocommerce_gallery_thumbnail';
	}
}

if ( ! function_exists( 'ideapark_woocommerce_cart_item_thumbnail' ) ) {
	function ideapark_woocommerce_cart_item_thumbnail( $product_get_image, $cart_item, $cart_item_key ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		ideapark_wp_scrset_on( 'retina' );
		$thumb = $_product->get_image( 'thumbnail' );
		ideapark_wp_scrset_off( 'retina' );

		return $thumb;
	}
}

if ( ! function_exists( 'ideapark_localize_vars' ) ) {
	function ideapark_localize_vars() {
		return [
			'themeDir'             => IDEAPARK_THEME_DIR,
			'themeUri'             => IDEAPARK_THEME_URI,
			'ajaxUrl'              => admin_url( 'admin-ajax.php' ),
			'searchUrl'            => home_url( '?s=' ),
			'svgUrl'               => esc_js( ideapark_svg_url() ),
			'isRtl'                => ideapark_is_rtl(),
			'searchType'           => ideapark_mod( 'search_type' ),
			'shopProductModal'     => ideapark_mod( 'shop_product_modal' ),
			'stickyMenu'           => ideapark_mod( 'sticky_menu' ),
			'productThumbnails'    => ideapark_mod( 'product_thumbnails' ),
			'stickySidebar'        => ideapark_mod( 'sticky_sidebar' ),
			'videoFirst'           => ideapark_mod( 'video_first' ),
			'popupCartLayout'      => ideapark_mod( 'popup_cart_layout' ),
			'popupCartOpenMobile'  => ideapark_mod( 'popup_cart_layout' ) == 'sidebar' && ideapark_mod( 'popup_cart_auto_open_mobile' ),
			'popupCartOpenDesktop' => ideapark_mod( 'popup_cart_layout' ) == 'sidebar' && ideapark_mod( 'popup_cart_auto_open_desktop' ),
			'viewMore'             => esc_html__( 'View More', 'kidz' ),
			'arrowLeft'            => '<a class="slick-prev ' . ideapark_mod( 'arrows_type' ) . '"><span>' . ideapark_arrow_left() . '</span></a>',
			'arrowRight'           => '<a class="slick-next ' . ideapark_mod( 'arrows_type' ) . '"><span>' . ideapark_arrow_right() . '</span></a>',
			'arrowLeftOwl'         => '<span class="' . ideapark_mod( 'arrows_type' ) . '">' . ideapark_arrow_left() . '</span>',
			'arrowRightOwl'        => '<span class="' . ideapark_mod( 'arrows_type' ) . '">' . ideapark_arrow_right() . '</span>',
			'jsDelay'              => ideapark_mod( 'js_delay' ),
			'ajaxAddToCart'        => ideapark_mod( 'product_page_ajax_add_to_cart' ) && 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' ) && 'yes' !== get_option( 'woocommerce_cart_redirect_after_add' ),
			'locale'               => get_locale(),
		];
	}
}

if ( ! function_exists( 'ideapark_arrow_left' ) ) {
	function ideapark_arrow_left() {
		return '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . ( ideapark_mod( 'arrows_type' ) == 'minimal' ? '#svg-minimal-arrow-left' : '#svg-angle-left' ) . '" /></svg>';
	}
}

if ( ! function_exists( 'ideapark_arrow_right' ) ) {
	function ideapark_arrow_right() {
		return '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . ( ideapark_mod( 'arrows_type' ) == 'minimal' ? '#svg-minimal-arrow-right' : '#svg-angle-right' ) . '" /></svg>';
	}
}

if ( ! function_exists( 'ideapark_heartbeat' ) ) {
	function ideapark_heartbeat( $code = '', $url = '' ) {
		$timeout           = 5;
		$args              = [];
		$args['heartbeat'] = 1;
		$args['site']      = get_option( 'siteurl' );
		$args['slug']      = IDEAPARK_THEME_SLUG;
		$args['version']   = IDEAPARK_THEME_VERSION;
		$args['code']      = $code ?: call_user_func( 'ideapark_get_pur' . 'chase_code' );
		$args['wrong']     = get_option( '_is_wro' . 'ng_code' );

		return wp_remote_post( $url ?: ideapark_api_url(), [
			'timeout'   => $timeout,
			'body'      => (array) $args,
			'sslverify' => false
		] );
	}
}

if ( ! function_exists( 'ideapark_disable_background_image' ) ) {
	function ideapark_disable_background_image( $value ) {
		if ( ideapark_mod( 'hide_inner_background' ) && ! is_front_page() && ! is_admin() ) {
			return '';
		} else {
			return $value;
		}
	}
}

if ( ! function_exists( 'ideapark_admin_scripts' ) ) {
	function ideapark_admin_scripts() {
		wp_enqueue_style( 'ideapark-admin', IDEAPARK_THEME_URI . '/css/admin.css', [], ideapark_mtime( IDEAPARK_THEME_DIR . '/css/admin.css' ) );
	}
}

if ( ! function_exists( 'ideapark_exists_theme_addons' ) ) {
	function ideapark_exists_theme_addons() {
		return defined( 'IDEAPARK_THEME_FUNC_VERSION' );
	}
}

if ( ! function_exists( 'ideapark_wrap' ) ) {
	function ideapark_wrap( $str, $before = '', $after = '' ) {
		if ( trim( $str ) != '' ) {
			return sprintf( '%s%s%s', $before, $str, $after );
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'ideapark_loop_add_to_cart_link' ) ) {
	function ideapark_loop_add_to_cart_link( $text, $product, $args ) {
		if ( $product->get_type() == 'simple' ) {
			return preg_replace( '~(<a[^>]+>)~ui', '\\1' . '<svg class="svg-add"><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-cart" /></svg>', $text );
		} else {
			return preg_replace( '~(</a>)~ui', '<svg class="svg-more"><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-arrow-more" /></svg>' . '\\1', $text );
		}
	}
}

if ( ! function_exists( 'ideapark_show_customizer_attention' ) ) {
	function ideapark_show_customizer_attention( $type = 'front_page_builder' ) {
		if ( is_customize_preview() ) {
			switch ( $type ) {
				case 'front_page_builder':
					?>
					<div class="container">
						<div class="ip_customizer_attention">
							<span
								class="dashicons dashicons-info"></span> <?php echo wp_kses( __( 'Please enable a <b>static page</b> for your homepage and start using <b>Front Page builder</b>', 'kidz' ), [ 'b' => [] ] ) ?>
							&nbsp;
							<button class="customizer-edit"
									data-control='{ "name":"show_on_front" }'><?php esc_html_e( 'Enable', 'kidz' ); ?></button>
						</div>
					</div>
					<?php
					break;
			}
		}
	}
}

if ( ! function_exists( 'ideapark_header_metadata' ) ) {
	function ideapark_header_metadata() {

		$lang_postfix = ideapark_get_lang_postfix();

		$fonts = [
			ideapark_mod( 'theme_font_0' . $lang_postfix ),
			ideapark_mod( 'theme_font_1' . $lang_postfix ),
			ideapark_mod( 'theme_font_2' . $lang_postfix ),
		];

		$css = ideapark_get_google_font_uri( $fonts );

		?>
		<link rel="stylesheet" href="<?php echo esc_url( $css ); ?>">
		<?php
	}
}

if ( ! function_exists( 'ideapark_init_filesystem' ) ) {
	function ideapark_init_filesystem() {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/file.php';
		}
		if ( is_admin() ) {
			$url   = admin_url();
			$creds = false;
			if ( function_exists( 'request_filesystem_credentials' ) ) {
				$creds = request_filesystem_credentials( $url, '', false, false, [] );
				if ( false === $creds ) {
					return false;
				}
			}
			if ( ! WP_Filesystem( $creds ) ) {
				if ( function_exists( 'request_filesystem_credentials' ) ) {
					request_filesystem_credentials( $url, '', true, false );
				}

				return false;
			}

			return true;
		} else {
			WP_Filesystem();
		}

		return true;
	}
}

if ( ! function_exists( 'ideapark_fpc' ) ) {
	function ideapark_fpc( $file, $data, $flag = 0 ) {
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->put_contents( $file, ( FILE_APPEND == $flag && $wp_filesystem->exists( $file ) ? $wp_filesystem->get_contents( $file ) : '' ) . $data, false );
			}
		}

		return false;
	}
}

if ( ! function_exists( 'ideapark_fgc' ) ) {
	function ideapark_fgc( $file ) {
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->get_contents( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_is_file' ) ) {
	function ideapark_is_file( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->is_file( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_is_dir' ) ) {
	function ideapark_is_dir( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->is_dir( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_mkdir' ) ) {
	function ideapark_mkdir( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return wp_mkdir_p( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_delete_file' ) ) {
	function ideapark_delete_file( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				if ( ideapark_is_file( $file ) ) {
					wp_delete_file( $file );
				}

				return true;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_mtime' ) ) {
	function ideapark_mtime( $file ) {
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;
		if ( ! empty( $file ) ) {
			if ( isset( $wp_filesystem ) && is_object( $wp_filesystem ) ) {
				$file = str_replace( ABSPATH, $wp_filesystem->abspath(), $file );

				return $wp_filesystem->mtime( $file );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'ideapark_woocommerce_gallery_image_size' ) ) {
	function ideapark_woocommerce_gallery_image_size( $size ) {
		return 'ideapark-large-2x';
	}
}

if ( ! function_exists( 'ideapark_ajax_custom_css' ) ) {
	function ideapark_ajax_custom_css() {
		echo ideapark_customize_css( true );
		die();
	}
}

if ( ! function_exists( 'ideapark_wp_scrset_on' ) ) {
	function ideapark_wp_scrset_on( $name = '' ) {
		$f = 'add_filter';
		$n = 'wp_calculate_image_' . 'srcset';
		call_user_func( $f, $n, 'ideapark_woocommerce_srcset' . ( $name ? '_' : '' ) . $name, 10, 5 );
	}
}

if ( ! function_exists( 'ideapark_wp_scrset_off' ) ) {
	function ideapark_wp_scrset_off( $name = '' ) {
		$f = 'remove_filter';
		$n = 'wp_calculate_image_' . 'srcset';
		call_user_func( $f, $n, 'ideapark_woocommerce_srcset' . ( $name ? '_' : '' ) . $name, 10 );
	}
}

if ( ! function_exists( 'ideapark_wp_max_scrset_on' ) ) {
	function ideapark_wp_max_scrset_on( $name = '' ) {
		$f = 'add_filter';
		$n = 'max_srcset_image_' . 'width';
		call_user_func( $f, $n, 'ideapark_woocommerce_max_srcset_image_width' . ( $name ? '_' : '' ) . $name, 10, 2 );
	}
}

if ( ! function_exists( 'ideapark_wp_max_scrset_off' ) ) {
	function ideapark_wp_max_scrset_off( $name = '' ) {
		$f = 'remove_filter';
		$n = 'max_srcset_image_' . 'width';
		call_user_func( $f, $n, 'ideapark_woocommerce_max_srcset_image_width' . ( $name ? '_' : '' ) . $name, 10 );
	}
}

if ( ! function_exists( 'ideapark_override_theme' ) ) {
	function ideapark_override_theme( $options ) {

		if ( ! empty( $options['package'] ) && preg_match( '~marketplace\.envato\.com~', $options['package'] ) ) {
			$options['clear_destination']           = true;
			$options['abort_if_destination_exists'] = false;
		}

		return $options;
	}
}

if ( ! function_exists( 'ideapark_correct_tgmpa_mofile' ) ) {
	function ideapark_correct_tgmpa_mofile( $mofile, $domain ) {
		if ( 'tgmpa' !== $domain ) {
			return $mofile;
		}

		return preg_replace( '`/([a-z]{2}_[A-Z]{2}.mo)$`', '/tgmpa-$1', $mofile );
	}
}

if ( IDEAPARK_THEME_DEMO ) {
	add_filter( 'term_links-product_cat', 'ideapark_cut_product_categories', 99, 1 );
}

if ( ! function_exists( 'ideapark_add_style' ) ) {
	function ideapark_add_style( $handle, $src = '', $deps = [], $ver = false, $media = 'all', $path = '' ) {
		global $ideapark_theme_styles;
		if ( ! array_key_exists( $handle, $ideapark_theme_styles ) ) {
			$ideapark_theme_styles[ $handle ] = [
				'handle' => $handle,
				'src'    => $src,
				'deps'   => $deps,
				'ver'    => $ver,
				'media'  => $media,
				'path'   => $path,
			];
		}
	}
}

if ( ! function_exists( 'ideapark_enqueue_style_hash' ) ) {
	function ideapark_enqueue_style_hash( $styles ) {
		$hash = IDEAPARK_THEME_VERSION . '_' . (string) ideapark_mtime( IDEAPARK_THEME_DIR . '/functions/customize/ip_customize_settings.php' ) . '_' . ( IDEAPARK_THEME_DEMO ? 'on' : 'off' );

		if ( ! empty( $styles ) ) {
			foreach ( $styles as $item ) {
				if ( is_array( $item ) ) {
					$hash .= $item['ver'] . '_';
				} else {
					$hash .= (string) ideapark_mtime( IDEAPARK_THEME_DIR . $item ) . '_';
				}
			}
		}

		return $hash ? md5( $hash ) : '';
	}
}


if ( ! function_exists( 'ideapark_enqueue_style' ) ) {
	function ideapark_enqueue_style() {
		global $ideapark_theme_styles;

		if ( ideapark_mod( 'use_minified_css' ) && ! is_customize_preview() ) {

			$lang_postfix = ideapark_get_lang_postfix();

			if ( $hash = ideapark_enqueue_style_hash( $ideapark_theme_styles ) . $lang_postfix ) {
				if ( ! ideapark_is_dir( IDEAPARK_THEME_UPLOAD_DIR ) ) {
					ideapark_mkdir( IDEAPARK_THEME_UPLOAD_DIR );
				}
				$css_path = IDEAPARK_THEME_UPLOAD_DIR . 'min' . $lang_postfix . '.css';
				$css_url  = IDEAPARK_THEME_UPLOAD_URL . 'min' . $lang_postfix . '.css';
				if ( get_option( $option_name = 'ideapark_styles_hash' . $lang_postfix ) != $hash || ! ideapark_is_file( $css_path ) ) {
					include_once( IDEAPARK_THEME_DIR . '/functions/lib/cssmin.php' );
					$code = "";
					foreach ( $ideapark_theme_styles as $style ) {
						$path = $style['path'] ? $style['path'] : ( IDEAPARK_THEME_DIR . preg_replace( '~^' . preg_quote( IDEAPARK_THEME_URI, '~' ) . '~', '', $style['src'] ) );
						$code .= ideapark_fgc( $path );
					}
					$code .= ideapark_customize_css( true );
					$code = CSSMin::compressCSS( $code );
					ideapark_fpc( $css_path, $code );
					if ( get_option( $option_name ) !== null ) {
						update_option( $option_name, $hash );
					} else {
						add_option( $option_name, $hash );
					}
				}
			}
			wp_enqueue_style( 'ideapark-core-css', $css_url, [], ideapark_mtime( $css_path ), 'all' );

		} else {
			foreach ( $ideapark_theme_styles as $style ) {
				wp_enqueue_style( $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
			}
			ideapark_customize_css();
		}
	}
}

if ( ! function_exists( 'ideapark_add_script' ) ) {
	function ideapark_add_script( $handle, $src = '', $deps = [], $ver = false, $in_footer = false ) {
		global $ideapark_theme_scripts;
		$ideapark_theme_scripts[ $handle ] = [
			'handle'    => $handle,
			'src'       => preg_replace( '~^' . preg_quote( IDEAPARK_THEME_URI, '~' ) . '~', '', $src ),
			'deps'      => $deps,
			'ver'       => $ver,
			'in_footer' => $in_footer
		];
	}
}

if ( ! function_exists( 'ideapark_array_merge' ) ) {
	function ideapark_array_merge( $a1, $a2 ) {
		for ( $i = 1; $i < func_num_args(); $i ++ ) {
			$arg = func_get_arg( $i );
			if ( is_array( $arg ) && count( $arg ) > 0 ) {
				foreach ( $arg as $k => $v ) {
					$a1[ $k ] = $v;
				}
			}
		}

		return $a1;
	}
}

if ( ! function_exists( 'ideapark_shortcode' ) ) {
	function ideapark_shortcode( $code ) {
		$f = 'do' . '_shortcode';

		return call_user_func( $f, $code );
	}
}

if ( ! function_exists( 'ideapark_af' ) ) {
	function ideapark_af( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		return add_filter( $tag, $function_to_add, $priority, $accepted_args );
	}
}

if ( ! function_exists( 'ideapark_rf' ) ) {
	function ideapark_rf( $tag, $function_to_remove, $priority = 10 ) {
		$f = 'remove_filter';

		return call_user_func( $f, $tag, $function_to_remove, $priority );
	}
}

if ( ! function_exists( 'ideapark_aa' ) ) {
	function ideapark_aa( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		return add_action( $tag, $function_to_add, $priority, $accepted_args );
	}
}

if ( ! function_exists( 'ideapark_ra' ) ) {
	function ideapark_ra( $tag, $function_to_remove, $priority = 10 ) {
		$f = 'remove_action';

		return call_user_func( $f, $tag, $function_to_remove, $priority );
	}
}

if ( ! function_exists( 'ideapark_get_template_part' ) ) {
	function ideapark_get_template_part( $template, $args = null ) {
		set_query_var( 'ideapark_var', $args );
		get_template_part( $template );
		set_query_var( 'ideapark_var', null );
	}
}

if ( ! function_exists( 'ideapark_enqueue_script' ) ) {
	function ideapark_enqueue_script() {
		global $ideapark_theme_scripts;

		$hash = '';

		if ( ideapark_mod( 'use_minified_js' ) ) {
			foreach ( $ideapark_theme_scripts as $script ) {
				$hash .= (string) ideapark_mtime( IDEAPARK_THEME_DIR . $script['src'] ) . '_';
			}
			if ( $hash ) {
				$hash = md5( $hash );
				if ( ! ideapark_is_dir( IDEAPARK_THEME_UPLOAD_DIR ) ) {
					ideapark_mkdir( IDEAPARK_THEME_UPLOAD_DIR );
				}
				if ( get_option( $option_name = 'ideapark_scripts_hash' ) != $hash || ! ideapark_is_file( IDEAPARK_THEME_UPLOAD_DIR . 'min.js' ) ) {
					include_once( IDEAPARK_THEME_DIR . '/functions/lib/jsmin.php' );
					$code = "";
					foreach ( $ideapark_theme_scripts as $script ) {
						$code .= JSMin::minify( ideapark_fgc( IDEAPARK_THEME_DIR . $script['src'] ) );
					}
					ideapark_fpc( IDEAPARK_THEME_UPLOAD_DIR . 'min.js', $code );
					if ( get_option( $option_name ) !== null ) {
						update_option( $option_name, $hash );
					} else {
						add_option( $option_name, $hash );
					}
				}

				wp_enqueue_script( 'ideapark-core', IDEAPARK_THEME_UPLOAD_URL . 'min.js', [ 'jquery' ], ideapark_mtime( IDEAPARK_THEME_UPLOAD_DIR . 'min.js' ), true );
			}
		}

		if ( ! $hash ) {
			foreach ( $ideapark_theme_scripts as $script ) {
				wp_enqueue_script( $script['handle'], IDEAPARK_THEME_URI . $script['src'], $script['deps'], $script['ver'], $script['in_footer'] );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_pingback_header' ) ) {
	function ideapark_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}
}

if ( ! function_exists( 'ideapark_generator_tag' ) ) {
	function ideapark_generator_tag( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="Kidz ' . esc_attr( IDEAPARK_THEME_VERSION ) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="Kidz ' . esc_attr( IDEAPARK_THEME_VERSION ) . '" />';
				break;
		}

		return $gen;
	}
}

if ( ! function_exists( 'ideapark_woocommerce_demo_store' ) ) {
	function ideapark_woocommerce_demo_store( $notice ) {
		return str_replace( 'woocommerce-store-notice ', 'woocommerce-store-notice woocommerce-store-notice--' . ideapark_mod( 'store_notice' ) . ' ', $notice );
	}
}

if ( ! function_exists( 'ideapark_empty_gif' ) ) {
	function ideapark_empty_gif() {
		return 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
	}
}

if ( ! function_exists( 'ideapark_get_sprite_url' ) ) {
	function ideapark_get_sprite_url() {
		return IDEAPARK_THEME_URI . '/img/sprite.svg?v=' . ideapark_mtime( IDEAPARK_THEME_DIR . '/img/sprite.svg' );
	}
}

if ( ! function_exists( 'ideapark_get_inline_svg' ) ) {
	function ideapark_get_inline_svg( $attachment_id, $class = '' ) {
		$svg = get_post_meta( $attachment_id, '_ideapark_inline_svg', true );
		if ( empty( $svg ) ) {
			$svg = ideapark_fgc( get_attached_file( $attachment_id ) );
			update_post_meta( $attachment_id, '_ideapark_inline_svg', $svg );
		}

		if ( ! empty( $svg ) ) {
			if ( $class ) {
				if ( preg_match( '~(<svg[^>]+class\s*=\s*[\'"][^\'"]*)([\'"][^>]*>)~i', $svg, $match ) ) {
					$svg = str_replace( $match[1], $match[1] . ' ' . esc_attr( $class ), $svg );
				} else {
					$svg = preg_replace( '~<svg~i', '<svg class="' . esc_attr( $class ) . '"', $svg );
				}
			}
		}

		return $svg;
	}
}

if ( ! function_exists( 'ideapark_product_search_sku' ) ) {
	function ideapark_product_search_sku( $args, $wp_query ) {
		global $wpdb;

		if ( is_admin() && ! IDEAPARK_THEME_IS_AJAX_SEARCH || ! ideapark_mod( 'search_by_sku' ) || empty( $wp_query->query_vars['s'] ) ) {
			return $args;
		}

		if ( strlen( ( $s = trim( preg_replace( '~\s\s+~', ' ', $wp_query->query_vars['s'] ) ) ) ) > 0 ) {
			$e = explode( ' ', $s );

			$search_ids = ideapark_search_by_sku_ids( $e );

			if ( sizeof( $search_ids ) > 0 && ! empty( $args['where'] ) ) {
				$args['where'] = str_replace( '((' . $wpdb->posts . '.post_title LIKE', '(( ' . $wpdb->posts . '.ID IN (' . implode( ',', $search_ids ) . ')) OR (' . $wpdb->posts . '.post_title LIKE', $args['where'] );
			}
		}

		return $args;
	}
}

if ( ! function_exists( 'ideapark_search_by_sku_ids' ) ) {
	function ideapark_search_by_sku_ids( $e ) {
		global $wpdb;

		$_where = [];
		foreach ( $e as $word ) {
			$s        = '%' . esc_sql( $wpdb->esc_like( $word ) ) . '%';
			$_where[] = $wpdb->prepare( "( pm.meta_value LIKE %s )", $s );
		}

		$sku_to_id = $wpdb->get_col( "
			SELECT pm.post_id
			FROM {$wpdb->postmeta} pm 
			WHERE pm.meta_key='_sku' AND ( " . implode( " AND ", $_where ) . " )" );

		$sku_to_parent_id = $wpdb->get_col( "
			SELECT p.post_parent post_id 
			FROM {$wpdb->posts} p 
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id and pm.meta_key='_sku' and ( " . implode( " AND ", $_where ) . " )
			WHERE p.post_parent <> 0
			GROUP BY p.post_parent" );

		$search_ids = array_merge( $sku_to_id, $sku_to_parent_id );

		return $search_ids;
	}
}

if ( ! function_exists( 'ideapark_search_product_tag' ) ) {
	function ideapark_search_product_tag( $args, $wp_query ) {
		global $wpdb;

		if ( is_admin() && ! IDEAPARK_THEME_IS_AJAX_SEARCH || ! ( ideapark_mod( 'search_by_category' ) || ideapark_mod( 'search_by_tag' ) || ideapark_mod( 'search_by_brand' ) ) || empty( $wp_query->query_vars['s'] ) ) {
			return $args;
		}

		if ( strlen( ( $s = trim( preg_replace( '~\s\s+~', ' ', $wp_query->query_vars['s'] ) ) ) ) > 0 ) {
			$e = explode( ' ', $s );

			$term_ids = ideapark_search_product_tag_ids( $e );

			if ( sizeof( $term_ids ) > 0 && ! empty( $args['where'] ) ) {
				$args['join']  .= " LEFT JOIN {$wpdb->term_relationships} tr_ip ON tr_ip.term_taxonomy_id IN (" . implode( ',', $term_ids ) . ") AND tr_ip.object_id = {$wpdb->posts}.ID";
				$args['where'] = str_replace( '((' . $wpdb->posts . '.post_title LIKE', '(tr_ip.object_id IS NOT NULL OR (' . $wpdb->posts . '.post_title LIKE', $args['where'] );
			}
		}

		return $args;
	}
}

if ( ! function_exists( 'ideapark_search_product_tag_ids' ) ) {
	function ideapark_search_product_tag_ids( $e ) {
		global $wpdb;

		$_where = [];
		foreach ( $e as $word ) {
			$s        = '%' . esc_sql( $wpdb->esc_like( $word ) ) . '%';
			$_where[] = $wpdb->prepare( "( t.name LIKE %s )", $s );
		}

		$taxonomies = [];

		if ( ideapark_mod( 'search_by_category' ) ) {
			$taxonomies[] = 'product_cat';
		}

		if ( ideapark_mod( 'search_by_tag' ) ) {
			$taxonomies[] = 'product_tag';
		}

		if ( ideapark_mod( 'search_by_brand' ) && ideapark_mod( 'product_brand_attribute' ) && taxonomy_exists( ideapark_mod( 'product_brand_attribute' ) ) ) {
			$taxonomies[] = ideapark_mod( 'product_brand_attribute' );
		}

		return $taxonomies ? $wpdb->get_col( "
			SELECT t.term_id
			FROM {$wpdb->terms} t 
			INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id and tt.taxonomy IN ('" . implode( "','", $taxonomies ) . "')
			WHERE " . implode( " AND ", $_where ) ) : [];
	}
}

if ( ! function_exists( 'ideapark_phone_wrap' ) ) {
	function ideapark_phone_wrap( $str, $before = '', $after = '', $add_link = true ) {
		if ( preg_match_all( '~\+?([\d \-()]{4,}|\d{3})~', $str, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as $match ) {
				$prefix  = $before;
				$postfix = $after;
				if ( $add_link ) {
					$prefix  .= '<a href="tel:' . preg_replace( '~[^0-9\+]~', '', $match[0] ) . '">';
					$postfix = '</a>' . $postfix;
				}
				$str = preg_replace( '~' . preg_quote( $match[0], '~' ) . '~', $prefix . '\\0' . $postfix, $str );
			}

			return $str;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'ideapark_image_meta' ) ) {
	function ideapark_image_meta( $image_id, $size = 'full' ) {
		$full = wp_get_attachment_image_src( $image_id, 'full' );
		if ( $image = $size == 'full' ? $full : wp_get_attachment_image_src( $image_id, $size ) ) {
			$srcset     = wp_get_attachment_image_srcset( $image_id, $size );
			$sizes      = wp_get_attachment_image_sizes( $image_id, $size );
			$alt        = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$attachment = get_post( $image_id );
			if ( ! $alt ) {
				if ( ! $attachment ) {
					$alt = '';
				} else {
					$alt = $attachment->post_excerpt;
					if ( ! $alt ) {
						$alt = $attachment->post_title;
					}
				}
			}
			$alt   = trim( strip_tags( $alt ) );
			$title = $attachment ? $attachment->post_title : '';

			return [
				'width'       => $image[1],
				'height'      => $image[2],
				'src'         => str_replace( ' ', '%20', $image[0] ),
				'srcset'      => $srcset,
				'sizes'       => $sizes,
				'full'        => $full[0],
				'full_width'  => $full[1],
				'full_height' => $full[2],
				'alt'         => $alt,
				'title'       => $title,
			];
		}

		return false;
	}
}

if ( ! function_exists( 'ideapark_img' ) ) {
	function ideapark_img( $image_meta, $class = '', $lazy = null, $attr = [] ) {
		static $is_skip_loading;
		if ( $is_skip_loading === null ) {
			$is_skip_loading = ( '1' === get_option( 'elementor_optimized_image_loading', '1' ) ) || wp_lazy_loading_enabled( 'img', null );
		}
		if ( $image_meta ) {
			if ( $class ) {
				$image_meta['class'] = $class;
			}
			if ( ! $is_skip_loading && ( ideapark_mod( 'lazyload' ) && $lazy === null || $lazy === true ) ) {
				$image_meta['loading'] = 'lazy';
			} elseif ( is_string( $lazy ) ) {
				$image_meta['loading'] = $lazy;
			}
			$attr_names = [
				'class',
				'width',
				'height',
				'src',
				'srcset',
				'sizes',
				'alt',
				'loading'
			];
			if ( is_array( $attr ) && $attr ) {
				foreach ( $attr as $key => $val ) {
					$image_meta[ $key ] = $val;
					if ( ! in_array( $key, $attr_names ) ) {
						$attr_names[] = $key;
					}
				}
			}
			$s = "<img";
			foreach (
				$attr_names as $attr_name
			) {
				if ( ! empty( $image_meta[ $attr_name ] ) || is_array( $attr ) && array_key_exists( $attr_name, $attr ) ) {
					if ( ( $attr_name == 'srcset' || $attr_name == 'sizes' ) && ( empty( $image_meta['srcset'] ) || empty( $image_meta['sizes'] ) ) ) {
						continue;
					}
					$s .= ' ' . $attr_name . '="' . esc_attr( $image_meta[ $attr_name ] ) . '"';
				}
			}
			$s .= "/>";

			return $s;
		}
	}
}

if ( ! function_exists( 'ideapark_woocommerce_subcategory_thumbnail' ) ) {
	function ideapark_woocommerce_subcategory_thumbnail( $category ) {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );
		$svg_id               = get_term_meta( $category->term_id, 'product_cat_svg_id', true );

		if ( $thumbnail_id && ( $image_meta = ideapark_image_meta( $thumbnail_id, $small_thumbnail_size ) ) ) {
			$image_meta['alt']    = $image_meta['title'] = $category->name;
			$image_meta['width']  = $dimensions['width'];
			$image_meta['height'] = $dimensions['height'];
			unset( $image_meta['title'] );
			echo ideapark_img( $image_meta );
		} elseif ( $svg_id ) {
			echo '<svg title="' . esc_attr( $category->name ) . '"  height="' . esc_attr( $dimensions['height'] ) . '" width="' . esc_attr( $dimensions['width'] ) . '" ><use xlink:href="#' . esc_attr( $svg_id ) . '"/></svg>';
		} elseif ( $placeholder = wc_placeholder_img_src() ) {
			echo ideapark_wrap( '<img src="' . esc_url( $placeholder ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />', '<div class="ip-shop-loop-thumb__spacer">', '</div>' );
		} else {
			echo '<div class="ip-shop-loop-thumb__spacer"></div>';
		}
	}
}

if ( ! function_exists( 'ideapark_fix_product_category_class' ) ) {
	function ideapark_fix_product_category_class( $classes ) {
		array_filter( $classes, function ( $class ) {
			return $class != 'product';
		} );

		return $classes;
	}
}

if ( ! function_exists( 'ideapark_stock_badge' ) ) {
	function ideapark_stock_badge() {
		global $product;
		/**
		 * @var $product WC_Product
		 */

		if ( ! ideapark_mod( 'outofstock_badge_text' ) ) {
			return;
		}

		$availability = $product->get_availability();
		if ( ! ( $product->is_in_stock() || $product->is_on_backorder() ) ) {
			$availability_html = '<span class="ip-shop-loop-stock-badge ' . esc_attr( $availability['class'] ) . '">' . esc_html( ideapark_mod( 'outofstock_badge_text' ) ) . '</span>';
			echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
		}
	}
}

if ( ! function_exists( 'ideapark_disable_block_editor' ) ) {
	function ideapark_disable_block_editor() {
		if ( ideapark_mod( 'disable_block_editor' ) ) {
			add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );
			add_filter( 'use_widgets_block_editor', '__return_false' );
			ideapark_ra( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
			ideapark_ra( 'wp_footer', 'wp_enqueue_global_styles', 1 );
		}
	}
}

if ( ! function_exists( 'ideapark_disable_purchase' ) ) {
	function ideapark_disable_purchase() {
		if ( ideapark_mod( 'disable_purchase' ) ) {
			add_filter( 'woocommerce_is_purchasable', '__return_false' );
		}

		if ( ideapark_mod( 'hide_prices' ) ) {
			add_filter( 'woocommerce_get_price_html', function ( $price ) {
				return is_admin() ? $price : '';
			}, 999 );
		}
	}
}

if ( ! function_exists( 'ideapark_hide_sku' ) ) {
	function ideapark_hide_sku() {
		if ( ideapark_mod( 'hide_sku' ) ) {
			add_filter( 'wc_product_sku_enabled', function () {
				return ! ( ! is_admin() && is_product() );
			} );
		}
	}
}

if ( ! function_exists( 'ideapark_is_elementor' ) ) {
	function ideapark_is_elementor() {
		return class_exists( 'Elementor\Plugin' );
	}
}

if ( ! function_exists( 'ideapark_is_elementor_page' ) ) {
	function ideapark_is_elementor_page( $page_id = null ) {
		global $post;
		if ( $page_id === null ) {
			$page_id = $post->ID;
		}

		return ideapark_is_elementor() && ( $elementor_instance = Elementor\Plugin::instance() ) && ( $el_page = $elementor_instance->documents->get( $page_id ) ) && $el_page->is_built_with_elementor();
	}
}

if ( ! function_exists( 'ideapark_is_elementor_preview' ) ) {
	function ideapark_is_elementor_preview() {
		return ideapark_is_elementor() && isset( $_GET['action'] ) && $_GET['action'] == 'elementor' && ! empty( $_GET['post'] ) && is_admin();
	}
}

if ( ! function_exists( 'ideapark_is_elementor_preview_mode' ) ) {
	function ideapark_is_elementor_preview_mode() {
		return ideapark_is_elementor() && ! empty( \Elementor\Plugin::$instance->preview ) && \Elementor\Plugin::$instance->preview->is_preview_mode();
	}
}

if ( ! function_exists( 'ideapark_wp_kses' ) ) {
	function ideapark_wp_kses( $string = '' ) {
		return wp_kses( $string, [
			'a'      => [
				'class'  => [],
				'href'   => [],
				'target' => []
			],
			'code'   => [
				'class' => []
			],
			'strong' => [],
			'br'     => [],
			'em'     => [],
			'p'      => [
				'class' => []
			],
			'span'   => [
				'class' => []
			],
		] );
	}
}

if ( ! function_exists( 'ideapark_wp_footer' ) ) {
	function ideapark_wp_footer() { ?>
		<div id="ip-quickview"></div>
		<?php if ( ideapark_mod( 'to_top_button' ) ) { ?>
			<div class="to-top-button"
			     <?php if ( ideapark_mod( 'to_top_button_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'to_top_button_color' ) ); ?>"<?php } ?>>
				<svg class="to-top-button__svg">
					<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-top"/>
				</svg>
			</div>
		<?php }
	}
}

if ( ! function_exists( 'ideapark_infinity_paging' ) ) {
	function ideapark_infinity_paging() {

		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}

		if ( IDEAPARK_THEME_IS_AJAX_INFINITY ) {
			ob_start();
		}

		$total   = wc_get_loop_prop( 'total_pages' );
		$current = wc_get_loop_prop( 'current_page' );
		$base    = esc_url_raw( add_query_arg( 'product-page', '%#%', false ) );
		$format  = '?product-page=%#%';

		if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
			$base   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
			$format = '';
		}

		if ( $current < $total ) {
			$link = str_replace( '%_%', $format, $base );
			$link = str_replace( '%#%', $current + 1, $link );
			$link = apply_filters( 'paginate_links', $link );
			if ( ! IDEAPARK_THEME_IS_AJAX_INFINITY && $current > 1 ) {
				$link = str_replace( '%_%', $format, $base );
				$link = str_replace( '%#%', $current - 1, $link );
				$link = apply_filters( 'paginate_links', $link ); ?>
				<script>ideapark_redirect_url = '<?php echo esc_url( $link ); ?>';</script>
			<?php } elseif ( ideapark_mod( 'product_grid_pagination' ) == 'loadmore' ) { ?>
				<div class="woocommerce-pagination c-product-grid__load-more-wrap">
					<a href="<?php echo esc_url( $link ); ?>"
					   onclick="return false"
					   class="c-button c-button--outline c-product-grid__load-more js-load-more"><?php echo esc_html( ideapark_mod( 'product_grid_load_more_text' ) ); ?></a>
				</div>
			<?php } elseif ( ideapark_mod( 'product_grid_pagination' ) == 'infinity' ) { ?>
				<div class="woocommerce-pagination c-product-grid__load-more-wrap">
					<span data-href="<?php echo esc_url( $link ); ?>"
						  class="c-product-grid__load-infinity js-load-infinity"></span>
				</div>
			<?php } ?>
		<?php }

		if ( IDEAPARK_THEME_IS_AJAX_INFINITY ) {
			ideapark_mod_set_temp( '_infinity_paging', ob_get_clean() );
		}
	}
}

if ( ! function_exists( 'ideapark_infinity_loading' ) ) {
	function ideapark_infinity_loading( $template ) {
		if ( IDEAPARK_THEME_IS_AJAX_INFINITY ) {
			$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : ( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] );
			if ( $page > 1 ) {
				if ( strripos( $template, '/archive-product.php' ) !== false || strripos( $template, '/taxonomy-product-cat.php' ) !== false || strripos( $template, '/taxonomy-product-tag.php' ) !== false || strripos( $template, '/taxonomy-product-attribute.php' ) !== false ) {
					$template = IDEAPARK_THEME_DIR . '/woocommerce/infinity-product.php';
				} elseif ( ! empty( $_GET['product-page'] ) ) {
					$template = IDEAPARK_THEME_DIR . '/woocommerce/infinity-shortcode.php';
				}
			}
		}

		return $template;
	}
}

if ( ! function_exists( 'ideapark_paging' ) ) {
	function ideapark_paging() {
		if ( ideapark_mod( 'product_grid_pagination' ) !== 'pagination' ) {
			ideapark_ra( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );
			add_action( 'woocommerce_after_shop_loop', 'ideapark_infinity_paging' );
			add_filter( 'template_include', 'ideapark_infinity_loading', 1000 );
		}
	}
}

if ( ! function_exists( 'ideapark_loop_product_thumbnail' ) ) {
	function ideapark_loop_product_thumbnail( $is_hover_image = false ) {
		global $product;
		$switch_image_on_hover = ideapark_mod( 'switch_image_on_hover' ) && $product->get_gallery_image_ids();

		if ( has_post_thumbnail() && ( $product_thumbnail_id = get_post_thumbnail_id() ) ) {

			if ( $is_hover_image ) {
				$ids                  = $product->get_gallery_image_ids();
				$product_thumbnail_id = ( ! empty( $ids[0] ) ) ? $ids[0] : 0;
			}

			$class = '';
			if ( ideapark_mod( 'grid_image_fit' ) == 'contain' ) {
				if ( $image_meta = ideapark_image_meta( $product_thumbnail_id, 'medium' ) ) {
					$class = 'thumb-shop-catalog thumb-shop-catalog--contain ' . ( is_front_page() ? 'front' : '' ) . ( ideapark_mod( 'outofstock_badge_text' ) ? ' thumb-shop-out-of-stock' : '' );
				}
			} else {
				if ( $image_meta = ideapark_image_meta( $product_thumbnail_id, 'woocommerce_thumbnail' ) ) {
					if ( ideapark_mod( 'product_small_mobile' ) == 'compact' ) {
						$mobile_add = " (max-width: 600px) and (min-width: 360px) 140px,";
					} elseif ( ideapark_mod( 'product_small_mobile' ) == 'small' ) {
						$mobile_add = " (max-width: 600px) and (min-width: 412px) 140px, (max-width: 411px) and (min-width: 360px) 100px,";
					} else {
						$mobile_add = "";
					}

					if ( is_front_page() ) {
						$product_thumbnail_sizes = "(min-width: 1200px) 210px, (min-width: 992px) 241px,{$mobile_add} 360px";
					} else {
						if ( ! ideapark_mod( '_with_sidebar' ) ) {
							$product_thumbnail_sizes = "(min-width: 1200px) 210px, (min-width: 992px) 241px, (min-width: 768px) 293px,{$mobile_add} 360px";
						} else {
							$product_thumbnail_sizes = "(min-width: 1200px) 210px, (min-width: 992px) 281px, (min-width: 768px) 293px,{$mobile_add} 360px";
						}
					}
					$image_meta['sizes'] = $product_thumbnail_sizes;
					$class               = 'thumb-shop-catalog ' . ( is_front_page() ? 'front' : '' ) . ( ideapark_mod( 'outofstock_badge_text' ) ? ' thumb-shop-out-of-stock' : '' );
				}
			}

			if ( $image_meta && $class ) {
				$class .= ( $switch_image_on_hover ? ( $is_hover_image ? ' thumb-shop-catalog--hover' : ' thumb-shop-catalog--base' ) : '' );
				echo ideapark_img( $image_meta, $class );

				if ( $switch_image_on_hover && ! $is_hover_image ) {
					ideapark_loop_product_thumbnail( true );
				}
			}

		} else {
			if ( wc_placeholder_img_src() ) {
				echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" class="thumb-shop-catalog' . ( ideapark_mod( 'outofstock_badge_text' ) ? ' thumb-shop-out-of-stock' : '' ) . '" alt="' . esc_html__( 'Awaiting product image', 'woocommerce' ) . '"/>';
			}
		}
	}
}

if ( ! function_exists( 'ideapark_mod_image_size' ) ) {
	function ideapark_mod_image_size( $mod_name ) {
		if ( ideapark_mod( $mod_name . '__width' ) && ideapark_mod( $mod_name . '__height' ) ) {
			return ' width="' . ideapark_mod( $mod_name . '__width' ) . '" height="' . ideapark_mod( $mod_name . '__height' ) . '" ';
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'ideapark_clear_cache' ) ) {
	function ideapark_clear_cache( $is_redirect = true ) {
		global $wpdb;
		do_action( 'after_update_theme', IDEAPARK_THEME_VERSION, IDEAPARK_THEME_VERSION );
		do_action( 'after_update_theme_late', IDEAPARK_THEME_VERSION, IDEAPARK_THEME_VERSION );
		delete_option( 'ideapark_scripts_hash' );
		if ( ideapark_is_elementor() ) {
			$elementor_instance = Elementor\Plugin::instance();
			$elementor_instance->files_manager->clear_cache();
		}
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_%' OR option_name LIKE '\_site\_transient\_%'" );
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key = '_ideapark_inline_svg'" );
		ideapark_regenerate_wc_lookup();

		$sql = $wpdb->prepare( "SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE taxonomy = %s", 'nav_menu' );
		if ( ( $term_taxonomy_ids = $wpdb->get_col( $sql ) ) && ! is_wp_error( $term_taxonomy_ids ) ) {
			wp_update_term_count_now( $term_taxonomy_ids, 'nav_menu' );
		}

		wp_cache_flush();
		do_action( 'ideapark_clear_cache' );
		if ( $is_redirect ) {
			wp_redirect( admin_url( 'themes.php?page=ideapark_about' ) );
		}
	}
}

if ( ! function_exists( 'ideapark_set_cookie' ) ) {
	function ideapark_set_cookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, defined( 'COOKIEPATH' ) && COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, $httponly );
		}
	}
}


if ( ! function_exists( 'ideapark_recently_products_shop_only' ) ) {
	function ideapark_recently_products_shop_only() {
		if ( is_shop() ) { ?>
			<div class="container ip-recently-shop-container">
				<div class="row row-flex-desktop">
					<div class="col-sm-12">
						<?php
						ideapark_recently_products();
						?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'ideapark_recently_products' ) ) {
	function ideapark_recently_products() {
		$args       = [];
		$product_id = is_product() ? get_the_ID() : 0;

		if ( $args['recently_products'] = ideapark_recently_get( $product_id ) ) {

			wc_set_loop_prop( 'name', 'recently' );
			wc_set_loop_prop( 'columns', 2 );

			wc_get_template( 'single-product/recently.php', $args );
		}
	}
}

if ( ! function_exists( 'ideapark_recently_get' ) ) {
	function ideapark_recently_get( $exclude_id = 0 ) {
		$list     = [];
		$key      = '_recently_viewed_products';
		$max_size = ideapark_mod( 'recently_product_number' );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$list    = get_user_meta( $user_id, $key, true );
		} elseif ( ! empty( $_COOKIE[ $key ] ) ) {
			$list = json_decode( stripslashes( $_COOKIE[ $key ] ), true );
		}
		if ( ! is_array( $list ) ) {
			$list = [];
		} elseif ( $exclude_id ) {
			if ( in_array( $exclude_id, $list ) ) {
				$list = array_filter( $list, function ( $val ) use ( $exclude_id ) {
					return $val != $exclude_id;
				} );
			}
		}
		if ( sizeof( $list ) > $max_size ) {
			$list = array_slice( $list, 0, $max_size );
		}

		return $list;
	}
}

if ( ! function_exists( 'ideapark_recently_set' ) ) {
	function ideapark_recently_set( $list ) {
		$key = '_recently_viewed_products';
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			update_user_meta( $user_id, $key, $list );
		} else {
			ideapark_set_cookie( $key, json_encode( $list ) );
		}
	}
}

if ( ! function_exists( 'ideapark_recently_add' ) ) {
	function ideapark_recently_add() {
		if ( ideapark_woocommerce_on() && is_product() ) {
			$product_id = get_the_ID();
			$list       = ideapark_recently_get( $product_id );
			array_unshift( $list, $product_id );
			ideapark_recently_set( $list );
		}
	}
}

if ( ! function_exists( 'ideapark_setup_woocommerce' ) ) {
	function ideapark_setup_woocommerce() {

		if ( ( ideapark_is_requset( 'frontend' ) || ideapark_is_elementor_preview() ) && ideapark_woocommerce_on() ) {
			if ( ideapark_is_elementor_preview() ) {
				WC()->frontend_includes();
				WC()->initialize_session();
			}

			if ( ( ! is_admin() || IDEAPARK_THEME_IS_AJAX_SEARCH || ideapark_is_elementor_preview() ) && ( $category_ids = ideapark_hidden_category_ids() ) ) {

				$f = function ( $args ) use ( $category_ids ) {
					$args['exclude'] = array_unique( array_filter( ! empty( $args['exclude'] ) && is_array( $args['exclude'] ) ? array_merge( $args['exclude'], $category_ids ) : ( ! empty( $args['exclude'] ) ? array_merge( [ (int) $args['exclude'] ], $category_ids ) : $category_ids ) ) );
					if ( ! empty( $args['include'] ) ) {
						$args['include'] = array_unique( array_filter( is_array( $args['include'] ) ? array_diff( $args['include'], $category_ids ) : array_diff( [ (int) $args['include'] ], $category_ids ) ) );
					}

					return $args;
				};

				add_filter( 'woocommerce_product_subcategories_args', $f );
				add_filter( 'woocommerce_product_categories_widget_args', $f );
				add_filter( 'woocommerce_product_categories_widget_dropdown_args', $f );

				add_filter( 'woocommerce_product_related_posts_query', function ( $query ) use ( $category_ids ) {
					global $wpdb;
					$query['join']  .= " LEFT JOIN (SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN (" . implode( ',', $category_ids ) . ") ) AS exclude_hidden_join ON exclude_hidden_join.object_id = p.ID ";
					$query['where'] .= " AND exclude_hidden_join.object_id IS NULL";

					return $query;
				} );

				add_action( 'woocommerce_product_query', function ( $q ) use ( $category_ids ) {
					$tax_query   = (array) $q->get( 'tax_query' );
					$tax_query[] = [
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $category_ids,
						'operator' => 'NOT IN'
					];
					$q->set( 'tax_query', $tax_query );

					return $q;
				} );

				add_action( 'woocommerce_shortcode_products_query', function ( $q ) use ( $category_ids ) {
					if ( ! isset( $q['tax_query'] ) || ! is_array( $q['tax_query'] ) ) {
						$q['tax_query'] = [];
					}
					$q['tax_query'][] = [
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $category_ids,
						'operator' => 'NOT IN'
					];

					return $q;
				} );

				add_filter( 'get_terms_args', function ( $params ) use ( $category_ids ) {
					if ( ! is_admin() && $params['taxonomy'] == [ 'product_cat' ] ) {
						$params['exclude'] = implode( ',', $category_ids );
					}

					return $params;
				} );

				add_filter( 'get_the_terms', function ( $terms, $post_ID, $taxonomy ) use ( $category_ids ) {
					if ( $taxonomy == "product_cat" ) {
						foreach ( $terms as $key => $term ) {
							if ( in_array( $term->term_id, $category_ids ) ) {
								unset( $terms[ $key ] );
							}
						}
					}

					return $terms;
				}, 11, 3 );
			}
		}

		if ( ideapark_mod( 'related_product_header' ) ) {
			add_filter( 'woocommerce_product_related_products_heading', function ( $header ) {
				return esc_html( ideapark_mod( 'related_product_header' ) );
			}, 100 );
		}

		if ( ideapark_mod( 'upsells_product_header' ) ) {
			add_filter( 'woocommerce_product_upsells_products_heading', function ( $header ) {
				return esc_html( ideapark_mod( 'upsells_product_header' ) );
			}, 100 );
		}

		if ( ideapark_mod( 'cross_sells_product_header' ) ) {
			add_filter( 'woocommerce_product_cross_sells_products_heading', function ( $header ) {
				return esc_html( ideapark_mod( 'cross_sells_product_header' ) );
			}, 100 );
		}

		if ( ideapark_mod( 'recently_product_header' ) ) {
			add_filter( 'woocommerce_product_recently_products_heading', function ( $header ) {
				return esc_html( ideapark_mod( 'recently_product_header' ) );
			}, 100 );
		}

		if ( ideapark_mod( 'recently_enabled' ) && ideapark_mod( 'recently_product_number' ) ) {
			add_action( 'wp', 'ideapark_recently_add' );
		}

		if ( ideapark_mod( 'recently_enabled' ) && ideapark_mod( 'recently_shop_show' ) && ideapark_mod( 'recently_product_number' ) ) {
			add_action( 'woocommerce_after_main_content', 'ideapark_recently_products_shop_only', 20 );
		}

		if ( ideapark_mod( 'recently_enabled' ) && ideapark_mod( 'recently_product_show' ) && ideapark_mod( 'recently_product_number' ) ) {
			add_action( 'woocommerce_after_single_product_summary', 'ideapark_recently_products', ideapark_mod( 'recently_position' ) == 'above' ? 18 : 21 );
		}

		ideapark_ra( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		add_action( 'woocommerce_after_cart', function () {
			?>
			<div class="row">
				<div class="col-xs-12">
					<?php woocommerce_cross_sell_display(); ?>
				</div>
			</div>
			<?php
		} );

		if ( ideapark_mod( 'recently_enabled' ) && ideapark_mod( 'recently_cart_show' ) && ideapark_mod( 'recently_product_number' ) ) {
			add_action( 'woocommerce_after_cart', 'ideapark_recently_products', ideapark_mod( 'recently_position' ) == 'above' ? 9 : 11 );
		}

		if ( ideapark_mod( 'store_notice_button_text' ) || ideapark_mod( 'store_notice_button_hide' ) ) {
			add_filter( "woocommerce_demo_store", function ( $notice ) {
				if ( ideapark_mod( 'store_notice_button_hide' ) ) {
					return preg_replace( "~<a [^>]*class=\"woocommerce-store-notice__dismiss-link\">[^>]+</a>~", '', $notice );
				} else {
					return preg_replace( "~(dismiss-link\">)([^>]+)(<)~", "\\1" . esc_html( ideapark_mod( 'store_notice_button_text' ) ) . "\\3", $notice );
				}
			} );
		}

		if ( ideapark_mod( 'product_brand_attribute' ) && taxonomy_exists( ideapark_mod( 'product_brand_attribute' ) ) ) {
			if ( ideapark_mod( 'show_product_page_brand' ) ) {
				add_action( 'woocommerce_product_meta_start', 'ideapark_template_brand_meta' );
			}

			if ( ideapark_mod( 'show_cart_page_brand' ) ) {
				add_filter( 'woocommerce_widget_cart_item_quantity', 'ideapark_cart_mini_brand', 1, 3 );
				add_action( 'woocommerce_after_cart_item_name', 'ideapark_cart_brand', 10, 2 );
			}
		}

		add_filter( 'woocommerce_get_breadcrumb', 'ideapark_woocommerce_get_brands_breadcrumb', 10, 2 );

		if ( ideapark_is_requset( 'admin' ) && ideapark_woocommerce_on() ) {
			add_filter( 'manage_product_cat_custom_column', function ( $content, $column_name, $term_id ) {
				if ( $column_name == 'handle' ) {
					$term = get_term( $term_id );
					if ( $term->taxonomy == 'product_cat' ) {
						if ( ideapark_mod( 'hide_uncategorized' ) && $term->term_id == get_option( 'default_product_cat' )
						     ||
						     ideapark_mod( 'hidden_product_category' ) && $term->term_id == ideapark_mod( 'hidden_product_category' )
						) {
							$content .= '<span class="ideapark-hidden-category">' . esc_html__( 'Hidden', 'kidz' ) . '</span>';
						}
					}
				}

				return $content;
			}, 100, 3 );
			add_filter( "product_cat_row_actions", function ( $actions, $term ) {
				if ( $term->taxonomy == 'product_cat' ) {
					if ( ideapark_mod( 'hide_uncategorized' ) && $term->term_id == get_option( 'default_product_cat' ) ) {
						$actions['unhide'] = sprintf( "<a href=\"%s\">%s</a>", admin_url( 'customize.php?autofocus[control]=hide_uncategorized' ), __( 'Unhide', 'kidz' ) );
					}

					if ( ideapark_mod( 'hidden_product_category' ) && $term->term_id == ideapark_mod( 'hidden_product_category' ) ) {
						$actions['unhide'] = sprintf( "<a href=\"%s\">%s</a>", admin_url( 'customize.php?autofocus[control]=hidden_product_category' ), __( 'Unhide', 'kidz' ) );
					}
				}

				return $actions;
			}, 10, 2 );

			add_action( 'woocommerce_product_write_panel_tabs', function () {
				echo '<li class="ideapark_custom_badge_tab"><a href="#ideapark_custom_badge_tab"><span>' . __( 'Custom badge', 'kidz' ) . '</span></a></li>';
			} );
			add_action( 'woocommerce_product_data_panels', 'ideapark_custom_badge_tab_admin' );
			add_action( 'woocommerce_process_product_meta', 'ideapark_custom_badge_tab_save', 10, 2 );
		}
	}
}

if ( ! function_exists( 'ideapark_regenerate_wc_lookup' ) ) {
	function ideapark_regenerate_wc_lookup() {
		if ( class_exists( 'WC_REST_System_Status_Tools_Controller' ) ) {
			try {
				if ( function_exists( 'wc_get_container' ) ) {
					wc_get_container()->get( Automattic\WooCommerce\Internal\ProductAttributesLookup\DataRegenerator::class )->initiate_regeneration();
				}
				$tools_controller = new WC_REST_System_Status_Tools_Controller();
				$tools_controller->execute_tool( 'regenerate_product_lookup_tables' );
				$tools_controller->execute_tool( 'db_update_routine' );
				WC()->queue()->schedule_single(
					time() + 5,
					'ideapark_delete_transient'
				);
				$tools_controller->execute_tool( 'db_update_routine' );
			} catch ( Exception $e ) {
			}
		}
	}
}

if ( ! function_exists( 'ideapark_shy' ) ) {
	function ideapark_shy( $s ) {
		return preg_replace( '~(\S{15})~u', '\\1&shy;', $s );
	}
}


if ( ! function_exists( 'ideapark_template_brand_meta' ) ) {
	function ideapark_template_brand_meta() {
		wc_get_template( 'loop/brand_meta.php' );
	}
}

if ( ! function_exists( 'ideapark_woocommerce_breadcrumbs' ) ) {
	function ideapark_woocommerce_breadcrumbs() {
		return [
			'delimiter'   => '',
			'wrap_before' => '<nav class="c-breadcrumbs"><ol class="c-breadcrumbs__list">',
			'wrap_after'  => '</ol></nav>',
			'before'      => '<li class="c-breadcrumbs__item">',
			'after'       => '</li>',
			'home'        => esc_html_x( 'Home', 'breadcrumb', 'woocommerce' ),
		];
	}
}

if ( ! function_exists( 'ideapark_woocommerce_get_brands_breadcrumb' ) ) {
	function ideapark_woocommerce_get_brands_breadcrumb( $crumbs, $obj ) {
		if ( is_tax() && ideapark_mod( 'brands_page' ) && ideapark_mod( 'product_brand_attribute' ) ) {
			$index = ideapark_mod( 'product_breadcrumbs_home' ) ? 1 : 0;
			if (
				( $this_term = $GLOBALS['wp_query']->get_queried_object() ) &&
				! is_wp_error( $this_term ) &&
				! empty( $crumbs[ $index ] ) &&
				$this_term->taxonomy == ideapark_mod( 'product_brand_attribute' ) &&
				( $page_id = apply_filters( 'wpml_object_id', ideapark_mod( 'brands_page' ), 'any' ) )
			) {
				$crumbs[ $index ][0] = get_the_title( $page_id );
				$crumbs[ $index ][1] = get_permalink( $page_id );
			}
		}

		return $crumbs;
	}
}

if ( ! function_exists( 'ideapark_cart_brand' ) ) {
	function ideapark_cart_brand( $cart_item, $cart_item_mini ) {
		/**
		 * @var $product WC_Product
		 **/
		$attribute = ideapark_mod( 'product_brand_attribute' );
		$product   = $cart_item['data'];
		if ( $parent_id = $product->get_parent_id() ) {
			$product = wc_get_product( $parent_id );
		}
		if ( $name = $product->get_attribute( $attribute ) ) {
			echo ideapark_wrap( $name, '<div class="c-cart__shop-brand">', '</div>' );
		}
	}
}

if ( ! function_exists( 'ideapark_cart_mini_brand' ) ) {
	function ideapark_cart_mini_brand( $html, $cart_item, $cart_item_mini ) {
		/**
		 * @var $product WC_Product
		 **/
		$attribute = ideapark_mod( 'product_brand_attribute' );
		$product   = $cart_item['data'];
		if ( $parent_id = $product->get_parent_id() ) {
			$product = wc_get_product( $parent_id );
		}
		if ( $name = $product->get_attribute( $attribute ) ) {
			$html = ideapark_wrap( $name, '<div class="c-product-list-widget__brand">', '</div>' ) . $html;
		}

		return $html;
	}
}

if ( ! function_exists( 'ideapark_grid_brand' ) ) {
	function ideapark_grid_brand() { ?>
		<?php
		/**
		 * @var $product WC_Product
		 **/
		global $product;

		$separator  = '<span class="h-bullet"></span>';
		$categories = [];
		$brands     = [];

		if ( ideapark_mod( 'shop_category' ) ) {
			$term_ids = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
			foreach ( $term_ids as $term_id ) {
				$categories[] = get_term_by( 'id', $term_id, 'product_cat' );
			}
		}

		if ( ideapark_mod( 'show_product_grid_brand' ) ) {
			if ( $terms = ideapark_brands() ) {
				foreach ( $terms as $term ) {
					$brands[] = '<a class="c-product-grid__category-item c-product-grid__category-item--brand" href="' . esc_url( get_term_link( $term->term_id, ideapark_mod( 'product_brand_attribute' ) ) ) . '">' . esc_html( $term->name ) . '</a>';
				}
				$brands = array_filter( $brands );
			}
		}

		if ( $categories || $brands ) { ?>
			<div class="c-product-grid__category-list">
				<?php
				if ( $categories ) {
					ideapark_category( $separator, $categories, 'c-product-grid__category-item' );
				}
				if ( $brands ) {
					echo ideapark_wrap( $categories ? ideapark_wrap( $separator ) : '' ) . implode( $separator, $brands );
				}
				?>
			</div>
		<?php }
	}
}

if ( ! function_exists( 'ideapark_brands' ) ) {
	function ideapark_brands() {
		global $product;
		if (
			( $brand_taxonomy = ideapark_mod( 'product_brand_attribute' ) ) &&
			( $attributes = $product->get_attributes() ) &&
			is_array( $attributes ) &&
			array_key_exists( $brand_taxonomy, $attributes ) &&
			is_object( $attributes[ $brand_taxonomy ] )
		) {
			$terms = $attributes[ $brand_taxonomy ]->get_terms();

			return is_wp_error( $terms ) ? [] : $terms;
		}
	}
}

if ( ! function_exists( 'ideapark_get_sale_percentage' ) ) {
	function ideapark_get_sale_percentage( $product ) {
		$percentage = false;

		if ( $product->is_type( 'variable' ) ) {
			$percentages = [];

			$prices = $product->get_variation_prices();

			foreach ( $prices['price'] as $key => $price ) {
				if ( $prices['regular_price'][ $key ] !== $price && $prices['regular_price'][ $key ] > 0 ) {
					$percentages[] = round( 100 - ( floatval( $prices['sale_price'][ $key ] ) / floatval( $prices['regular_price'][ $key ] ) * 100 ) );
				}
			}
			if ( $percentages ) {
				$percentage = max( $percentages );
			}

		} elseif ( $product->is_type( 'grouped' ) ) {
			$percentages = [];

			$children_ids = $product->get_children();

			foreach ( $children_ids as $child_id ) {
				if ( $child_product = wc_get_product( $child_id ) ) {

					$regular_price = (float) $child_product->get_regular_price();
					$sale_price    = (float) $child_product->get_sale_price();

					if ( ( $sale_price != 0 || ! empty( $sale_price ) && $regular_price > 0 ) ) {
						$percentages[] = round( 100 - ( $sale_price / $regular_price * 100 ) );
					}
				}
			}
			if ( $percentages ) {
				$percentage = max( $percentages );
			}

		} else {
			$regular_price = (float) $product->get_regular_price();
			$sale_price    = (float) $product->get_sale_price();

			if ( ( $sale_price != 0 || ! empty( $sale_price ) ) && $regular_price > 0 ) {
				$percentage = round( 100 - ( $sale_price / $regular_price * 100 ) );
			}
		}

		return $percentage;
	}
}

if ( ! function_exists( 'ideapark_hidden_category_ids' ) ) {
	function ideapark_hidden_category_ids() {
		static $cache;
		global $wpdb;

		if ( ! is_null( $cache ) ) {
			return $cache;
		}

		$main_category_ids = [];

		if ( $category_id = ideapark_mod( 'hidden_product_category' ) ) {
			$main_category_ids[] = $category_id;
		}

		if ( ideapark_mod( 'hide_uncategorized' ) && ( $category_id = get_option( 'default_product_cat' ) ) ) {
			$main_category_ids[] = $category_id;
		}

		if ( empty( $wpdb->wc_category_lookup ) ) {
			$wpdb->wc_category_lookup = $wpdb->prefix . 'wc_category_lookup';
		}

		if ( $main_category_ids ) {
			$_category_ids = $wpdb->get_col( "SELECT category_id FROM  {$wpdb->wc_category_lookup} WHERE category_tree_id IN (" . implode( ',', $main_category_ids ) . ")" );
			$category_ids  = array_merge( array_unique( $main_category_ids ), array_map( function ( $category_id ) {
				return apply_filters( 'wpml_object_id', $category_id, 'product_cat', true );
			}, $_category_ids ) );
		} else {
			$category_ids = [];
		}

		return $cache = array_filter( array_unique( $category_ids ) );
	}
}

if ( ! function_exists( 'ideapark_custom_badge_tab_admin' ) ) {
	function ideapark_custom_badge_tab_admin() {
		global $post;

		$_tab_title    = get_post_meta( $post->ID, 'ideapark_custom_badge_tab_title', true ) ?: '';
		$_tab_color    = get_post_meta( $post->ID, 'ideapark_custom_badge_tab_color', true ) ?: '';
		$_tab_bg_color = get_post_meta( $post->ID, 'ideapark_custom_badge_tab_bg_color', true ) ?: '';

		?>
		<div id="ideapark_custom_badge_tab" class="panel wc-metaboxes-wrapper woocommerce_options_panel">
			<div class="options_group">
				<p class="form-field">
					<label for="ideapark_custom_badge_tab_title"><?php esc_html_e( 'Title', 'kidz' ); ?></label>
					<input
						type="text" class="short" name="ideapark_custom_badge_tab_title"
						id="ideapark_custom_badge_tab_title" value="<?php echo esc_attr( $_tab_title ); ?>">
				</p>
				<p class="form-field">
					<label for="ideapark_custom_badge_tab_color"><?php esc_html_e( 'Text Color', 'kidz' ); ?></label>
					<input
						type="text" class="short color-picker" name="ideapark_custom_badge_tab_color"
						data-alpha-enabled="true"
						data-alpha-color-type="hex"
						id="ideapark_custom_badge_tab_color" value="<?php echo esc_attr( $_tab_color ); ?>">
				</p>
				<p class="form-field">
					<label for="ideapark_custom_badge_tab_bg_color"><?php esc_html_e( 'Background Color', 'kidz' ); ?></label>
					<input
						type="text" class="short color-picker" name="ideapark_custom_badge_tab_bg_color"
						data-alpha-enabled="true"
						data-alpha-color-type="hex"
						id="ideapark_custom_badge_tab_bg_color" value="<?php echo esc_attr( $_tab_bg_color ); ?>">
				</p>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ideapark_custom_badge_tab_save' ) ) {
	function ideapark_custom_badge_tab_save( $post_id, $post ) {

		if ( empty( $post_id ) ) {
			return;
		}

		foreach ( [ 'ideapark_custom_badge_tab_title', 'ideapark_custom_badge_tab_color', 'ideapark_custom_badge_tab_bg_color' ] as $field_name ) {
			$value = ! empty( $_POST[ $field_name ] ) ? wp_unslash( $_POST[ $field_name ] ) : '';

			if ( $value ) {
				update_post_meta( $post_id, $field_name, $value );
				if ( $field_name == 'ideapark_custom_badge_tab_title' ) {
					do_action( 'wpml_register_single_string', IDEAPARK_THEME_SLUG, 'Custom badge title - ' . $value, $value );
				}
			} else {
				delete_post_meta( $post_id, $field_name );
			}
		}
	}
}

if ( ! function_exists( 'ideapark_disable_native_wc_brands' ) ) {
	function ideapark_disable_native_wc_brands() {
		if ( get_option( 'wc_feature_woocommerce_brands_enabled', '' ) === '' ) {
			update_option( 'wc_feature_woocommerce_brands_enabled', 'no' );
			update_option( IDEAPARK_THEME_SLUG . '_flush_rewrite_rules', 'yes' );
		}
	}

	add_action( 'after_switch_theme', 'ideapark_disable_native_wc_brands', 2 );
	add_action( 'after_update_theme_late', 'ideapark_disable_native_wc_brands', 2 );
}

/*------------------------------------*\
	Actions + Filters
\*------------------------------------*/

if ( ! function_exists( 'ideapark_ajax_add_to_cart' ) ) {
	function ideapark_ajax_add_to_cart() {
		ideapark_mod_set_temp( '_get_notice', true );
		WC_AJAX::get_refreshed_fragments();
	}

	add_action( 'wc_ajax_ip_add_to_cart', 'ideapark_ajax_add_to_cart' );
	add_action( 'wc_ajax_nopriv_ip_add_to_cart', 'ideapark_ajax_add_to_cart' );
}

if ( IDEAPARK_THEME_IS_AJAX_SEARCH ) {
	add_filter( 'posts_clauses', 'ideapark_product_search_sku', 100, 2 );
	add_filter( 'posts_clauses', 'ideapark_search_product_tag', 100, 2 );
	add_action( 'wp_ajax_ideapark_ajax_search', 'ideapark_ajax_search' );
	add_action( 'wp_ajax_nopriv_ideapark_ajax_search', 'ideapark_ajax_search' );
	add_filter( 'wcml_multi_currency_ajax_actions', function ( $actions ) {
		$actions[] = 'ideapark_ajax_search';

		return $actions;
	} );
} else {
	add_action( 'wp_head', 'ideapark_pingback_header' );
	add_action( 'wp_footer', 'ideapark_wp_footer' );
	add_action( 'widgets_init', 'ideapark_widgets_init' );
	add_action( 'wp_loaded', 'ideapark_disable_block_editor', 20 );
	add_action( 'wp_loaded', 'ideapark_hide_sku', 99 );
	add_action( 'wp_loaded', 'ideapark_disable_purchase', 99 );
	add_action( 'wp_loaded', 'ideapark_paging', 99 );
	add_action( 'wp_loaded', 'ideapark_setup_woocommerce', 99 );
	add_action( 'wp_loaded', function () {
		if ( ideapark_mod( 'description_tab_header' ) ) {
			add_filter( 'woocommerce_product_description_tab_title', function () {
				return ideapark_mod( 'description_tab_header' );
			} );
		}

		if ( ideapark_mod( 'additional_information_tab_header' ) ) {
			add_filter( 'woocommerce_product_additional_information_tab_title', function () {
				return ideapark_mod( 'additional_information_tab_header' );
			} );
		}
	}, 99 );

	add_action( 'after_switch_theme', 'ideapark_set_image_dimensions', 1 );
	add_action( 'admin_init', 'ideapark_set_image_dimensions', 1000 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'ideapark_woocommerce_show_product_loop_new_badge', 30 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'ideapark_stock_badge', 35 );
	add_action( 'admin_enqueue_scripts', 'ideapark_admin_scripts' );
	add_action( 'customize_controls_enqueue_scripts', 'ideapark_admin_scripts' );
	add_action( 'wp_enqueue_scripts', 'ideapark_scripts_disable_cf7', 9 );
	add_action( 'wp_enqueue_scripts', 'ideapark_scripts', 99 );
	add_action( 'wp_enqueue_scripts', 'ideapark_scripts_load', 999 );
	add_action( 'wp_head', 'ideapark_sprite_loader' );
	add_action( 'wp_head', 'ideapark_header_metadata' );
	add_action( 'wp_ajax_ideapark_ajax_custom_css', 'ideapark_ajax_custom_css' );
	add_action( 'ideapark_delete_transient', function () {
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '\_transient\_%' OR option_name LIKE '\_site\_transient\_%'" );
	} );

	add_filter( 'woocommerce_get_price_html', function ( $price, $product ) {
		if ( ideapark_mod( 'hide_variable_price_range' ) && $product->is_type( 'variable' ) && is_product() ) {
			return '';
		}

		return $price;
	}, 10, 2 );

	add_filter( 'woocommerce_show_variation_price', function ( $condition ) {
		if ( ideapark_mod( 'hide_variable_price_range' ) ) {
			return true;
		}

		return $condition;
	}, 10 );

	add_filter( 'body_class', 'ideapark_body_class' );
	add_filter( "theme_mod_background_image", 'ideapark_disable_background_image', 10, 1 );
	add_filter( 'get_search_form', 'ideapark_search_form', 10 );
	add_filter( 'excerpt_more', 'ideapark_excerpt_more' );
	add_filter( 'excerpt_length', 'ideapark_custom_excerpt_length', 999 );
	add_filter( 'upgrader_package_options', 'ideapark_override_theme' );

	add_filter( 'woo_variation_swatches_product_data_tab', '__return_false' );
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
	add_filter( 'woocommerce_add_to_cart_fragments', 'ideapark_header_add_to_cart_fragment' );
	add_filter( 'woocommerce_cart_hash', 'ideapark_header_add_to_cart_hash' );
	add_filter( 'woocommerce_breadcrumb_defaults', 'ideapark_woocommerce_breadcrumbs' );
	add_filter( 'woocommerce_account_menu_items', 'ideapark_woocommerce_account_menu_items' );
	add_filter( 'woocommerce_product_description_heading', 'ideapark_remove_product_description_heading' );
	add_action( 'woocommerce_archive_description', 'ideapark_woocommerce_archive_description' );
	add_filter( 'woocommerce_cart_item_thumbnail', 'ideapark_woocommerce_cart_item_thumbnail', 10, 3 );
	add_filter( 'woocommerce_loop_add_to_cart_link', 'ideapark_loop_add_to_cart_link', 99, 3 );
	add_filter( 'woocommerce_gallery_image_size', 'ideapark_woocommerce_gallery_image_size', 99, 1 );
	add_filter( 'woocommerce_product_tabs', 'ideapark_woocommerce_product_tabs', 11 );
	add_filter( 'woocommerce_demo_store', 'ideapark_woocommerce_demo_store' );
	add_filter( 'subcategory_archive_thumbnail_size', 'ideapark_subcategory_archive_thumbnail_size', 99, 1 );
	add_filter( 'get_the_generator_html', 'ideapark_generator_tag', 10, 2 );
	add_filter( 'get_the_generator_xhtml', 'ideapark_generator_tag', 10, 2 );
	add_filter( 'posts_clauses', 'ideapark_product_search_sku', 100, 2 );
	add_filter( 'posts_clauses', 'ideapark_search_product_tag', 100, 2 );
	add_filter( 'product_cat_class', 'ideapark_fix_product_category_class' );
	add_filter( 'should_load_separate_core_block_assets', '__return_true' );
	add_filter( 'woocommerce_cross_sells_total', function () {
		return 4;
	} );
	add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
		$args['posts_per_page'] = (int) ideapark_mod( 'related_product_number' );

		return $args;
	}, 100 );
	if ( function_exists( 'yoast_get_primary_term_id' ) ) {
		add_filter( 'woocommerce_breadcrumb_main_term', function ( $main_term, $terms ) {
			$primary_category_id = yoast_get_primary_term_id( 'product_cat', get_the_ID() );
			foreach ( $terms as $term ) {
				if ( $primary_category_id === $term->term_id ) {
					return $term;
				}
				if ( $primary_category_id === $term->parent ) {
					return $term;
				}
			}

			return $main_term;
		}, 10, 2 );
	}
}

add_action( 'after_setup_theme', 'ideapark_init_filesystem', 0 );
add_action( 'after_setup_theme', 'ideapark_check_version', 1 );
add_action( 'after_setup_theme', 'ideapark_setup' );

ideapark_ra( 'init', [ 'WC_Regenerate_Images', 'init' ] );
add_filter( 'woocommerce_resize_images', '__return_false' );

add_action( 'init', 'ideapark_custom_woocommerce_thumbnail' );

add_filter( 'lazyload_is_enabled', '__return_false' ); // disable JetPack images Lazy load
add_filter( 'do_rocket_lazyload', '__return_false' ); // disable WPRocket images Lazy load
add_filter( 'pre_option_elementor_use_mini_cart_template', function () {
	return 'no';
} );
add_filter( 'pre_option_siteground_optimizer_lazyload_images', '__return_empty_string' );

if ( ! defined( 'LITESPEED_CONF' ) ) {
	define( 'LITESPEED_CONF', true );
	define( 'LITESPEED_CONF__MEDIA_LAZY', false );
}

add_filter( 'loop_shop_per_page', function () {
	return ideapark_mod( 'products_per_page' );
} );
add_action( 'after_update_theme_late', function () {
	delete_transient( 'wc_system_status_theme_info' );
} );
add_action( 'woocommerce_page_wc-status', function () { // Fix WooCommerce bug
	if ( ! class_exists( 'WC_Plugin_Updates' ) && ideapark_is_file( WP_PLUGIN_DIR . '/woocommerce/includes/admin/plugin-updates/class-wc-plugin-updates.php' ) ) {
		include_once WP_PLUGIN_DIR . '/woocommerce/includes/admin/plugin-updates/class-wc-plugin-updates.php';
	}
}, 1 );
add_filter( 'woocommerce_create_pages', function ( $data ) {
	$data['cart']['content']     = '<!-- wp:woocommerce/classic-shortcode {"shortcode":"cart"} /-->';
	$data['checkout']['content'] = '<!-- wp:woocommerce/classic-shortcode {"shortcode":"checkout"} /-->';

	return $data;
} );
add_filter( 'hidden_meta_boxes', function ( $hidden, $screen, $use_defaults ) {
	if ( $screen->id == 'nav-menus' ) {
		$hidden = array_diff( $hidden, [ 'add-product_cat' ] );
	}

	return $hidden;
}, 99, 3 );








// تبسيط صفحة الـ Checkout
add_filter('woocommerce_checkout_fields', function ($fields) {

    // 🟢 الاسم
    $fields['billing']['billing_first_name']['label'] = 'الاسم';
    $fields['billing']['billing_first_name']['placeholder'] = 'اكتب اسمك';

    // 🟢 رقم الجوال
    $fields['billing']['billing_phone']['label'] = 'رقم الجوال';
    $fields['billing']['billing_phone']['placeholder'] = '05XXXXXXXX';

    // 🟢 المدينة
    $fields['billing']['billing_city']['label'] = 'المدينة';

    // 🟢 العنوان
    $fields['billing']['billing_address_1']['label'] = 'العنوان';
    $fields['billing']['billing_address_1']['placeholder'] = 'اسم الشارع / الحي';

    // ❌ حذف الحقول
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_address_2']);

    // ❌ الإيميل اختياري
    $fields['billing']['billing_email']['required'] = false;

    // 🔢 ترتيب الحقول
    $fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_phone']['priority'] = 20;
    $fields['billing']['billing_city']['priority'] = 30;
    $fields['billing']['billing_address_1']['priority'] = 40;

    return $fields;
});


// إلغاء validation الزائد
add_filter('woocommerce_default_address_fields', function ($fields) {
    $fields['postcode']['required'] = false;
    return $fields;
});

add_filter('woocommerce_cart_needs_shipping_address', '__return_false');