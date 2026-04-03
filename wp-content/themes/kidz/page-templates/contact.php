<?php

/*
 * Template Name: Contact
 * Description: Contact Page Template.
 *
 * @package kidz
 */

get_header();
global $post; ?>
<div class="ip-page-container">
	<header class="main-header">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</div>
	</header>

	<div class="container post-container">

		<div class="row">
			<div class="col-lg-12">
				<ul class="entry-content contact-blocks">
					<?php if ( ideapark_mod( 'contact_phones' ) ) { ?>
						<li class="contact-block">
							<svg class="contact-ico contact-ico__phones">
								<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-phone"/>
							</svg>
							<div class="contact-header"><?php esc_html_e( 'Phones', 'kidz' ); ?></div>
							<span id="contact-block-phones">
								<?php echo ideapark_mod( 'contact_phones' ); ?>
							</span>
						</li>
					<?php } ?>
					<?php if ( ideapark_mod( 'contact_email' ) ) { ?>
						<li class="contact-block">
							<svg class="contact-ico contact-ico__email">
								<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-envelope"/>
							</svg>
							<div class="contact-header"><?php esc_html_e( 'Email', 'kidz' ); ?></div>
							<span id="contact-block-email">
								<?php echo ideapark_mod( 'contact_email' ); ?>
							</span>
						</li>
					<?php } ?>
					<?php if ( ideapark_mod( 'contact_address' ) ) { ?>
						<li class="contact-block">
							<div class="contact-header"><?php esc_html_e( 'Address', 'kidz' ); ?></div>
							<svg class="contact-ico contact-ico__address">
								<use xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-marker"/>
							</svg>
							<span id="contact-block-address">
								<?php echo ideapark_mod( 'contact_address' ); ?>
							</span>
						</li>
					<?php } ?>
				</ul>
				<?php if ( ideapark_mod( 'contact_map_shortcode' ) || ideapark_mod( 'contact_form_shortcode' ) ) { ?>
					<div class="contact-row-2">
						<?php if ( ideapark_mod( 'contact_form_shortcode' ) ) { ?>
							<div class="contact-form" id="contact-form">
								<?php echo do_shortcode( ideapark_mod( 'contact_form_shortcode' ) ); ?>
							</div>
						<?php } ?>
						<?php if ( ideapark_mod( 'contact_map_shortcode' ) ) { ?>
							<div class="contact-map" id="contact-map">
								<?php echo do_shortcode( ideapark_mod( 'contact_map_shortcode' ) ); ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if ( have_posts() ) { ?>
					<section role="main" class="post-open">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content' ); ?>
						<?php endwhile; ?>
					</section>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>












