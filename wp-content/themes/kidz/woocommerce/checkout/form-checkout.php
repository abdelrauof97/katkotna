<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_checkout_coupon_form', 10 );

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( method_exists( $checkout, 'is_registration_enabled' ) ) {
	if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
		echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );

		return;
	}
} else {
	if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
		echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );

		return;
	}
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
	  action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="row row-flex-desktop">
		<div class="col-md-7 col-lg-8">
			<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			<div class="js-sticky-sidebar-nearby"><?php } ?>
				<?php if ( method_exists( $checkout, 'get_checkout_fields' ) ? $checkout->get_checkout_fields() : ( sizeof( $checkout->checkout_fields ) > 0 ) ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div
						class="col2-set <?php if ( ideapark_mod( 'product_two_columns_checkout' ) ) { ?>col2-set--2-col<?php } ?>"
						id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
				<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			</div><?php } ?>
		</div>

		<div class="col-md-5 col-lg-4">
			<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			<div class="js-sticky-sidebar"><?php } ?>
				<div class="collaterals checkout-collaterals">

					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>
					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>
				<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			</div><?php } ?>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
