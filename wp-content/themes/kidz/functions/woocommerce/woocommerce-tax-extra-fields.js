(function ($, root, undefined) {

	$(function () {
		"use strict";

		var ajax = new XMLHttpRequest();
		ajax.open("GET", ideapark_wp_vars_wtef.themeUri + "/img/sprite.svg", true);
		ajax.send();
		ajax.onload = function (e) {
			var div = document.createElement("div");
			div.className = "wtef-svg-sprite-container";
			div.innerHTML = ajax.responseText;
			document.body.insertBefore(div, document.body.childNodes[0]);
		};

		$('.wtef-svg-icons input[type=radio]').on('click', function(){
			$('.wtef-svg-icons .clear').addClass('show');
		});
	});


})(jQuery, this);
