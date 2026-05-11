<?php
/* @var $model array */
use SW_WAPF\Includes\Classes\Helper;
use SW_WAPF\Includes\Classes\Html;


$_field_types = \SW_WAPF\Includes\Classes\Fields::get_field_types();
$field_icons = \SW_WAPF\Includes\Classes\Fields::get_field_icons();

$_free_types = array_values(array_filter($_field_types, function($t) { return !$t['pro']; }));
$_pro_types  = array_values(array_filter($_field_types, function($t) { return  $t['pro']; }));

// Closure to render the popover (used in two places below).
$_render_popover = function() use ($_free_types, $_pro_types, $field_icons ) { ?>
    <div class="wapf-field-popover">
        <div class="wapf-field-popover-items">
            <?php foreach ($_free_types as $_type) {
                $icon = $field_icons[$_type['id']] ?? ''; ?>
                <a href="#" class="wapf-field-item" data-field-type="<?php echo esc_attr( $_type['id'] ) ?>" rv-on-click="addFieldOfType">
                    <span class="wapf-field-item-icon"><?php echo wp_kses( $icon, Html::$allow_only_svg ) ?></span>
                    <span class="wapf-field-item-label"><?php echo esc_html( $_type['title'] ) ?></span>
                </a>
            <?php } ?>
            <?php foreach ($_pro_types as $_type) {
                $icon = $field_icons[$_type['id']] ?? ''; ?>
                <span class="wapf-field-item wapf-field-item-pro">
                    <span class="wapf-field-item-icon"><?php echo wp_kses( $icon, Html::$allow_only_svg ) ?></span>
                    <span class="wapf-field-item-label"><?php echo esc_html($_type['title']); ?> (Pro)</span>
                </span>
            <?php } ?>
        </div>
    </div>
<?php };
?>
<div rv-controller="FieldListCtrl"
     data-raw-fields="<?php echo Helper::thing_to_html_attribute_string( $model['fields'] ) ?>"
     data-field-conditions="<?php echo Helper::thing_to_html_attribute_string( $model['condition_options'] ) ?>"
>

    <input type="hidden" name="wapf-fields" rv-value="fieldsJson" />

    <div class="wapf-field-list">

        <div class="wapf-field-list__body">
            <div rv-show="fields | isEmpty" class="wapf-list--empty" style="display: <?php echo empty($model['fields']) ? 'block' : 'none';?>;">
                <div class="apf-add-first">
                    <div class="apf-new-field-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="m20 10.5v-3.7c0-1.68016 0-2.52024-.327-3.16197-.2876-.56449-.7465-1.02343-1.311-1.31105-.6418-.32698-1.4818-.32698-3.162-.32698h-6.4c-1.68016 0-2.52024 0-3.16197.32698-.56449.28762-1.02343.74656-1.31105 1.31105-.32698.64173-.32698 1.48181-.32698 3.16197v10.4c0 1.6802 0 2.5202.32698 3.162.28762.5645.74656 1.0234 1.31105 1.311.64173.327 1.48181.327 3.16197.327h3.2m2-11h-6m2 4h-2m8-8h-8m10 14v-6m-3 3h6" stroke="var(--apf-primary-color)" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path></svg>
                    </div>
                    <p>
                        <?php 
                        !empty( $model['for_product_admin'] ) ?
                            esc_html_e( 'Get started by creating new input fields to this product.', 'advanced-product-fields-for-woocommerce' ) 
                            : esc_html_e( 'Get started by creating new input fields for your WooCommerce products.', 'advanced-product-fields-for-woocommerce' ) ?>
                    </p>
                </div>
                
                <div class="wapf-add-field-wrapper">
                    <button class="button button-primary button-large wapf-v-center" style="display: flex;font-size:14px" rv-on-click="toggleAddFieldPopover">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 36 36"><path fill="white" d="M31 15H21V5a3 3 0 1 0-6 0v10H5a3 3 0 1 0 0 6h10v10a3 3 0 1 0 6 0V21h10a3 3 0 1 0 0-6z"></path></svg>
                        <span style="padding-left: 6px"><?php esc_html_e( "add an input field", 'advanced-product-fields-for-woocommerce' ) ?></span>
                    </button>
                    <?php $_render_popover(); ?>
                </div>
            </div>

            <div rv-each-field="fields" rv-cloak>
                <?php \SW_WAPF\Includes\Classes\Html::admin_field( [], $model['type'] ) ?>
            </div>

        </div>

        <div rv-cloak>
            <div rv-show="fields | isNotEmpty" class="wapf-field-list__footer">
                <div class="wapf-add-field-wrapper">
                    <button class="button button-primary button-large wapf-v-center" style="display: flex;font-size:14px" rv-on-click="toggleAddFieldPopover">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 36 36"><path fill="white" d="M31 15H21V5a3 3 0 1 0-6 0v10H5a3 3 0 1 0 0 6h10v10a3 3 0 1 0 6 0V21h10a3 3 0 1 0 0-6z"></path></svg>
                        <span style="padding-left: 6px"><?php esc_html_e( "input field", 'advanced-product-fields-for-woocommerce' ) ?></span>
                    </button>
                    <?php $_render_popover(); ?>
                </div>
            </div>
        </div>

    </div>

</div>