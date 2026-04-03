<?php

class Ideapark_Advantages_Widget extends WP_Widget {

	function __construct() {
		$widget_options = [
			'classname'   => 'c-a-w',
			'description' => esc_html__( 'A widget that displays an About widget', 'ideapark-theme-functionality' )
		];

		parent::__construct(
			'ideapark_advantages_widget', esc_html__( 'Custom Advantages', 'ideapark-theme-functionality' ), $widget_options
		);
	}


	function widget( $args, $instance ) {
		/**
		 * @var string $before_widget
		 * @var string $before_title
		 * @var string $after_title
		 * @var string $after_widget
		 */
		extract( $args );

		$title        = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$description  = isset( $instance['description'] ) ? $instance['description'] : '';
		$more         = isset( $instance['more'] ) ? $instance['more'] : '';
		$image        = ! empty( $instance['image'] ) ? wp_get_attachment_image_src( $instance['image'], 'thumbnail' ) : 0;
		$image_bottom = ! empty( $instance['image_bottom'] ) ? wp_get_attachment_image_src( $instance['image_bottom'], 'full' ) : 0;

		ob_start();
		?>

		<?php if ( $more ) : ?>
			<a class="more" href="<?php echo esc_url( $more ); ?>">
		<?php endif; ?>

		<?php if ( $image ) : ?>
			<div class="img-advantages">
				<img alt="<?php echo esc_html( $title ); ?>" src="<?php echo esc_url( $image[0] ); ?>">
			</div>
		<?php endif; ?>

		<?php echo ideapark_wrap( esc_html( $title ), $before_title, $after_title ); ?>

		<div class="clearfix"></div>

		<?php if ( $description ) : ?>
			<div class="description">
				<?php echo do_shortcode( nl2br( $description ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $image_bottom ) : ?>
			<div class="image-bottom">
				<img src="<?php echo esc_url( $image_bottom[0] ); ?>" alt="<?php echo esc_html( $title ); ?>"/>
			</div>
		<?php endif; ?>

		<?php if ( $more ) : ?>
			</a>
		<?php endif; ?>

		<?php

		$inner_widget = ob_get_contents();
		ob_end_clean();

		echo ideapark_wrap( $inner_widget, $before_widget, $after_widget );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['image']        = strip_tags( $new_instance['image'] );
		$instance['image_bottom'] = strip_tags( $new_instance['image_bottom'] );
		$instance['description']  = wp_kses_post( $new_instance['description'] );
		$instance['more']         = strip_tags( $new_instance['more'] );

		return $instance;
	}

	function form( $instance ) {

		$defaults = [
			'title'        => '',
			'image'        => 0,
			'image_bottom' => 0,
			'description'  => '',
			'more'         => '',
		];
		wp_enqueue_media();
		wp_enqueue_script( 'wpq-media-manager', get_template_directory_uri() . '/js/wpq.js', [ 'jquery' ], '1.0', true );
		$instance     = wp_parse_args( (array) $instance, $defaults );
		$image        = ! empty( $instance['image'] ) ? wp_get_attachment_image_src( $instance['image'] ) : 0;
		$image_bottom = ! empty( $instance['image_bottom'] ) ? wp_get_attachment_image_src( $instance['image_bottom'] ) : 0;
		?>

		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'ideapark-theme-functionality' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				   value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:96%;"/>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Header icon:', 'ideapark-theme-functionality' ); ?></label><br/>
			<input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>"
				   id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"
				   value="<?php echo esc_attr( $instance['image'] ); ?>"/>
			<span class="ideapark-media-manager-target"><?php if ( $image ) { ?>
					<img src="<?php echo esc_url( $image[0] ); ?>" /><?php } ?></span><br/>
			<a class="ideapark-media-manager-link button"
			   data-controller="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>">
				<?php esc_html_e( 'Choose Image', 'ideapark-theme-functionality' ); ?>
			</a>
			<a class="ideapark-media-manager-remove button"
			   data-controller="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>">
				<?php esc_html_e( 'Remove', 'ideapark-theme-functionality' ); ?>
			</a>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'ideapark-theme-functionality' ); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"
					  name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" style="width:95%;"
					  rows="6"><?php echo esc_textarea( $instance['description'] ); ?></textarea>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'image_bottom' ) ); ?>"><?php esc_html_e( 'Bottom image:', 'ideapark-theme-functionality' ); ?></label><br/>
			<input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'image_bottom' ) ); ?>"
				   id="<?php echo esc_attr( $this->get_field_id( 'image_bottom' ) ); ?>"
				   value="<?php echo esc_attr( $instance['image_bottom'] ); ?>"/>
			<span class="ideapark-media-manager-target"><?php if ( $image_bottom ) { ?>
					<img src="<?php echo esc_url( $image_bottom[0] ); ?>" /><?php } ?></span><br/>
			<a class="ideapark-media-manager-link button"
			   data-controller="<?php echo esc_attr( $this->get_field_id( 'image_bottom' ) ); ?>">
				<?php esc_html_e( 'Choose Image', 'ideapark-theme-functionality' ); ?>
			</a>
			<a class="ideapark-media-manager-remove button"
			   data-controller="<?php echo esc_attr( $this->get_field_id( 'image_bottom' ) ); ?>">
				<?php esc_html_e( 'Remove', 'ideapark-theme-functionality' ); ?>
			</a>
		</p>
		<p>
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'more' ) ); ?>"><?php esc_html_e( 'URL (Read More):', 'ideapark-theme-functionality' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'more' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'more' ) ); ?>"
				   value="<?php echo esc_attr( $instance['more'] ); ?>" style="width:96%;"/>
		</p>
		<?php
	}
}

register_widget( 'Ideapark_Advantages_Widget' );