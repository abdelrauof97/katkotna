<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
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
?>
<?php if ( ideapark_woocommerce_on() && is_user_logged_in() ) { ?>
	<div class="woocommerce-MyAccount-login-info">
		<?php echo sprintf( esc_attr__( 'Logged in as %s%s%s', 'kidz' ), '<strong>', esc_html( $current_user->display_name ), '</strong>' ); ?>
		<a href="<?php echo esc_url( wc_logout_url() ); ?>">
			<svg>
				<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-logout" />
			</svg> <?php _e( 'Logout', 'kidz' ); ?></a>
	</div>
<?php } ?>

<div class="row">
	<div class="col-md-3">
		<?php
		/**
		 * My Account navigation.
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_navigation' ); ?>
	</div>

	<div class="col-md-9">
		<div class="woocommerce-MyAccount-content">
			<?php
			/**
			 * My Account content.
			 * @since 2.6.0
			 */
			do_action( 'woocommerce_account_content' );
			?>
		</div>
	</div>
</div>