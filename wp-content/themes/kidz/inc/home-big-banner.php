<?php if ( ideapark_mod( 'home_big_banner_header' ) || ideapark_mod( 'home_big_banner_subheader' ) || ideapark_mod( "home_big_banner_image" ) ) { ?>
	<?php
	$ip_styles        = [];
	$ip_styles_button = [];
	$is_parallax      = ideapark_mod( 'home_big_banner_parallax' ) && ideapark_mod( "home_big_banner_image" );
	$is_lazyload      = ideapark_mod( 'lazyload' );

	if ( ideapark_mod( $n = "home_big_banner_background_color" ) ) {
		$ip_styles[]        = 'background-color:' . esc_attr( ideapark_mod( $n ) );
		$ip_styles[]        = 'text-shadow: 0 0 5px ' . esc_attr( ideapark_mod( $n ) );
		$ip_styles_button[] = 'color:' . esc_attr( ideapark_mod( $n ) );
	}

	if ( ideapark_mod( $n = "home_big_banner_text_color" ) && ideapark_mod( $n ) != ideapark_mod_default( $n ) ) {
		$ip_styles[] = 'color:' . esc_attr( ideapark_mod( $n ) );
	}

	if ( ideapark_mod( $n = "home_big_banner_image" ) ) {
		$ip_styles[] = 'background-image: url(' . esc_url( ideapark_mod( $n ) ) . ')';
	}

	if ( ideapark_mod( $nx = "home_big_banner_image_position_x" ) && ideapark_mod( $ny = "home_big_banner_image_position_y" ) ) {
		$ip_styles[] = 'background-position: ' . esc_attr( ideapark_mod( $nx ) ) . ' ' . esc_attr( ideapark_mod( $ny ) );
	}

	if ( ideapark_mod( $n = "home_big_banner_image_size" ) ) {
		$ip_styles[] = 'background-size:' . esc_attr( ideapark_mod( $n ) );
	}

	if ( ideapark_mod( $n = "home_big_banner_button_color" ) && ideapark_mod( $n ) != ideapark_mod_default( $n ) ) {
		$ip_styles_button[] = 'background-color:' . esc_attr( ideapark_mod( $n ) );
	}
	?>
	<div id="home-big-banner"
		<?php echo ideapark_wrap( trim( ( $is_parallax ? 'banner-image-parallax' : '' ) . ' ' . ideapark_mod( 'home_big_banner_text_align' ) . ' ' . ( ideapark_mod( "home_big_banner_header" ) ? 'home-big-banner-header' : '' ) . ' ' . ( ideapark_mod( 'home_big_banner_container' ) ? 'container' : '' ) ), 'class="', '"' ) ?>
		<?php echo ideapark_wrap( implode( ';', $ip_styles ), 'style="', '"' ) ?>>
		<div class="shadow"
			 style="background-color: <?php echo ideapark_mod( $n = "home_big_banner_background_color" ) ? ideapark_mod( $n ) : '#fff'; ?>"></div>
		<?php if ( $is_parallax ) { ?>
			<div class="parallax-wrap">
				<img class="parallax-img<?php if ( $is_lazyload ) { ?> parallax-lazy<?php } else {?> parallax<?php } ?>" src="<?php echo esc_url( ideapark_mod( "home_big_banner_image" ) ); ?>"
					 alt="<?php echo esc_attr( ideapark_mod( 'home_big_banner_header' ) ) ?>" <?php if ( $is_lazyload ) { ?>loading="lazy"<?php } ?> />
			</div>
		<?php } ?>
		<div class="wrap">
			<?php if ( ideapark_mod( 'home_big_banner_header' ) ) { ?>
				<div class="header"><?php echo esc_html( ideapark_mod( 'home_big_banner_header' ) ) ?></div>
			<?php } ?>
			<?php if ( ideapark_mod( 'home_big_banner_subheader' ) ) { ?>
				<div class="sub-header"><?php echo esc_html( ideapark_mod( 'home_big_banner_subheader' ) ) ?></div>
			<?php } ?>
			<?php if ( ideapark_mod( 'home_big_banner_link' ) ) { ?>
			<a href="<?php echo esc_url( ideapark_mod( 'home_big_banner_link' ) ); ?>"
			   <?php if ( ! ideapark_mod( 'home_big_banner_button_text' ) ) { ?>class="whole-banner"<?php } ?>><?php } ?>
				<?php if ( ideapark_mod( 'home_big_banner_button_text' ) ) { ?>
					<div
						class="button" <?php echo ideapark_wrap( implode( ';', $ip_styles_button ), 'style="', '"' ) ?>><?php echo esc_html( ideapark_mod( 'home_big_banner_button_text' ) ) ?></div>
				<?php } ?>
				<?php if ( ideapark_mod( 'home_big_banner_link' ) ) { ?></a><?php } ?>
		</div>
	</div>
<?php } ?>