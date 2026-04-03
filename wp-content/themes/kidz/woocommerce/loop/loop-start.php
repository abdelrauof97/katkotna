<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version       11.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
ideapark_wp_max_scrset_on( '768' );
ideapark_wp_scrset_on();
?>
<?php if ( ! ideapark_mod( '_hide_grid_wrapper' ) ) { ?>
	<div class="products-wrap<?php if (ideapark_mod('product_small_mobile') == 'small') { ?> products-wrap--mobile-small<?php } elseif (ideapark_mod('product_small_mobile') == 'compact') { ?> products-wrap--mobile-compact<?php } ?>">
	<div class="products products--layout-<?php echo esc_attr(ideapark_mod( 'home_boxed' )); ?> columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?><?php if (ideapark_mod('product_small_mobile') == 'small') { ?> products--mobile-small<?php } elseif (ideapark_mod('product_small_mobile') == 'compact') { ?> products--mobile-compact<?php } ?>">
<?php } ?>
<!-- grid-start -->