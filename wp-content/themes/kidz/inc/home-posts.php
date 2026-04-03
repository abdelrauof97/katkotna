<?php if ( $posts = get_posts( [ 'category' => ideapark_mod( 'home_post_category' ), 'posts_per_page' => ideapark_mod( 'home_post_count' ), 'meta_key' => '_thumbnail_id', 'suppress_filters' => false ] ) ) { ?>
	<section id="home-post" <?php if ( ideapark_mod( 'home_post_background_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_post_background_color' ) ); ?>"<?php } ?>>
		<div class="container">
			<div class="home-post-header-wrap">
				<a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"><h2><?php echo esc_html( ideapark_mod( 'home_post_title' )); ?></h2></a>
			</div>
			<div class="row">
				<?php foreach ( $posts as $i => $post ) {
					setup_postdata( $post );
					?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12<?php if ($i == 2) { ?> hidden-sm hidden-xs<?php } ?>">
						<?php get_template_part( 'content', 'bottom'); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } ?>
<?php wp_reset_postdata(); ?>