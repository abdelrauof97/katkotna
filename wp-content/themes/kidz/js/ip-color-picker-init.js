(function ($) {
	
	'use strict';
	
	function initColorPicker(widget) {
		widget.find('.ip-widget-color-picker:not(.init)').addClass('init').wpColorPicker({
			change: function (event, ui) {
				$(this).closest('form').find('input[type=hidden]').first().trigger('change');
				var element = event.target;
				var color = ui.color.toString();
			},
		});
		setTimeout(function (){
			widget.find('.wp-color-result').each(function () {
				$(this).attr('data-name', $(this).closest('li').data('name'));
			});
		}, 100);
	}
	
	function onFormUpdate(event, widget) {
		initColorPicker(widget);
	}
	
	var $document = $(document);
	
	$document.on('widget-added widget-updated', onFormUpdate);
	
	$document.ready(function () {
		$('.ip-widget-attributes-list').each(function () {
			if ($(this).data('id') != 'ip_woocommerce_color_filter-__i__') {
				initColorPicker($(this));
			}
		});
	});
}(jQuery));
