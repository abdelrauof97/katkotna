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

<?php if ( ideapark_mod( 'home_html_content' . $postfix ) ) { ?>
	<div
		id="home-html<?php echo esc_attr( $index ) ?>" <?php echo ideapark_wrap( trim( ( ideapark_mod( "home_html_margins" . $postfix ) ? 'home-html-margin' : '' ) . ' ' . ( ideapark_mod( "home_html_header" . $postfix ) ? 'home-html-header' : '' ) . ' home-html' ), 'class="', '"' ) ?>
		<?php if ( ideapark_mod( 'home_html_background_color' . $postfix ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_html_background_color' . $postfix ) ); ?>"<?php } ?>>
		<?php if ( ideapark_mod( 'home_html_header' . $postfix ) ) { ?>
			<div class="home-post-header-wrap">
				<h2><?php echo esc_html( ideapark_mod( 'home_html_header' . $postfix ) ) ?></h2>
			</div>
		<?php } ?>
		<?php if ( ideapark_mod( 'home_html_container' . $postfix ) ) { ?>
		<div class="container"><?php } ?>
			<?php echo do_shortcode( ideapark_mod( 'home_html_content_type' . $postfix ) == 'source' ? ideapark_mod( 'home_html_content_source' . $postfix ) : apply_filters( 'the_content', ideapark_mod( 'home_html_content' . $postfix ) ) ); ?>
			<?php if ( ideapark_mod( 'home_html_container' . $postfix ) ) { ?></div><?php } ?>
	</div>
<?php } ?>