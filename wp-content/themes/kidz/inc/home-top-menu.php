<?php if ( ideapark_mod( 'top_menu' ) ) {
	$ip_styles = [];

	if ( ( $n = ideapark_mod( "top_menu_background_color" ) ) && $n != ideapark_mod_default( "top_menu_background_color" ) ) {
		$ip_styles[] = 'background-color:' . esc_attr( $n );
	}

	if ( ( $n = ideapark_mod( "top_menu_color" ) ) && $n != ideapark_mod_default( "top_menu_color" ) ) {
		$ip_styles[] = 'color:' . esc_attr( $n );
	}
	ob_start();
	?>
	<?php
	$menu_locations = get_nav_menu_locations();
	wp_nav_menu( [
		'menu'        => ! empty( $menu_locations['primary'] ) ? $menu_locations['primary'] : '',
		'container'   => 'nav',
		'fallback_cb' => '',
		'after'       => '<a href="#" class="js-more"><i class="more"></i></a>'
	] );
	?>
	<?php if ( ideapark_mod( 'top_menu_text' ) ) { ?>
		<div
			class="text <?php if ( ! ideapark_mod( 'top_menu_auth' ) ) { ?> text--right<?php } ?>"><?php echo do_shortcode( ideapark_mod( 'top_menu_text_phone_clickable' ) ? ideapark_phone_wrap( ideapark_mod( 'top_menu_text' ) ) : ideapark_mod( 'top_menu_text' ) ); ?></div>
	<?php } ?>
	<?php if ( ideapark_woocommerce_on() && ideapark_mod( 'top_menu_auth' ) ) { ?>
		<div class="auth"><?php echo ideapark_get_account_link(); ?></div>
	<?php } ?>
	<?php $content = trim( ob_get_clean() ); ?>
	<?php if ( $content ) { ?>
		<div id="home-top-menu"
			 class="top-menu <?php if ( ( $n = ideapark_mod( 'top_menu_background_color' ) ) && ! preg_match( '~^#f{3,6}$~i', $n ) ) { ?>with-bg<?php } ?>" <?php echo ideapark_wrap( implode( ';', $ip_styles ), 'style="', '"' ) ?>>
			<?php echo ideapark_wrap( $content, '<div class="container">', '</div>' ); ?>
		</div>
	<?php } ?>
<?php } ?>