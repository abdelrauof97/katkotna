<?php
/**
 *    The template for displaying quickview product content
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'ideapark_qv_product_summary_availability', 3 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
add_action( 'woocommerce_single_product_summary', 'ideapark_qv_product_summary_actions', 50 );

?>

<div class="ip-p-c qv">
	<div id="product-<?php the_ID(); ?>" <?php post_class( 'product--quick-view' ); ?>>
		<div class="ip-qv-product-image">
			<?php wc_get_template( 'quickview/product-image.php' ); ?>
		</div>

		<div class="summary entry-summary ip-qv-product-summary">
			<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked ideapark_qv_product_summary_actions - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
			?>
		</div>
	</div>
</div>
