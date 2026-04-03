<?php
$postfix = '';
$index   = '';
if ( isset( $ideapark_var['section_id'] ) ) {
	if ( preg_match( '~-(\d+)$~', $ideapark_var['section_id'], $match ) ) {
		$postfix = '_' . $match[1];
		$index   = '-' . absint( $match[1] );
	}
}
?>
<?php if ( ideapark_mod( 'home_shortcode_content' . $postfix ) ) { ?>
	<section id="home-shortcode<?php echo esc_attr( $index ) ?>" <?php echo ideapark_wrap( trim( ( ideapark_mod( "home_shortcode_margins" . $postfix ) ? 'home-shortcode-margin' : '' ) . ' ' . ( ideapark_mod( "home_shortcode_header" . $postfix ) ? 'home-shortcode-header' : '' ) . ' home-shortcode' ), 'class="', '"' ) ?> <?php if ( ideapark_mod( 'home_shortcode_background_color' . $postfix ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_shortcode_background_color' . $postfix ) ); ?>"<?php } ?>>
		<?php if ( ideapark_mod( 'home_shortcode_header' . $postfix ) ) { ?>
			<div class="home-post-header-wrap">
				<h2><?php echo esc_html( ideapark_mod( 'home_shortcode_header' . $postfix ) ) ?></h2>
			</div>
		<?php } ?>
		<?php if ( ideapark_mod( 'home_shortcode_container' . $postfix ) ) { ?>
		<div class="container"><?php } ?>
			<?php echo ideapark_shortcode( ideapark_mod( 'home_shortcode_content' . $postfix ) ); ?>
			<?php if ( ideapark_mod( 'home_shortcode_container' . $postfix ) ) { ?></div><?php } ?>
	</section>
<?php } ?>