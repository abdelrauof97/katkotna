<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @package       WooCommerce/Templates
 * @version         11.0.0
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}
global $post, $product;
ob_start();
do_action( 'woocommerce_product_thumbnails' );
$thumbnails   = trim( ob_get_clean() );
$with_sidebar = ! ideapark_mod( 'product_hide_sidebar' ) && is_active_sidebar( 'product-sidebar' );

if ( ideapark_mod( 'product_thumbnails' ) == 'left' ) {
	echo ideapark_wrap( $thumbnails, '<div class="ip-product-thumbnails-col col-md-1 hidden-sm hidden-xs">', '</div>' );
}

ideapark_mod_set_temp( 'product_has_thumbnails', $th = ! ! $thumbnails && ideapark_mod( 'product_thumbnails' ) == 'left' );
$col = '';
if ( ! $with_sidebar ) {
	$col = 'col-lg-6 col-md-6';
} elseif ( ideapark_mod( 'product_short_sidebar' ) ) {
	$col = $th ? 'col-lg-4 col-md-4' : 'col-lg-5 col-md-5';
} else {
	$col = $th ? 'col-lg-5 col-md-5' : 'col-lg-6 col-md-6';
}

if ( empty( $has_variation_gallery_images ) ) {
	$product_id = $post->ID;
}
?>
<div
	class="images ip-product-images-col <?php echo esc_attr( $col ); ?> col-sm-12">

	<div class="wrap">
		<div
			class="slick-product-single h-fade<?php if ( ideapark_mod( 'shop_product_modal' ) ) { ?> product-modal-gallery<?php } ?> h-carousel h-carousel--flex js-product-info-carousel">
			<?php
			add_filter( 'wp_lazy_loading_enabled', '__return_false', 100 );
			ideapark_wp_scrset_on( 'single' );

			$div_class = [];
			if ( ideapark_mod( 'shop_product_modal' ) && ! ideapark_mod( 'shop_product_zoom' ) ) {
				$div_class[] = 'ip-product-image--zoom';
			}

			if ( ideapark_mod( 'shop_product_modal' ) ) {
				$div_class[] = 'js-product-image-modal';
			}

			$class = 'ip-product-image-img ip-product-image-img--' . esc_attr( ideapark_mod( 'product_image_fit' ) );

			$_product_id = ! empty( $variation_id ) ? $variation_id : $product_id;

			if ( has_post_thumbnail( $_product_id ) ) {
				$image_title = esc_attr( get_the_title( $_product_id ) );
				$image       = get_the_post_thumbnail( $_product_id, apply_filters( 'woocommerce_gallery_image_size', 'ideapark-large-2x' ), [
					'alt'   => $image_title,
					'class' => $class,
				] );
				$zoom_class  = '';

				if ( ideapark_mod( 'shop_product_modal' ) || ideapark_mod( 'shop_product_zoom' ) ) {
					$full_image = wp_get_attachment_image_src( get_post_thumbnail_id( $_product_id ), 'full' );
				}

				if ( ideapark_mod( 'shop_product_modal' ) ) {
					$image_wrap_open  = sprintf( '<a href="%s" class="ip-product-image-link%s" data-size="%sx%s" onclick="return false;" data-elementor-open-lightbox="no">', esc_url( $full_image[0] ), ideapark_mod( 'shop_product_modal' ) ? ' zoom' : '', intval( $full_image[1] ), intval( $full_image[2] ) );
					$image_wrap_close = '</a>';
				} else {
					$image_wrap_open  = '';
					$image_wrap_close = '';
				}

				if ( ideapark_mod( 'shop_product_zoom' ) ) {
					$image_wrap_open  .= sprintf( '<div data-img="%s" class="ip-product__image-zoom js-product-zoom %s">', esc_url( $full_image[0] ), ideapark_mod( 'shop_product_zoom_mobile_hide' ) ? 'js-product-zoom--mobile-hide' : '' );
					$image_wrap_close = "</div>" . $image_wrap_close;
				}

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div data-index="0" class="slide %s woocommerce-product-gallery__image">%s%s%s</div>', implode( ' ', $div_class ), $image_wrap_open, $image, $image_wrap_close ), $_product_id );

			} elseif ( $placeholder_image_id = get_option( 'woocommerce_placeholder_image', 0 ) ) {

				if ( ideapark_mod( 'shop_product_modal' ) ) {
					$full_image       = wp_get_attachment_image_src( $placeholder_image_id, 'full' );
					$image_wrap_open  = sprintf( '<a href="%s" class="ip-product-image-link%s" data-size="%sx%s" onclick="return false;" data-elementor-open-lightbox="no">', esc_url( $full_image[0] ), '', intval( $full_image[1] ), intval( $full_image[2] ) );
					$image_wrap_close = '</a>';
				} else {
					$image_wrap_open  = '';
					$image_wrap_close = '';
				}

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div data-index="0" class="slide ' . ( ideapark_mod( 'shop_product_modal' ) ? 'js-product-image-modal' : '' ) . ' woocommerce-product-gallery__image">%s%s%s</div>', $image_wrap_open, wc_placeholder_img( '', [ 'class' => $class ] ), $image_wrap_close ), $_product_id );

			}

			$attachment_ids = [];

			if ( ! ( ! empty( $variation_id ) && ( $attachment_ids = get_post_meta( $variation_id, 'ideapark_variation_images', true ) ) ) ) {
				$attachment_ids = $product->get_gallery_image_ids();
			}

			if ( $attachment_ids ) {
				$index = 0;
				foreach ( $attachment_ids as $attachment_id ) {
					$index ++;
					$image_link = wp_get_attachment_url( $attachment_id );

					if ( ! $image_link ) {
						continue;
					}

					$image_title = esc_attr( get_the_title( $attachment_id ) );
					$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'woocommerce_gallery_image_size', 'ideapark-large-2x' ), false, [
						'alt'   => $image_title,
						'class' => 'ip-product-image-img ip-product-image-img--' . esc_attr( ideapark_mod( 'product_image_fit' ) ),
					] );

					if ( ideapark_mod( 'shop_product_modal' ) ) {
						$full_image       = wp_get_attachment_image_src( $attachment_id, 'full' );
						$image_wrap_open  = sprintf( '<a href="%s" class="ip-product-image-link%s" data-size="%sx%s" onclick="return false;">', esc_url( $full_image[0] ), ideapark_mod( 'shop_product_modal' ) ? ' zoom' : '', intval( $full_image[1] ), intval( $full_image[2] ) );
						$image_wrap_close = '</a>';
					} else {
						$image_wrap_open  = '';
						$image_wrap_close = '';
					}

					if ( ideapark_mod( 'shop_product_zoom' ) ) {
						$image_wrap_open  .= sprintf( '<div data-img="%s" class="ip-product__image-zoom js-product-zoom %s">', esc_url( $full_image[0] ), ideapark_mod( 'shop_product_zoom_mobile_hide' ) ? 'js-product-zoom--mobile-hide' : '' );
						$image_wrap_close = "</div>" . $image_wrap_close;
					}

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div data-index="%s" class="slide %s woocommerce-product-gallery__image">%s%s%s</div>', $index, implode( ' ', $div_class ), $image_wrap_open, $image, $image_wrap_close ), $product_id );
				}
			}

			ideapark_wp_scrset_off( 'single' );
			ideapark_rf( 'wp_lazy_loading_enabled', '__return_false', 100 );
			?>
		</div>
		<?php woocommerce_show_product_sale_flash(); ?>
		<?php ideapark_woocommerce_show_product_loop_new_badge(); ?>
		<?php if ( ideapark_mod( 'product_thumbnails' ) == 'below' ) {
			echo ideapark_wrap( $thumbnails, '<div class="ip-product__thumbnails--below' . ( ideapark_mod( 'product_thumbnails_show_mobile' ) ? '' : ' hidden-sm hidden-xs' ) . '">', '</div>' );
		}
		?>
		<?php
		if ( ideapark_mod( 'shop_product_zoom' ) && ideapark_mod( 'shop_product_modal' ) && ! ideapark_mod( 'shop_product_zoom_mobile_hide' ) ) {
			echo "<a href='' onclick='return false;' class='ip-product__image-zoom-mobile " . ( ideapark_mod( 'product_thumbnails' ) == 'below' ? "ip-product__image-zoom-mobile--below" : "" ) . " js-mobile-modal'><svg class='ip-product__image-zoom-svg'><use xlink:href='" . esc_url( ideapark_svg_url() ) . "#svg-search'/></svg></a>";
		}
		?>
	</div>
</div>
