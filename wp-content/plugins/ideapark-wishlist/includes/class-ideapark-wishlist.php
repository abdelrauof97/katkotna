<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ideapark_Wishlist {

	/**
	 * The single instance of Ideapark_Wishlist.
	 * @var    object
	 * @access   private
	 * @since    1.0.0
	 */
	private static $_instance = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Wishlist cookie name
	 */
	private $cookie_name = 'ip-wishlist-items';

	/**
	 * Wishlist product ids
	 */

	private $wishlist_ids = array();

	/**
	 * Guest mode
	 */

	private $view_mode = false;

	private $templates_dir = '';

	/**
	 * Constructor function.
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token   = 'ideapark_wishlist';

		// Load plugin environment variables
		$this->file          = $file;
		$this->dir           = dirname( $this->file );
		$this->templates_dir = trailingslashit( $this->dir ) . 'templates';
		$this->assets_dir    = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url    = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Handle localisation
		$this->load_plugin_textdomain();

		// Register Ajax functions
		add_action( 'wp_ajax_ip_wishlist_toggle', array( $this, 'toggle' ) );
		add_action( 'wp_ajax_nopriv_ip_wishlist_toggle', array( $this, 'toggle' ) );

		// Wishlist shortcode
		add_shortcode( 'ip_wishlist', array( $this, 'wishlist' ) );

		if ( isset( $_GET['ip_wishlist_share'] ) && ! empty( $_GET['ip_wishlist_share'] ) ) {
			$this->wishlist_ids = $this->get_share_url_ids();
			$this->view_mode    = true;
		} else {
			$this->wishlist_ids = $this->get_products_cookie();
			$this->view_mode    = false;
		}

	} // End __construct ()

	/**
	 * Get wishlist button code
	 * @return string
	 */
	function button( $with_label = false ) {
		global $product;

		$button_class = '';
		$title        = null;

//		if ( in_array( $product->get_id(), $this->wishlist_ids ) ) {
//			$button_class = ' added';
//		}

		$output = '<a href="" onclick="return false;" class="ip-wishlist-btn ip-wishlist-item-' . esc_attr( $product->get_id() ) . '-btn' . $button_class . '" data-product-id="' . esc_attr( $product->get_id() ) . '" data-title="' . __( 'Wishlist', 'ideapark-wishlist' ) . '" aria-label="' . __( 'Wishlist', 'ideapark-wishlist' ) . '"><svg class="on"><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-wishlist-on" /></svg><svg class="off"><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-wishlist-off" /></svg>' . ( $with_label === true ? ' ' . '<span class="on">' . __( 'Remove from Wishlist', 'ideapark-wishlist' ) . '</span><span class="off">' . __( 'Add to Wishlist', 'ideapark-wishlist' ) . '</span>' : ( $with_label ? $with_label : '' ) ) . '</a>';

		echo $output;
	}

	/**
	 * Shortcode: Wishlist
	 */
	function wishlist() {
		if ( class_exists( 'WooCommerce' ) ) {
			include( $this->templates_dir . '/wishlist.php' );
			wp_reset_postdata();
		}
	}

	/**
	 * Get wishlist id's
	 * @return array
	 */
	public function ids() {
		return $this->wishlist_ids;
	}

	/**
	 * Get view_mode
	 * @return array
	 */
	public function view_mode() {
		return $this->view_mode;
	}

	/**
	 * AJAX: Add/remove product from wishlist
	 */
	function toggle() {
		$return_data = array();
		$product_id  = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : null;

		if ( $product_id ) {
			$wishlist_ids = $this->get_products_cookie();

			if ( in_array( $product_id, $wishlist_ids ) ) {
				$wishlist_ids          = array_diff( $wishlist_ids, array( $product_id ) );
				$return_data['status'] = "0";
			} else {
				$wishlist_ids[]        = $product_id;
				$return_data['status'] = "1";
			}

			$this->set_products_cookie( $wishlist_ids );

			$return_data['count'] = count( $wishlist_ids );

			if ( $page_id = ideapark_mod( 'wishlist_page' ) ) {
				$return_data['share_link'] = get_permalink( $page_id ) . ( strpos( get_permalink( $page_id ), '?' ) === false ? '?' : '&' ) . 'ip_wishlist_share=' . implode( ',', $wishlist_ids );
			}
		}

		echo json_encode( $return_data );
		exit;
	}

	/**
	 * Set products cookie
	 */
	function set_products_cookie( $wishlist_ids = array() ) {
		$wishlist_ids_json = json_encode( stripslashes_deep( $wishlist_ids ) );
		wc_setcookie( $this->cookie_name, $wishlist_ids_json, time() + 60 * 60 * 24 * 30, false );
	}

	/**
	 * Get products cookie
	 * @return array
	 */
	private function get_products_cookie() {
		if ( isset( $_COOKIE[ $this->cookie_name ] ) ) {
			return json_decode( stripslashes( $_COOKIE[ $this->cookie_name ] ), true );
		}

		return array();
	}

	/**
	 * Get wishlist share URL id's
	 * @return array
	 */
	private function get_share_url_ids() {
		$wishlist_ids = array();
		$e            = explode( ',', $_GET['ip_wishlist_share'] );
		foreach ( $e as $id ) {
			if ( (int) $id > 0 ) {
				$wishlist_ids[] = (int) $id;
			}
		}

		return $wishlist_ids;
	}


	/**
	 * Load frontend Javascript.
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-frontend' );

		wp_localize_script( $this->_token . '-frontend', 'ip_wishlist_vars', array(
			'cookieName'  => $this->cookie_name,
			'titleAdd'    => __( 'Add to Wishlist', 'ideapark-wishlist' ),
			'titleRemove' => __( 'Remove from Wishlist', 'ideapark-wishlist' )
		) );
	} // End enqueue_scripts ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */

	public function load_plugin_textdomain() {
		$domain = 'ideapark-wishlist';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		if ( ! load_textdomain( $domain, get_template_directory() . '/languages/' . $domain . '-' . $locale . '.mo' ) ) {
			load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
		}
	} // End load_plugin_textdomain ()


	/**
	 * Main Ideapark_Wishlist Instance
	 *
	 * Ensures only one instance of Ideapark_Wishlist is loaded or can be loaded.
	 *
	 * @return Main Ideapark_Wishlist instance
	 * @see   Ideapark_Wishlist()
	 * @since 1.0.0
	 * @static
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ideapark-wishlist' ), 'ideapark-wishlist' );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ideapark-wishlist' ), 'ideapark-wishlist' );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function install() {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	private function _log_version_number() {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
