(function ($) {
	$.entwine("js-nw-icon-picker", function ($) {
		$("select.js-nw-icon-picker").entwine({
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
	});
})(jQuery);