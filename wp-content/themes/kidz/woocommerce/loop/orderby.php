<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @package       WooCommerce/Templates
 * @version         11.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

?>
<div class="col-md-4 col-sm-12 ip-shop-ordering-row">
    <form class="woocommerce-ordering" method="get">
        <select name="orderby" class="orderby styled" aria-label="<?php esc_attr_e('Shop order', 'woocommerce'); ?>">
            <?php foreach ($catalog_orderby_options as $id => $name) : ?>
                <option
                        value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="paged" value="1"/>
        <?php wc_query_string_form_fields(null, ['orderby', 'submit', 'paged', 'product-page']); ?>
    </form>

    <?php if (!ideapark_mod('shop_hide_sidebar') && is_active_sidebar('shop-sidebar')) { ?>
        <a href="#" class="mobile-sidebar">
            <?php echo esc_html(ideapark_mod('filter_button_text')); ?>
            <svg>
                <use xlink:href="<?php echo esc_url(ideapark_svg_url()); ?>#svg-filter"/>
            </svg>
        </a>
    <?php } ?>
</div>

