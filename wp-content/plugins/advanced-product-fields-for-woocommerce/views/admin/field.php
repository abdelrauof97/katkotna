<?php
use SW_WAPF\Includes\Classes\Html;
use SW_WAPF\Includes\Classes\Fields;
/* @var $field array */
/* @var $type string */

$field_types = Fields::get_field_types();
$field_icons = Fields::get_field_icons();
?>

<div class="wapf-field" rv-data-field-id="field.id" rv-class-wapf--active="activeField | equalIds field">
    <div class="wapf-field__header">
        <div class="wapf-field-sort sort--left" title="<?php esc_attr_e( 'Drag & drop', 'advanced-product-fields-for-woocommerce' ) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 6h.006M8 12h.006M8 18h.006m7.988-12H16m-.006 6H16m-.006 6H16"></path></svg>
        </div>
        <div class="wapf-field-icon">
            <div rv-show="field.type | eq 'url'">
                <?php echo wp_kses( $field_icons[ 'url' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'email'">
                <?php echo wp_kses( $field_icons[ 'email' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'textarea'">
                <?php echo wp_kses( $field_icons[ 'textarea' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'text'">
                <?php echo wp_kses( $field_icons[ 'text' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'number'">
                <?php echo wp_kses( $field_icons[ 'number' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'true-false'">
                <?php echo wp_kses( $field_icons[ 'true-false' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'checkboxes'">
                <?php echo wp_kses( $field_icons[ 'checkboxes' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'select'">
                <?php echo wp_kses( $field_icons[ 'select' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'radio'">
                <?php echo wp_kses( $field_icons[ 'radio' ], Html::$allow_only_svg ) ?>
            </div>
            <div rv-show="field.type | eq 'content'">
                <?php echo wp_kses( $field_icons[ 'content' ], Html::$allow_only_svg ) ?>
            </div>
        </div>
        <div class="wapf-field__label" rv-on-click="setActiveField">
            {field.label} 
            <span class="wapf-field-id">
                ID: {field.id}
            </span>
        </div>

        <div class="wapf-field-type">
            
            <?php foreach( $field_types as $ft ) { ?>
                <div rv-show="field.type | eq '<?php echo esc_attr( $ft['id'] ) ?>'">
                    <span><?php echo esc_html( $ft['title'] ) ?></span>
                </div>
            <?php } ?>
            
        </div>
        
        <div class="wapf-field-actions">
            <a  href="#" rv-on-click="duplicateField" title="<?php esc_attr_e( 'Duplicate field', 'advanced-product-fields-for-woocommerce' ) ?>">
                <?php esc_html_e( 'Duplicate', 'advanced-product-fields-for-woocommerce' ) ?>
            </a>
            <a href="#" style="color: #a00 !important" title="<?php esc_attr_e( 'Delete field', 'advanced-product-fields-for-woocommerce' ) ?>" rv-on-click="deleteField">
                <?php esc_html_e( 'Delete', 'advanced-product-fields-for-woocommerce' ) ?>
            </a>
        </div>
    </div>

    <div class="wapf-field__body" style="display: none;">
        <div class="wapf-field__setting">
            <div class="wapf-setting__label">
                <label><?php echo esc_html_e( 'Type', 'advanced-product-fields-for-woocommerce' ) ?></label>
            </div>
            <div class="wapf-setting__input">
                <div class="wapf-flex wapf-v-center" rv-show="field.type | eq 'url'">
                    <span style="padding-left: 12px"><?php echo esc_html( $field_types['url']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'email'">
                    <span><?php echo esc_html( $field_types['email']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'textarea'">
                    <span><?php echo esc_html( $field_types['textarea']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'text'">
                    <span><?php echo esc_html( $field_types['text']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'number'">
                    <span><?php echo esc_html( $field_types['number']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'true-false'">
                    <span><?php echo esc_html( $field_types['true-false']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'checkboxes'">
                    <span><?php echo esc_html( $field_types['checkboxes']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'select'">
                    <span><?php echo esc_html( $field_types['select']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'radio'">
                    <span><?php echo esc_html( $field_types['radio']['title'] ) ?></span>
                </div>
                <div rv-show="field.type | eq 'content'">
                    <span><?php echo esc_html( $field_types['content']['title'] ) ?></span>
                </div>
            </div>
        </div>
        <?php

        Html::setting([
            'type'              => 'text',
            'id'                => 'label',
            'label'             => __('Label','advanced-product-fields-for-woocommerce'),
            'description'       => __('This is the label that is shown above the field.','advanced-product-fields-for-woocommerce'),
            'is_field_setting'  => true
        ]);

        echo '<div rv-if="field.type | neq \'content\'">';

        Html::setting([
            'type'              => 'textarea',
            'id'                => 'description',
            'label'             => __('Instructions','advanced-product-fields-for-woocommerce'),
            'description'       => __('Instructions can be used to display extra info near the field.','advanced-product-fields-for-woocommerce'),
            'is_field_setting'  => true
        ]);

        Html::setting([
            'type'              => 'true-false',
            'id'                => 'required',
            'label'             => __('Required','advanced-product-fields-for-woocommerce'),
            'description'       => __('Select "yes" if the field should require input from the user.','advanced-product-fields-for-woocommerce'),
            'is_field_setting'  => true
        ]);

        echo '</div>';

        foreach(\SW_WAPF\Includes\Classes\Fields::get_field_options($type) as $field_type => $options) {?>
            <div rv-if="field.type | eq '<?php echo $field_type; ?>'" class="wapf_field__options">
                <?php
                    foreach($options as $option) {
                        if(!empty($option) && isset($option['id']) && isset($option['type']))
                            Html::setting($option);
                    }
                ?>
            </div>
        <?php
        }

        echo '<div rv-if="field.type | neq \'content\'">';

        Html::setting([
            'type'              => 'true-false',
            'id'                => 'qty_based',
            'label'             => __('Repeat field','advanced-product-fields-for-woocommerce'),
            'description'       => __('Should this field be repeated multiple times either by a button or quantity input box?','advanced-product-fields-for-woocommerce'),
            'is_field_setting'  => true,
            'pro'               => true
        ]);


        Html::setting([
            'type'              => 'conditionals',
            'id'                => 'conditionals',
            'label'             => __('Conditional logic','advanced-product-fields-for-woocommerce'),
            'description'       => __('Only show this field when certain rules are true.','advanced-product-fields-for-woocommerce'),
            'is_field_setting'  => true
        ]);

        echo '</div>';

        Html::setting([
            'type'              => 'attributes',
            'id'                => 'attributes',
            'label'             => __('Wrapper attributes','advanced-product-fields-for-woocommerce'),
            'is_field_setting'  => true
        ]);
        ?>

    </div>

</div>