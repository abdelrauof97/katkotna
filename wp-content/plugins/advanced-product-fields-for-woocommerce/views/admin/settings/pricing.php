<?php
/* @var $model array */
?>

<div class="wapf-field__setting" data-setting="<?php echo $model['id']; ?>">

    <div class="wapf-setting__label">
        <label><?php echo esc_html($model['label'] );?></label>
        <?php if(isset($model['description'])) { ?>
            <p class="wapf-description">
                <?php echo esc_html($model['description'] );?>
            </p>
        <?php } ?>
    </div>

    <div class="wapf-setting__input">

        <div class="wapf-toggle" rv-unique-checkbox>
            <input rv-on-change="onChange" rv-checked="<?php echo $model['is_field_setting'] ? 'field' : 'settings'; ?>.pricing.enabled" type="checkbox" >
            <label class="wapf-toggle-label" for="wapf-toggle-">
                <span class="wapf-toggle-inner" data-true="<?php esc_attr_e( 'Yes', 'advanced-product-fields-for-woocommerce' ) ?>" data-false="<?php esc_attr_e( 'No', 'advanced-product-fields-for-woocommerce' ) ?>"></span>
                <span class="wapf-toggle-switch"></span>
            </label>
        </div>

        <div class="wapf-setting__pricing" rv-show="<?php echo $model['is_field_setting'] ? 'field' : 'settings'; ?>.pricing.enabled">
            <div class="wapf-pricing__inner">
                <div>
                    <select rv-on-change="onChange" rv-value="<?php echo $model['is_field_setting'] ? 'field' : 'settings'; ?>.pricing.type">
                        <?php
                        foreach(\SW_WAPF\Includes\Classes\Fields::get_pricing_options() as $k => $v) {
                            echo '<option ' . ($v['pro'] === true ? 'disabled' : '') . ' value="'.$k.'">'.$v['label'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <input rv-on-change="onChange" type="number" min="0" step="any" rv-value="<?php echo $model['is_field_setting'] ? 'field' : 'settings'; ?>.pricing.amount" />
                </div>
            </div>
            <div>
                <div class="apf-info-note">
                    <div class="dashicon dashicons dashicons-warning"></div>
                    <div>
                        <?php _e( 'Please read this', 'advanced-product-fields-for-woocommerce' ); ?>

                        <?php
                        $p1 = __( 'Please note in the free version, pricing is applied as a fixed add-on fee and does not change with the product quantity. Only the "flat fee" option is available.', 'advanced-product-fields-for-woocommerce' );

                        $link = sprintf(
                            '<a target="_blank" href="%s">%s</a>',
                            'https://www.studiowombat.com/knowledge-base/all-pricing-options-explained/?utm_source=apffree&utm_medium=plugin&utm_campaign=info',
                            __( 'See which other pricing options are available in the premium version.', 'advanced-product-fields-for-woocommerce' )
                        );

                        $text = "<p style='font-size:1rem!important'>{$p1}</p><p style='font-size:1rem!important'>{$link}</p>";

                        \SW_WAPF\Includes\Classes\Html::help_modal(
                            $text,
                            __( 'Important note about pricing', 'advanced-product-fields-for-woocommerce' ),
                            __( 'important note about pricing', 'advanced-product-fields-for-woocommerce' )
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>