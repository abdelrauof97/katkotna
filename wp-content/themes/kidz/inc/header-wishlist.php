<?php if ( ideapark_woocommerce_on() && ideapark_mod( 'wishlist_enabled' ) && ideapark_mod( 'wishlist_page' ) && class_exists( 'Ideapark_Wishlist' ) ) { ?>
	<a rel="nofollow" aria-label="<?php esc_attr_e('Wishlist', 'kidz'); ?>" class="wishlist-info <?php if ( ideapark_mod( '_is_mobile_header' ) ) { ?>mobile-<?php } ?>wishlist<?php if ( class_exists( 'Ideapark_Wishlist' ) && Ideapark_Wishlist()->ids() ) { ?> added<?php } ?>" href="<?php echo esc_url( get_permalink( apply_filters( 'wpml_object_id', ideapark_mod( 'wishlist_page' ), 'page' ) ) ); ?>">
		<svg class="svg on">
			<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-wishlist-on" />
		</svg>
		<svg class="svg off">
			<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-wishlist-off" />
		</svg>
		<?php echo ideapark_wishlist_info(); ?>
	</a>
<?php } ?>