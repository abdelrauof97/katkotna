<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
$with_sidebar = ! ideapark_mod( 'product_hide_sidebar' ) && is_active_sidebar( 'product-sidebar' );
get_header( 'shop' ); ?>

<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'woocommerce_before_main_content' );
?>

	<div class="container ip-p-c">
		<div class="row">
			<div
				class="<?php if ( ideapark_mod( 'product_short_sidebar' ) || ! $with_sidebar ) { ?>col-md-12<?php } else { ?>col-md-8<?php } ?>">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; // end of the loop. ?>
			</div>
			<?php if ( ! ideapark_mod( 'product_short_sidebar' ) && $with_sidebar ) { ?>
				<div class="col-md-offset-1 col-md-3">
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
		</div>
	</div>

<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
?>

<?php if ( ideapark_mod( 'shop_product_modal' ) ) { ?>
	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="pswp__bg"></div>
		<div class="pswp__scroll-wrap">
			<div class="pswp__container">
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
			</div>
			<div class="pswp__ui pswp__ui--hidden">
				<div class="pswp__top-bar">
					<div class="pswp__counter"></div>
					<button class="pswp__button pswp__button--close"
							title="<?php _e( 'Close (Esc)', 'kidz' ) ?>"></button>
					<button class="pswp__button pswp__button--share" title="<?php _e( 'Share', 'kidz' ) ?>"></button>
					<button class="pswp__button pswp__button--fs"
							title="<?php _e( 'Toggle fullscreen', 'kidz' ) ?>"></button>
					<button class="pswp__button pswp__button--zoom"
							title="<?php _e( 'Zoom in/out', 'kidz' ) ?>"></button>
					<div class="pswp__preloader">
						<div class="pswp__preloader__icn">
							<div class="pswp__preloader__cut">
								<div class="pswp__preloader__donut"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip"></div>
				</div>
				<button class="pswp__button pswp__button--arrow--left"
						title="<?php _e( 'Previous (arrow left)', 'kidz' ) ?>">
					<span><svg><use
								xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-angle-left"/></svg></span>
				</button>
				<button class="pswp__button pswp__button--arrow--right"
						title="<?php _e( 'Next (arrow right)', 'kidz' ) ?>">
					<span><svg><use
								xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-angle-right"/></svg></span>
				</button>
				<div class="pswp__caption">
					<div class="pswp__caption__center"></div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<?php get_footer( 'shop' ); ?>