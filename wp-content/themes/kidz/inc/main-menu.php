<?php if ( ideapark_mod( 'mega_menu' ) && class_exists( 'Ideapark_Megamenu_Walker' ) && function_exists( 'wp_nav_menu' ) ) {
	wp_nav_menu( [
		'container'      => '',
		'menu_class'     => 'menu main-menu-container ' . ideapark_mod('main_menu_view') . ( ideapark_mod('main_menu_responsive') ? ' main-menu-responsive' : ' main-menu-fixed'),
		'theme_location' => 'megamenu',
		'fallback_cb'    => 'ideapark_empty_menu',
		'walker'         => new Ideapark_Megamenu_Walker()
	] );
} else { ?>
	<?php ideapark_category_menu(); ?>
<?php } ?>