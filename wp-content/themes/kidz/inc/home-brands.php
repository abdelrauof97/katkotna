<?php $brands = get_posts( [
	'posts_per_page'   => - 1,
	'post_type'        => 'brand',
	'meta_key'         => '_thumbnail_id',
	'suppress_filters' => false
] );
if ( $brands ) { ?>
	<section id="home-brand"
	         <?php if ( ideapark_mod( 'home_brands_background_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_brands_background_color' ) ); ?>"<?php } ?>>
		<div class="container">
			<div
				class="list list--<?php echo esc_attr( sizeof( $brands ) ); ?> h-carousel h-carousel--wide-arrows js-brands-carousel"
				data-autoplay-interval="<?php echo esc_attr( ideapark_mod( 'home_brands_interval' ) ); ?>"
				data-mobile-dots="<?php echo ideapark_mod( 'home_brands_mobile_dots' ) ? 1 : 0; ?>">
				<?php foreach ( $brands as $i => $post ) { ?>
					<?php $brand_link = get_post_meta( $post->ID, '_ip_brand_link', true ); ?>
					<div class="brand<?php if ( $brand_link ) { ?> brand--link<?php } ?>">
						<?php
						if ( ( $attachment_id = get_post_thumbnail_id( $post->ID ) ) && ( $image_meta = ideapark_image_meta( $attachment_id, 'ideapark-home-brands' ) ) ) {
							if ( $brand_link ) { ?>
								<a href="<?php echo esc_url( $brand_link ); ?>">
							<?php }
							echo ideapark_img( $image_meta );
							?>
							<?php if ( $brand_link ) { ?>
								</a>
							<?php } ?>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } ?>
<?php wp_reset_postdata(); ?>