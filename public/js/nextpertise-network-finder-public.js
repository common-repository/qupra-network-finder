(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 $( window ).load(function() {
	 	// $("form.search_provider").on('submit', function(e){
	 	// 	e.preventDefault();
	 	// 	$(this).find('.search_results').html('');
	 	// 	let resultSection = $(this).find('.search_results');
	 	// 	console.log(resultSection[0]);
	 	// 	let data = $(this).serializeArray();
	 	// 	let formData = {};
	 	// 	for(var d in data){
	 	// 		formData[data[d].name] = data[d].value;
	 	// 	}
	 	// 	$.ajax({
			// 	url: admin.base_url+"/wp-json/mra/v1/lookup",
			// 	type: 'post',
			// 	data: formData,
			// 	success: function(response) {
			// 		if(response.status){
			// 			var div document.createElement("div");
			// 			for (var i = 0; i < response.result.available.length; i++) {
							
			// 			}
			// 			// var list = document.createElement('ul');
			// 			// for (var i = 0; i < response.result.available.length; i++) {
			// 			// 	var item = document.createElement('li');
			// 			// 	item.appendChild(document.createTextNode(response.result.available[i].Provider));
			// 			// 	list.appendChild(item);
			// 			// }
			// 			// resultSection[0].appendChild(list);
			// 		}
			// 	}
			// });

	 	// });
	 });

})( jQuery );
