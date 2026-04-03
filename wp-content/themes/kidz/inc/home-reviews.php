<?php if ( $reviews = get_posts( [ 'posts_per_page'   => - 1,
                                   'post_type'        => 'review',
                                   'suppress_filters' => false
] ) ) { ?>
	<section id="home-review"
	         <?php if ( ideapark_mod( 'home_review_background_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_review_background_color' ) ); ?>"<?php } ?>>
		<div class="container">
			<div class="slick-review h-carousel h-carousel--flex h-carousel--wide-arrows js-review-carousel"
				 data-autoplay-interval="<?php echo esc_attr( ideapark_mod( 'home_review_interval' ) ); ?>"
				 data-mobile-dots="<?php echo ideapark_mod( 'home_review_mobile_dots' ) ? 1 : 0; ?>">
				<?php foreach ( $reviews as $i => $post ) {
					setup_postdata( $post );
					$review_occupation = get_post_meta( $post->ID, '_ip_review_occupation', true );
					?>
					<div class="review">
						<svg class="quote">
							<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-quote"/>
						</svg>
						<?php the_excerpt(); ?>
						<?php if ( has_post_thumbnail() && ( $attachment_id = get_post_thumbnail_id( $post->ID ) ) && ( $image_meta = ideapark_image_meta( $attachment_id, 'thumbnail' ) ) ) {
							echo ideapark_wrap( ideapark_img( $image_meta, 'image' ), '<div class="thumb">', '</div>' );
						} ?>
						<div class="author"><?php the_title(); ?></div>
						<?php if ( $review_occupation ) { ?>
							<div class="occupation">
								<?php echo esc_html( $review_occupation ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } ?>
<?php wp_reset_postdata(); ?>