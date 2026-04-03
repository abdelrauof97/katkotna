<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version   11.0.0
 */

defined( 'ABSPATH' ) || exit;

$with_sidebar = ! ideapark_mod( 'shop_hide_sidebar' ) && is_active_sidebar( 'shop-sidebar' );
ideapark_mod_set_temp('_with_sidebar', $with_sidebar);

global $product, $woocommerce_loop;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

$product_link = ' href="' . esc_url( get_permalink() ) . '"';

if ( ideapark_mod( 'quickview_enabled' ) ) {
	$quickview_link = $product_link . ' onclick="return false;" data-title="' . __( 'Quick View', 'kidz' ) . '" aria-label="' . __( 'Quick View', 'kidz' ) . '" data-lang="' . esc_attr( ideapark_current_language() ) . '" data-product_id="' . esc_attr( $product->get_id() ) . '" class="ip-quickview-btn product_type_' . esc_attr( $product->get_type() ) . '"';
} else {
	$quickview_link = '';
}
?>
<div <?php if ( function_exists( 'wc_product_class' ) ) {
	wc_product_class( '', $product );
} else {
	post_class();
}; ?>>

	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>

	<div class="ip-shop-loop-wrap">

		<div class="ip-shop-loop-thumb">
			<a<?php echo ideapark_wrap( $product_link ); ?>>
				<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook.
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woocommerce_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );
				?>

				<?php ideapark_loop_product_thumbnail(); ?>
			</a>
		</div>

		<div class="ip-shop-loop-details">
			<?php ideapark_grid_brand(); ?>
			<h3><a<?php echo ideapark_wrap( $product_link ); ?> class="ip-shop-loop-title"><?php the_title(); ?></a>
			</h3>

			<?php if ( ideapark_mod( 'product_preview_rating' ) && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) { ?>
				<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
			<?php } ?>

			<div class="ip-shop-loop-after-title">
				<div class="ip-shop-loop-price">
					<?php
					/**
					 * woocommerce_after_shop_loop_item_title hook.
					 *
					 * @hooked woocommerce_template_loop_rating - 5
					 * @hooked woocommerce_template_loop_price - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
					?>
				</div>
				<div class="ip-shop-loop-actions">
					<?php
					/**
					 * woocommerce_after_shop_loop_item hook.
					 *
					 * @hooked woocommerce_template_loop_product_link_close - 5
					 * @hooked woocommerce_template_loop_add_to_cart - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item' );
					if ( $quickview_link ) {
						echo '<a' . $quickview_link . '><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-quick-view" /></svg></a>';
					}
					if ( ideapark_mod( 'wishlist_page' ) && class_exists( 'Ideapark_Wishlist' ) ) {
						echo Ideapark_Wishlist()->button();
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>