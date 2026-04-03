<?php if ( ideapark_mod( 'home_subscribe_content' ) ) { ?>
	<div id="home-subscribe" <?php echo ideapark_wrap( trim( 'home-subscribe ' . ( ideapark_mod( "home_subscribe_container" ) ? 'container' : '' ) . ' ' . ( ideapark_mod( "home_subscribe_margins" ) ? 'home-subscribe-margin' : '' ) . ' ' . ( ideapark_mod( "home_subscribe_header" ) ? 'home-subscribe-header' : '' ) ), 'class="', '"' ) ?> <?php if ( ideapark_mod( 'home_subscribe_background_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_subscribe_background_color' ) ); ?>"<?php } ?>>
		<?php if ( !ideapark_mod( 'home_subscribe_container' ) ) { ?><div class="container"><?php } ?>
			<div class="home-subscribe__wrap">
				<?php if ( ideapark_mod( 'home_subscribe_header' ) ) { ?>
					<div class="home-subscribe__header">
						<svg class="home-subscribe__svg">
							<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-subscribe" />
						</svg>
						<?php echo esc_html( ideapark_mod( 'home_subscribe_header' ) ) ?>
					</div>
				<?php } ?>
				<div class="home-subscribe__code">
					<?php echo do_shortcode( ideapark_mod( 'home_subscribe_content' ) ); ?>
				</div>
			</div>
		<?php if ( !ideapark_mod( 'home_subscribe_container' ) ) { ?></div><?php } ?>
	</div>
<?php } ?>