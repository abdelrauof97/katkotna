<?php
/**
 * The template for displaying product widget entries.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

ideapark_wp_scrset_on( 'retina' );
/**
 * @var $product WC_Product
 */
?>
	<li>
		<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>

		<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
			<?php echo ideapark_wrap( $product->get_image( 'thumbnail' ) ); ?>
			<span class="product-title"><?php echo esc_html( strip_tags( $product->get_name() ) ); ?></span>
		</a>

		<?php if ( ! empty( $show_rating ) ) : ?>
			<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
		<?php endif; ?>

		<?php echo ideapark_wrap( $product->get_price_html() ); ?>

		<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
	</li>

<?php ideapark_wp_scrset_off( 'retina' ); ?>