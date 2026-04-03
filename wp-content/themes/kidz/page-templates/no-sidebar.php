<?php

/*
 * Template Name: Without Sidebar
 * Description: A Page Template without sidebar.
 *
 * @package kidz
 */

ideapark_mod_set_temp( 'sidebar_post', '' );
ideapark_mod_set_temp( 'sidebar_page', '' );

get_template_part( 'single' );
