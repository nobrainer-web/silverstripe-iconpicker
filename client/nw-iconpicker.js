(function ($) {
	$.entwine(function ($) {
		$("select.js-nw-icon-picker").entwine({
			onchange: function () {
				this._super();
				$('#Form_EditForm_Icon_chosen').trigger('change');
			},
			onmatch: function () {
				this._super();
				this.select2({
					templateResult: formatState
				});
			}
		});
		function formatState(state) {
			if (!state.id) {
				return state.text;
			}
			return $('<span><img src="' + state.element.dataset.icon + '" class="img-flag" /> ' + state.text + '</span>');
		}

		$("input.js-nw-icon-textfield").entwine({
			updateIcon: function () {

				var field = $(this),
					img = field.siblings('img').first(),
					src = img.attr('src');
				if(!checkFieldValue(field)){
					toggleImg(field, img);
					return;
				}
				src = src.replace(/(.*)\/.*(\.svg)/i, '$1/' + field.val() + '$2');

				img.attr('src', src);

				setTimeout(function(){
					toggleImg(field, img);
					//field.removeClass('changed'); // fix bug
				}, 700);
			},
			onmatch: function () {
				this.updateIcon();
				this._super();
			},
			onchange: function () {
				this.updateIcon();
				this._super();
			},/*
			onclick: function () {
				this.updateIcon();
			},
			oninput: function () {
				this.updateIcon();
			},*/
			onkeyup: function () {
				this.updateIcon();
				this._super();
			},
			onpaste: function () {
				this.updateIcon();
				this._super();
			}
		});
		function checkFieldValue(field) {
			return field.val();
		}
		function toggleImg(field, img){
			if(!checkFieldValue(field)){
				img.hide();
			} else {
				img.css('display', 'flex');
			}
		}
	});
})(jQuery);