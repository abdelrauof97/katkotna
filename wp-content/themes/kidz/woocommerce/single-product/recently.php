<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $recently_products ) : ?>

	<section
		class="recently products">

		<?php
		$heading = apply_filters( 'woocommerce_product_recently_products_heading', __( 'Recently viewed products', 'kidz' ) );
		if ( $heading ) { ?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php } ?>

		<?php woocommerce_product_loop_start(); ?>

		<?php foreach ( $recently_products as $recently_product_id ) : ?>

			<?php
			$post_object = get_post( $recently_product_id );

			setup_postdata( $GLOBALS['post'] =& $post_object );

			wc_get_template_part( 'content', 'product' ); ?>

		<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

<?php endif;

wp_reset_postdata();
