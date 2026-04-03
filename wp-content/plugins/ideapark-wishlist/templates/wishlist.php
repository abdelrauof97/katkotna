<?php
/**
 * Ideapark - Wishlist template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( function_exists( 'Ideapark_Wishlist' ) && ( $ip_wishlist_ids = Ideapark_Wishlist()->ids() ) ) {
	$share_link_url = get_permalink() . ( strpos( get_permalink(), '?' ) === false ? '?' : '&' ) . 'ip_wishlist_share=' . implode( ',', $ip_wishlist_ids );

	foreach ( $ip_wishlist_ids as $i => $id ) {
		$ip_wishlist_ids[ $i ] = apply_filters( 'wpml_object_id', $id, 'any' );
	}

	$args = array(
		'post_type'      => 'product',
		'order'          => 'DESC',
		'orderby'        => 'post__in',
		'posts_per_page' => - 1,
		'post__in'       => $ip_wishlist_ids
	);

	$wishlist_loop = new WP_Query( $args );
} else {
	$wishlist_loop = false;
}

if ( $wishlist_loop && $wishlist_loop->have_posts() ) { ?>

	<div id="ip-wishlist">
		<table id="ip-wishlist-table">
			<thead>
			<tr>
				<th class="product-name" colspan="2">
					<span><?php esc_html_e( 'Product', 'ideapark-wishlist' ); ?></span>
				</th>
				<th class="product-price">
					<span><?php esc_html_e( 'Price', 'ideapark-wishlist' ); ?></span>
				</th>
				<?php if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) { ?>
					<th class="stock">
						<span><?php esc_html_e( 'Stock Status', 'ideapark-wishlist' ); ?></span>
					</th>
				<?php } ?>
				<th class="action">
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			while ( $wishlist_loop->have_posts() ) : $wishlist_loop->the_post();

				global $product;
				?>
				<tr data-product-id="<?php echo $product->get_id(); ?>">
					<td class="product-thumbnail">
						<?php if ( ! Ideapark_Wishlist()->view_mode() ) { ?>
							<a href="#" class="ip-wishlist-remove remove"
							   title="<?php esc_html_e( 'Remove', 'ideapark-wishlist' ); ?>">&times;</a>
						<?php } ?>
						<a href="<?php the_permalink(); ?>"><?php echo $product->get_image(); ?></a>
					</td>
					<td class="product-name">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</td>
					<td class="product-price">
						<?php
						woocommerce_template_loop_price();
						?>
					</td>
					<?php if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) { ?>
						<td class="ip-product-stock-status stock">
							<?php
							ideapark_single_product_summary_availability();
							?>
						</td>
					<?php } ?>
					<td class="actions">
						<?php woocommerce_template_loop_add_to_cart(); ?>
					</td>
				</tr>
			<?php endwhile; ?>
			</tbody>
		</table>

		<?php if ( ideapark_mod( 'wishlist_share' ) && ! Ideapark_Wishlist()->view_mode() ) { ?>
			<div class="ip-wishlist-share row">
				<div class="col-xs-6">
					<span><?php echo __( 'Share Wishlist:', 'ideapark-wishlist' ); ?></span>
					<a target="_blank"
					   href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $share_link_url ); ?>">
						<svg>
							<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-facebook"/>
						</svg>
					</a>
					<a target="_blank"
					   href="https://twitter.com/home?status=<?php echo rawurlencode( $share_link_url ); ?>">
						<svg>
							<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-twitter"/>
						</svg>
					</a>
					<a target="_blank" data-pin-do="skipLink"
					   href="https://pinterest.com/pin/create/button/?url=<?php echo rawurlencode( $share_link_url ); ?>">
						<svg>
							<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-pinterest"/>
						</svg>
					</a>
					<a class="whatsapp-share-button" target="_blank" data-action="share/whatsapp/share"
					   href="whatsapp://send?text=<?php echo rawurlencode( $share_link_url ); ?>">
						<svg>
							<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-whatsapp"/>
						</svg>
					</a>
				</div>
				<div class="col-xs-6">
					<span><?php echo __( 'Share Link:', 'ideapark-wishlist' ); ?></span>
					<input type="text" id="ip-wishlist-share-link" value="<?php echo esc_attr( $share_link_url ); ?>"/>
				</div>
			</div>
		<?php } ?>
	</div>

<?php } ?>

<div id="ip-wishlist-empty" <?php if ( $wishlist_loop && $wishlist_loop->have_posts() ) { ?> class="hidden"<?php } ?>>

	<p class="wishlist-empty">
		<svg>
			<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-placeholder"/>
		</svg>
		<br>
		<svg>
			<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-close-light"/>
		</svg>
		<br>
		<?php esc_html_e( 'The wishlist is currently empty.', 'ideapark-wishlist' ); ?>
	</p>

	<p class="note"><?php printf( __( 'Click the %s icons to add products', 'ideapark-wishlist' ), '<svg><use xlink:href="' . esc_url( ideapark_svg_url() ) . '#svg-wishlist-off" /></svg>' ); ?></p>

	<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
		<p class="return-to-shop">
			<a class="button wc-backward"
			   href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<?php _e( 'Return To Shop', 'ideapark-wishlist' ) ?>
			</a>
		</p>
	<?php endif; ?>

</div>
<?php wp_reset_postdata(); ?>


