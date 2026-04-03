<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/sidebar.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version         11.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div id="ip-shop-sidebar"
     <?php if ( ! is_product() && ideapark_mod( 'sticky_sidebar' ) ) { ?>class="js-sticky-sidebar"<?php } ?>>
	<?php
	if ( is_active_sidebar( is_product() ? 'product-sidebar' : 'shop-sidebar' ) ) {
		dynamic_sidebar( is_product() ? 'product-sidebar' : 'shop-sidebar' );
	}
	?>
	<a class="mobile-sidebar-close" href="#">
		<svg>
			<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-close"/>
		</svg>
	</a>
</div>

