<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version   11.0.0
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<div class="row row-flex-desktop">
		<div class="col-md-7 col-lg-8">
			<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			<div class="js-sticky-sidebar-nearby"><?php } ?>
				<?php do_action( 'woocommerce_before_cart_table' ); ?>
				<table
					class="shop_table shop_table_responsive cart woocommerce-cart-form__contents <?php if ( ! ideapark_mod( 'product_thumbnail_cart_mobile' ) ) { ?>shop_table--hide-thumb-mobile<?php } else {?>shop_table--show-thumb-mobile<?php } ?>"
					cellspacing="0">
					<thead>
					<tr>
						<th class="product-name" colspan="2"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
						<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
						<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						/**
						 * Filter the product name.
						 *
						 * @since 7.8.0
						 * @param string $product_name Name of the product in the cart.
						 */
						$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

								<td class="product-thumbnail">
									<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove" aria-label="%s"  data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), $product_name ) ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
									?>
									<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo ideapark_wrap( $thumbnail );
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
									}
									?>
								</td>

								<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
									<?php

									$name = ideapark_shy( method_exists( $_product, 'get_name' ) ? esc_html( $_product->get_name() ) : $_product->get_title() );
									if ( ! $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $name, $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $name ), $cart_item, $cart_item_key ) );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									// Meta data
									if ( function_exists( 'wc_get_formatted_cart_item_data' ) ) {
										echo wc_get_formatted_cart_item_data( $cart_item );
									} else {
										echo WC()->cart->get_item_data( $cart_item );
									}

									// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
									}
									?>

									<div class="product-price"
										 data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
										<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
									</div>

									<?php if ( ! ( $_product->is_in_stock() || $_product->is_on_backorder() ) ) { ?>
										<span class="ip-product-stock-status">
										<span
											class="ip-stock ip-out-of-stock out-of-stock"><svg><use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-close" /></svg><?php echo esc_html( ideapark_mod( 'outofstock_badge_text' ) ); ?></span>
											</span>
									<?php } ?>
								</td>

								<td class="product-quantity"
									data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( [
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => method_exists( $_product, 'get_max_purchase_quantity' ) ? $_product->get_max_purchase_quantity() : ( $_product->backorders_allowed() ? '' : $_product->get_stock_quantity() ),
											'min_value'    => '0',
											'product_name' => method_exists( $_product, 'get_name' ) ? $_product->get_name() : $_product->get_title(),
										], $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									?>
								</td>

								<td class="product-subtotal"
									data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
									<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</td>
							</tr>
							<?php
						}
					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

					<tr>
						<td colspan="4" class="actions">
							<div class="update-cart">
								<input type="submit" class="button<?php if (ideapark_mod( 'cart_auto_update' )) { ?> c-cart__shop-update-button--auto<?php } ?>" name="update_cart"
									   value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"/>
								<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
							</div>
							<?php do_action( 'woocommerce_cart_actions' ); ?>
						</td>
					</tr>
					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table>
				<?php do_action( 'woocommerce_after_cart_table' ); ?>
				<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			</div><?php } ?>
		</div>
		<div class="col-md-5 col-lg-4">
			<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			<div class="js-sticky-sidebar"><?php } ?>
				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
				<div class="collaterals cart-collaterals">
					<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
					?>
				</div>
				<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			</div><?php } ?>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_cart' ); ?>
