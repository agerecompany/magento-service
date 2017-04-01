var AgereService = {
	elm: null,

	fancyOptions: {
		'ajax': {cache: true, dataType: 'html'},
		'minWidth': 300,
		'afterShow': function (prev, curr) {
			AgereService.elm.trigger('fb.afterShow', [prev, curr]);
		},
		'beforeLoad': function() {
			this.ajax.data = {
				'referUrl': window.location.href
			};
			AgereService.elm.trigger('fb.beforeLoad');
		},
	},

	activate: function() {
		var self = AgereService;
		self.elm = jQuery(this);
		var options = jQuery.extend(true, self.fancyOptions, self.elm.data('options').fancybox);
		// @see http://stackoverflow.com/a/13948085/1335142
		jQuery.fancybox.open([
			options
		], {
			padding : 0
		});
	},

	capitalizeFirstLetter: function (string) {
		return string[0].toUpperCase() + string.slice(1);
	}
};

//jQuery(document).ready(function($) {
//	AgereService.init();
//});