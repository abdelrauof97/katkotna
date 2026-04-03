<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

global $product;

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
add_action( 'woocommerce_single_product_summary', 'ideapark_single_product_summary_break', 23 );

$with_sidebar   = ! ideapark_mod( 'product_hide_sidebar' ) && is_active_sidebar( 'product-sidebar' );

?>

<?php
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

global $post;

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php if ( function_exists( 'wc_product_class' ) ) {
	wc_product_class( '', $product );
} else {
	post_class();
} ?>>
	<div class="row ip-single-product-nav">

		<div class="col-xs-12">
			<?php
			next_post_link( '%link', '<i class="next"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-angle-left" /></svg></i>', ideapark_mod( 'shop_product_navigation_same_term' ), [], 'product_cat' );
			previous_post_link( '%link', '<i class="prev"><svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-angle-right" /></svg></i>', ideapark_mod( 'shop_product_navigation_same_term' ), [], 'product_cat' );
			?>
		</div>
	</div>

	<div class="row">
		<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		?>

		<?php if ( ( $ideapark_video = get_post_meta( $post->ID, '_ip_product_video_url', true ) ) && ! ( ideapark_mod( 'product_thumbnails' ) == 'below' && ideapark_mod( 'product_thumbnails_show_mobile' ) ) ) { ?>
			<div class="col-xs-12 ip-product-video-col hidden-lg hidden-md">
				<div class="watch-video watch-video--single">
					<a href="<?php echo esc_url( $ideapark_video ); ?>" target="_blank" class="ip-watch-video-btn ip-watch-video-btn--single">
						<svg>
							<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-play"/>
						</svg>
						<span><?php _e( 'Video', 'kidz' ); ?></span>
					</a>
				</div>
			</div>
		<?php } ?>

		<?php
		$th  = ideapark_mod( 'product_has_thumbnails' );
		$col = '';
		if ( ! $with_sidebar ) {
			$col = $th ? 'col-lg-5 col-md-5' : 'col-lg-6 col-md-6';
		} elseif ( ideapark_mod( 'product_short_sidebar' ) ) {
			$col = 'col-lg-4 col-md-4';
		} else {
			$col = 'col-lg-6 col-md-6';
		}
		?>

		<div class="summary entry-summary <?php echo esc_attr( $col ); ?> col-sm-12 col-xs-12">

			<div class="row">
				<?php if (ideapark_mod( 'hide_stock' )) { ?>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php woocommerce_breadcrumb(); ?>
					</div>
				<?php } else { ?>
					<div class="col-md-8 col-sm-6 col-xs-6">
						<?php woocommerce_breadcrumb(); ?>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-6 ip-product-stock-status">
						<?php ideapark_single_product_summary_availability(); ?>
					</div>
				<?php } ?>

			</div>

			<div class="row">
				<div class="col-md-12 col-xs-6 break">
					<?php
					/**
					 * woocommerce_single_product_summary hook.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>

		</div><!-- .summary -->

		<?php if ( $with_sidebar && ideapark_mod( 'product_short_sidebar' ) ) { ?>
			<div class="col-md-3">
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

	<?php
	/**
	 * woocommerce_after_single_product_summary hook.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>

<?php if ( ! empty( $ideapark_video ) ) { ?>
	<?php if ( $embded_video = wp_oembed_get( $ideapark_video ) ) { ?>
		<input type="hidden" id="ip_hidden_product_video"
			   value="<?php echo esc_js( wp_oembed_get( $ideapark_video ) ); ?>">
	<?php } ?>
<?php } ?>
