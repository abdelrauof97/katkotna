<?php if ( ( $post = get_post() ) && ( $content = apply_filters( 'the_content', $post->post_content ) ) ) { ?>
	<section id="home-text" <?php if ( ideapark_mod( 'home_text_background_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_text_background_color' ) ); ?>"<?php } ?>>
		<div class="container">
			<?php if ( ! ideapark_mod( 'home_text_hide_header' ) ) { ?>
			<h1><?php echo get_the_title( $post ); ?></h1>
			<?php } ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="entry-content">
						<?php echo ideapark_wrap( $content ); ?>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php } ?>
<?php $s = $post; ?>
