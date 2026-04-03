<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', [] );

if ( ! empty( $product_tabs ) ) : ?>
	<div class="row">
		<div class="woocommerce-tabs wc-tabs-wrapper <?php if ( ideapark_mod( 'product_tabs_as_sections_desktop' ) ) { ?>woocommerce-tabs--section-desktop<?php } ?>">
			<div
				class="wrap <?php if ( ideapark_mod( 'product_tabs_as_sections' ) ) { ?>h-hide-mobile<?php } ?> <?php if ( ideapark_mod( 'product_tabs_as_sections_desktop' ) ) { ?>h-hide-desktop<?php } ?>">
				<ul class="tabs wc-tabs col-lg-12" role="tablist">
					<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
						<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>"
							role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
							<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( strip_tags( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ) ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php $is_first = true; ?>
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<div data-title="<?php echo esc_attr( $product_tab['title'] ); ?>"
					 class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel <?php if ( $key == 'description' ) { ?>entry-content<?php } ?> wc-tab <?php if ( $is_first ) { ?> current<?php $is_first = false;
				     } ?> col-md-10 col-md-offset-1 <?php if ( ideapark_mod( 'product_tabs_as_sections' ) ) { ?> woocommerce-Tabs-panel--section-on-mobile<?php } ?><?php if ( ideapark_mod( 'product_tabs_as_sections_desktop' ) ) { ?> woocommerce-Tabs-panel--section-on-desktop<?php } ?>"
					 id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
					 aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					} ?>
				</div>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_product_after_tabs' ); ?>
		</div>
	</div>

<?php endif; ?>
