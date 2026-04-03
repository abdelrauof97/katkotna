<?php

/*
 * Template Name: Full Width
 * Description: A fullwidth page template without sidebar and page title.
 *
 * @package kidz
 */

ideapark_mod_set_temp( 'sidebar_post', '' );
ideapark_mod_set_temp( 'sidebar_page', '' );

ideapark_get_template_part( 'single', [ 'fullwidth' => true ] );
