<?php if ( ideapark_mod( 'home_fullwidth_slider' ) == false ) { ?>
<div class="container"><?php } ?>
	<?php if ( ideapark_mod( 'slider_shortcode' ) ) { ?>
		<?php echo do_shortcode( ideapark_mod( 'slider_shortcode' ) ); ?>
	<?php } else { ?>
		<?php $post_per_page = ideapark_mod( 'slider_items' ); ?>
		<?php if ( $post_per_page && ( $sliders = get_posts( [
				'posts_per_page'   => $post_per_page,
				'post_type'        => 'slider',
				'meta_key'         => '_thumbnail_id',
				'suppress_filters' => false
			] ) ) ) {

			$only_images = true;
			foreach ( $sliders as $post ) {
				if ( trim( $post->post_title ) || trim( get_post_meta( $post->ID, '_ip_slider_subheader', true ) ) ) {
					$only_images = false;
					break;
				}
			}

			$class = $only_images ? 'slick-short' : '';

			?>
			<section id="home-slider"
					 class="home-section <?php if ( $only_images ) { ?> home-section--short<?php } else { ?> home-section--long<?php } ?>"
					 data-slides-count="<?php echo sizeof( $sliders ); ?>"
					 data-slider_hide_dots="<?php echo ideapark_mod( 'slider_hide_dots' ) ? 1 : 0; ?>"
					 data-slider_hide_arrows="<?php echo ideapark_mod( 'slider_hide_arrows' ) ? 1 : 0; ?>"
					 data-slider_effect="<?php echo esc_attr( ideapark_mod( 'slider_effect' ) ); ?>"
					 data-slider_interval="<?php echo esc_attr( ideapark_mod( 'slider_interval' ) ); ?>">

				<?php $post = $sliders[0];
				setup_postdata( $post ); ?>

				<div class="slick h-carousel js-slider-carousel <?php echo esc_attr( $class ); ?>">
					<?php foreach ( $sliders as $index => $post ) {
						setup_postdata( $post );
						$slide_subheader    = get_post_meta( $post->ID, '_ip_slider_subheader', true );
						$slide_link         = get_post_meta( $post->ID, '_ip_slider_link', true );
						$slide_color        = trim( get_post_meta( $post->ID, '_ip_slider_color', true ) );
						$slide_mobile_image = get_post_meta( $post->ID, '_ip_slider_mobile_image', true );
						$with_mobile_image  = false;
						if ( $image_meta = ideapark_image_meta( get_post_thumbnail_id( $post->ID ), 'full' ) ) { ?>
							<div class="slide<?php if ( ! $index ) { ?> slide--first<?php } ?>">
								<?php if ( $slide_mobile_image && ( $mobile_image_meta = ideapark_image_meta( $slide_mobile_image, 'full' ) ) ) { ?>
									<img
										class="bg-image bg-image--mobile"
										src="<?php echo esc_attr( $mobile_image_meta['src'] ); ?>"
										<?php if ( $mobile_image_meta['srcset'] && $mobile_image_meta['sizes'] ) { ?>
											srcset="<?php echo esc_attr( $mobile_image_meta['srcset'] ); ?>"
											sizes="<?php echo esc_attr( $mobile_image_meta['sizes'] ); ?>"
										<?php } ?>
										alt="<?php echo esc_attr( $mobile_image_meta['alt'] ); ?>"
										title="<?php echo esc_attr( $mobile_image_meta['title'] ); ?>"
										<?php if ( $index ) { ?>loading="lazy"<?php } ?>>
									<?php
									$with_mobile_image = true;
								} ?>
								<img
									class="bg-image <?php if ($with_mobile_image) { ?>bg-image--desktop<?php } ?>"
									src="<?php echo esc_attr( $image_meta['src'] ); ?>"
									<?php if ( $image_meta['srcset'] && $image_meta['sizes'] ) { ?>
										srcset="<?php echo esc_attr( $image_meta['srcset'] ); ?>"
										sizes="<?php echo esc_attr( $image_meta['sizes'] ); ?>"
									<?php } ?>
									alt="<?php echo esc_attr( $image_meta['alt'] ); ?>"
									title="<?php echo esc_attr( $image_meta['title'] ); ?>"
									<?php if ( $index ) { ?>loading="lazy"<?php } ?>>
								<?php if ( ! $only_images ) { ?>
									<div class="inner"
									     <?php if ( $slide_color ) { ?>style="color: <?php echo esc_attr( $slide_color ) ?>"<?php } ?>>
										<h3><?php the_title(); ?></h3>
										<?php if ( $slide_subheader ) { ?>
											<h4><?php echo esc_html( $slide_subheader ); ?></h4>
										<?php } ?>
									</div>
								<?php } ?>
								<?php if ( $slide_link ) { ?>
									<a class="whole" href="<?php echo esc_url( $slide_link ); ?>"></a>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } ?>
				</div>

				<div class="slick-preloader <?php echo esc_attr( $class ); ?>">
				</div>
			</section>
		<?php } ?>
		<?php wp_reset_postdata(); ?>
	<?php } ?>
	<?php if ( ideapark_mod( 'home_fullwidth_slider' ) == false ) { ?></div><?php } ?>

