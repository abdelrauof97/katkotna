<?php
/**
 * @var array $ideapark_var
 */
$postfix  = '';
$index    = '';
$index_id = '';
if ( isset( $ideapark_var['section_id'] ) ) {
	if ( preg_match( '~-(\d+)$~', $ideapark_var['section_id'], $match ) ) {
		$index_id = absint( $match[1] );
		$postfix  = '_' . $index_id;
		$index    = '-' . $index_id;
	}
}

$get_tab_title = function ( $tab, $postfix ) {
	$title = '';
	switch ( $tab ) {
		case 'featured_products':
			$title = ideapark_mod( 'home_featured_title' . $postfix );
			break;
		case 'sale_products':
			$title = ideapark_mod( 'home_sale_title' . $postfix );
			break;
		case 'best_selling_products':
			$title = ideapark_mod( 'home_best_selling_title' . $postfix );
			break;
		case 'recent_products':
			$title = ideapark_mod( 'home_recent_title' . $postfix );
			break;
		case 'shortcode':
			$title = ideapark_mod( 'home_tab_shortcode_title' . $postfix );
			break;
		default:
			if ( ( $cat_id = absint( $tab ) ) && ( $cat = get_term_by( 'id', $cat_id, 'product_cat', 'ARRAY_A' ) ) ) {
				$title = $cat['name'];
			}
	}

	return $title;
}
?>

<?php if ( ideapark_woocommerce_on() && ideapark_mod( 'home_tab_products' . $postfix ) > 0 && ( $tabs = array_keys( array_filter( ideapark_parse_checklist( ideapark_mod( 'home_product_order' . $postfix ) ) ) ) ) ) { ?>
	<?php
	$is_first = true; ?>
	<div id="home-tabs<?php echo esc_attr( $index ) ?>"
		 class="c-home-tabs<?php if ( ideapark_mod( 'home_tab_padding_top' . $postfix ) ) { ?> c-home-tabs--padding-top<?php } ?><?php if ( ideapark_mod( 'home_tab_padding_bottom' . $postfix ) ) { ?> c-home-tabs--padding-bottom<?php } ?><?php if ( ideapark_mod( 'home_tab_carousel' . $postfix ) ) { ?> c-home-tabs--carousel h-carousel h-carousel--flex js-product-carousel<?php } ?>"
	     <?php if ( ideapark_mod( 'home_tab_background_color' . $postfix ) ) { ?>style="background-color: <?php echo esc_attr( ideapark_mod( 'home_tab_background_color' . $postfix ) ); ?>"<?php } ?>>
		<?php if ( sizeof( $tabs ) == 1 ) { ?>
			<div class="container home-tabs-title-wrap">
				<h2>
					<?php echo esc_html( $get_tab_title( $tabs[0], $postfix ) ); ?>
				</h2>
			</div>
		<?php } else { ?>
			<div class="container home-tabs-wrap">
				<ul class="home-tabs clear">
					<?php foreach ( $tabs as $tab ) { ?>
						<li<?php if ( $is_first ) { ?> class="current"<?php } ?>>
							<a href="#tab-<?php echo esc_attr( $tab . $index ); ?>">
								<?php echo esc_html( $get_tab_title( $tab, $postfix ) ); ?>
							</a>
						</li>
						<?php $is_first = false; ?>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>

		<?php $is_first = true; ?>
		<?php foreach ( $tabs as $tab ) { ?>
			<?php if ( ideapark_mod( 'featured_badge_text_hide' ) && ( $tab == 'featured_products' ) ) {
				$_featured_badge_text = ideapark_mod( 'featured_badge_text' );
				ideapark_mod_set_temp( 'featured_badge_text', '' );
			} ?>
			<?php $cat_id = preg_match( '~^\d+$~', $tab ) ? $cat_id = absint( $tab ) : 0; ?>
			<?php if ( $cat_id ) {
				$cat_link = get_term_link( $cat_id, 'product_cat' );
				if ( is_wp_error( $cat_link ) ) {
					continue;
				}
			} ?>
			<div id="tab-<?php echo esc_attr( $tab . $index ); ?>"
			     <?php if ( $cat_id ) { ?>data-index="<?php echo esc_attr( $index_id ); ?>"
				 data-tab="<?php echo esc_attr( $tab ); ?>"<?php } ?>
				 data-per-page="<?php echo esc_attr( ideapark_mod( 'home_tab_products' . $postfix ) ); ?>"
			     <?php if ( ideapark_mod( 'home_tab_view_more' . $postfix ) && ideapark_mod( 'home_tab_view_more_item' . $postfix ) && ideapark_mod( 'home_tab_carousel' . $postfix ) && $cat_id ) { ?>data-view-more="<?php echo esc_url( get_term_link( $cat_id, 'product_cat' ) ); ?>"<?php } ?>

				 class="container home-tab<?php if ( $is_first ) { ?> visible current<?php } ?>">
				<?php
				$limit   = (int) ideapark_mod( 'home_tab_products' . $postfix );
				$orderby = ideapark_mod( 'home_tab_orderby' . $postfix );
				$order   = ideapark_mod( 'home_tab_order' . $postfix );
				if ( $cat_id ) {
					echo ideapark_shortcode( '[products category="' . $cat_id . '" limit="' . $limit . '"' . ( $orderby ? ' orderby="' . $orderby . '" order="' . $order . '"' : '' ) . ']' );
				} elseif ( $tab == 'shortcode' && ( $shortcode = trim( ideapark_mod( 'home_tab_shortcode' . $postfix ) ) ) && preg_match( '~\[([^\] ]+)~', $shortcode, $match ) && shortcode_exists( $match[1] ) ) {
					$shortcode = preg_replace( '~(limit|order|orderby)\s*=\s*["\'][\s\S]*["\']~uUi', '', $shortcode );
					$shortcode = preg_replace( '~\]~', ' limit="' . $limit . '"' . ( $orderby ? ' orderby="' . $orderby . '" order="' . $order . '"' : '' ) . ']', $shortcode );
					echo ideapark_shortcode( $shortcode );
				} elseif ( $tab != 'shortcode' ) {
					echo ideapark_shortcode( '[' . $tab . ' limit="' . $limit . '"' . ( $orderby ? ' orderby="' . $orderby . '" order="' . $order . '"' : '' ) . ']' );
				} ?>
				<?php if ( $cat_id && ideapark_mod( 'home_tab_view_more' . $postfix ) && ! ( ideapark_mod( 'home_tab_view_more_item' . $postfix ) && ideapark_mod( 'home_tab_carousel' . $postfix ) ) ) { ?>
					<div
						class="view-more-wrap <?php if ( ideapark_mod( 'home_tab_carousel' . $postfix ) ) { ?><?php if ( ideapark_mod( 'product_small_mobile' ) == 'small' ) { ?>view-more-wrap--carousel-small<?php } else { ?>view-more-wrap--carousel<?php } ?><?php } ?>">
						<a href="<?php echo esc_url( get_term_link( $cat_id, 'product_cat' ) ); ?>"
						   class="view-more-button js-tab-view-more"><?php esc_html_e( 'View More', 'kidz' ); ?></a>
					</div>
				<?php } ?>
			</div>
			<?php if ( ideapark_mod( 'featured_badge_text_hide' ) && ( $tab == 'featured_products' ) ) {
				ideapark_mod_set_temp( 'featured_badge_text', $_featured_badge_text );
			} ?>
			<?php $is_first = false; ?>
		<?php } ?>
	</div>
<?php } ?>