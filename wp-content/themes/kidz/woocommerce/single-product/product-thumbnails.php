<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
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

if ( ideapark_mod( 'product_thumbnails' ) == 'hide' ) {
	return;
}

ideapark_wp_scrset_on( 'retina' );

$variation_id = ideapark_mod( '_variation_id' );
$product_id   = $variation_id ?: $post->ID;
if ( $variation_id ) {
	$product = wc_get_product( $variation_id );
}

$attachment_ids = [];

if ( ! ( ! empty( $variation_id ) && ( $attachment_ids = get_post_meta( $variation_id, 'ideapark_variation_images', true ) ) ) ) {
	$attachment_ids = $product->get_gallery_image_ids();
}

$count        = ( ! empty( $attachment_ids ) ? sizeof( $attachment_ids ) : 0 ) + 1;
$video_url    = get_post_meta( $product_id, '_ip_product_video_url', true );
$is_short     = ideapark_mod( 'product_short_sidebar' );
$with_sidebar = ! ideapark_mod( 'product_hide_sidebar' ) && is_active_sidebar( 'product-sidebar' );
$slide_count  = ideapark_mod( 'product_thumbnails' ) === 'left' ? ( $with_sidebar && $is_short ? 4 : ( $with_sidebar ? 5 : 6 ) ) : ( ! $with_sidebar ? ( $video_url ? 5 : 6 ) : ( $video_url ? ( $is_short ? 4 : 3 ) : ( $is_short ? 5 : 4 ) ) );
if ( $video_url ) {
	$count ++;
}
if ( $count < $slide_count ) {
	$slide_count = $count;
}
if ( $video_url ) {
	ob_start();
	?>
	<div class="slide">
	<div class="watch-video watch-video--slick">
		<a href="<?php echo esc_url( $video_url ); ?>" target="_blank"
		   class="ip-watch-video-btn ip-watch-video-btn--slick">
			<svg>
				<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-play"/>
			</svg>
			<span><?php esc_html_e( 'Video', 'kidz' ); ?></span>
		</a>
	</div>
	</div><?php
	$video_thumb = ob_get_clean();
} else {
	$video_thumb = '';
}
if ( $count > 1 ) {
	$loop = 0;
	?>
	<div class="c-product__thumbnails-wrap">
		<div
			class="thumbnails h-fade slick-product slick-slide-count--<?php echo esc_attr( $count ); ?> slick-product--<?php echo esc_attr( ideapark_mod( 'product_thumbnails' ) ); ?>"
			data-count="<?php echo esc_attr( $slide_count ); ?>"
		><?php
			$loop = 0;
			if ( ideapark_mod( 'video_first' ) ) {
				echo ideapark_wrap( $video_thumb );
			}


			$loop ++;
			$active_class = ' class="slide current"';
			if ( has_post_thumbnail( $product_id ) && ( $attachment_id = get_post_thumbnail_id( $product_id ) ) && ( $image = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'thumbnail' ) ) ) ) {
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div%s>%s</div>', $active_class, $image ), $attachment_id );
			} else {
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div%s><img src="%s" alt="%s" /></div>', $active_class, wc_placeholder_img_src(), esc_attr__( 'Placeholder', 'woocommerce' ) ), $product_id );
			}

			if ( ! empty( $attachment_ids ) ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$loop ++;
					$active_class = ( $loop == 1 ) ? ' class="slide current"' : ' class="slide"';
					if ( $image = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'thumbnail' ) ) ) {
						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div%s>%s</div>', $active_class, $image ), $attachment_id );
					}
				}
			}

			if ( ! ideapark_mod( 'video_first' ) ) {
				echo ideapark_wrap( $video_thumb );
			}

			?></div>
	</div>
	<?php
	ideapark_wp_scrset_off( 'retina' );
}