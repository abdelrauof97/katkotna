<div class="soc">
	<?php
	$soc_count = 0;
	$soc_list  = [
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
	foreach ( $soc_list as $soc_name ) {
		if ( ideapark_mod( $soc_name ) ) {
			$soc_count ++; ?>
			<a href="<?php echo esc_url( ideapark_mod( $soc_name ) ); ?>"
			   aria-label="<?php echo esc_attr( $soc_name ); ?>" target="_blank"
			   <?php if ( ideapark_mod( 'soc_background_color' ) && ideapark_mod( 'soc_background_color' ) != ideapark_mod_default( 'soc_background_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'soc_background_color' ) ); ?>"<?php } ?>>
				<svg class="soc-img soc-<?php echo esc_attr( $soc_name ); ?>"
				     <?php if ( ideapark_mod( 'soc_color' ) && ideapark_mod( 'soc_color' ) != ideapark_mod_default( 'soc_color' ) ) { ?>style="fill: <?php echo esc_attr( ideapark_mod( 'soc_color' ) ); ?>"<?php } ?>>
					<use
						xlink:href="<?php echo esc_url( ideapark_svg_url() ); ?>#svg-<?php echo esc_attr( $soc_name ); ?>"/>
				</svg>
			</a>
		<?php } ?>
	<?php } ?>
	<?php if (
		ideapark_mod( 'custom_soc_icon' ) &&
		ideapark_mod( 'custom_soc_url' ) &&
		! empty( ideapark_mod( 'custom_soc_icon__attachment_id' ) ) &&
		( $attachment_id = ideapark_mod( 'custom_soc_icon__attachment_id' ) ) &&
		( $type = get_post_mime_type( $attachment_id ) )
	) {
		$soc_count ++; ?>
		<a href="<?php echo esc_url( ideapark_mod( 'custom_soc_url' ) ); ?>" target="_blank" aria-label="<?php esc_attr_e('Social Media', 'kidz'); ?>"
		   <?php if ( ideapark_mod( 'soc_background_color' ) && ideapark_mod( 'soc_background_color' ) != ideapark_mod_default( 'soc_background_color' ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'soc_background_color' ) ); ?>"<?php } ?>>

			<?php
			if ( $type == 'image/svg+xml' ) {
				echo ideapark_get_inline_svg( $attachment_id, 'soc-img soc-custom soc-custom--svg' );
			} elseif ( $image_meta = ideapark_image_meta( $attachment_id, 'thumbnail' ) ) {
				echo ideapark_img( $image_meta, 'soc-img soc-custom soc-custom--image' );
				?>
			<?php } ?>
		</a>

	<?php } ?>
</div>