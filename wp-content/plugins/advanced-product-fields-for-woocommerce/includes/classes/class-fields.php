<?php

namespace SW_WAPF\Includes\Classes {


    use SW_WAPF\Includes\Models\Field;
	use SW_WAPF\Includes\Models\FieldGroup;

	class Fields
    {

		public static function get_field_types() {
			$types = [
				'text' => [
					'id'    => 'text',
					'title' => __('Text','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
				'textarea' => [
					'id'    => 'textarea',
					'title' => __('Multi-line text','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
				'number' => [
					'id'    => 'number',
					'title' => __('Number','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
				'email' => [
					'id'    => 'email',
					'title' => __('E-mail','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
                'url' => [
					'id'    => 'url',
					'title' => __('URL','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
                'select' => [
					'id'    => 'select',
					'title' => __('Select','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
                'true-false' => [
					'id'    => 'true-false',
					'title' => __('True/False','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
                'checkboxes' => [
					'id'    => 'checkboxes',
					'title' => __('Checkboxes','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
                'radio' => [
					'id'    => 'radio',
					'title' => __('Radio buttons','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
                'content' => [
					'id'    => 'content',
					'title' => __('Paragraph','advanced-product-fields-for-woocommerce'),
					'pro'   => false,
				],
                'file' => [
					'id'    => 'file',
					'title' => __('File upload','advanced-product-fields-for-woocommerce'),
					'pro'   => true,
				],
                'date' => [
					'id'    => 'date',
					'title' => __('Calendar','advanced-product-fields-for-woocommerce'),
					'pro'   => true,
				],
                'image-swatch' => [
					'id'    => 'image-swatch',
					'title' => __('Image swatches','advanced-product-fields-for-woocommerce'),
					'pro'   => true,
				],
                'color-swatch' => [
					'id'    => 'color-swatch',
					'title' => __('Color swatches','advanced-product-fields-for-woocommerce'),
					'pro'   => true,
				],
                'text-swatch' => [
					'id'    => 'text-swatch',
					'title' => __('Text swatches','advanced-product-fields-for-woocommerce'),
					'pro'   => true,
				],
                'cards' => [
                    'id'    => 'cards',
                    'title' => __('Cards','advanced-product-fields-for-woocommerce'),
                    'pro'   => true,
                ],
                'img-qty' => [
                    'id'    => 'img-qty',
                    'title' => __('Images + quantities','advanced-product-fields-for-woocommerce'),
                    'pro'   => true,
                ],
                'calc' => [
                    'id'    => 'calc',
                    'title' => __('Calculation','advanced-product-fields-for-woocommerce'),
                    'pro'   => true,
                ],
                'products' =>  [
                    'id'    => 'products',
                    'title' => __('Child Products','advanced-product-fields-for-woocommerce'),
                    'pro'   => true,
                ],
                'd_content' => [
					'id'    => 'd_content',
					'title' => __('Shortcodes & HTML','advanced-product-fields-for-woocommerce'),
					'pro'   => true,
				],
                'img' => [
                    'id'    => 'img',
                    'title' => __('Image','advanced-product-fields-for-woocommerce'),
                    'pro'   => true,
                ],
                'section' => [
					'id'    => 'section',
					'title' => __('Section','advanced-product-fields-for-woocommerce'),
					'pro'   => true,
				],
			];

			return apply_filters( 'wapf/field_types', $types);
            
		}

        public static function get_field_icons(): array {
             return [
                 'text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path stroke-width="0.5" stroke="currentColor" fill="currentColor" d="M2 5V3a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v2a1 1 0 0 1-2 0V4h-7v16h3a1 1 0 0 1 0 2H8a1 1 0 0 1 0-2h3V4H4v1a1 1 0 0 1-2 0Z"/></svg>',
                 'textarea' => '<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path stroke="none" fill="currentColor" d="m368.41 29.97h-347.05c-13.26 0-24.01 10.75-24.01 24.01v35.51c0 13.26 10.75 24.01 24.01 24.01s24.01-10.75 24.01-24.01v-11.51h125.51v361.32h-29.82c-13.26 0-24.01 10.75-24.01 24.01s10.75 24.01 24.01 24.01h107.65c13.26 0 24.01-10.75 24.01-24.01s-10.75-24.01-24.01-24.01h-29.82v-361.32h125.51v11.51c0 13.26 10.75 24.01 24.01 24.01s24.01-10.75 24.01-24.01v-35.51c0-13.26-10.75-24.01-24.01-24.01z"></path><path stroke="none" fill="currentColor" d="m485.35 215.18h-164.12c-13.26 0-24.01 10.75-24.01 24.01v16.79c0 13.26 10.75 24.01 24.01 24.01 10.75 0 19.84-7.06 22.9-16.79h35.15v176.11h-1.45c-13.26 0-24.01 10.75-24.01 24.01s10.75 24.01 24.01 24.01h50.91c13.26 0 24.01-10.75 24.01-24.01s-10.75-24.01-24.01-24.01h-1.45v-176.11h35.15c3.06 9.73 12.16 16.79 22.9 16.79 13.26 0 24.01-10.75 24.01-24.01v-16.79c0-13.26-10.75-24.01-24.01-24.01z"></path></svg>',
                 'number' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path stroke="none" fill="currentColor" d="M20 14h-4.3l.73-4H20a1 1 0 0 0 0-2h-3.21l.69-3.81A1 1 0 0 0 16.64 3a1 1 0 0 0-1.22.82L14.67 8h-3.88l.69-3.81A1 1 0 0 0 10.64 3a1 1 0 0 0-1.22.82L8.67 8H4a1 1 0 0 0 0 2h4.3l-.73 4H4a1 1 0 0 0 0 2h3.21l-.69 3.81A1 1 0 0 0 7.36 21a1 1 0 0 0 1.22-.82L9.33 16h3.88l-.69 3.81a1 1 0 0 0 .84 1.19 1 1 0 0 0 1.22-.82l.75-4.18H20a1 1 0 0 0 0-2zM9.7 14l.73-4h3.87l-.73 4z"/></svg>',
                 'email' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Zm0 0v1.5a2.5 2.5 0 0 0 2.5 2.5v0a2.5 2.5 0 0 0 2.5-2.5V12a9 9 0 1 0-9 9h4"/></svg>',
                 'url' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M9 17H7A5 5 0 0 1 7 7h2m6 10h2a5 5 0 0 0 0-10h-2m-8 5h10"/></svg>',
                 'select' => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke="currentColor" fill="currentColor" stroke-width="0.5" d="m19 22.6h-14c-2 0-3.6-1.6-3.6-3.6v-14c0-2 1.6-3.6 3.6-3.6h14c2 0 3.6 1.6 3.6 3.6v14c0 2-1.6 3.6-3.6 3.6zm-14-20c-1.3 0-2.4 1.1-2.4 2.4v14c0 1.3 1.1 2.4 2.4 2.4h14c1.3 0 2.4-1.1 2.4-2.4v-14c0-1.3-1.1-2.4-2.4-2.4z"/><path fill="currentColor" stroke="currentColor" stroke-width="0.5" d="m12 16.6c-.2 0-.3-.1-.4-.2l-6-6c-.2-.1-.2-.4-.2-.6s.4-.4.6-.4h12c.2 0 .5.1.6.4s0 .5-.1.7l-6 6c-.2 0-.3.1-.5.1zm-4.6-6 4.6 4.6 4.6-4.6z"/></svg>',
                 'true-false' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path stroke="none" fill="currentColor" d="M4 3h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm1 2v14h14V5H5zm6.003 11L6.76 11.757l1.414-1.414 2.829 2.829 5.656-5.657 1.415 1.414L11.003 16z"/></svg>',
                 'radio' => '<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" stroke-width="5" stroke="currentColor" d="m256 490c-62.5 0-121.3-24.3-165.5-68.5s-68.5-103-68.5-165.5 24.3-121.3 68.5-165.5 103-68.5 165.5-68.5 121.3 24.3 165.5 68.5 68.5 103 68.5 165.5-24.3 121.3-68.5 165.5-103 68.5-165.5 68.5zm0-436c-111.4 0-202 90.6-202 202s90.6 202 202 202 202-90.6 202-202-90.6-202-202-202z"/><path stroke="currentColor" stroke-width="5" fill="currentColor" d="m256 390c-73.9 0-134-60.1-134-134s60.1-134 134-134 134 60.1 134 134-60.1 134-134 134zm0-236c-56.2 0-102 45.8-102 102s45.8 102 102 102 102-45.8 102-102-45.8-102-102-102z"/></svg>',
                 'checkboxes' => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" stroke="currentColor" stroke-width="0.5" d="m18.375 6.25h-8.75a3.044 3.044 0 0 0 -3.375 3.375v8.75a3.044 3.044 0 0 0 3.375 3.375h8.75a3.044 3.044 0 0 0 3.375-3.375v-8.75a3.044 3.044 0 0 0 -3.375-3.375zm1.875 12.125c0 1.332-.543 1.875-1.875 1.875h-8.75c-1.332 0-1.875-.543-1.875-1.875v-8.75c0-1.332.543-1.875 1.875-1.875h8.75c1.332 0 1.875.543 1.875 1.875zm-16.5-12.755v8.76c0 1.2.483 1.493.642 1.59a.751.751 0 0 1 -.784 1.28 3.106 3.106 0 0 1 -1.358-2.87v-8.76a3.068 3.068 0 0 1 3.37-3.37h8.76a3.1 3.1 0 0 1 2.87 1.358.75.75 0 1 1 -1.279.784c-.1-.159-.393-.642-1.591-.642h-8.76c-1.328 0-1.87.542-1.87 1.87zm13.28 6.18a.75.75 0 0 1 0 1.061l-3.33 3.339a.748.748 0 0 1 -1.06 0l-1.67-1.67a.75.75 0 0 1 1.06-1.06l1.137 1.136 2.8-2.8a.749.749 0 0 1 1.063-.006z"/></svg>',
                 'image-swatch' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m4 17 3.59-3.23a2 2 0 0 1 2.75.07L11.5 15l3.59-3.59a2 2 0 0 1 2.82 0L20 13.5M11 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2Z"/></svg>',
                 'img-qty' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m4 17 3.59-3.23a2 2 0 0 1 2.75.07L11.5 15l3.59-3.59a2 2 0 0 1 2.82 0L20 13.5M11 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2Z"/></svg>',
                 'cards' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" fill="currentColor" stroke="currentColor" stroke-width="0.5" d="M28 5H4c-.796 0-1.559.316-2.121.879A2.996 2.996 0 0 0 1 8v16c0 .796.316 1.559.879 2.121A2.996 2.996 0 0 0 4 27h24c.796 0 1.559-.316 2.121-.879A2.996 2.996 0 0 0 31 24V8c0-.796-.316-1.559-.879-2.121A2.996 2.996 0 0 0 28 5Zm0 2H4a.997.997 0 0 0-1 1v16a.997.997 0 0 0 1 1h24a.997.997 0 0 0 1-1V8a.997.997 0 0 0-1-1Z"/><path stroke="none" fill="currentColor" d="M14.132 13.748a2 2 0 0 0-2-2h-5a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h5a2 2 0 0 0 2-2v-5Zm-2 0v5h-5v-5h5ZM18 13h8a1 1 0 0 0 0-2h-8a1 1 0 0 0 0 2ZM18 17h8a1 1 0 0 0 0-2h-8a1 1 0 0 0 0 2ZM18 21h8a1 1 0 0 0 0-2h-8a1 1 0 0 0 0 2Z"/></svg>',
                 'color-swatch' => '<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" stroke="currentColor" stroke-width="1" d="M11.456 49.837a1.75 1.75 0 0 0-2.912 0l-3.323 4.984a5.743 5.743 0 1 0 9.558 0zM10 60.25a2.244 2.244 0 0 1-1.867-3.488l1.867-2.8 1.867 2.8A2.244 2.244 0 0 1 10 60.25zM58.058 2.837a7.256 7.256 0 0 0-10.214-.894l-6.95 5.832c-3.452-2.056-7.835.397-7.889 4.414a5.207 5.207 0 0 0 .339 1.921L16.805 27.988a24.924 24.924 0 0 0-8.541 14.89 1.748 1.748 0 0 0 1.715 2.043A24.925 24.925 0 0 0 26.125 39.1l16.54-13.879c3.451 2.056 7.833-.395 7.888-4.412a5.215 5.215 0 0 0-.339-1.922l6.95-5.832a7.258 7.258 0 0 0 .894-10.218ZM23.875 36.415A21.433 21.433 0 0 1 12.2 41.321a21.429 21.429 0 0 1 6.859-10.652l15.935-13.44 5.093 6.008-16.212 13.178ZM46.439 22.05a1.752 1.752 0 0 1-2.465-.216L36.9 13.408c-.866-1.032-.29-2.614 1.036-2.848a1.75 1.75 0 0 1 1.645.598l7.071 8.427a1.75 1.75 0 0 1-.213 2.465Zm8.475-11.68-6.7 5.624-4.82-5.745 6.7-5.624c2.198-1.872 5.598-.662 6.119 2.177a3.75 3.75 0 0 1-1.298 3.568h-.001Z"/></svg>',
                 'text-swatch' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="currentColor" stroke="none" d="M43 3H5a2 2 0 0 0-2 2v38a2 2 0 0 0 2 2h38a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Zm-2 38H7V7h34Z"></path><path fill="currentColor" stroke="none" d="M17.2 31h13.6l2.6 6h4.4L26.5 11h-5L10.2 37h4.4ZM24 14.9 29.1 27H18.9Z"/></path></svg>',
                 'file' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M13 3H7a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h10a2 2 0 0 0 2-2V9m-6-6 6 6m-6-6v5a1 1 0 0 0 1 1h5"/></svg>',
                 'content' => '<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg"><path stroke="none" fill="currentColor" d="m13 17c0-1.1046.8954-2 2-2h21c1.1046 0 2 .8954 2 2s-.8954 2-2 2h-21c-1.1046 0-2-.8954-2-2z"/><path stroke="none" fill="currentColor" d="m44 17c0 1.1046.8954 2 2 2h3c1.1046 0 2-.8954 2-2s-.8954-2-2-2h-3c-1.1046 0-2 .8954-2 2z"/><path stroke="none" fill="currentColor" d="m15 25c-1.1046 0-2 .8954-2 2s.8954 2 2 2h34c1.1046 0 2-.8954 2-2s-.8954-2-2-2z"/><path stroke="none" fill="currentColor" d="m15 35c-1.1046 0-2 .8954-2 2s.8954 2 2 2h6c1.1046 0 2-.8954 2-2s-.8954-2-2-2z"/><path stroke="none" fill="currentColor" d="m49 39h-18c-1.1046 0-2-.8954-2-2s.8954-2 2-2h18c1.1046 0 2 .8954 2 2s-.8954 2-2 2z"/><path stroke="none" fill="currentColor" d="m15 45c-1.1046 0-2 .8954-2 2s.8954 2 2 2h16c1.1046 0 2-.8954 2-2s-.8954-2-2-2z"/></svg>',
                 'img' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m4 17 3.59-3.23a2 2 0 0 1 2.75.07L11.5 15l3.59-3.59a2 2 0 0 1 2.82 0L20 13.5M11 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2Z"/></svg>',
                 'd_content' => '<svg viewBox="0 0 505 505" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" stroke="none" d="m206.835 459.6c-1.845 0-3.72-.224-5.59-.692-12.313-3.078-19.799-15.555-16.72-27.867l91.919-367.676c3.078-12.312 15.553-19.797 27.867-16.72 12.313 3.078 19.798 15.555 16.72 27.867l-91.919 367.676c-2.611 10.441-11.981 17.412-22.277 17.412zm183.821-91.926c-5.881 0-11.763-2.243-16.249-6.73-8.974-8.974-8.974-23.524 0-32.498l75.669-75.669-75.669-75.67c-8.974-8.975-8.974-23.524 0-32.498 8.975-8.974 23.525-8.974 32.498 0l91.919 91.919c8.974 8.975 8.974 23.524 0 32.498l-91.919 91.919c-4.487 4.486-10.368 6.729-16.249 6.729zm-275.758 0c-5.881 0-11.762-2.243-16.249-6.73l-91.918-91.919c-8.974-8.974-8.974-23.524 0-32.498l91.919-91.919c8.973-8.973 23.524-8.975 32.498 0 8.974 8.974 8.974 23.524 0 32.498l-75.669 75.67 75.669 75.669c8.974 8.974 8.974 23.524 0 32.498-4.488 4.488-10.368 6.731-16.25 6.731z" /></svg>',
                 'section' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><path fill="currentColor" stroke="none" fill-rule="evenodd" d="M2 1.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM2 5v5h11V5H2Zm0-1a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H2Zm-.5 10a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM4 1.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM3.5 14a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM6 1.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM5.5 14a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM8 1.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.5 14a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM10 1.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM9.5 14a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM12 1.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM11.5 14a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM14 1.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM13.5 14a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Z" clip-rule="evenodd"/></svg>',
                 'date' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" stroke="none" d="M6 22h12a3 3 0 0 0 3-3V7a2 2 0 0 0-2-2h-2V3a1 1 0 0 0-2 0v2H9V3a1 1 0 0 0-2 0v2H5a2 2 0 0 0-2 2v12a3 3 0 0 0 3 3Zm-1-9.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5V19a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1Z"/></svg>',
                 'calc' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 56 56"><path stroke="currentColor" stroke-width="1" fill="currentColor" d="m20.7 23-1.5 1.7.1 1.2h4c-.4 4-1.5 8.1-2.6 14.5a36.6 36.6 0 0 1-2.6 10.8c-.4.8-1 2-1.8 2L13 50.9c-.3-.2-.2-.2-1 0-.8.7-1.5 1.7-1.5 2.6.7 1.2 1.5 2.2 3 2.2C15 55.7 17 55 19 53c2.8-2.7 5-6.4 6.7-14.4C27 34.2 28 30 28.2 26l4.8-1.1 1-2h-5.3c1.4-8.7 2.5-10 3.8-10s2.7.7 3.1 2c.4.5 1 1 1.3.1.7-.4 1.5-1.4 1.6-2.4 0-1-1.2-2.3-3.3-2.3-2 0-5 1.3-7.4 3.8-2.2 2.3-2.6 5.8-4.1 8.9h-3Zm15.4 5.9c1.5-2 2.4-2.7 2.8-2.7 1 0 .9.5 1.7 4l1.4 3.7c-2.7 4.1-4.7 6.8-5.9 6.8-.4 0-.8-.5-1-.8-.3-.3.2-.5-1-.5-1 0-2.2 1.2-2.2 2.7 0 1.5 1 2.6 2.4 2.6 2.4 0 4.5-1.7 8.4-8.6l1.1 3.8c1 3.5 2.2 4.8 4.1 4.8 1.3 0 3.7-1.5 6.3-5.6l-1-1.3c-1.7 1.9-2.7 2.8-3.3 2.8-.7 0-1.3-1-2.1-3.6l-1.7-5.5c1-1.5 2-2.7 2.8-3.7 1-1.1 1.9-1.6 2.4-1.6.4 0 .8 1 1.2.4.2.4.4.6.8.6.8 0 2.2-1.1 2.2-2.6 0-1.3-.8-2.5-2.2-2.5-2.2 0-4.2 2-7.2 7.4l-1.4-2.3c-1-3.4-1.8-5-3-5-2 0-4.4 2-6.8 5.5l1.2 1.2Z"/></svg>',
                 'products' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M447.9 176c0-10.6-2.6-21-7.6-30.3l-49.1-91.9c-4.3-13-16.5-21.8-30.3-21.8H87.1c-13.8 0-26 8.8-30.4 21.9L7.6 145.8c-5 9.3-7.6 19.7-7.6 30.3C.1 236.6 0 448 0 448c0 17.7 14.3 32 32 32h384c17.7 0 32-14.3 32-32 0 0-.1-211.4-.1-272zm-87-112l50.8 96H286.1l-12-96h86.8zM192 192h64v64h-64v-64zm49.9-128l12 96h-59.8l12-96h35.8zM87.1 64h86.8l-12 96H36.3l50.8-96zM32 448s.1-181.1.1-256H160v64c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32v-64h127.9c0 74.9.1 256 .1 256H32z"/></svg>'
             ];
        }
        public static function get_field_options($type = 'wapf_product') {

	        $options =  [

		        'true-false' => [
			        [
				        'type'          => 'text',
				        'id'            => "message",
				        'label'         => __('Message','advanced-product-fields-for-woocommerce'),
				        'description'   => __('Displays text alongside the checkbox.','advanced-product-fields-for-woocommerce'),
			        ],
			        [
				        'type'          => 'select',
				        'options'       => [
					        'checked'   => __('Checked','advanced-product-fields-for-woocommerce'),
					        'unchecked' => __('Unchecked', 'advanced-product-fields-for-woocommerce')
                        ],
                        'default'       => 'unchecked',
                        'id'            => "default",
                        'label'         => __('Default value','advanced-product-fields-for-woocommerce'),
                        'description'   => __('The pre-set value of the field when the page loads.','advanced-product-fields-for-woocommerce'),
                    ],
                    [
	                    'type'          => 'pricing',
	                    'id'            => "pricing",
	                    'label'         => __('Adjust pricing','advanced-product-fields-for-woocommerce'),
	                    'description'   => __('Should the price of the product or cart change when the user interacts with this field?','advanced-product-fields-for-woocommerce'),
                    ],
                ],

                'text'      => [
		        [
			        'type'          => 'text',
			        'id'            => 'default',
			        'label'         => __('Default value','advanced-product-fields-for-woocommerce'),
			        'description'   => __('The pre-set value of the field when the page loads.','advanced-product-fields-for-woocommerce'),
		        ],
		        [
			        'type'          => 'text',
			        'id'            => 'placeholder',
			        'label'         => __('Placeholder text','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Appears within the input field','advanced-product-fields-for-woocommerce')
		        ],
		        [
			        'type'          => 'pricing',
			        'id'            => "pricing",
			        'label'         => __('Adjust pricing','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Should the price of the product or cart change when the user interacts with this field?','advanced-product-fields-for-woocommerce'),
		        ],
	        ],

                'textarea'      => [
		        [
			        'type'          => 'textarea',
			        'id'            => 'default',
			        'label'         => __('Default value','advanced-product-fields-for-woocommerce'),
			        'description'   => __('The pre-set value of the field when the page loads.','advanced-product-fields-for-woocommerce'),
		        ],
		        [
			        'type'          => 'text',
			        'id'            => 'placeholder',
			        'label'         => __('Placeholder text','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Appears within the input field','advanced-product-fields-for-woocommerce')
		        ],
		        [
			        'type'          => 'pricing',
			        'id'            => "pricing",
			        'label'         => __('Adjust pricing','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Should the price of the product or cart change when the user interacts with this field?','advanced-product-fields-for-woocommerce'),
		        ],
	        ],

                'number'      => [
                    [
                        'type'          => 'select',
                        'id'            => 'number_type',
                        'label'         => __('Number type','advanced-product-fields-for-woocommerce'),
                        'description'   => __('Allow integers (whole numbers) or decimals.','advanced-product-fields-for-woocommerce'),
                        'options'       => [
                            'int'       => __('Integer','advanced-product-fields-for-woocommerce'),
                            'any'       => __('Integer & decimals','advanced-product-fields-for-woocommerce')
                        ],
                        'default'       => 'int'
                    ],
		        [
			        'type'          => 'number',
			        'id'            => 'default',
			        'label'         => __('Default value','advanced-product-fields-for-woocommerce'),
			        'description'   => __('The pre-set value of the field when the page loads.','advanced-product-fields-for-woocommerce'),
		        ],
		        [
			        'type'          => 'text',
			        'id'            => 'placeholder',
			        'label'         => __('Placeholder text','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Appears within the input field','advanced-product-fields-for-woocommerce')
		        ],
		        [
			        'type'          => 'number',
			        'id'            => 'minimum',
			        'label'         => __('Minimum value','advanced-product-fields-for-woocommerce'),
			        'placeholder'   => __('No minimum','advanced-product-fields-for-woocommerce')
		        ],
		        [
			        'type'          => 'number',
			        'id'            => 'maximum',
			        'label'         => __('Maximum value','advanced-product-fields-for-woocommerce'),
			        'placeholder'   => __('No maximum','advanced-product-fields-for-woocommerce')
		        ],
		        [
			        'type'          => 'pricing',
			        'id'            => "pricing",
			        'label'         => __('Adjust pricing','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Should the price of the product or cart change when the user interacts with this field?','advanced-product-fields-for-woocommerce'),
		        ],
	        ],

                'email'     => [
		        [
			        'type'          => 'email',
			        'id'            => 'default',
			        'label'         => __('Default value','advanced-product-fields-for-woocommerce'),
			        'description'   => __('The pre-set value of the field when the page loads.','advanced-product-fields-for-woocommerce'),
		        ],
		        [
			        'type'          => 'text',
			        'id'            => 'placeholder',
			        'label'         => __('Placeholder text','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Appears within the input field','advanced-product-fields-for-woocommerce')
		        ],
		        $type === 'wapf_product' ?
			        [
				        'type'          => 'pricing',
				        'id'            => "pricing",
				        'label'         => __('Adjust pricing','advanced-product-fields-for-woocommerce'),
				        'description'   => __('Should the price of the product or cart change when the user interacts with this field?','advanced-product-fields-for-woocommerce'),
			        ] : [],
	        ],

                'url'       => [
		        [
			        'type'          => 'url',
			        'id'            => 'default',
			        'label'         => __('Default value','advanced-product-fields-for-woocommerce'),
			        'description'   => __('The pre-set value of the field when the page loads.','advanced-product-fields-for-woocommerce'),
		        ],
		        [
			        'type'          => 'text',
			        'id'            => 'placeholder',
			        'label'         => __('Placeholder text','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Appears within the input field','advanced-product-fields-for-woocommerce')
		        ],
		        [
			        'type'          => 'pricing',
			        'id'            => "pricing",
			        'label'         => __('Adjust pricing','advanced-product-fields-for-woocommerce'),
			        'description'   => __('Should the price of the product or cart change when the user interacts with this field?','advanced-product-fields-for-woocommerce'),
		        ],
	        ],

                'select'    => [
		        [
			        'type'                  => 'options',
			        'id'                    => 'options',
			        'label'                 => __('Options','advanced-product-fields-for-woocommerce'),
			        'description'           => __('Add the options for this select list.','advanced-product-fields-for-woocommerce'),
			        'multi_option'          => false,
			        'show_pricing_options'  => true
		        ]
	        ],

                'checkboxes'  => [
		        [
			        'type'                  => 'options',
			        'id'                    => 'options',
			        'label'                 => __('Options','advanced-product-fields-for-woocommerce'),
			        'description'           => __('Each option is a checkbox.','advanced-product-fields-for-woocommerce'),
			        'multi_option'          => true,
			        'show_pricing_options'  => true
		        ],

	        ],

            'radio'  => [
		        [
			        'type'                  => 'options',
			        'id'                    => 'options',
			        'label'                 => __('Options','advanced-product-fields-for-woocommerce'),
			        'description'           => __('Each option is a radio button.','advanced-product-fields-for-woocommerce'),
			        'multi_option'          => false,
			        'show_pricing_options'  => true
		        ],

	        ],
	        'paragraph' => [
		        [
			        'type'                  => 'textarea',
			        'id'                    => 'p_content',
			        'label'                 => __("Content",'advanced-product-fields-for-woocommerce'),
			        'description'           => __('Enter your text here. Do you need to add shortcodes & HTML? Consider upgrading to our Pro version.', 'advanced-product-fields-for-woocommerce')
		        ]
	        ],
            'content' => [
                    [
                        'type'                  => 'textarea',
                        'id'                    => 'p_content',
                        'label'                 => __("Content",'advanced-product-fields-for-woocommerce'),
                        'description'           => __('Enter your text here. Do you need to add shortcodes & HTML? Consider upgrading to our Pro version.', 'advanced-product-fields-for-woocommerce')
                    ]
                ],
            ];

            $options = apply_filters('wapf/field_options', $options);

            foreach($options as &$group) {
                foreach($group as &$option) {
                    $option['is_field_setting'] = true;
                }
            }

            return $options;

        }

	    public static function should_field_be_filled_out(FieldGroup $group, Field $field) {

		    if(!$field->required)
			    return false;

		    if(!$field->has_conditionals())
			    return true;

		    foreach ($field->conditionals as $conditional) {
			    if(self::validate_rules($group,$conditional->rules)) // conditional rules are valid, so the field should be shown and so it should be filled out if set to "required".
				    return true;
		    }

		    // Shouldn't be filled out as it should be hidden due to the conditionals.
		    return false;

	    }

		private static function validate_rules(FieldGroup $group, $rules) {

			foreach ($rules as $rule) {
				if(!self::is_valid_rule($group,$rule->field,$rule->condition,$rule->value))
					return false;
			}

			return true;
		}


		private static function is_valid_rule(FieldGroup $group, $field_id, $condition, $rule_value) {

			$field = Enumerable::from($group->fields)->firstOrDefault(function($x) use($field_id) {
				return $x->id === $field_id;
			});

			if(!$field)
				return false;

			$value = Fields::get_raw_field_value_from_request($field, 0, true);

			if($value === null)
				return false;

			$value = self::sanitize_raw_value($field, $value);

			switch($condition) {
				case "check"     : return $value === 'true';
				case "!check"    : return $value === 'false';
				case '=='        : return in_array($rule_value, (array) $value);
				case '!='        : return !in_array($rule_value, (array) $value);
				case 'empty'     : return empty($value);
				case '!empty'    : return !empty($value);
				case 'lt'        : return floatval($value) < floatval($rule_value);
				case 'gt'        : return floatval($value) > floatval($rule_value);
			}

			return false;
		}

        public static function get_pricing_options() {

            $options = [
                'fixed'     => [ 'label' => __('Flat fee (not quantity-based)', 'advanced-product-fields-for-woocommerce'), 'pro' => false ],
                'qt'        => [ 'label' => __('Quantity based flat fee (Pro only)', 'advanced-product-fields-for-woocommerce'), 'pro' => true ],
                'fx'        => [ 'label' => __('Formula/calculation (Pro only)', 'advanced-product-fields-for-woocommerce'), 'pro' => true ],
                'percent'   => [ 'label' => __('Percentage based (Pro only)', 'advanced-product-fields-for-woocommerce'), 'pro' => true ],
                'nr'        => [ 'label' => __('Amount & field value (Pro only)', 'advanced-product-fields-for-woocommerce'), 'pro' => true ],
                'char'      => [ 'label' => __('Amount & character count (Pro only)', 'advanced-product-fields-for-woocommerce'), 'pro' => true ],
            ];

            return $options;
        }

        // Same function as below but doesn't sanitize labels but the raw value coming from the frontend.
        public static function sanitize_raw_value(Field $field,$value) {
	        switch($field->type) {
		        case 'checkboxes'   :
		        case 'radio'        :
		        case 'select'       :
		        	return Enumerable::from((array)$value)->select(function($x){
		        		return sanitize_text_field($x);
			        })->toArray();
		        case 'textarea'     : return sanitize_textarea_field(trim($value));
		        case 'number'       : return filter_var(Helper::normalize_string_decimal($value),FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		        case 'true-false'   : return $value == '1' ? __('true','advanced-product-fields-for-woocommerce') : __('false','advanced-product-fields-for-woocommerce');
		        case 'email'        : return sanitize_email(trim($value));
		        default             : return self::sanitize_value($field,$value);
	        }
        }

        public static function sanitize_value(Field $field,$value) {

            switch($field->type) {
                case 'textarea'     : return sanitize_textarea_field(trim($value));
                case 'number'       : return filter_var(Helper::normalize_string_decimal($value),FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                case 'true-false'   : return $value == '1' ? __('true','advanced-product-fields-for-woocommerce') : __('false','advanced-product-fields-for-woocommerce');
                case 'email'        : return sanitize_email(trim($value));
	            case 'checkboxes'   :
	            case 'radio'        :
	            case 'select'       :
		            return join(', ', Enumerable::from((array) $value)->select(function($v) use ($field) {
			            $choice = Enumerable::from($field->options['choices'])->firstOrDefault(function($choice) use($v) {
				            return $choice['slug'] === $v;
			            });
			            if($choice)
				            return esc_html($choice['label']);

			            return '';
		            })->toArray());
                default             : return sanitize_text_field(trim($value));
            }
        }

	    public static function get_raw_field_value_from_request(Field $for_field, $clone_index = 0, $return_null = false) {

		    $field_name = 'field_' . $for_field->id . ($clone_index > 0 ? ('_clone_'.$clone_index):'');

		    if(!isset($_REQUEST['wapf']) || !isset($_REQUEST['wapf'][$field_name]))
			    return $return_null ? null : '';

		    return is_string($_REQUEST['wapf'][$field_name]) ? stripslashes($_REQUEST['wapf'][$field_name]) : $_REQUEST['wapf'][$field_name];
	    }

        public static function pricing_value(Field $field, $raw_value) {

            // Bail early
            if(empty($raw_value))
                return [];

            // True-false Checkbox wasn't checked, so pricing is 0 (the default).
            if($field->type === 'true-false' && $raw_value == '0')
                return [];

            $pricing = [];

            // It's a choice field, pricing is on the choices.
            if( $field->is_choice_field() ) {

                foreach ((array) $raw_value as $rv) {

                    $choice = Enumerable::from($field->options['choices'])->firstOrDefault(function($choice) use($rv) {
                        return $choice['slug'] === $rv;
                    });

                    if(!$choice || $choice['pricing_type'] === 'none')
                        continue;

                    $pricing[] = [ 'value' => $choice['pricing_amount'], 'type' => $choice['pricing_type'] ];

                }
                return $pricing;

            }

            // Field is "normal" pricing field/

            $pricing[] = ['value' => $field->pricing->amount, 'type' => $field->pricing->type];

            return $pricing;
        }

        public static function value_to_string(Field $field, $raw_value, $include_price_label = true, $product = null, $for_page = 'shop') {

            if($include_price_label) {

                // Pricing field has options. We'll append the pricing indication to the labels.
                if(!empty($field->options['choices'])) {
                    // Raw value is an array with the option text
                    $labels = [];

                    foreach ((array) $raw_value as $rv) {

                        $choice = Enumerable::from($field->options['choices'])->firstOrDefault(function($choice) use($rv) {
                            return $choice['slug'] === $rv;
                        });

                        if(!$choice)
                            continue;

                        if( $choice['pricing_type'] === 'none' )
                            $labels[] = esc_html($choice['label']);
                        else $labels[] = sprintf('%s (%s)', esc_html($choice['label']), Helper::format_pricing_hint($choice['pricing_type'],$choice['pricing_amount'],$product,$for_page));

                    }

                    return join(', ', $labels);
                }

                return $field->pricing_enabled() ?
	                sprintf('%s (%s)', self::sanitize_value($field,$raw_value), Helper::format_pricing_hint($field->pricing->type,$field->pricing->amount,$product,$for_page))
	                : self::sanitize_value($field,$raw_value) ;
            }

            return self::sanitize_value($field,$raw_value);

        }

        public static function do_pricing($amount, $qty) {
            // Fixed fee
            return (float) $amount/$qty;
        }

	    // Currently, we only validate "required" fields but we should extend this. For example "number" fields should
	    //have a number between min/max attributes etc...
	    public static function is_field_value_valid(Field $field, $value = null) {

		    if($field->required) {

			    // Value is null or not posted to the backend. That means this form field was hidden (due to dependency)
			    // So it's valid.
			    if($value === null)
				    return true;

			    if(empty($value))
				    return false;

		    }

		    return true;
	    }

    }
}