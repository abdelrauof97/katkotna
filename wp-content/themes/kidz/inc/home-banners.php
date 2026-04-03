<?php if ( ideapark_mod( 'home_fullwidth_slider' ) == false && ideapark_mod( '_last_section' ) == 'slider' ) { ?>
<div class="container"><?php } ?>
	<?php if ( $banners = get_posts( [ 'posts_per_page'   => ideapark_mod( 'home_boxed' ) == 'fullscreen' ? 4 : 3,
	                                   'post_type'        => 'banner',
	                                   'meta_key'         => '_thumbnail_id',
	                                   'suppress_filters' => false,
	                                   'order'            => 'ASC',
	                                   'orderby'          => ideapark_mod( 'home_banners_4_order' ) == 'fixed' ? 'menu_order' : 'rand'
	] ) ) { ?>
		<section id="home-banners" class="preloading"><!--
		<?php foreach ( $banners

			as $post ) {
			setup_postdata( $post );
			$banner_hide_title  = get_post_meta( $post->ID, '_ip_banner_hide_title', true );
			$banner_stretch_image = get_post_meta( $post->ID, '_ip_banner_stretch_image', true );
			$banner_price       = get_post_meta( $post->ID, '_ip_banner_price', true );
			$banner_button_text = get_post_meta( $post->ID, '_ip_banner_button_text', true );
			$banner_button_link = preg_replace( '~^/~', home_url() . '/', get_post_meta( $post->ID, '_ip_banner_button_link', true ) );
			$banner_alfa        = get_post_meta( $post->ID, '_ip_banner_alfa', true );
			$banner_bg          = get_post_meta( $post->ID, '_ip_banner_bg', true );
			$banner_color       = trim( get_post_meta( $post->ID, '_ip_banner_color', true ) );
			$attachment_id      = get_post_thumbnail_id( $post->ID );
			if ( $image_meta = ideapark_image_meta( $attachment_id, 'ideapark-home-banners' ) ) {
				$image_meta['sizes'] = "(min-width: 1200px) " . round( $image_meta['width'] * 250 / $image_meta['height'] ) . "px, (min-width: 769px) and (max-width: 1199px) " . round( $image_meta['width'] * 220 / $image_meta['height'] ) . "px, (min-width: 601px) and (max-width: 768px) " . round( $image_meta['width'] * 180 / $image_meta['height'] ) . "px, (min-width: 414px) and (max-width: 600px) " . round( $image_meta['width'] * 220 / $image_meta['height'] ) . "px, (max-width: 413px) " . round( $image_meta['width'] * 180 / $image_meta['height'] ) . "px";
				$image_meta = apply_filters( 'ideapark_banner_image_meta', $image_meta );
				$class = 'thumb';
				if ( $banner_stretch_image ) {
					$class .= ' thumb--stretched';
				}
				$image = ideapark_img( $image_meta, $class );
			} else {
				$image = '';
			}
			if ( ! preg_match( '~^#([ABCDEF0-9]{3}|[ABCDEF0-9]{6})$~i', $banner_color ) ) {
				$banner_color = '#5DACF5';
			}
			?>
			--><div
				class="banner <?php if ( ideapark_mod( 'home_boxed' ) != 'fullscreen' ) { ?> boxed-layout<?php } ?><?php if ( $banner_stretch_image ) { ?> stretched-image<?php } ?><?php if ( $banner_alfa ) { ?> alfa-image<?php } else { ?> non-alfa-image<?php } ?>">
				<div class="bg"
					 style="background: <?php echo ideapark_wrap( $banner_alfa ? esc_attr( $banner_color ) : ( $banner_bg ? esc_attr( $banner_bg ) : '#FFF' ) ); ?>"></div>
				<?php echo ideapark_wrap( $image ); ?>
				<?php if ( ! $banner_hide_title ) { ?>
					<div class="inner">
						<h3><?php the_title(); ?></h3>
					</div>
				<?php } ?>
				<?php if ( $banner_price ) { ?>
					<div class="price"
						 style="color: <?php echo esc_attr( $banner_color ); ?>"><?php echo esc_html( $banner_price ); ?></div>
				<?php } ?>
				<?php if ( $banner_button_link && $banner_button_text ) { ?>
					<a class="more" href="<?php echo esc_url( $banner_button_link ); ?>"
					   style="background: <?php echo esc_attr( $banner_color ); ?>"><?php echo esc_html( $banner_button_text ); ?></a>
				<?php } ?>
				<?php if ( $banner_button_link && ! $banner_button_text ) { ?>
					<a class="more-whole" href="<?php echo esc_url( $banner_button_link ); ?>"></a>
				<?php } ?>
			</div><!--
		<?php } ?>
	--></section>
	<?php } ?>
	<?php if ( ideapark_mod( 'home_fullwidth_slider' ) == false && ideapark_mod( '_last_section' ) == 'slider' ) { ?>
</div><?php } ?>
<?php wp_reset_postdata(); ?>

