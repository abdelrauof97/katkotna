<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version   11.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
					<?php switch($endpoint) {
						case 'dashboard':
							echo '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-account-dashboard" /></svg>';
							break;
						case 'orders':
							echo '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-account-orders" /></svg>';
							break;
						case 'downloads':
							echo '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-account-downloads" /></svg>';
							break;
						case 'edit-address':
							echo '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-account-address" /></svg>';
							break;
						case 'edit-account':
							echo '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-account-edit" /></svg>';
							break;
						case 'payment-methods':
							echo '<svg class="tall"><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-account-payment" /></svg>';
							break;
						default:
							echo '<svg class="tall"><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-account-custom" /></svg>';
					} ?>
					<?php echo esc_html( $label ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
