(function ($, root, undefined) {
	"use strict";
	/**
	 * Sets product images for the chosen variation
	 */
	var
		ideapark_variation_gallery_cache = {},
		ideapark_variation_default_slider = null,
		ideapark_variation_default_thumbs = null,
		ideapark_variation_gallery_loaded = false,
		ideapark_variation_gallery_timer = null;
	
	$.fn.wc_variations_image_update = function (variation) {
		var $form = this;
		IdeaparkQueue.enqueue(() => ideapark_variations_image_update($form, variation));
	};
	
	$(document.body).trigger('ideapark-variations-init');
	
	var ideapark_variations_image_update = function ($form, variation) {
		return new Promise((resolve, reject) => {
			
			if (variation && variation.has_variation_gallery_images) {
				if (typeof ideapark_variation_gallery_cache[variation.variation_id] !== 'undefined') {
					ideapark_switch_variation_gallery(ideapark_variation_gallery_cache[variation.variation_id]);
					resolve();
				} else {
					$.ajax({
						url    : ideapark_wp_vars.ajaxUrl,
						type   : 'POST',
						data   : {
							action      : 'ideapark_variation_images',
							variation_id: variation.variation_id,
							is_quickview: $form.closest('.product').hasClass('product--quick-view') ? 1 : 0,
						},
						success: function (html) {
							ideapark_variation_gallery_cache[variation.variation_id] = html;
							ideapark_switch_variation_gallery(html);
							resolve();
						}
					});
				}
			} else {
				var f = function () {
					var $product = $form.closest('.product'),
						$product_gallery = $product.find('.images'),
						$product_img_wrap = $product_gallery.find('.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder').first(),
						$product_img = $product_img_wrap.find('.ip-product-image-img'),
						$product_link = $product_img_wrap.find('a').first(),
						$product_zoom = $product_img_wrap.find('.js-product-zoom'),
						$gallery_img = $product.find('.slick-product .slide:eq(0) img');
					
					if (variation && variation.image && variation.image.src && variation.image.src.length > 1) {
						$product_img.wc_set_variation_attr('src', variation.image.src);
						$product_img.wc_set_variation_attr('height', variation.image.src_h);
						$product_img.wc_set_variation_attr('width', variation.image.src_w);
						$product_img.wc_set_variation_attr('srcset', variation.image.srcset);
						$product_img.wc_set_variation_attr('sizes', variation.image.sizes);
						$product_img.wc_set_variation_attr('title', variation.image.title);
						$product_img.wc_set_variation_attr('alt', variation.image.alt);
						$product_img.wc_set_variation_attr('data-src', variation.image.full_src);
						$product_img.wc_set_variation_attr('data-large_image', variation.image.full_src);
						$product_img.wc_set_variation_attr('data-large_image_width', variation.image.full_src_w);
						$product_img.wc_set_variation_attr('data-large_image_height', variation.image.full_src_h);
						$product_link.wc_set_variation_attr('href', variation.image.full_src);
						$product_img_wrap.wc_set_variation_attr('data-thumb', variation.image.src);
						if ($product_zoom.length) {
							var old_img = $product_zoom.data('img');
							$product_zoom.wc_set_variation_attr('data-img', variation.image.full_src);
							$product_zoom.data('img', $product_zoom.attr('data-img'));
							if (old_img != $product_zoom.data('img')) {
								$product_zoom.removeClass('init').trigger('zoom.destroy');
								ideapark_init_zoom();
							}
						}
						
						$gallery_img.wc_set_variation_attr('srcset', variation.image.srcset);
						$gallery_img.wc_set_variation_attr('src', variation.image.gallery_thumbnail_src);
					} else {
						$product_img.wc_reset_variation_attr('src');
						$product_img.wc_reset_variation_attr('width');
						$product_img.wc_reset_variation_attr('height');
						$product_img.wc_reset_variation_attr('srcset');
						$product_img.wc_reset_variation_attr('sizes');
						$product_img.wc_reset_variation_attr('title');
						$product_img.wc_reset_variation_attr('data-caption');
						$product_img.wc_reset_variation_attr('alt');
						$product_img.wc_reset_variation_attr('data-src');
						$product_img.wc_reset_variation_attr('data-large_image');
						$product_img.wc_reset_variation_attr('data-large_image_width');
						$product_img.wc_reset_variation_attr('data-large_image_height');
						$product_img_wrap.wc_reset_variation_attr('data-thumb');
						if ($product_zoom.length) {
							$product_zoom.wc_reset_variation_attr('data-img');
							$product_zoom.data('img', $product_zoom.attr('data-img'));
							$product_zoom.removeClass('init').trigger('zoom.destroy');
							ideapark_init_zoom();
						}
						$product_link.wc_reset_variation_attr('href');
						$gallery_img.wc_reset_variation_attr('src');
						$gallery_img.wc_reset_variation_attr('srcset');
					}
					
					resolve();
				};
				
				if (ideapark_variation_gallery_loaded) {
					ideapark_switch_variation_gallery('', f);
				} else {
					f();
				}
			}
			
			window.setTimeout(function () {
				$form.wc_maybe_trigger_slide_position_reset(variation);
				$(window).trigger('resize');
			}, 20);
		});
	};
	
	var ideapark_switch_variation_gallery = function (html, callback) {
		var is_switch_to_default = false;
		
		if (html === '' && ideapark_variation_default_slider) {
			is_switch_to_default = true;
			html = ideapark_variation_default_slider;
			if (ideapark_variation_default_thumbs) {
				html += ideapark_variation_default_thumbs;
			}
		}
		
		if (!html) {
			return;
		}
		
		var $slider = $('.js-product-info-carousel,.slick-product-qv'),
			$thumbs = $('.slick-product'),
			$slider_parent = $slider.parent(),
			$thumbs_parent = $thumbs.parent();
		
		$slider.addClass('h-fade--out');
		$thumbs.addClass('h-fade--out');
		
		var $new = $("<div class='h-hidden' />").append(html);
		$new.find('.h-fade').addClass('h-fade--out');
		
		if (ideapark_variation_gallery_timer !== null) {
			clearTimeout(ideapark_variation_gallery_timer);
			ideapark_variation_gallery_timer = null;
		}
		
		ideapark_variation_gallery_timer = setTimeout(function () {
			if ($slider.length) {
				if ($slider.hasClass('owl-carousel')) {
					$slider
						.removeClass('owl-carousel')
						.trigger("destroy.owl.carousel");
				}
				$slider
					.find('.init,.active')
					.removeClass('init active');
				
				if (ideapark_variation_default_slider === null) {
					ideapark_variation_default_slider = $("<div />").append($slider.clone()).html();
				}
				$slider.remove();
			}
			
			if ($thumbs.length) {
				if ($thumbs.hasClass('slick-initialized')) {
					$thumbs.slick('unslick');
					$thumbs
						.trigger("destroy.owl.carousel")
						.removeClass('slick-initialized slick-slider init');
				}
				$thumbs
					.find('.init,.active')
					.removeClass('init active');
				
				if (ideapark_variation_default_thumbs === null) {
					ideapark_variation_default_thumbs = $("<div />").append($thumbs.clone()).html();
				}
				$thumbs.remove();
			}
			else {
				ideapark_variation_default_thumbs = '';
			}
			
			var $slider_new = $('.js-product-info-carousel,.slick-product-qv', $new),
				$thumbs_new = $('.slick-product', $new);
			
			if ($slider_new.length) {
				$slider_new.detach().prependTo($slider_parent);
				if ($slider_new.hasClass('slick-product-qv')) {
					ideapark_quickview_gallery($slider_new);
				} else {
					ideapark_init_product_gallery(true);
				}
				ideapark_init_zoom();
				$slider_new.removeClass('h-fade--out');
			}
			if ($thumbs_new.length) {
				$thumbs_new.detach().appendTo($thumbs_parent);
				ideapark_init_thumbs(true);
				$thumbs_new.removeClass('h-fade--out');
			}
			
			$new.remove();
			
			ideapark_variation_gallery_loaded = !is_switch_to_default;
			
			if (typeof callback === 'function') {
				callback();
			}
			ideapark_variation_gallery_timer = null;
		}, 300);
	};
	
	$(document).on('show_variation hide_variation', function (e) {
		
		var is_hide = e.type === 'hide_variation';
		var $this = $(this);
		var $availability_text = $this.find('.woocommerce-variation-availability p');
		var $ip_stock = $('.ip-stock');
		
		if (is_hide) {
			$ip_stock.removeClass('out-of-stock')
				.removeClass('ip-out-of-stock')
				.removeClass('in-stock')
				.removeClass('ip-in-stock')
				.html('');
		} else {
			if ($availability_text.length) {
				var in_stock = $this.find('.woocommerce-variation-availability .out-of-stock').length === 0;
				var stock_html = $availability_text.html();
				if (in_stock) {
					$ip_stock
						.removeClass('out-of-stock')
						.removeClass('ip-out-of-stock')
						.addClass('in-stock')
						.addClass('ip-in-stock')
						.html(ideapark_wc_add_to_cart_variation_vars.in_stock_svg + stock_html);
				} else {
					$ip_stock
						.removeClass('in-stock')
						.removeClass('ip-in-stock')
						.addClass('out-of-stock')
						.addClass('ip-out-of-stock')
						.html(ideapark_wc_add_to_cart_variation_vars.out_of_stock_svg + stock_html);
				}
			} else {
				$ip_stock.removeClass('out-of-stock')
					.removeClass('ip-out-of-stock')
					.addClass('in-stock')
					.addClass('ip-in-stock')
					.html(ideapark_wc_add_to_cart_variation_vars.in_stock_svg + ideapark_wc_add_to_cart_variation_vars.in_stock_message);
			}
		}
	});
})(jQuery, this);