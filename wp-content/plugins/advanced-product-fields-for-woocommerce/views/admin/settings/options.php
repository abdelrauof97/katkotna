<?php /* @var $model array */ ?>


<div class="wapf-field__setting" data-setting="<?php echo $model['id']; ?>">
    <div class="wapf-setting__label">
        <label><?php echo esc_html( $model['label'] ) ?></label>
        <?php if(isset($model['description'])) { ?>
            <p class="wapf-description">
                <?php echo esc_html( $model['description'] ) ?>
            </p>
        <?php } ?>
    </div>
    <div class="wapf-setting__input">
        <div class="wapf-option-table" style="width: 100%;" rv-show="field.choices | isNotEmpty">
            <div class="wapf-options__header">
                <div class="wapf-option-cell wapf-option__sort"></div>
                <div class="wapf-option-cell wapf-option__flex">
                    <?php esc_html_e( 'Option label', 'advanced-product-fields-for-woocommerce' ) ?>
                </div>
                <?php if(isset($model['show_pricing_options']) && $model['show_pricing_options']) { ?>
                    <div class="wapf-option-cell wapf-option__flex"><?php esc_html_e('Adjust pricing','advanced-product-fields-for-woocommerce'); ?></div>
                    <div class="wapf-option-cell wapf-option__flex"><?php esc_html_e('Pricing amount','advanced-product-fields-for-woocommerce'); ?></div>
                <?php } ?>
                <div class="wapf-option-cell wapf-option__selected"><?php esc_html_e('Selected', 'advanced-product-fields-for-woocommerce'); ?></div>
            </div>
            <div rv-sortable-options="field.choices" class="wapf-options__body">
                <div class="wapf-option" rv-each-choice="field.choices" rv-data-option-slug="choice.slug">
                    <div class="wapf-option-cell wapf-option__sort"><span rv-sortable-option class="wapf-option-sort">☰</span></div>
                    <div class="wapf-option-cell wapf-option__flex">
                        <input rv-on-keyup="onChange" rv-on-change="onChange" type="text" class="choice-label" rv-value="choice.label"/>
                    </div>
                    <?php if( isset( $model['show_pricing_options'] ) && $model['show_pricing_options'] ) { ?>

                        <div class="wapf-option-cell wapf-option__flex">
                            <select rv-on-change="onChange" rv-value="choice.pricing_type">
                                <option value="none"><?php esc_html_e('No price change','advanced-product-fields-for-woocommerce'); ?></option>
                                <?php
                                foreach(\SW_WAPF\Includes\Classes\Fields::get_pricing_options() as $k => $v) {
                                    echo '<option ' . ($v['pro'] === true ? 'disabled' : '') . ' value="'.$k.'">'.$v['label'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="wapf-option-cell wapf-option__flex">
                            <input rv-on-change="onChange" type="number" min="0" step="any" rv-value="choice.pricing_amount" />
                        </div>
                        
                    <?php } ?>

                    <div class="wapf-option-cell wapf-option__selected">
                        <div class="wapf-toggle wapf-toggle--small" rv-unique-checkbox>
                            <input data-multi-option="<?php echo isset($model['multi_option']) ? $model['multi_option'] : '0' ;?>" rv-on-change="field.checkSelected" rv-checked="choice.selected" type="checkbox" />
                            <label class="wapf-toggle-label" for="wapf-toggle-">
                                <span class="wapf-toggle-inner" data-true="" data-false=""></span>
                                <span class="wapf-toggle-switch"></span>
                            </label>
                        </div>
                    </div>

                    <a style="position:absolute;right:-11px;top:12px" href="#" rv-on-click="field.deleteChoice" class="wapf-button--tiny-rounded wapf-del"></a>

                </div>
            </div>
        </div>


        <div style="display: flex;align-items: center;justify-content: space-between;width:100%;">
            <div>
                <a href="#" rv-on-click="field.addChoice" class="button">
                    <?php esc_html_e( 'Add option', 'advanced-product-fields-for-woocommerce' ) ?>
                </a>
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