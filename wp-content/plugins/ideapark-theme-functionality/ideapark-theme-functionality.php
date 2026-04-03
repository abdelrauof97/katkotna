<?php
/*
 * Plugin Name: Kidz Core
 * Version: 5.25
 * Description: Core plugin for Kidz theme.
 * Author: parkofideas.com
 * Author URI: http://parkofideas.com
 * Text Domain: ideapark-theme-functionality
 * Domain Path: /lang/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IDEAPARK_THEME_FUNC_VERSION', '5.25' );

$theme_obj = wp_get_theme();

if ( empty( $theme_obj ) || strtolower( $theme_obj->get( 'TextDomain' ) ) != 'kidz' && strtolower( $theme_obj->get( 'TextDomain' ) ) != 'kidz-child' ) {

	add_filter( 'plugin_row_meta', function ( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = [
				'warning' => '<b style="vertical-align:middle;display:inline-flex;align-items:center;border:solid 2px #dc3545;padding: 2px 10px;color: #dc3545"><span class="dashicons dashicons-warning" style="margin-right: 5px;"></span>' . esc_html__( 'The Kidz theme is not activated! This plugin works only with Kidz theme', 'ideapark-theme-functionality' ) . '</b>',
			];

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}, 10, 2 );

	return;
}

if ( ! empty( $theme_obj ) && version_compare( IDEAPARK_THEME_FUNC_VERSION, $theme_obj->parent() ? $theme_obj->parent()->get( 'Version' ) : $theme_obj->get( 'Version' ), '!=' ) ) {

	add_filter( 'plugin_row_meta', function ( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$row_meta = [
				'warning' => '<b style="vertical-align:middle;display:inline-flex;align-items:center;border:solid 2px #dc3545;padding: 2px 10px;color: #dc3545;"><span class="dashicons dashicons-warning" style="margin-right: 5px;"></span>' . sprintf( esc_html__( 'The Luciana theme version and the theme core plugin version must be the same. Please update the plugin to version %s', 'ideapark-theme-functionality' ), IDEAPARK_THEME_VERSION ) . '</b>',
			];

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}, 10, 2 );
}

$ip_dir = dirname( __FILE__ );

require_once( $ip_dir . '/importer/importer.php' );
require_once( $ip_dir . '/includes/svg-support.php' );
require_once( $ip_dir . '/includes/class-ideapark-theme-functionality.php' );
require_once( $ip_dir . '/includes/lib/class-ideapark-theme-functionality-admin-api.php' );
require_once( $ip_dir . '/includes/lib/class-ideapark-theme-functionality-post-type.php' );
require_once( $ip_dir . '/includes/variation-gallery/class-ideapark-variation-gallery.php' );

if ( ! function_exists( 'Ideapark_Theme_Functionality' ) ) {
	function Ideapark_Theme_Functionality() {
		$instance = Ideapark_Theme_Functionality::instance( __FILE__, IDEAPARK_THEME_FUNC_VERSION );

		return $instance;
	}

	Ideapark_Theme_Functionality();
}

if ( ! function_exists( 'Ideapark_Importer' ) ) {
	function Ideapark_Importer() {
		$instance = Ideapark_Importer::instance( __FILE__, IDEAPARK_THEME_FUNC_VERSION );

		return $instance;
	}

	Ideapark_Importer();
}

if ( ! function_exists( 'ideapark_theme_functionality_widgets_init' ) ) {
	function ideapark_theme_functionality_widgets_init() {
		$ip_dir = dirname( __FILE__ );
		include_once( $ip_dir . "/widgets/latest-posts-widget.php" );
		include_once( $ip_dir . "/widgets/advantages-widget.php" );
		if ( class_exists( 'WC_Widget' ) ) {
			include_once( $ip_dir . "/widgets/wc-color-filter-widget.php" );
			include_once( $ip_dir . "/widgets/wc-attribute-filter-widget.php" );
			include_once( $ip_dir . "/widgets/wc-product-categories-widget.php" );
		}
	}

	add_action( 'widgets_init', 'ideapark_theme_functionality_widgets_init' );
}

if ( ! function_exists( 'ideapark_theme_functionality_init_custom_post_types' ) ) {
	function ideapark_theme_functionality_init_custom_post_types() {
		Ideapark_Theme_Functionality()->register_post_type(
			'slider',
			esc_html__( 'Slides', 'ideapark-theme-functionality' ),
			esc_html__( 'Slide', 'ideapark-theme-functionality' ),
			'Home Page Slider',
			[
				'menu_icon'           => 'dashicons-slides',
				'public'              => true,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => 4,
				'capability_type'     => 'post',
				'supports'            => [ 'title', 'thumbnail' ],
				'has_archive'         => true,
				'query_var'           => false,
				'can_export'          => true,
			] );

		Ideapark_Theme_Functionality()->register_post_type(
			'banner',
			esc_html__( 'Banners', 'ideapark-theme-functionality' ),
			esc_html__( 'Banner', 'ideapark-theme-functionality' ),
			'Home Page Banners',
			[
				'menu_icon'           => 'dashicons-images-alt2',
				'public'              => true,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => 4,
				'capability_type'     => 'post',
				'supports'            => [ 'title', 'thumbnail' ],
				'has_archive'         => true,
				'query_var'           => false,
				'can_export'          => true,
			] );

		Ideapark_Theme_Functionality()->register_post_type(
			'brand',
			esc_html__( 'Brands', 'ideapark-theme-functionality' ),
			esc_html__( 'Brand', 'ideapark-theme-functionality' ),
			'Home Page Brands',
			[
				'menu_icon'           => 'dashicons-images-alt',
				'public'              => true,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => 4,
				'capability_type'     => 'post',
				'supports'            => [ 'title', 'thumbnail' ],
				'has_archive'         => true,
				'query_var'           => false,
				'can_export'          => true,
			] );

		Ideapark_Theme_Functionality()->register_post_type(
			'review',
			esc_html__( 'Reviews', 'ideapark-theme-functionality' ),
			esc_html__( 'Review', 'ideapark-theme-functionality' ),
			'Home Page Reviews',
			[
				'menu_icon'           => 'dashicons-editor-quote',
				'public'              => true,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => 4,
				'capability_type'     => 'post',
				'supports'            => [ 'title', 'thumbnail', 'excerpt' ],
				'has_archive'         => true,
				'query_var'           => false,
				'can_export'          => true,
			] );


		Ideapark_Theme_Functionality()->set_sorted_post_types( [ 'slider', 'banner', 'brand', 'review' ] );
	}

	add_action( 'after_setup_theme', 'ideapark_theme_functionality_init_custom_post_types' );
}

if ( ! function_exists( 'ideapark_theme_functionality_add_meta_box' ) ) {
	function ideapark_theme_functionality_add_meta_box() {
		Ideapark_Theme_Functionality()->admin->add_meta_box( 'ideapark_metabox_slider_details', esc_html__( 'Slider details', 'ideapark-theme-functionality' ), [ "slider" ] );
		Ideapark_Theme_Functionality()->admin->add_meta_box( 'ideapark_metabox_banner_details', esc_html__( 'Banner details', 'ideapark-theme-functionality' ), [ "banner" ] );
		Ideapark_Theme_Functionality()->admin->add_meta_box( 'ideapark_metabox_brand_details', esc_html__( 'Brand details', 'ideapark-theme-functionality' ), [ "brand" ] );
		Ideapark_Theme_Functionality()->admin->add_meta_box( 'ideapark_metabox_review_details', esc_html__( 'Review details', 'ideapark-theme-functionality' ), [ "review" ] );
	}

	add_action( 'add_meta_boxes', 'ideapark_theme_functionality_add_meta_box' );
}

if ( ! function_exists( 'ideapark_slider_add_custom_fields' ) ) {
	function ideapark_slider_add_custom_fields() {
		$fields   = [];
		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_slider_details"
			],
			'id'      => "_ip_slider_subheader",
			'label'   => esc_html__( 'Subheader', 'ideapark-theme-functionality' ),
			'type'    => 'text',
		];
		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_slider_details"
			],
			'id'      => "_ip_slider_link",
			'label'   => esc_html__( 'Link', 'ideapark-theme-functionality' ),
			'type'    => 'url',
		];
		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_slider_details"
			],
			'id'      => "_ip_slider_color",
			'label'   => esc_html__( 'Text Color', 'ideapark-theme-functionality' ),
			'type'    => 'color',
			'default' => ''
		];
		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_slider_details"
			],
			'id'      => "_ip_slider_mobile_image",
			'label'   => esc_html__( 'Custom mobile image (by default, the featured image is used)', 'ideapark-theme-functionality' ),
			'type'    => 'image',
			'default' => ''
		];

		return $fields;
	}

	add_filter( "slider_custom_fields", "ideapark_slider_add_custom_fields" );
}

if ( ! function_exists( 'ideapark_home_banner_add_custom_fields' ) ) {
	function ideapark_home_banner_add_custom_fields() {
		$fields = [];

		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_banner_details"
			],
			'id'      => "_ip_banner_hide_title",
			'label'   => esc_html__( 'Hide Title', 'ideapark-theme-functionality' ),
			'type'    => 'checkbox',
		];

		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_banner_details"
			],
			'id'      => "_ip_banner_stretch_image",
			'label'   => esc_html__( 'Stretch Image', 'ideapark-theme-functionality' ),
			'type'    => 'checkbox',
		];

		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_banner_details"
			],
			'id'      => "_ip_banner_price",
			'label'   => esc_html__( 'Price', 'ideapark-theme-functionality' ),
			'type'    => 'text',
		];

		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_banner_details"
			],
			'id'      => "_ip_banner_button_text",
			'label'   => esc_html__( 'Button Text', 'ideapark-theme-functionality' ),
			'type'    => 'text',
			'default' => 'Shop Now'
		];

		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_banner_details"
			],
			'id'      => "_ip_banner_button_link",
			'label'   => esc_html__( 'Button Link', 'ideapark-theme-functionality' ),
			'type'    => 'url',
		];

		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_banner_details"
			],
			'id'      => "_ip_banner_color",
			'label'   => esc_html__( 'Accent Color', 'ideapark-theme-functionality' ),
			'type'    => 'color',
			'default' => '#5DACF5'
		];

		return $fields;
	}

	add_filter( "banner_custom_fields", "ideapark_home_banner_add_custom_fields" );
}

if ( ! function_exists( 'ideapark_home_brand_add_custom_fields' ) ) {
	function ideapark_home_brand_add_custom_fields() {
		$fields   = [];
		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_brand_details"
			],
			'id'      => "_ip_brand_link",
			'label'   => esc_html__( 'Link', 'ideapark-theme-functionality' ),
			'type'    => 'url',
		];

		return $fields;
	}

	add_filter( "brand_custom_fields", "ideapark_home_brand_add_custom_fields" );
}

if ( ! function_exists( 'ideapark_home_review_add_custom_fields' ) ) {
	function ideapark_home_review_add_custom_fields() {
		$fields   = [];
		$fields[] = [
			"metabox" => [
				'name' => "ideapark_metabox_review_details"
			],
			'id'      => "_ip_review_occupation",
			'label'   => esc_html__( 'Occupation', 'ideapark-theme-functionality' ),
			'type'    => 'text',
		];

		return $fields;
	}

	add_filter( "review_custom_fields", "ideapark_home_review_add_custom_fields" );
}

if ( ! function_exists( 'ideapark_add_img_column' ) ) {
	function ideapark_add_img_column( $columns ) {
		$columns['img'] = esc_html__( 'Featured Image', 'ideapark-theme-functionality' );

		return $columns;
	}

	add_filter( 'manage_banner_posts_columns', 'ideapark_add_img_column' );
	add_filter( 'manage_slider_posts_columns', 'ideapark_add_img_column' );
	add_filter( 'manage_brand_posts_columns', 'ideapark_add_img_column' );
}

if ( ! function_exists( 'ideapark_manage_img_column' ) ) {
	function ideapark_manage_img_column( $column_name, $post_id ) {
		if ( $column_name == 'img' ) {
			echo get_the_post_thumbnail( $post_id, 'thumbnail' );
		}
	}

	add_filter( 'manage_posts_custom_column', 'ideapark_manage_img_column', 10, 2 );
}

if ( ! function_exists( 'ideapark_wp_calculate_image_srcset_slider' ) ) {
	function ideapark_wp_calculate_image_srcset_slider( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {

		if ( $size_array[1] == 590 ) {
			foreach ( $sources as $i => $source ) {
				$height = $source['value'] / $size_array[0] * $size_array[1];
				if ( $height < 300 ) {
					unset( $sources[ $i ] );
				}
			}
		}

		return $sources;
	}

	add_filter( 'wp_calculate_image_srcset', 'ideapark_wp_calculate_image_srcset_slider', 100, 5 );
}

if ( ! function_exists( 'ideapark_woo_add_custom_advanced_fields' ) ) {
	function ideapark_woo_add_custom_advanced_fields() {
		echo '<div class="options_group">';
		woocommerce_wp_text_input(
			[
				'id'          => '_ip_product_video_url',
				'label'       => esc_html__( 'Video URL', 'ideapark-theme-functionality' ),
				'placeholder' => 'http://',
				'desc_tip'    => 'true',
				'description' => esc_html__( 'Enter the url to product video (Youtube, Vimeo etc.).', 'ideapark-theme-functionality' )
			]
		);
		echo '</div>';
	}

	add_action( 'woocommerce_product_options_advanced', 'ideapark_woo_add_custom_advanced_fields' );
}

if ( ! function_exists( 'ideapark_contactmethods' ) ) {
	function ideapark_contactmethods( $contactmethods ) {
		global $ideapark_customize;

		$soc_list = [
			'facebook',
			'instagram',
			'vk',
			'ok',
			'telegram',
			'whatsapp',
			'twitter',
			'youtube',
			'vimeo',
			'linkedin',
			'flickr',
			'pinterest',
			'tumblr',
			'dribbble',
			'github'
		];

		if ( ! empty( $ideapark_customize ) ) {
			foreach ( $ideapark_customize as $section ) {
				if ( ! empty( $section['controls'] ) && array_key_exists( 'facebook', $section['controls'] ) ) {
					foreach ( $section['controls'] as $control_name => $control ) {
						if ( in_array( $control_name, $soc_list ) ) {
							$contactmethods[ $control_name ] = $control['label'];
						}
					}
				}
			}
		}

		return $contactmethods;
	}

	add_filter( 'user_contactmethods', 'ideapark_contactmethods', 10, 1 );
}

if ( ! function_exists( 'ideapark_woo_add_custom_general_fields_save' ) ) {
	function ideapark_woo_add_custom_general_fields_save( $post_id ) {
		if ( isset( $_POST['_ip_product_video_url'] ) && ( $woocommerce_text_field = trim( $_POST['_ip_product_video_url'] ) ) ) {
			update_post_meta( $post_id, '_ip_product_video_url', $woocommerce_text_field );
		} else {
			delete_post_meta( $post_id, '_ip_product_video_url' );
		}
	}

	add_action( 'woocommerce_process_product_meta', 'ideapark_woo_add_custom_general_fields_save' );
}

if ( ! function_exists( 'ideapark_shortcode_two_col' ) ) {
	function ideapark_shortcode_two_col( $atts, $content ) {
		$content = '<div class="clear"></div><div class="two-col">' . do_shortcode( $content ) . '</div><div class="clear"></div>';

		return force_balance_tags( $content );
	}

	add_shortcode( 'ip-two-col', 'ideapark_shortcode_two_col' );
}

if ( ! function_exists( 'ideapark_shortcode_left' ) ) {
	function ideapark_shortcode_left( $atts, $content ) {
		$content = '<div class="left"><div>' . $content . '</div></div>';

		return $content;
	}

	add_shortcode( 'ip-left', 'ideapark_shortcode_left' );
}

if ( ! function_exists( 'ideapark_shortcode_right' ) ) {
	function ideapark_shortcode_right( $atts, $content ) {
		$content = '<div class="right"><div>' . $content . '</div></div>';

		return $content;
	}

	add_shortcode( 'ip-right', 'ideapark_shortcode_right' );
}

if ( ! function_exists( 'ideapark_shortcode_post_share' ) ) {
	function ideapark_shortcode_post_share( $atts ) {
		global $post;
		ob_start();
		if ( ! empty( $post ) && is_single( $post ) && function_exists( 'ideapark_svg_url' ) ) {
			?>
			<span><?php echo esc_html__( 'Share', 'ideapark-theme-functionality' ); ?></span>
			<a class="facebook-share-button" target="_blank"
			   href="https://www.facebook.com/sharer.php?u=<?php echo rawurlencode( get_permalink( $post ) ); ?>">
				<svg>
					<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-facebook"/>
				</svg>
			</a>
			<a class="twitter-share-button" target="_blank"
			   href="https://twitter.com/home?status=Check%20out%20this%20article:%20<?php echo rawurlencode( get_the_title() ); ?>%20-%20<?php echo rawurlencode( get_permalink( $post ) ); ?>">
				<svg>
					<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-twitter"/>
				</svg>
			</a>
			<a class="pinterest-share-button" target="_blank" data-pin-do="skipLink"
			   href="https://pinterest.com/pin/create/button/?url=<?php echo rawurlencode( get_permalink( $post ) ); ?>&media=<?php echo rawurlencode( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) ); ?>&description=<?php echo rawurlencode( get_the_title() ); ?>">
				<svg>
					<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-pinterest"/>
				</svg>
			</a>
			<a class="whatsapp-share-button" target="_blank" data-action="share/whatsapp/share"
			   href="whatsapp://send?text=<?php echo rawurlencode( get_permalink( $post ) ); ?>">
				<svg>
					<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-whatsapp"/>
				</svg>
			</a>
			<?php
		}
		$content = ob_get_clean();

		return $content;
	}

	add_shortcode( 'ip-post-share', 'ideapark_shortcode_post_share' );
}

if ( ! function_exists( 'ideapark_brands_shortcode' ) ) {
	function ideapark_brands_shortcode() {
		ob_start();
		if ( $brand_taxonomy = ideapark_mod( 'product_brand_attribute' ) ) {
			$args  = [
				'taxonomy'     => $brand_taxonomy,
				'orderby'      => 'name',
				'order'        => 'ASC',
				'show_count'   => 0,
				'pad_counts'   => 0,
				'hierarchical' => 0,
				'title_li'     => '',
				'hide_empty'   => 0,
			];
			$index = 0;
			if ( $all_brands = apply_filters( 'ideapark_brand_list', get_categories( $args ) ) ) { ?>
				<div class="c-ip-brand-list c-ip-brand-list--alpha">
					<ul class="c-ip-brand-list__list c-ip-brand-list__list--alpha">
						<?php $letter = ''; ?>
						<?php foreach ( $all_brands

						as $brand ) { ?>
						<?php if ( ( $_letter = ucfirst( $brand->name[0] ) ) && ( $_letter != $letter ) ) {
						$letter = $_letter;
						?>
						<?php if ( $index ) { ?></ul>
					</li><?php } ?>
					<li class="c-ip-brand-list__item-parent">
						<ul class="c-ip-brand-list__list-inner">
							<li class="c-ip-brand-list__item--letter"><?php echo $letter; ?></li>
							<?php } ?>
							<li class="c-ip-brand-list__item c-ip-brand-list__item--alpha">
								<a class="c-ip-brand-list__link c-ip-brand-list__link--alpha"
								   href="<?php echo esc_url( get_term_link( $brand ) ); ?>">
									<div
										class="c-ip-brand-list__title c-ip-brand-list__title--alpha">
										<?php echo esc_html( $brand->name ); ?>
									</div>
								</a>
							</li>
							<?php $index ++; ?>
							<?php } ?>
						</ul>
					</li>
					</ul>
				</div>
			<?php }
			$content = ob_get_clean();

			return $content;
		}
	}

	add_shortcode( 'ip-brands', 'ideapark_brands_shortcode' );
}

if ( ! function_exists( 'ideapark_product_share' ) ) {
	function ideapark_product_share() {
		global $post;

		if ( ! function_exists( 'ideapark_mod' ) ) {
			return;
		}

		$esc_permalink = esc_url( get_permalink() );
		$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), false, '' );

		ob_start();
		if ( ideapark_mod( 'wishlist_page' ) && class_exists( 'Ideapark_Wishlist' ) ) { ?>
			<div class="ip-product-wishlist-button"><?php Ideapark_Wishlist()->button( true ) ?></div>
		<?php }
		if ( ideapark_mod( 'product_share' ) ) {
			$buttons     = ideapark_parse_checklist( ideapark_mod( 'product_share_buttons' ) );
			$share_links = [];
			foreach ( $buttons as $button_index => $enabled ) {
				if ( $enabled ) {
					switch ( $button_index ) {
						case 'facebook':
							$share_links[] = '<a class="facebook-share-button" href="//www.facebook.com/sharer.php?u=' . $esc_permalink . '" target="_blank" title="' . esc_html__( 'Share on Facebook', 'ideapark-theme-functionality' ) . '"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-facebook" /></svg></a>';
							break;
						case 'twitter':
							$share_links[] = '<a class="twitter-share-button" href="//twitter.com/share?url=' . $esc_permalink . '" target="_blank" title="' . esc_html__( 'Share on Twitter', 'ideapark-theme-functionality' ) . '"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-twitter" /></svg></a>';
							break;
						case 'pinterest':
							$share_links[] = '<a class="pinterest-share-button" href="//pinterest.com/pin/create/button/?url=' . $esc_permalink . '&amp;media=' . esc_url( ! empty( $product_image[0] ) ? $product_image[0] : '' ) . '&amp;description=' . urlencode( get_the_title() ) . '" target="_blank" title="' . esc_html__( 'Pin on Pinterest', 'ideapark-theme-functionality' ) . '"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-pinterest" /></svg></a>';
							break;
						case 'whatsapp':
							$share_links[] = '<a class="whatsapp-share-button" href="whatsapp://send?text=' . $esc_permalink . '" target="_blank" title="' . esc_html__( 'Share on Whatsapp', 'ideapark-theme-functionality' ) . '"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-whatsapp" /></svg></a>';
							break;
					}
				}
			} ?>
			<?php if ( $share_links ) { ?>
				<div class="ip-product-share">
					<span><?php echo __( 'Share', 'ideapark-theme-functionality' ); ?></span>
					<?php
					foreach ( $share_links as $link ) {
						echo $link;
					}
					?>
				</div>
			<?php } ?>
		<?php }
		$content = trim( ob_get_clean() );
		echo ideapark_wrap( $content, '<div class="ip-product-share-wrap">', '</div>' );
	}

	add_action( 'woocommerce_share', 'ideapark_product_share' );
}

if ( ! function_exists( 'ideapark_shortcode_empty_paragraph_fix' ) ) {
	function ideapark_shortcode_empty_paragraph_fix( $content ) {
		$shortcodes = [ 'ip-two-col', 'ip-left', 'ip-right' ];
		foreach ( $shortcodes as $shortcode ) {
			$array   = [
				'<p>[' . $shortcode    => '[' . $shortcode,
				'<p>[/' . $shortcode   => '[/' . $shortcode,
				$shortcode . ']</p>'   => $shortcode . ']',
				$shortcode . ']<br />' => $shortcode . ']'
			];
			$content = strtr( $content, $array );
		}

		return $content;
	}

	add_filter( 'the_content', 'ideapark_shortcode_empty_paragraph_fix' );
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

if ( ! function_exists( 'ideapark_customize_loaded_components' ) ) {
	function ideapark_customize_loaded_components( $components ) {

		foreach ( [ 'nav_menus', 'widgets' ] as $key ) {
			$i = array_search( $key, $components );
			if ( false !== $i ) {
				unset( $components[ $i ] );
			}
		}

		return $components;
	}

	add_filter( 'customize_loaded_components', 'ideapark_customize_loaded_components' );
}

if ( ! function_exists( 'ideapark_is_network_activated' ) ) {
	function ideapark_is_network_activated() {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		return is_multisite() && is_plugin_active_for_network( 'ideapark-theme-functionality/ideapark-theme-functionality.php' );
	}
}

if ( ! function_exists( 'ideapark_fix_contact_form_7_locale' ) ) {
	function ideapark_fix_contact_form_7_locale( $old_locale = '', $locale = null ) {
		global $wpdb;
		if ( $locale === null ) {
			$locale = get_locale();
		} elseif ( $locale === '' ) {
			$locale = 'en_US';
		}
		$locales = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key='_locale'" );
		if ( sizeof( $locales ) == 1 && $locales[0] !== $locale || function_exists( 'ideapark_active_languages' ) && ( $_l = ideapark_active_languages() ) && sizeof( $_l ) == 1 ) {
			$wpdb->query( "UPDATE {$wpdb->postmeta} SET meta_value = '" . esc_sql( $locale ) . "' WHERE meta_key='_locale'" );
		}
	}

	function _ideapark_fix_contact_form_7_locale() {
		ideapark_fix_contact_form_7_locale();
	}

	if ( is_admin() ) {
		add_action( 'after_update_theme', '_ideapark_fix_contact_form_7_locale', 100, 2 );
		add_action( 'update_option_WPLANG', 'ideapark_fix_contact_form_7_locale', 100, 2 );
		add_action( 'ideapark_after_import_finish', '_ideapark_fix_contact_form_7_locale', 100 );
	}
}

if ( ! function_exists( 'ideapark_update_theme_complete_actions' ) ) {
	function ideapark_update_theme_complete_actions( $update_actions, $theme ) {

		if ( defined( 'IDEAPARK_THEME_SLUG' ) && $theme == IDEAPARK_THEME_SLUG && empty( $update_actions['activate'] ) ) {
			$update_actions['ideapark_themes_page'] = sprintf(
				'<a href="%s" target="_parent">%s</a><script>document.location="' . admin_url( 'themes.php?page=ideapark_about' ) . '";</script>',
				admin_url( 'themes.php?page=ideapark_about' ),
				sprintf( __( 'Go to %s Theme page', 'ideapark-theme-functionality' ), IDEAPARK_THEME_NAME )
			);
		}

		return $update_actions;
	}

	add_action( 'update_theme_complete_actions', 'ideapark_update_theme_complete_actions', 10, 2 );
}

add_action( 'admin_init', function () {
	global $rs_admin;
	if ( function_exists( 'ideapark_ra' ) && isset( $rs_admin ) ) {
		ideapark_ra( 'admin_notices', [ $rs_admin, 'add_plugins_page_notices' ] );
	}
} );

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

if ( ! function_exists( 'ideapark_theme_notice' ) ) {
	function ideapark_theme_notice() {

		$screen = get_current_screen();
		if ( in_array( $screen->id, [ 'appearance_page_ideapark_about' ], true ) ) {
			return;
		}

		if ( ( $code = ideapark_get_purchase_code() ) && ( $code !== IDEAPARK_THEME_SKIP_REGISTER ) ) {
			return;
		}

		$message          = __( 'You have not registered the theme yet! Please <a href="%1$s">enter your purchase code</a> or <a href="%2$s" target="_blank">get a new license here</a>.', 'ideapark-theme-functionality' );
		$theme_about_page = admin_url( 'themes.php?page=ideapark_about' );

		echo '<div id="ideapark-notification" class="notice notice-warning is-dismissible"><p><span class="dashicons dashicons-warning" style="color: #f56e28"></span> ', ideapark_wp_kses( sprintf( $message, $theme_about_page, preg_replace( '~#.*$~', '', IDEAPARK_THEME_CHANGELOG ) ) ), '</p></div>';

	}

	$admin_notices_hook = ideapark_is_network_activated() ? 'network_admin_notices' : 'admin_notices';
	add_action( $admin_notices_hook, 'ideapark_theme_notice' );
}

if ( ! function_exists( 'ideapark_get_page_by_title' ) ) {
	function ideapark_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
		global $wpdb;

		if ( is_array( $post_type ) ) {
			$post_type           = esc_sql( $post_type );
			$post_type_in_string = "'" . implode( "','", $post_type ) . "'";
			$sql                 = $wpdb->prepare(
				"
			SELECT ID
			FROM $wpdb->posts
			WHERE post_title = %s
			AND post_type IN ($post_type_in_string)
		",
				$page_title
			);
		} else {
			$sql = $wpdb->prepare(
				"
			SELECT ID
			FROM $wpdb->posts
			WHERE post_title = %s
			AND post_type = %s
		",
				$page_title,
				$post_type
			);
		}

		$page = $wpdb->get_var( $sql );

		if ( $page ) {
			return get_post( $page, $output );
		}

		return null;
	}
}

add_shortcode( 'ip-button', function ( $atts ) {

	$default_atts = [
		'type'           => 'primary', // primary, accent
		'text'           => '',
		'link'           => '',
		'href'           => '',
		'target'         => '_self',
		'text_transform' => '',
		'margin'         => '',
		'custom_class'   => '',
		'html_type'      => 'anchor', // anchor, button
	];

	$params = shortcode_atts( $default_atts, $atts );

	$styles = [];

	if ( ! empty( $params['text_transform'] ) ) {
		$styles[] = 'text-transform: ' . $params['text_transform'];
	}

	if ( $params['margin'] !== '' ) {
		$styles[] = 'margin: ' . $params['margin'];
	}

	ob_start();
	?>
	<?php if ( $params['type'] == 'button' ) { ?>
		<button type="button"
				class="c-button c-button--<?php echo esc_html( $params['type'] ); ?> <?php if ( $params['custom_class'] ) {
			        esc_attr( $params['custom_class'] );
		        } ?>" <?php if ( $styles ) { ?>style="<?php echo esc_attr( implode( ';', $styles ) ); ?>"<?php } ?>>
			<?php echo esc_html( $params['text'] ); ?>
		</button>
	<?php } else { ?>
		<a href="<?php echo esc_url( $params['href'] ? $params['href'] : $params['link'] ); ?>"
		   target="<?php echo esc_attr( $params['target'] ); ?>"
		   class="c-button c-button--<?php echo esc_html( $params['type'] ); ?> <?php if ( $params['custom_class'] ) {
			   esc_attr( $params['custom_class'] );
		   } ?>" <?php if ( $styles ) { ?>style="<?php echo esc_attr( implode( ';', $styles ) ); ?>"<?php } ?>>
			<?php echo esc_html( $params['text'] ); ?>
		</a>
	<?php } ?>
	<?php

	return preg_replace( '~[\r\n]~', '', ob_get_clean() );
} );