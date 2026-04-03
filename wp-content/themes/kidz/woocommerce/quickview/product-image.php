<?php
/**
 *    Quickview Product Image
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$product_id            = ! empty( $variation_id ) ? $variation_id : $post->ID;
$image_title           = esc_attr( get_the_title( $product_id ) );
if ( ! ( ! empty( $variation_id ) && ( $attachment_ids = get_post_meta( $variation_id, 'ideapark_variation_images', true ) ) ) ) {
	$attachment_ids = $product->get_gallery_image_ids();
}
$slider_disabled_class = ( count( $attachment_ids ) == 0 ) ? ' ip-carousel-disabled' : ' slick-product-qv h-fade h-carousel h-carousel-flex';

add_filter( 'woocommerce_available_variation', 'ideapark_quickview_woocommerce_available_variation', 99, 1 );
function ideapark_quickview_woocommerce_available_variation( $variation ) {
	$attachment_id = get_post_thumbnail_id( $variation['variation_id'] );
	$attachment    = wp_get_attachment_image_src( $attachment_id, 'ideapark-large-2x' );
	$image         = $attachment ? current( $attachment ) : '';
	$image_srcset  = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $attachment_id, 'ideapark-large-2x' ) : false;
	$image_sizes   = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $attachment_id, 'ideapark-large-2x' ) : false;

	$variation = array_merge( $variation,
		[
			'image_src'    => $image,
			'image_srcset' => $image_srcset ? $image_srcset : '',
			'image_sizes'  => $image_sizes ? $image_sizes : ''
		] );

	return $variation;
}

?>
<?php add_filter( 'wp_lazy_loading_enabled', '__return_false', 100 ); ?>
<div class="product-images images <?php echo ideapark_wrap( $slider_disabled_class ); ?>">
	<?php
	$class = 'ip-product-image-img ip-product-image-img--' . esc_attr( ideapark_mod( 'product_image_fit' ) );
	if ( has_post_thumbnail( $product_id ) ) {
		$image = get_the_post_thumbnail( $product_id, apply_filters( 'single_product_large_thumbnail_size', 'ideapark-large-2x' ), [
			'alt'   => $image_title,
			'class' => $class,
		] );
		if ( ideapark_mod( 'quickview_product_zoom' ) ) {
			$full_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'full' );
			$image      = sprintf( '<div data-img="%s" class="ip-product__image-zoom js-product-zoom %s">%s</div>', esc_url( $full_image[0] ), ideapark_mod( 'quickview_product_zoom_mobile_hide' ) ? 'js-product-zoom--mobile-hide' : '', $image );
		}
		echo apply_filters( 'woocommerce_quickview_single_product_image_html', '<div class="slide woocommerce-product-gallery__image">' . $image . '</div>', $product_id );
	} else {
		echo apply_filters( 'woocommerce_quickview_single_product_image_html', sprintf( '<div class="slide woocommerce-product-gallery__image"><img src="%s" alt="%s" class="%s" /></div>', wc_placeholder_img_src(), esc_attr__( 'Placeholder', 'woocommerce' ), esc_attr( $class ) ), $product_id );
	}

	if ( $attachment_ids ) {
		foreach ( $attachment_ids as $attachment_id ) {
			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link ) {
				continue;
			}

			$image = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', '' ), false, [
				'alt'   => $image_title,
				'class' => 'ip-product-image-img ip-product-image-img--' . esc_attr( ideapark_mod( 'product_image_fit' ) ),
			] );

			if ( ideapark_mod( 'quickview_product_zoom' ) ) {
				$full_image = wp_get_attachment_image_src( $attachment_id, 'full' );
				$image      = sprintf( '<div data-img="%s" class="ip-product__image-zoom js-product-zoom %s">%s</div>', esc_url( $full_image[0] ), ideapark_mod( 'quickview_product_zoom_mobile_hide' ) ? 'js-product-zoom--mobile-hide' : '', $image );
			}

			echo apply_filters( 'woocommerce_quickview_single_product_image_html', '<div class="slide woocommerce-product-gallery__image">' . $image . '</div>', $product_id );
		}
	}
	?>
</div>
<?php ideapark_rf( 'wp_lazy_loading_enabled', '__return_false', 100 ); ?>
