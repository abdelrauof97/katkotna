<span class="logo-wrap">
	<?php if ( ! is_front_page() ): ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php endif ?>
		<?php if ( ideapark_mod( 'logo' ) ) { ?>
			<img <?php echo ideapark_mod_image_size( 'logo' ); ?> src="<?php echo stripslashes( ideapark_mod( 'logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="logo" />
		<?php } else { ?>
			<img width="153" height="49" src="<?php echo esc_url( get_template_directory_uri() ) ?>/img/logo.svg" class="logo svg" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
		<?php } ?>
		<?php if ( ! is_front_page() ): ?></a><?php endif ?>
</span>
