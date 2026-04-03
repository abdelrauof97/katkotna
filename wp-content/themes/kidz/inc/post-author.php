<?php
global $ideapark_customize;

$description = get_the_author_meta( 'description' );
$author_soc  = "";
$soc_list    = [
	'facebook',
	'instagram',
	'vk',
	'ok',
	'telegram',
	'whatsapp',
	'twitter',
	'youtube',
	'vimeo',
	'snapchat',
	'tiktok',
	'linkedin',
	'flickr',
	'pinterest',
	'tumblr',
	'dribbble',
	'github'
];

if ( ! empty( $ideapark_customize ) ) {
	ob_start();
	foreach ( $ideapark_customize as $section ) {
		if ( ! empty( $section['controls'] ) && array_key_exists( 'facebook', $section['controls'] ) ) {
			foreach ( $soc_list as $soc_name ) { ?>
				<?php if ( get_the_author_meta( $soc_name ) ) { ?>
					<a target="_blank" href="<?php echo esc_url( get_the_author_meta( $soc_name ) ); ?>">
						<svg class="soc-img">
							<use
								xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-<?php echo esc_attr( $soc_name ); ?>"/>
						</svg>
					</a>
				<?php } ?>
			<?php }
		}
	}
	$author_soc = trim( ob_get_clean() );
}
?>


<div class="post-author clearfix">

	<div class="author-img">
		<?php echo get_avatar( get_the_author_meta( 'email' ) ); ?>
	</div>

	<div class="author-content<?php if ( ! $description && ! $author_soc ) { ?> author-content--center<?php } ?>">
		<h5><?php the_author_posts_link(); ?></h5>
		<?php if ( $description || $author_soc ) { ?>
			<?php echo ideapark_wrap( $description, '<p>', '</p>' ); ?>
			<?php echo ideapark_wrap( $author_soc, '<div class="soc">', '</div>' ); ?>
		<?php } ?>
	</div>

</div>
