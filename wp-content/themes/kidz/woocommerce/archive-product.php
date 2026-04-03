<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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

defined( 'ABSPATH' ) || exit;

$with_sidebar = ! ideapark_mod( 'shop_hide_sidebar' ) && is_active_sidebar( 'shop-sidebar' );

ideapark_ra( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
ideapark_ra( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
ideapark_ra( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
ideapark_rf( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

get_header( 'shop' ); ?>


<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'woocommerce_before_main_content' );
?>

<div class="container ip-shop-container <?php if ( ! $with_sidebar ) { ?>hide-sidebar<?php } ?>">

	<div class="row row-flex-desktop">
		<?php if ( $with_sidebar ) { ?>
			<div class="col-md-3 col-sidebar">
				<?php
				/**
				 * woocommerce_sidebar hook.
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action( 'woocommerce_sidebar' );
				?>
			</div>
		<?php } ?>
		<div class="<?php if ( ! $with_sidebar ) { ?>col-sm-12<?php } else { ?>col-md-9<?php } ?>">
			<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			<div class="js-sticky-sidebar-nearby"><?php } ?>
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

					<header class="woocommerce-products-header main-header ip-shop-header">
						<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
						<div class="products-wrap products-wrap--category">
							<div
								class="product-category-list"><?php echo woocommerce_maybe_show_product_subcategories(); ?></div>
						</div>
						<?php if ( woocommerce_get_loop_display_mode() !== 'subcategories' ) { ?>
							<div class="row grid-header">
								<div class="col-md-8">
									<?php woocommerce_breadcrumb(); ?>
								</div>
								<?php woocommerce_catalog_ordering(); ?>
							</div>
						<?php } ?>
					</header>

				<?php endif; ?>

				<?php
				/**
				 * woocommerce_archive_description hook.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				if ( ! ideapark_mod( 'product_category_bottom_description' ) ) {
					do_action( 'woocommerce_archive_description' );
				}
				?>

				<?php

				if ( function_exists( 'woocommerce_product_loop' ) ? woocommerce_product_loop() : have_posts() ) {

					/**
					 * Hook: woocommerce_before_shop_loop.
					 *
					 * @hooked wc_print_notices - 10
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					do_action( 'woocommerce_before_shop_loop' );

					woocommerce_product_loop_start();

					if ( ! function_exists( 'wc_get_loop_prop' ) || wc_get_loop_prop( 'total' ) ) {
						while ( have_posts() ) {
							the_post();

							/**
							 * Hook: woocommerce_shop_loop.
							 *
							 * @hooked WC_Structured_Data::generate_product_data() - 10
							 */
							do_action( 'woocommerce_shop_loop' );

							wc_get_template_part( 'content', 'product' );
						}
					}

					woocommerce_product_loop_end();

					/**
					 * Hook: woocommerce_after_shop_loop.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );
				} else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action( 'woocommerce_no_products_found' );
				}

				if ( ideapark_mod( 'product_category_bottom_description' ) ) { ?>
					<?php do_action( 'woocommerce_archive_description' ); ?>
				<?php } ?>
				<?php if ( ideapark_mod( 'sticky_sidebar' ) ) { ?>
			</div><?php } ?>
		</div>
	</div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
?>

<?php get_footer( 'shop' ); ?>
