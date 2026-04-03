(function ($, root, undefined) {
	"use strict";
	
	$.migrateMute = true;
	$.migrateTrace = false;
	
	root.ideapark_videos = [];
	root.ideapark_players = [];
	root.ideapark_env_init = false;
	root.ideapark_slick_paused = false;
	root.ideapark_is_mobile = false;
	
	try {
		document.createEvent("TouchEvent");
		root.ideapark_is_mobile = true;
	} catch (e) {
		root.ideapark_is_mobile = false;
	}
	root.ideapark_is_responsinator = false;
	if (document.referrer) {
		root.ideapark_is_responsinator = (document.referrer.split('/')[2] == 'www.responsinator.com');
	}
	
	root.old_windows_width = 0;
	
	var isIosDevice =
		typeof window !== 'undefined' &&
		window.navigator &&
		window.navigator.platform &&
		/iP(ad|hone|od)/.test(window.navigator.platform);
	
	var ideapark_is_mobile_layout = window.innerWidth < 992;
	var $body = $('body');
	var $window = $(window);
	var ideapark_scroll_busy = true;
	var ideapark_resize_busy = true;
	var ideaparkStickHeight = 0;
	var needUpdateIdeaparkStickHeight = false;
	var lastBannerIndex = 0;
	var $home_banners_count = $('#home-banners .banner').length;
	var $home_banners = $('#home-banners');
	var ideapark_parallax_on = !!$('.parallax,.parallax-lazy').length && typeof simpleParallax !== 'undefined';
	var ideaparkStickyCheckoutTimeout = null;
	var $ideaparkCheckout = $('.checkout-collaterals');
	var $ideaparkWoocommerce = $('.woocommerce');
	var ideaparkCheckoutTop = 0;
	var ideaparkCheckoutInTransition = false;
	var $to_top_button = $('.to-top-button');
	var ideapark_mega_menu_break_mode = 0;
	var ideapark_submenu_direction_set = false;
	var ideapark_megamenu_left_set = false;
	var $slick_product_single = $('.js-product-info-carousel');
	var $slick_product_single_slides = $('.slide', $slick_product_single);
	var $slick_product_thumbnails = $('.slick-product');
	var $slick_product_thumbnails_slides = $('.slide', $slick_product_thumbnails);
	var slick_product_has_video = $('.ip-watch-video-btn').length > 0;
	var $ideapark_submenu_open = [];
	var ideapark_simple_parallax_instances = [];
	var ideapark_current_slide = null;
	var ideapark_current_index_thumbnail = 0;
	var ideapark_all_is_loaded = false;
	var $ideapark_header = $('#header');
	var $ideapark_menu = $('#header .main-menu');
	var $ideapark_sticky_sidebar = $('.js-sticky-sidebar');
	var $ideapark_sticky_sidebar_nearby = $('.js-sticky-sidebar-nearby');
	var ideapark_sticky_sidebar_old_style = null;
	var ideapark_is_sticky_sidebar_inner = !!$ideapark_sticky_sidebar_nearby.find('.js-sticky-sidebar').length;
	var $ideapark_infinity_loader;
	var ideapark_has_loader = false;
	
	document.onreadystatechange = function () {
		if (document.readyState === 'complete') {
			ideapark_all_is_loaded = true;
			ideapark_mega_menu_init();
		}
	};
	
	$(window).on("pageshow", function (e) {
		if (e.originalEvent.persisted) {
			setTimeout(function () {
				try {
					var wc_fragments = JSON.parse(sessionStorage.getItem(wc_cart_fragments_params.fragment_name));
					if (wc_fragments && wc_fragments['div.widget_shopping_cart_content']) {
						$('div.widget_shopping_cart_content').replaceWith(wc_fragments['div.widget_shopping_cart_content']);
					}
				} catch (err) {
				}
			}, 500);
		}
	});
	
	$(function () {
		$('html > head').append($('<style>svg{width: initial;height: initial;}</style>'));
		$('select.styled').customSelect();
		$('.single-product[data-product-page-preselected-id]').addClass('ip-p-c').addClass('single-product--shortcode');
		$(document.body)
			.on('added_to_cart', function (e, fragments, cart_hash) {
				if (ideapark_is_mobile_layout && ideapark_wp_vars.popupCartOpenMobile || !ideapark_is_mobile_layout && ideapark_wp_vars.popupCartOpenDesktop) {
					$('#js-cart-sidebar').trigger('click');
				} else {
					if (typeof fragments.ideapark_notice !== 'undefined') {
						var notice = fragments.ideapark_notice;
						var $wrapper = $('.ip-p-c.qv');
						var is_quickview = true;
						if (!$wrapper.length) {
							$wrapper = $('.woocommerce-notices-wrapper');
							is_quickview = false;
						}
						if (!$wrapper.length || ideapark_empty(notice)) {
							return;
						}
						
						var $notices = notice instanceof jQuery ? notice : $(notice);
						$notices.addClass('shown');
						$('.woocommerce-message').hide('slow', function () {
							$(this).remove();
						});
						if (is_quickview) {
							$wrapper.prepend($notices);
							if (ideapark_is_mobile) {
								$('html, body').animate({scrollTop: 0}, 800);
							}
						} else {
							$wrapper.append($notices);
							$('html, body').animate({scrollTop: 0}, 800);
						}
					}
				}
			})
			.on('click', '.js-load-more', function (e) {
				ideapark_infinity_loader($(this), e);
			});
		ideapark_init_masonry();
		ideapark_init_ajax_add_to_cart();
		
		ideapark_defer_action_add(function () {
			if (typeof ideapark_redirect_url !== 'undefined' && ideapark_redirect_url) {
				location.href = ideapark_redirect_url;
				return;
			}
			$('#ajax-search,#ajax-search-result,.search-shadow,.menu-shadow').removeClass('hidden');
			$('.woocommerce-tabs .tabs li a').on('click', function () {
				var _ = $(this);
				var $tab = $(_.attr('href'));
				var $li = $(this).parent('li');
				if ($li.hasClass('active') && $(window).width() < 992 && $tab.hasClass('current')) {
					$li.parent('ul').toggleClass('expand');
				} else {
					$('.woocommerce-tabs .tabs li.active').removeClass('active');
					$li.addClass('active');
					$('.woocommerce-tabs .current').removeClass('current');
					setTimeout(function () {
						$tab.addClass('current');
					}, 100);
					$li.parent('ul').removeClass('expand');
				}
			});
			$('.product-categories > ul > li.menu-item-has-children > a').on('click', function (e) {
				var width = $(window).width();
				if (ideapark_is_mobile && width >= 992 && width <= 1024) {
					e.preventDefault();
				}
			});
			$('section.products').each(function () {
				var $this = $(this);
				if (!$this.hasClass('c-home-tabs--carousel')) {
					$this.addClass('c-home-tabs--carousel');
					var $list = $this.find('.products:not(.owl-carousel)');
					if ($list.length) {
						$list.addClass('h-carousel h-carousel--flex');
						ideapark_init_home_tab_carousel($list);
					}
				}
			});
			$("#ip-wishlist-share-link").on('focus', function () {
				$(this).select();
			});
			$("#ajax-search-input").on('input', function () {
				if (ideapark_wp_vars.searchType != 'search-type-3') {
					var _ = $(this);
					if (_.val().trim().length > 1) {
						$(".js-ajax-search-result").removeClass('loaded');
						$('.search-shadow').addClass('loading');
						ajaxSearchFunction();
					} else {
						$('.search-shadow').removeClass('loading');
						$(".js-ajax-search-result").removeClass('loaded');
					}
				}
			}).on('keydown', function (event) {
				if (event.keyCode == 13) {
					event.preventDefault();
					$('.search-shadow').removeClass('loading');
					if ($("#ajax-search-input").val().trim()) {
						$("#ajax-search form").submit();
					}
				} else if (event.keyCode == 27) {
					$('.search-shadow').removeClass('loading');
					$('#mobilesearch-close').trigger('click');
					$('#search-close').trigger('click');
				}
			});
			$("#ajax-search form").on('submit', function () {
				if (!$("#ajax-search-input").val().trim()) {
					return false;
				}
			});
			$('.ip-watch-video-btn').on('click', function () {
				
				var $container = $('#ip-quickview'),
					$video_code = $("#ip_hidden_product_video");
				
				if ($body.hasClass('quickview-open') || $video_code.length != 1) {
					return false;
				}
				
				var $shadow = $('<div id="ip-quickview-shadow" class="loading"><div class="ip-shop-loop-loading"><i></i><i></i><i></i></div></div>');
				$body.append($shadow);
				$body.addClass('quickview-open');
				
				$container.html($video_code.val());
				
				$container.fitVids();
				
				$.magnificPopup.open({
					mainClass   : 'ip-mfp-quickview ip-mfp-fade-in',
					closeMarkup : '<a class="mfp-close ip-mfp-close video"><svg><use xlink:href="' + ideapark_wp_vars.svgUrl + '#svg-close-light" /></svg></a>',
					removalDelay: 180,
					items       : {
						src : $container,
						type: 'inline'
					},
					callbacks   : {
						open       : function () {
							$shadow.removeClass('loading');
							$shadow.one('touchstart', function () {
								$.magnificPopup.close();
							});
						},
						beforeClose: function () {
							$shadow.addClass('mfp-removing');
						},
						close      : function () {
							$shadow.remove();
							$body.removeClass('quickview-open');
						}
					}
				});
				
				return false;
			});
			
			$('#customer_login .tab-header').on('click', function () {
				$('#customer_login .tab-header.active').removeClass('active');
				$(this).addClass('active');
				$('#customer_login .wrap li.active').removeClass('active');
				$('#customer_login .wrap li.' + $(this).data('tab-class')).addClass('active');
				return false;
			});
			
			$('.entry-content a > img').each(
				function () {
					
					var $shadow, $a = $(this).closest('a');
					
					if ($a.attr('href').search(/\.(gif|jpg|png|jpeg)$/i) >= 0) {
						
						$a.magnificPopup({
							type               : 'image',
							closeOnContentClick: true,
							image              : {
								verticalFit: true
							},
							mainClass          : 'ip-mfp-quickview ip-mfp-fade-in',
							closeMarkup        : '<a class="mfp-close ip-mfp-close"><svg><use xlink:href="' + ideapark_wp_vars.svgUrl + '#svg-close-light" /></svg></a>',
							removalDelay       : 300,
							callbacks          : {
								beforeOpen: function () {
									$shadow = $('<div id="ip-quickview-shadow" class="loading"><div class="ip-shop-loop-loading"><i></i><i></i><i></i></div></div>');
									$body.append($shadow);
									
									$shadow.one('touchstart', function () {
										$.magnificPopup.close();
									});
								},
								
								open: function () {
									$body.addClass('quickview-open');
								},
								
								imageLoadComplete: function () {
									$shadow.removeClass('loading');
								},
								
								beforeClose: function () {
									$shadow.addClass('mfp-removing');
									$body.removeClass('quickview-open');
								},
								close      : function () {
									$shadow.remove();
								}
							}
						});
					}
				}
			);
			$to_top_button.on('click', function () {
				$('html, body').animate({scrollTop: 0}, 800);
				return false;
			});
			
			ideapark_init_mobile_menu();
			ideapark_init_mobile_sidebar();
			ideapark_init_cart_sidebar();
			ideapark_init_zoom();
			ideapark_init_home_slider();
			ideapark_banners();
			ideapark_init_product_tabs();
			ideapark_init_home_tabs();
			ideapark_init_home_brands();
			ideapark_init_home_review();
			ideapark_to_top_button();
			ideapark_parallax_init();
			ideapark_init_product_gallery();
			ideapark_init_thumbs();
			ideapark_init_cart_auto_update();
			ideapark_init_product_categories_widget();
			
			if (ideapark_wp_vars.stickyMenu) {
				$('#header .logo').imagesLoaded(function () {
					ideaparkStickHeight = $ideapark_header.outerHeight();
					ideapark_stickyNav();
				});
			}
			
			$(".container").fitVids();
			
			ideapark_scroll_actions();
			ideapark_resize_actions();
			
			$ideapark_infinity_loader = $('.js-load-infinity');
			ideapark_has_loader = $ideapark_infinity_loader.length || $('.js-load-more').length;
			
			$('body.preload').removeClass('preload');
		});
		
		if (!ideapark_wp_vars.jsDelay || ($window.width() >= 768 && $window.width() <= 1189)) {
			ideapark_defer_action_run();
		}
	});
	
	root.ideapark_scroll_actions = function () {
		ideapark_banners();
		ideapark_stickyNav();
		ideapark_to_top_button();
		ideapark_sticky_sidebar();
		ideapark_infinity_loading();
		ideapark_scroll_busy = false;
	};
	
	$(window).on('scroll',
		function () {
			if (window.requestAnimationFrame) {
				if (!ideapark_scroll_busy) {
					ideapark_scroll_busy = true;
					window.requestAnimationFrame(ideapark_scroll_actions);
				}
			} else {
				ideapark_scroll_actions();
			}
		}
	);
	
	root.ideapark_resize_actions = function () {
		var ideapark_is_mobile_layout_new = (window.innerWidth < 992);
		var is_layout_changed = (ideapark_is_mobile_layout !== ideapark_is_mobile_layout_new);
		ideapark_is_mobile_layout = ideapark_is_mobile_layout_new;
		
		ideapark_banners();
		ideapark_stickyNav();
		
		ideapark_mega_menu_break();
		ideapark_megamenu();
		ideapark_submenu_direction();
		
		ideapark_wpadminbar_mobile();
		ideapark_sticky_sidebar();
		
		if (is_layout_changed) {
			if ((!ideapark_is_mobile_layout || !$slick_product_thumbnails.parent().hasClass('hidden-xs')) && ideapark_current_slide !== null && $slick_product_thumbnails.length) {
				if (!ideapark_init_thumbs()) {
					setTimeout(function () {
						$slick_product_thumbnails.slick('refresh');
					}, 500);
				}
				$slick_product_thumbnails.eq(ideapark_current_slide).trigger('click');
			}
			if ($body.hasClass('menu-open')) {
				$('.mobile-menu-close').trigger('click');
			}
			ideapark_init_zoom();
		}
		
		ideapark_resize_busy = false;
	};
	
	$(window).on('resize',
		function () {
			if (window.requestAnimationFrame) {
				if (!ideapark_resize_busy) {
					ideapark_resize_busy = true;
					window.requestAnimationFrame(ideapark_resize_actions);
				}
			} else {
				ideapark_resize_actions();
			}
		}
	);
	
	$(document)
		.one('click', '.mobile-menu, .mobile-sidebar, #js-cart-sidebar', function (e) {
			e.preventDefault();
			if (!ideapark_defer_action_done()) {
				var $this = $(this);
				$(document).one('ideapark.defer.done', function () {
					$this.trigger('click');
				});
				ideapark_defer_action_run();
			}
		})
		.on('click', ".product-categories > ul li.has-children > a:not(.js-more), .product-categories > ul li.menu-item-has-children > a:not(.js-more)", function () {
			if ($(this).closest('.sub-menu .sub-menu').length > 0) {
				return true;
			}
			if ($(window).width() >= 992) {
				return true;
			}
			if (!$(this).attr('href')) {
				$(this).parent().children('.js-more').trigger('click');
				return false;
			}
		})
		.on('click', ".js-more", function () {
			if ($(window).width() >= 992) {
				return true;
			}
			if ($ideapark_submenu_open.length === 0) {
				$(document.body).addClass("submenu-open");
			}
			$ideapark_submenu_open.push($(this).closest('li'));
			var $li = $(this).closest('li');
			var $ul = $li.find('.sub-menu').first();
			var $ul_parent = $li.closest('.sub-menu');
			$li.addClass('selected');
			$ul_parent.addClass('h-hidden-overflow-mobile');
			if ($ul_parent.length) {
				$ul_parent[0].scrollTop = 0;
			}
			bodyScrollLock.clearAllBodyScrollLocks();
			bodyScrollLock.disableBodyScroll($ul[0]);
			return false;
		})
		.on('click', '#header .search, #header .mobile-search, #search-close', function () {
			$('html').toggleClass('search-open');
			
			if ($body.toggleClass('search-open').hasClass('search-open')) {
				bodyScrollLock.disableBodyScroll($('#ajax-search-result')[0]);
				setTimeout(function () {
					$("#ajax-search-input").trigger("focus");
				}, 200);
			} else {
				bodyScrollLock.clearAllBodyScrollLocks();
			}
			
			if (!$(".js-ajax-search-result").text() && $("#ajax-search-input").val().trim()) {
				ajaxSearchFunction();
			}
			return false;
		})
		.on('click', ".coupon .header a", function () {
			var $coupon = $(".coupon");
			$coupon.toggleClass('opened');
			if ($coupon.hasClass('opened')) {
				setTimeout(function () {
					$coupon.find('input[type=text]').first().trigger("focus");
				}, 500);
			}
			return false;
			
		})
		.on('click', ".collaterals .shipping-calculator .header a", function () {
			$(this).closest('.shipping-calculator').toggleClass('opened');
			
		})
		.on('click', ".ip-prod-quantity-minus", function (e) {
			e.preventDefault();
			var $input = $(this).parent().find('input[type=number]');
			var quantity = parseInt($input.val().trim(), 10);
			var min = parseInt($input.attr('min'), 10) || 1;
			var step = parseInt($input.attr('step'), 10) || 1;
			quantity -= step;
			quantity = Math.max(quantity, min);
			$input.val(quantity);
			$input.trigger('change');
			
		})
		.on('click', ".ip-prod-quantity-plus", function (e) {
			e.preventDefault();
			var $input = $(this).parent().find('input[type=number]');
			var quantity = parseInt($input.val().trim(), 10);
			var max = parseInt($input.attr('max'), 10);
			var step = parseInt($input.attr('step'), 10) || 1;
			quantity += step;
			if (max) {
				quantity = Math.min(quantity, max);
			}
			if (quantity > 0) {
				$input.val(quantity);
				$input.trigger('change');
			}
		})
		.on('keypress', "#coupon_code", function (e) {
			if(e.which == 13) {
				$('#ip-checkout-apply-coupon').trigger('click');
			}
		})
		.on('click', "#ip-checkout-apply-coupon", function () {
			var $form = $(this).closest('form');
			
			if ($form.is('.processing')) {
				return false;
			}
			
			$form.addClass('processing').block({
				message   : null,
				overlayCSS: {
					background: '#fff',
					opacity   : 0.6
				}
			});
			
			var data = {
				security   : wc_checkout_params.apply_coupon_nonce,
				coupon_code: $form.find('input[name="coupon_code"]').val()
			};
			
			$.ajax({
				type    : 'POST',
				url     : wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
				data    : data,
				success : function (code) {
					$('.woocommerce-error, .woocommerce-message').remove();
					$form.removeClass('processing').unblock();
					
					if (code) {
						$form.before(code);
						$(document.body).trigger('update_checkout', {update_shipping_method: false});
					}
				},
				dataType: 'html'
			});
			
			return false;
		})
		.on('click', ".js-mobile-modal", function (e) {
			$('.js-product-info-carousel .owl-item.active a:first').trigger('click');
		})
		.on('wc_fragments_refreshed added_to_cart removed_from_cart', function () {
			setTimeout(function () {
				var $supports_html5_storage = true;
				
				try {
					$supports_html5_storage = ('sessionStorage' in window && window.sessionStorage !== null);
					window.sessionStorage.setItem('wc', 'test');
					window.sessionStorage.removeItem('wc');
					window.localStorage.setItem('wc', 'test');
					window.localStorage.removeItem('wc');
				} catch (err) {
					$supports_html5_storage = false;
				}
				
				if ($supports_html5_storage) {
					var fragments = sessionStorage.getItem(wc_cart_fragments_params.fragment_name);
					fragments = fragments.replace('animate', '');
					sessionStorage.setItem(wc_cart_fragments_params.fragment_name, fragments);
				}
			}, 500);
			
		})
		.on('click', '.ip-quickview-btn', function () {
			var $button = $(this),
				$container = $('#ip-quickview'),
				ajaxUrl,
				productId = $(this).data('product_id'),
				data = {
					product_id: productId,
					lang      : $button.data('lang')
				};
			
			if ($body.hasClass('quickview-open')) {
				return false;
			}
			
			if (productId) {
				var $shadow = $('<div id="ip-quickview-shadow" class="loading"><div class="ip-shop-loop-loading"><i></i><i></i><i></i></div></div>');
				$body.append($shadow);
				setTimeout(function () {
					$body.addClass('quickview-open');
				}, 100);
				
				ajaxUrl = ideapark_wp_vars.ajaxUrl;
				data.action = 'ip_ajax_load_product';
				
				root.ip_quickview_get_product = $.ajax({
					type      : 'POST',
					url       : ajaxUrl,
					data      : data,
					dataType  : 'html',
					cache     : false,
					headers   : {'cache-control': 'no-cache'},
					beforeSend: function () {
						if (root.window.ip_quickview_get_product === 'object') {
							root.ip_quickview_get_product.abort();
						}
					},
					error     : function (XMLHttpRequest, textStatus, errorThrown) {
						$shadow.remove();
						$body.removeClass('quickview-open');
					},
					success   : function (data) {
						
						$container.html(data);
						
						$.magnificPopup.open({
							mainClass   : 'ip-mfp-quickview ip-mfp-fade-in',
							closeMarkup : '<a class="mfp-close ip-mfp-close"><svg><use xlink:href="' + ideapark_wp_vars.svgUrl + '#svg-close-light" /></svg></a>',
							removalDelay: 300,
							items       : {
								src : $container,
								type: 'inline'
							},
							callbacks   : {
								open       : function () {
									$shadow.removeClass('loading');
									$shadow.one('touchstart', function () {
										$.magnificPopup.close();
									});
									var $slick_product_qv = $('.slick-product-qv', $container);
									ideapark_quickview_gallery($slick_product_qv);
									$('select.styled, .variations select', $container).customSelect();
									
									var $currentContainer = $container.find('#product-' + productId),
										$productForm = $currentContainer.find('form.cart');
									
									ideapark_init_zoom();
									
									$('.product-images', $container).imagesLoaded(function () {
										
										if ($currentContainer.hasClass('product-type-variable')) {
											
											$productForm.wc_variation_form().find('.variations select:eq(0)').trigger('change');
											
											$(".variations_form").on("woocommerce_variation_select_change", function () {
												$(".variations_form select").each(function () {
													$(this).next('span.customSelect').html($(this).find(':selected').html());
												});
												
												if (typeof $slick_product_qv != 'undefined' && $slick_product_qv.length) {
													setTimeout(function () {
														$slick_product_qv.trigger('to.owl.carousel', 0);
														var $fisrtSlide = $slick_product_qv.find('.slide').first().children('a');
														if ($fisrtSlide.length) {
															var tmpImg = new Image();
															tmpImg.src = $fisrtSlide.attr('href');
															tmpImg.onload = function () {
																$fisrtSlide.attr('data-size', this.width + 'x' + this.height);
															};
														}
													}, 500);
												}
											});
										}
									});
									
									$(document).trigger('wishlist_refresh');
									
									ideapark_init_ajax_add_to_cart();
								},
								beforeClose: function () {
									$body.removeClass('quickview-open');
								},
								close      : function () {
									$shadow.remove();
								}
							}
						});
					}
				});
			}
			
			return false;
		});
	
	$(".variations_form").on("woocommerce_variation_select_change", function () {
		$(".variations_form select").each(function () {
			$(this).next('span.customSelect').html($(this).find(':selected').html());
		});
		
		if (typeof $slick_product_single != 'undefined' && $slick_product_single.length) {
			setTimeout(function () {
				$slick_product_single.trigger('to.owl.carousel', 0);
				var $fisrtSlide = $slick_product_single_slides.first().children('a');
				
				if ($fisrtSlide.length) {
					var tmpImg = new Image();
					tmpImg.src = $fisrtSlide.attr('href');
					tmpImg.onload = function () {
						$fisrtSlide.attr('data-size', this.width + 'x' + this.height);
					};
				}
				
			}, 500);
		}
	});
	
	root.ideapark_refresh_parallax = ideapark_debounce(function () {
	}, 500);
	
	root.ideapark_third_party_reload = function () {
		if (typeof root.sbi_init === "function") {
			window.sbiCommentCacheStatus = 0;
			root.sbi_init(function (imagesArr, transientName) {
				root.sbi_cache_all(imagesArr, transientName);
			});
		}
	};
	
	root.ideapark_parallax_destroy = function () {
		if (ideapark_parallax_on && ideapark_simple_parallax_instances.length) {
		}
	};
	
	root.ideapark_parallax_init = function () {
		if (ideapark_parallax_on) {
			var images = document.querySelectorAll('.parallax');
			ideapark_simple_parallax_instances.push(new simpleParallax(images, {
				scale   : 1.5,
				overflow: true
			}));
			$('.parallax-lazy').imagesLoaded().progress(function (instance, image) {
				ideapark_simple_parallax_instances.push(new simpleParallax(image.img, {
					scale   : 1.5,
					overflow: true
				}));
			});
		}
		
	};
	
	root.ideapark_mega_menu_break = function (force) {
		if (force) {
			ideapark_mega_menu_break_mode = 0;
		}
		if ($window.width() < 992) {
			if (ideapark_mega_menu_break_mode === 1) {
				$('.mega-menu-break').each(function () {
					var $ul = $(this);
					$ul.css({height: ''}).removeClass('mega-menu-break');
				});
				ideapark_mega_menu_break_mode = 0;
			}
			return;
		}
		if (ideapark_mega_menu_break_mode === 0 && ideapark_all_is_loaded) {
			var main_items = $('.main-menu .menu').find('.menu-col-2,.menu-col-3,.menu-col-4');
			if (main_items.length) {
				main_items.each(function () {
					var $li_main = $(this);
					var cols = 0;
					if ($li_main.hasClass('menu-col-2')) {
						cols = 2;
					} else if ($li_main.hasClass('menu-col-3')) {
						cols = 3;
					} else if ($li_main.hasClass('menu-col-4')) {
						cols = 4;
					}
					var $ul = $li_main.find('.sub-menu').first();
					var padding_top = $ul.css('padding-top') ? parseInt($ul.css('padding-top').replace('px', '')) : 0;
					var padding_bottom = $ul.css('padding-bottom') ? parseInt($ul.css('padding-bottom').replace('px', '')) : 0;
					var heights = [];
					var max_height = 0;
					var all_sum_height = 0;
					$ul.children('li').each(function () {
						var $li = $(this);
						var height = $li.outerHeight();
						if (height > max_height) {
							max_height = height;
						}
						all_sum_height += height;
						heights.push(height);
					});
					var test_cols = 0;
					var cnt = 0;
					var test_height = max_height - 1;
					do {
						test_height++;
						cnt++;
						test_cols = 1;
						var sum_height = 0;
						for (var i = 0; i < heights.length; i++) {
							sum_height += heights[i];
							if (sum_height > test_height) {
								sum_height = 0;
								i--;
								test_cols++;
							}
						}
					} while (test_cols > cols && cnt < 1000);
					
					if (test_cols <= cols && test_height > 0) {
						$ul.css({height: (test_height + padding_top + padding_bottom) + 'px'}).addClass('mega-menu-break');
					}
				});
				ideapark_mega_menu_break_mode = 1;
			}
			$ideapark_menu.addClass('initialized');
		}
	};
	
	root.ideapark_init_home_slider = function () {
		var $carousel = $('.js-slider-carousel:not(.owl-carousel)');
		if ($carousel.length) {
			var $slider = $('#home-slider');
			var sliderEffect = $slider.data('slider_effect');
			var sliderInterval = $slider.data('slider_interval');
			var sliderShowDots = !$slider.data('slider_hide_dots');
			var sliderShowArrows = !$slider.data('slider_hide_arrows');
			
			var params = {
				items        : 1,
				loop         : true,
				margin       : 0,
				nav          : sliderShowArrows,
				dots         : sliderShowDots,
				rtl          : !!ideapark_wp_vars.isRtl,
				navText      : [
					ideapark_wp_vars.arrowLeftOwl,
					ideapark_wp_vars.arrowRightOwl
				],
				onInitialized: function (e) {
					ideapark_owl_hide_arrows(e);
				}
			};
			
			if (sliderInterval) {
				params.autoplay = true;
				params.autoplayTimeout = sliderInterval;
				params.autoplayHoverPause = true;
			}
			
			if (sliderEffect === 'fade') {
				params.animateOut = 'fadeOut';
			}
			
			var $first_slide = $('.slide--first');
			if ($first_slide.length === 1) {
				$carousel.addClass('owl-carousel')
					.on('resized.owl.carousel', ideapark_owl_hide_arrows)
					.owlCarousel(params);
				$first_slide.imagesLoaded(function () {
					var $preloaded = $('.slick-preloader');
					$preloaded.on('transitionend webkitTransitionEnd oTransitionEnd', function () {
						$preloaded.remove();
					});
					$preloaded.addClass('slick-preloader--hide');
					setTimeout(function () {
						$carousel.find('img[loading]').removeAttr('loading');
					}, 3000);
				});
			}
		}
	};
	
	root.ideapark_init_home_review = function () {
		var $carousel = $('.js-review-carousel:not(.owl-carousel)');
		
		if ($carousel.length) {
			var params = {
				items        : 1,
				loop         : false,
				margin       : 0,
				nav          : true,
				dots         : false,
				rtl          : !!ideapark_wp_vars.isRtl,
				navText      : [
					ideapark_wp_vars.arrowLeftOwl,
					ideapark_wp_vars.arrowRightOwl
				],
				responsive   : {
					0  : {
						dots: $carousel.data('mobile-dots'),
						nav : false
					},
					480: {
						nav : true,
						dots: false
					}
				},
				onInitialized: ideapark_owl_hide_arrows
			};
			
			var autoplayInterval = $carousel.data('autoplay-interval');
			if (autoplayInterval) {
				params.loop = true;
				params.autoplay = true;
				params.autoplayTimeout = autoplayInterval;
				params.autoplayHoverPause = true;
			}
			
			$carousel
				.addClass('owl-carousel')
				.on('resized.owl.carousel', ideapark_owl_hide_arrows)
				.owlCarousel(params);
		}
		
	};
	
	root.ideapark_init_home_brands = function () {
		var $carousel = $('.js-brands-carousel:not(.owl-carousel)');
		if ($carousel.length) {
			var params = {
				center       : false,
				autoWidth    : true,
				loop         : false,
				margin       : 0,
				nav          : true,
				dots         : false,
				rtl          : !!ideapark_wp_vars.isRtl,
				navText      : [
					ideapark_wp_vars.arrowLeftOwl,
					ideapark_wp_vars.arrowRightOwl
				],
				responsive   : {
					0  : {
						dots: $carousel.data('mobile-dots'),
						nav : false
					},
					768: {
						nav : true,
						dots: false
					}
				},
				onInitialized: ideapark_owl_hide_arrows
			};
			
			var autoplayInterval = $carousel.data('autoplay-interval');
			if (autoplayInterval) {
				params.loop = true;
				params.autoplay = true;
				params.autoplayTimeout = autoplayInterval;
				params.autoplayHoverPause = true;
			}
			
			$carousel
				.addClass('owl-carousel')
				.on('resized.owl.carousel', ideapark_owl_hide_arrows)
				.owlCarousel(params);
		}
	};
	
	root.ideapark_init_view_more_item = function ($tab, href, postfix) {
		if ($tab && $tab.length) {
			var $li = $tab.find('.js-view-more-item');
			var new_item = false;
			if (!$li.length) {
				$li = $('<div class="product product--view-more js-view-more-item"><div class="ip-shop-loop-wrap ip-shop-loop-wrap--view-more"><a class="button" href="' + href + '">' + ideapark_wp_vars.viewMore + (postfix ? ' ' + postfix : '') + '</a></div></div>');
				new_item = true;
			}
			var $grid = $tab.find('.products');
			if (new_item) {
				$grid.append($li);
				$tab.addClass('js-view-more-tab');
			}
		}
	};
	
	root.ideapark_init_home_tab_carousel = function ($product_list) {
		
		$product_list.each(function () {
			var $this = $(this);
			var responsive = $this.hasClass('products--mobile-small') ?
				{
					0  : {
						dots  : true,
						nav   : false,
						margin: 30
					},
					601: {
						nav   : true,
						dots  : false,
						margin: 0
					}
				} :
				{
					0  : {
						dots  : true,
						nav   : false,
						margin: 30
					},
					360: {
						dots  : true,
						nav   : false,
						margin: 0
					},
					480: {
						nav   : true,
						dots  : false,
						margin: 0
					}
				};
			$this
				.addClass('products--' + $this.children().length)
				.addClass('owl-carousel')
				.on('resized.owl.carousel', ideapark_owl_hide_arrows)
				.owlCarousel({
					center       : false,
					autoWidth    : true,
					loop         : false,
					rtl          : !!ideapark_wp_vars.isRtl,
					navText      : [
						ideapark_wp_vars.arrowLeftOwl,
						ideapark_wp_vars.arrowRightOwl
					],
					responsive   : responsive,
					onInitialized: ideapark_owl_hide_arrows
				});
		});
		
	};
	
	root.ideapark_init_home_tabs = function () {
		var $tabs = $(".c-home-tabs:not(.init)");
		$tabs.each(function () {
			var $tab = $(this);
			
			if ($tab.hasClass('js-product-carousel')) {
				
				$tab.find('.home-tab').each(function () {
					var $tab = $(this);
					var product_count = $tab.find('.product').length;
					if ($tab.data('view-more') && $tab.data('per-page') == product_count) {
						ideapark_init_view_more_item($tab, $tab.data('view-more'));
					}
				});
				
				ideapark_init_home_tab_carousel($tab.find('.products:not(.owl-carousel)'));
			}
			
			var $tab_buttons = $tab.find(".home-tabs li");
			if ($tab_buttons.length) {
				var set_tab_width = function () {
					var el = document.getElementById('ideapark-core-css');
					
					if (el) {
						if (el.getAttribute('media') === 'all' && $tab_buttons.first().outerWidth() > 0) {
							var maxTabWidth = 0;
							$tab_buttons.each(function () {
								var _ = $(this);
								if (_.outerWidth() > maxTabWidth) {
									maxTabWidth = _.outerWidth();
								}
							});
							$tab.find(".home-tabs").css({width: maxTabWidth + 10});
						} else {
							setTimeout(set_tab_width, 100);
						}
					}
				};
				set_tab_width();
				
				$tab.find('.home-tabs li a').on('click', function () {
					var _ = $(this);
					var $wrap = _.closest('.c-home-tabs');
					var $tab = $(_.attr('href'));
					var $li = $(this).parent('li');
					
					if ($li.hasClass('current')) {
						$li.parent('ul').toggleClass('expand');
						return false;
					}
					$('.home-tabs li.current', $wrap).removeClass('current');
					$li.addClass('current');
					$('.home-tab.current', $wrap).removeClass('current');
					$('.home-tab.visible', $wrap).removeClass('visible');
					$tab.addClass('visible');
					setTimeout(function () {
						$tab.addClass('current');
						setTimeout(function () {
							ideapark_owl_hide_arrows($tab);
							$tab.find('[data-src]').each(function () {
								var $this = $(this);
								$this.attr('srcset', $this.attr('data-srcset'));
								$this.attr('src', $this.attr('data-src'));
								$this.attr('sizes', $this.attr('data-sizes'));
								$this.removeAttr('data-srcset');
								$this.removeAttr('data-src');
								$this.removeAttr('data-sizes');
							});
						}, 500);
					}, 100);
					$li.parent('ul').removeClass('expand');
					return false;
				});
			}
			
			$tab.addClass('init');
		});
		
		
	};
	
	root.ideapark_init_product_tabs = function () {
		var $tabs = $(".woocommerce-tabs .tabs li");
		if ($tabs.length) {
			var set_product_tab_width = function () {
				var el = document.getElementById('ideapark-core-css');
				if (el && el.getAttribute('media') === 'all' && $tabs.first().outerWidth() > 0) {
					var maxTabWidth = 0;
					$tabs.each(function () {
						var _ = $(this);
						if (_.outerWidth() > maxTabWidth) {
							maxTabWidth = _.outerWidth();
						}
					});
					$(".woocommerce-tabs .tabs").css({width: maxTabWidth + 10});
				} else {
					setTimeout(set_product_tab_width, 100);
				}
			};
			set_product_tab_width();
		}
	};
	
	root.ideapark_wpadminbar_mobile = function () {
		var $ideapark_admin_bar = $('#wpadminbar');
		if ($ideapark_admin_bar.length) {
			var window_width = $window.width();
			if (window_width > 782 && $ideapark_admin_bar.hasClass('mobile')) {
				$ideapark_admin_bar.removeClass('mobile');
			} else if (window_width <= 782 && !$ideapark_admin_bar.hasClass('mobile')) {
				$ideapark_admin_bar.addClass('mobile');
			}
		}
	};
	
	root.ideapark_submenu_direction = function (force) {
		if (force) {
			ideapark_submenu_direction_set = false;
		}
		if ($(window).width() < 992 || ideapark_submenu_direction_set) {
			return true;
		}
		
		var window_width = $window.width();
		var container_width = $('.product-categories').outerWidth();
		var container_left = Math.round(window_width / 2 - container_width / 2);
		var container_right = container_left + container_width;
		var container_center = container_left + container_width / 2;
		var i = 0;
		var parent_class = '';
		
		$('.sub-menu__inner').each(function () {
			var $ul = $(this);
			var ul_left = $ul.offset().left;
			var ul_rigth = ul_left + $ul.width();
			var ul_center = ul_left + $ul.width() / 2;
			var new_class = '';
			var old_class = '';
			
			if ($ul.hasClass('menu-ltr')) {
				if (ul_rigth > container_right) {
					$ul.removeClass('menu-ltr').addClass('menu-rtl');
					old_class = 'menu-ltr';
					new_class = 'menu-rtl';
				} else {
					$ul.removeClass('menu-rtl').addClass('menu-ltr');
					old_class = 'menu-rtl';
					new_class = 'menu-ltr';
				}
			} else if ($ul.hasClass('menu-rtl')) {
				if (ul_left < container_left) {
					$ul.removeClass('menu-rtl').addClass('menu-ltr');
					old_class = 'menu-rtl';
					new_class = 'menu-ltr';
				} else {
					$ul.removeClass('menu-ltr').addClass('menu-rtl');
					old_class = 'menu-ltr';
					new_class = 'menu-rtl';
				}
			} else if (ul_left > container_center) {
				$ul.removeClass('menu-ltr').addClass('menu-rtl');
				old_class = 'menu-ltr';
				new_class = 'menu-rtl';
			} else {
				$ul.removeClass('menu-rtl').addClass('menu-ltr');
				old_class = 'menu-rtl';
				new_class = 'menu-ltr';
			}
			
			if (old_class) {
				$('.sub-menu__inner', $ul).removeClass(old_class).addClass(new_class);
			} else if (new_class) {
				$('.sub-menu__inner', $ul).addClass(new_class);
			}
			
			parent_class = new_class;
		});
		
		ideapark_submenu_direction_set = true;
	};
	
	root.ideapark_megamenu = function () {
		var window_width = $window.width();
		if (window_width >= 992) {
			var $uls = $('.main-menu .product-categories > ul > li[class*="menu-col-"] > ul');
			if ($uls.length) {
				var $container = $('.main-menu .container').first();
				var container_left = $container.offset().left;
				var container_right = container_left + $container.width();
				
				$uls.each(function () {
					var delta;
					var _ = $(this);
					
					if (!_.attr('data-left')) {
						_.attr('data-left', _.css('left'));
					} else {
						_.css({
							left: _.attr('data-left')
						});
					}
					
					var ul_left = _.offset().left;
					var ul_right = ul_left + _.width();
					
					if (ul_left < container_left) {
						delta = Math.round(parseInt(_.attr('data-left').replace('px', '')) + container_left - ul_left + 1);
						_.css({
							left: delta
						});
					}
					if (ul_right > container_right) {
						delta = Math.round(parseInt(_.attr('data-left').replace('px', '')) - ul_right + container_right - 1);
						_.css({
							left: delta
						});
					}
				});
				ideapark_megamenu_left_set = true;
			}
		}
		
		if (ideapark_megamenu_left_set && window_width < 992) {
			$('.main-menu .product-categories > ul > li[class*="menu-col-"] > ul[data-left]').each(function () {
				var _ = $(this);
				_.css({
					left: 0
				});
				
				ideapark_megamenu_left_set = false;
			});
		}
	};
	
	root.ideapark_mega_menu_init = function () {
		ideapark_mega_menu_break(true);
		ideapark_megamenu();
		ideapark_submenu_direction(true);
	};
	
	root.ideapark_stickyNav = function () {
		if (ideapark_wp_vars.stickyMenu) {
			if (ideaparkStickHeight) {
				var scrollTop = $(window).scrollTop();
				var is_modal_open = $body.hasClass('menu-open') || $body.hasClass('sidebar-open');
				
				if (scrollTop > ideaparkStickHeight && !$body.hasClass('sticky')) {
					$ideapark_header.css({height: ideaparkStickHeight});
					needUpdateIdeaparkStickHeight = true;
					
					if (!is_modal_open) {
						// $ideapark_menu.hide();
					}
					$body.addClass('sticky');
					setTimeout(function () {
						$ideapark_menu.addClass('transition');
					}, 200);
					setTimeout(function () {
						$ideapark_menu.addClass('appear');
					}, 300);
					if (!is_modal_open) {
						// $ideapark_menu.fadeTo(300, 1);
					}
				} else if (scrollTop <= ideaparkStickHeight && $body.hasClass('sticky')) {
					var f = function () {
						f = function () {
						};
						$ideapark_menu.removeClass('transition');
						$body.removeClass('sticky');
						if (needUpdateIdeaparkStickHeight) {
							$ideapark_header.css({height: ''});
							ideaparkStickHeight = $ideapark_header.outerHeight();
							needUpdateIdeaparkStickHeight = false;
							if (ideapark_parallax_on) {
								ideapark_refresh_parallax();
							}
						}
						ideapark_sticky_sidebar();
					};
					ideapark_on_transition_end_callback($ideapark_menu, f);
					setTimeout(f, 400);
					$ideapark_menu.removeClass('appear');
				}
			}
		}
	};
	
	root.ideapark_banners = function () {
		
		var $w = $window;
		if ($home_banners_count) {
			
			if ($w.width() <= 991) {
				var wst = $w.scrollTop();
				var wh = $w.height();
				var bh = $('.banner', $home_banners).first().outerHeight();
				var bot = $home_banners.offset().top;
				var mmh = $body.hasClass('sticky') ? $('.main-menu').outerHeight() + 50 : 0;
				var delta = (bot - mmh) - (bot + bh - wh);
				var index = Math.round((wst - (bot + bh - wh)) / delta * $home_banners_count);
				
				if (wst < bot - mmh && wst >= bot + bh - wh || lastBannerIndex != index || wst < bot + bh - wh && lastBannerIndex != 1 || wst > bot - mmh && lastBannerIndex != $home_banners_count) {
					if (index <= 0) {
						index = 1;
					} else if (index >= $home_banners_count) {
						index = $home_banners_count;
					}
					if (!$home_banners.hasClass('shift-' + index)) {
						$home_banners.removeClass();
						$home_banners.addClass('shift-' + index);
					}
					lastBannerIndex = index;
				}
				
				$home_banners.removeClass('preloading');
			}
		}
	};
	
	root.ideapark_open_photo_swipe = function (imageWrap, index) {
		var $this, $a, $img, items = [], size, item;
		$slick_product_single_slides.each(function () {
			$this = $(this);
			
			$a = $this.children('a');
			
			if ($a.length) {
				$img = $a.children('img');
				size = $a.data('size').split('x');
				
				item = {
					src : $a.attr('href'),
					w   : parseInt(size[0], 10),
					h   : parseInt(size[1], 10),
					msrc: $img.attr('src'),
					el  : $a[0]
				};
				
				items.push(item);
			}
		});
		
		var options = {
			index              : index,
			showHideOpacity    : true,
			bgOpacity          : 1,
			loop               : false,
			closeOnVerticalDrag: false,
			mainClass          : ($slick_product_single_slides.length > 1) ? 'pswp--minimal--dark' : 'pswp--minimal--dark pswp--single--image',
			barsSize           : {top: 0, bottom: 0},
			captionEl          : false,
			fullscreenEl       : false,
			zoomEl             : false,
			shareEl            : false,
			counterEl          : false,
			tapToClose         : true,
			tapToToggleControls: false
		};
		
		var pswpElement = $('.pswp')[0];
		
		var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
		gallery.init();
		
		gallery.listen('initialZoomIn', function () {
			if ($slick_product_thumbnails.length && !ideapark_is_mobile_layout) {
				$(this).product_thumbnails_speed = $slick_product_thumbnails.slick('slickGetOption', 'speed');
				$slick_product_thumbnails.slick('slickSetOption', 'speed', 0);
			}
		});
		
		var slide = index;
		gallery.listen('beforeChange', function (dirVal) {
			slide = slide + dirVal;
			$slick_product_single.trigger('to.owl.carousel', slide);
		});
		gallery.listen('close', function () {
			if ($slick_product_thumbnails.length && !ideapark_is_mobile_layout) {
				$slick_product_thumbnails.slick('slickSetOption', 'speed', $(this).product_thumbnails_speed);
			}
		});
	};
	
	root.ajaxSearchFunction = ideapark_debounce(function () {
		var search = $("#ajax-search-input").val().trim();
		var $search_form = $("#ajax-search-input").closest('form');
		$.ajax({
			url    : ideapark_wp_vars.ajaxUrl,
			type   : 'POST',
			data   : {
				action: 'ideapark_ajax_search',
				s     : search,
				lang  : ideapark_wp_vars.locale
			},
			success: function (results) {
				$(".js-ajax-search-result").html(results).addClass('loaded');
				$('.search-shadow').removeClass('loading');
				
			}
		});
		
	}, 500);
	
	root.ideapark_to_top_button = function () {
		if ($to_top_button.length) {
			if ($window.scrollTop() > 500) {
				if (!$to_top_button.hasClass('active')) {
					$to_top_button.addClass('active');
				}
			} else {
				if ($to_top_button.hasClass('active')) {
					$to_top_button.removeClass('active');
				}
			}
		}
	};
	
	root.ideapark_owl_hide_arrows = function (event) {
		var $element;
		if (event instanceof jQuery) {
			$element = event;
		} else {
			$element = $(event.target);
		}
		var $prev = $element.find('.owl-prev');
		var $next = $element.find('.owl-next');
		if ($prev.length && $next.length) {
			if ($prev.hasClass('disabled') && $next.hasClass('disabled')) {
				$prev.addClass('h-hidden');
				$next.addClass('h-hidden');
			} else {
				$prev.removeClass('h-hidden');
				$next.removeClass('h-hidden');
			}
		}
	};
	
	root.ideapark_quickview_gallery = function ($slick_product_qv) {
		if ($slick_product_qv.length == 1) {
			var is_zoom = !!$slick_product_qv.find(".js-product-zoom").length;
			var is_zoom_mobile_hide = !!$slick_product_qv.find(".js-product-zoom--mobile-hide").length;
			$('.slick-product-qv:not(.owl-carousel)')
				.addClass('owl-carousel')
				.on('resized.owl.carousel', ideapark_owl_hide_arrows)
				.owlCarousel({
					items        : 1,
					loop         : false,
					margin       : 0,
					nav          : true,
					dots         : false,
					mouseDrag    : !is_zoom,
					touchDrag    : !is_zoom || is_zoom_mobile_hide,
					rtl          : !!ideapark_wp_vars.isRtl,
					navText      : [
						ideapark_wp_vars.arrowLeftOwl,
						ideapark_wp_vars.arrowRightOwl
					],
					onInitialized: ideapark_owl_hide_arrows
				});
		}
	};
	
	root.ideapark_init_thumbs = function (is_force) {
		
		if (typeof is_force !== 'undefined') {
			$slick_product_thumbnails = $('.slick-product');
			$slick_product_thumbnails_slides = $('.slide', $slick_product_thumbnails);
		}
		
		if ((!ideapark_is_mobile_layout || !$slick_product_thumbnails.parent().hasClass('hidden-xs')) && $slick_product_thumbnails.length && !$slick_product_thumbnails.hasClass('init')) {
			var is_vertical = ideapark_wp_vars.productThumbnails == 'left';
			
			$slick_product_thumbnails.on('init', function () {
				$slick_product_thumbnails_slides.on('click', function () {
					var $this = $(this);
					
					if ($this.hasClass('current')) {
						return;
					}
					
					var direction = $this.index() > ideapark_current_index_thumbnail ? 'right' : 'left';
					ideapark_current_index_thumbnail = $this.index();
					
					$('.slide.current', $slick_product_thumbnails).removeClass('current');
					$this.addClass('current');
					
					var cnt = 0;
					var need_to_show_next_prev = false;
					var find = function () {
						if (!$('.slide.current', $slick_product_thumbnails).hasClass('slick-active')) {
							if (direction === 'right') {
								$slick_product_thumbnails.slick('slickNext');
							} else {
								$slick_product_thumbnails.slick('slickPrev');
							}
							cnt++;
							if (cnt > 5) {
								direction = direction === 'right' ? 'left' : 'right';
								cnt = 0;
							}
							need_to_show_next_prev = true;
							setTimeout(find, 500);
						} else if (need_to_show_next_prev) {
							show_next_prev();
						}
					};
					var show_next_prev = function () {
						if (direction === 'right' && !$this.next().hasClass('slick-active')) {
							$slick_product_thumbnails.slick('slickNext');
						} else if (direction === 'left' && !$this.prev().hasClass('slick-active')) {
							$slick_product_thumbnails.slick('slickPrev');
						}
					};
					find();
					show_next_prev();
					$slick_product_single.trigger('to.owl.carousel', $this.index() - (ideapark_wp_vars.videoFirst && slick_product_has_video ? 1 : 0));
				});
			});
			
			$slick_product_thumbnails.addClass('init').slick({
				dots          : false,
				arrows        : false,
				slidesToShow  : $slick_product_thumbnails.data('count'),
				variableWidth : is_vertical ? false : true,
				slidesToScroll: 1,
				rtl           : !!ideapark_wp_vars.isRtl && !is_vertical,
				adaptiveHeight: false,
				vertical      : ideapark_wp_vars.productThumbnails === 'left',
				infinite      : false,
				focusOnSelect : false,
				draggable     : false,
				touchMove     : false,
			});
			
			return true;
		}
		return false;
	};
	
	root.ideapark_init_product_gallery = function (is_force) {
		
		if (typeof is_force !== 'undefined') {
			$slick_product_single = $('.js-product-info-carousel');
			$slick_product_single_slides = $('.slide', $slick_product_single);
		}
		
		if ($slick_product_single.length) {
			var count = $slick_product_single.find('.woocommerce-product-gallery__image').length;
			if (count > 1) {
				$('.js-product-info-carousel:not(.owl-carousel)').each(function () {
					var $this = $(this);
					var is_zoom = !!$this.find(".js-product-zoom").length;
					var is_zoom_mobile_hide = !!$this.find(".js-product-zoom--mobile-hide").length;
					
					$this
						.addClass('owl-carousel')
						.on('resized.owl.carousel', ideapark_owl_hide_arrows)
						.on('changed.owl.carousel', function (event) {
							var currentItem = event.item.index;
							ideapark_current_slide = currentItem;
							if ($slick_product_thumbnails.length && (!ideapark_is_mobile_layout || !$slick_product_thumbnails.parent().hasClass('hidden-xs'))) {
								$slick_product_thumbnails_slides.eq(currentItem + (ideapark_wp_vars.videoFirst && slick_product_has_video ? 1 : 0)).trigger('click');
							}
						})
						.owlCarousel({
							items        : 1,
							loop         : false,
							margin       : 0,
							nav          : true,
							dots         : false,
							mouseDrag    : !is_zoom,
							touchDrag    : !is_zoom || is_zoom_mobile_hide,
							rtl          : !!ideapark_wp_vars.isRtl,
							navText      : [
								ideapark_wp_vars.arrowLeftOwl,
								ideapark_wp_vars.arrowRightOwl
							],
							onInitialized: ideapark_owl_hide_arrows
						});
				});
			}
			
			$slick_product_single_slides.on('click', function (e) {
				if ($slick_product_single.hasClass('animating')) {
					return;
				}
				e.preventDefault();
				var $this = $(this);
				var index = $this.data('index');
				if (ideapark_wp_vars.shopProductModal && $this.hasClass('js-product-image-modal')) {
					ideapark_open_photo_swipe(this, index);
				}
			});
		}
	};
	
	root.ideapark_init_masonry = function () {
		if ($.fn.masonry) {
			var $grid = $('.grid.masonry');
			
			if ($grid.length) {
				$grid.masonry({
					itemSelector   : '.post, .page, .product',
					columnWidth    : '.post-sizer',
					percentPosition: true
				});
				
				$grid.imagesLoaded().progress(function () {
					$grid.masonry('layout');
				});
				
				$grid.imagesLoaded(function () {
					$grid.masonry('layout');
				});
			}
		}
	};
	
	root.ideapark_reset_sticky_sidebar = function () {
		delete root.ideapark_scroll_offset_last;
		if (ideapark_sticky_sidebar_old_style !== null) {
			$ideapark_sticky_sidebar.attr('style', ideapark_sticky_sidebar_old_style);
			ideapark_sticky_sidebar_old_style = null;
		}
		ideapark_sticky_sidebar();
	};
	
	root.ideapark_sticky_sidebar = function () {
		
		if (ideapark_wp_vars.stickySidebar && $ideapark_sticky_sidebar.length && $ideapark_sticky_sidebar_nearby.length) {
			
			var sb = $ideapark_sticky_sidebar;
			var content = $ideapark_sticky_sidebar_nearby;
			var is_disable_transition = false;
			var is_disable_transition_delay = false;
			var is_enable_transition = false;
			
			if (ideapark_is_mobile_layout) {
				
				if (ideapark_sticky_sidebar_old_style !== null) {
					sb.attr('style', ideapark_sticky_sidebar_old_style);
					ideapark_sticky_sidebar_old_style = null;
				}
				
			} else {
				
				var sb_height = sb.outerHeight(true);
				var content_height = content.outerHeight(true);
				var content_top = content.offset().top;
				var scroll_offset = $window.scrollTop();
				var window_width = $window.width();
				var $body = $('body');
				
				var top_panel_fixed_height = (ideapark_wp_vars.stickyMenu ? 80 : 0) + ($body.hasClass('admin-bar') ? 38 : 0) + 10;
				
				if (sb_height < content_height && scroll_offset + top_panel_fixed_height > content_top) {
					
					var sb_init = {
						'position': 'undefined',
						'float'   : 'none',
						'top'     : 'auto',
						'bottom'  : 'auto'
					};
					
					if (typeof ideapark_scroll_offset_last == 'undefined') {
						root.ideapark_sb_top_last = content_top;
						root.ideapark_scroll_offset_last = scroll_offset;
						root.ideapark_scroll_dir_last = 1;
						root.ideapark_window_width_last = window_width;
					}
					
					var scroll_dir = scroll_offset - ideapark_scroll_offset_last;
					if (scroll_dir === 0) {
						scroll_dir = ideapark_scroll_dir_last;
					} else {
						scroll_dir = scroll_dir > 0 ? 1 : -1;
					}
					
					var sb_big = sb_height + 30 >= $window.height() - top_panel_fixed_height,
						sb_top = sb.offset().top;
					
					if (sb_top < 0) {
						sb_top = ideapark_sb_top_last;
					}
					
					if (sb_big) {
						
						if (scroll_dir != ideapark_scroll_dir_last && sb.css('position') == 'fixed') {
							sb_init.top = sb_top - content_top;
							sb_init.position = 'absolute';
							
						} else if (scroll_dir > 0) {
							if (scroll_offset + $window.height() >= content_top + content_height + 30) {
								if (ideapark_is_sticky_sidebar_inner || ideapark_has_loader) {
									sb_init.top = (content_height - sb_height) + 'px';
									is_disable_transition = true;
								} else {
									sb_init.bottom = 0;
								}
								sb_init.position = 'absolute';
								
							} else if (scroll_offset + $window.height() >= (sb.css('position') == 'absolute' ? sb_top : content_top) + sb_height + 30) {
								sb_init.bottom = 30;
								sb_init.position = 'fixed';
								is_enable_transition = true;
							}
							
						} else {
							
							if (scroll_offset + top_panel_fixed_height <= sb_top) {
								sb_init.top = top_panel_fixed_height;
								sb_init.position = 'fixed';
								is_enable_transition = true;
							}
						}
						
					} else {
						if (scroll_offset + top_panel_fixed_height >= content_top + content_height - sb_height) {
							if (ideapark_is_sticky_sidebar_inner || ideapark_has_loader) {
								sb_init.top = (content_height - sb_height) + 'px';
								is_disable_transition = true;
								
							} else {
								sb_init.bottom = 0;
							}
							sb_init.position = 'absolute';
						} else {
							sb_init.top = top_panel_fixed_height;
							sb_init.position = 'fixed';
							is_enable_transition = true;
						}
					}
					
					if (is_disable_transition_delay) {
						is_disable_transition_delay = false;
						setTimeout(function () {
							sb.addClass('js-sticky-sidebar--disable-transition');
						}, 250);
					}
					
					if (is_disable_transition) {
						is_disable_transition = false;
						sb.addClass('js-sticky-sidebar--disable-transition');
					}
					
					if (sb_init.position != 'undefined') {
						
						if (sb.css('position') != sb_init.position || ideapark_scroll_dir_last != scroll_dir || ideapark_window_width_last != window_width) {
							
							root.ideapark_window_width_last = window_width;
							sb_init.width = sb.parent().width();
							
							if (ideapark_sticky_sidebar_old_style === null) {
								var style = sb.attr('style');
								if (!style) {
									style = '';
								}
								ideapark_sticky_sidebar_old_style = style;
							}
							sb.css(sb_init);
						}
					}
					
					if (is_enable_transition) {
						is_enable_transition = false;
						setTimeout(function () {
							sb.removeClass('js-sticky-sidebar--disable-transition');
						}, 20);
					}
					
					root.ideapark_sb_top_last = sb_top;
					root.ideapark_scroll_offset_last = scroll_offset;
					root.ideapark_scroll_dir_last = scroll_dir;
					
				} else {
					if (ideapark_sticky_sidebar_old_style !== null) {
						sb.attr('style', ideapark_sticky_sidebar_old_style);
						ideapark_sticky_sidebar_old_style = null;
					}
					setTimeout(function () {
						sb.removeClass('js-sticky-sidebar--disable-transition');
					}, 20);
				}
			}
			
		}
	};
	
	root.ideapark_init_zoom = function () {
		if (ideapark_is_mobile_layout) {
			$(".js-product-zoom--mobile-hide.init").each(function () {
				var $this = $(this);
				$this.removeClass('init').trigger('zoom.destroy');
			});
			$(".js-product-zoom:not(.js-product-zoom--mobile-hide):not(.init)").each(function () {
				var $this = $(this);
				$this.addClass('init').zoom({
					url      : $this.data('img'),
					duration : 0,
					onZoomIn : function () {
						$(this).parent().addClass('zooming');
					},
					onZoomOut: function () {
						$(this).parent().removeClass('zooming');
					}
				});
			});
		} else {
			$(".js-product-zoom:not(.init)").each(function () {
				var $this = $(this);
				$this.addClass('init').zoom({
					url      : $this.data('img'),
					duration : 0,
					onZoomIn : function () {
						$(this).parent().addClass('zooming');
					},
					onZoomOut: function () {
						$(this).parent().removeClass('zooming');
					}
					
				});
			});
		}
	};
	
	root.ideapark_init_ajax_add_to_cart = function () {
		if (ideapark_wp_vars.ajaxAddToCart) {
			$('form.cart:not(.init)').on('submit', function (e) {
				if ($(this).closest('.product-type-external').length) {
					return true;
				}
				e.preventDefault();
				var $form = $(this);
				var $button = $form.find('.single_add_to_cart_button:not(.disabled)');
				$form.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
				
				var formData = new FormData($form[0]);
				formData.append('add-to-cart', $form.find('[name=add-to-cart]').val());
				
				// Ajax action.
				$.ajax({
					url        : wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'ip_add_to_cart'),
					data       : formData,
					type       : 'POST',
					processData: false,
					contentType: false,
					complete   : function (response) {
						response = response.responseJSON;
						
						if (!response) {
							return;
						}
						
						if (response.error && response.product_url) {
							window.location = response.product_url;
							return;
						}
						
						// Redirect to cart option
						if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
							window.location = wc_add_to_cart_params.cart_url;
							return;
						}
						
						// Trigger event so themes can refresh other areas.
						$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, null]);
						
						$form.unblock();
					}
				});
			}).addClass('init');
		}
	};
	
	root.ideapark_infinity_loader = function ($button, e) {
		if (typeof e !== 'undefined') {
			e.preventDefault();
		}
		var $grid = $button.parent().prev().prev().find('.products');
		var url = $button.attr('href');
		var is_a = true;
		if (!url) {
			url = $button.data('href');
			is_a = false;
		}
		if ($button.hasClass('js-loading')) {
			return;
		}
		$button.ideapark_button('loading', is_a ? 25 : 50);
		$.ajax({
			url    : url,
			type   : 'POST',
			data   : {
				'ideapark_infinity_loading': 1
			},
			success: function (results) {
				$button.ideapark_button('reset');
				if (results.products) {
					$grid.append(results.products);
					ideapark_sticky_sidebar();
				}
				if (results.paging) {
					$button.parent().replaceWith(results.paging);
				} else {
					$button.parent().remove();
				}
				$ideapark_infinity_loader = $('.js-load-infinity');
			}
		});
	};
	
	root.ideapark_infinity_loading = function () {
		if (typeof $ideapark_infinity_loader !== 'undefined' && $ideapark_infinity_loader.length && !$ideapark_infinity_loader.hasClass('js-loading')) {
			if ($ideapark_infinity_loader.offset().top - $(window).scrollTop() - $(window).height() <= 300) {
				ideapark_infinity_loader($ideapark_infinity_loader);
			}
		}
	};
	
	root.ideapark_init_mobile_menu = function () {
		if (ideapark_init_mobile_menu.initialized) {
			return;
		}
		ideapark_init_mobile_menu.initialized = true;
		
		$("#header .mobile-menu-back").on('click', function () {
			if ($ideapark_submenu_open.length) {
				var $li = $ideapark_submenu_open.pop();
				$li.removeClass('selected');
				$li.closest('.sub-menu').removeClass('h-hidden-overflow-mobile');
				if ($ideapark_submenu_open.length === 0) {
					$(document.body).removeClass("submenu-open");
					bodyScrollLock.clearAllBodyScrollLocks();
					bodyScrollLock.disableBodyScroll($('.product-categories > .menu')[0]);
				} else {
					var $ul = $li.closest('.sub-menu');
					bodyScrollLock.clearAllBodyScrollLocks();
					bodyScrollLock.disableBodyScroll($ul[0]);
				}
			}
			return false;
		});
		$('.menu-item').on('click', function () {
			$(this).toggleClass('open');
		});
		var $ul = $(".product-categories > ul");
		if (!$ul.length) {
		 	$ul = $("<ul id=\"menu-mega-menu\" class=\"menu main-menu-container main-menu-icons main-menu-fixed\"></ul>").appendTo(".product-categories");
			$ul.append('<li class="space-item-small"></li>');
		} else {
			$ul.append('<li class="space-item"></li>');
		}
		$('#header .top-menu .menu > li').each(function () {
			$ul.append($(this).clone());
		});
		var $text = $('#home-top-menu .text');
		$ul.append('<li class="space-item"></li>');
		if ($text.length && $text.html() != '') {
			$ul.append('<li class="text">' + $text.html() + '</li>');
		}
		
		$(document).on('click', '.mobile-menu, .mobile-menu-close, .menu-open .menu-shadow', function () {
			if ($body.hasClass('cart-sidebar-open')) {
				$('#mobile-cart-close').trigger('click');
			}
			$('html').toggleClass('menu-open');
			if ($body.toggleClass('menu-open').hasClass('menu-open')) {
				bodyScrollLock.disableBodyScroll($('.product-categories > .menu')[0]);
			} else {
				bodyScrollLock.clearAllBodyScrollLocks();
			}
			return false;
			
		});
	};
	
	root.ideapark_init_mobile_sidebar = function () {
		if (ideapark_init_mobile_sidebar.initialized) {
			return;
		}
		ideapark_init_mobile_sidebar.initialized = true;
		
		$(document).on('click', '.mobile-sidebar, .mobile-sidebar-close, .sidebar-open .menu-shadow', function () {
			var $sidebar = $('#ip-shop-sidebar');
			if ($sidebar.length) {
				if ($body.hasClass('cart-sidebar-open')) {
					$('#mobile-cart-close').trigger('click');
				}
				$('html').toggleClass('sidebar-open');
				if (!isIosDevice || $('#ip-shop-sidebar').find('[class~=select2]').length === 0) {
					if ($body.toggleClass('sidebar-open').hasClass('sidebar-open')) {
						bodyScrollLock.disableBodyScroll($('#ip-shop-sidebar')[0]);
					} else {
						bodyScrollLock.clearAllBodyScrollLocks();
					}
				} else {
					$body.toggleClass('sidebar-open');
				}
			}
			return false;
		});
	};
	
	root.ideapark_init_cart_sidebar = function () {
		if (ideapark_init_cart_sidebar.initialized) {
			return;
		}
		ideapark_init_cart_sidebar.initialized = true;
		if (ideapark_wp_vars.popupCartLayout == 'sidebar') {
			$('#js-cart-sidebar').on('click', function (e) {
				e.preventDefault();
				if ($body.hasClass('menu-open')) {
					$('.mobile-menu-close').trigger('click');
				}
				if ($body.hasClass('sidebar-open')) {
					$('.mobile-sidebar-close').trigger('click');
				}
				var $sidebar = $('#ip-cart-sidebar');
				if ($sidebar.length) {
					$('html').addClass('cart-sidebar-open');
					$body.addClass('cart-sidebar-open');
				}
				return false;
			});
			$('#mobile-cart-close, .js-cart-sidebar-shadow').on('click', function (e) {
				e.preventDefault();
				var $sidebar = $('#ip-cart-sidebar');
				if ($sidebar.length) {
					$('html').removeClass('cart-sidebar-open');
					$body.removeClass('cart-sidebar-open');
				}
				return false;
			});
		}
	};
	
	root.ideapark_init_cart_auto_update = function () {
		var $button = $(".c-cart__shop-update-button--auto");
		if ($button.length) {
			$(document.body).on('change', 'input.qty', ideapark_debounce(function () {
				$(".c-cart__shop-update-button--auto").trigger("click");
			}, 500));
		}
	};
	
	root.ideapark_init_product_categories_widget = function () {
		$('.js-product-categories-widget:not(.init)').each(function () {
			let $widget = $(this);
			$('.cat-parent', $widget).each(function () {
				let $parent = $(this);
				let $plus = $('<span class="c-ip-product-categories-widget__plus js-product-categories-plus"></span>');
				$parent.append($plus);
				$parent.addClass($parent.hasClass('current-cat-parent') || $parent.hasClass('current-cat cat-parent') ? 'expanded' : 'collapsed');
				$plus.on('click', function () {
					let $li = $plus.closest('.cat-parent');
					if ($li.hasClass('collapsed')) {
						$li.removeClass('collapsed');
						$li.addClass('expanded');
						$li.children(".children").slideDown({
							duration   : 500, start: function () {
								$(this).css({
									display: "block"
								});
							}, complete: function () {
								$(this).css({
									display: "block"
								});
								ideapark_reset_sticky_sidebar();
							}
						});
					} else {
						$li.removeClass('expanded');
						$li.addClass('collapsed');
						$li.children(".children").slideUp({
							duration: 500, complete: function () {
								ideapark_reset_sticky_sidebar();
							}
						});
					}
				});
			});
			$widget.addClass('init');
		});
	};
	
	$.fn.extend({
		ideapark_button: function (option, size, ignore_size) {
			return this.each(function () {
				var $this = $(this);
				if (typeof size === 'undefined') {
					size = 32;
				}
				if (option === 'loading' && !$this.hasClass('js-loading')) {
					$this.data('button', $this.html());
					if (!ignore_size) {
						$this.data('css-width', $this.css('width'));
						// $this.data('css-height', $this.css('height'));
					} else {
						$this.data('ignore-size', $this.css('width'));
					}
					// $this.css('height', $this.outerHeight());
					$this.css('width', $this.outerWidth());
					var $loader = $('<span class="ip-shop-loop-loading"><i></i><i></i><i></i></span>');
					$loader.css({
						width: size + 'px',
						// height: size + 'px',
					});
					$this.html($loader);
					$this.addClass('h-after-before-hide js-loading');
				} else if (option === 'reset' && $this.hasClass('js-loading')) {
					var css_width = $this.data('css-width');
					// var css_height = $this.data('css-height');
					var content = $this.data('button');
					ignore_size = ignore_size || $this.data('ignore-size');
					$this.data('button', '');
					$this.data('css-width', '');
					// $this.data('css-height', '');
					$this.data('ignore-size', '');
					$this.html(content);
					$this.removeClass('h-after-before-hide js-loading');
					if (!ignore_size) {
						$this.css('width', css_width);
						// $this.css('height', css_height);
					} else {
						$this.css('width', '');
						// $this.css('height', '');
					}
				}
			});
		}
	});
	
})
(jQuery, window);