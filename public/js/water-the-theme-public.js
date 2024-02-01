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
	 
	 	// #BED8D3' '#D8E4E6'
		// $('#Statistics tr:nth-child(6n), #Statistics tr:nth-child(6n-1), #Statistics tr:nth-child(6n-2)').css({backgroundColor:'#D8E4E6'});
		// $('#Statistics tr:nth-child(6n-3), #Statistics tr:nth-child(6n-4), #Statistics tr:nth-child(6n-5)').css({backgroundColor:'#BED8D3'});	 
	 
		$("td[datetime]").each(function(key,value){
																	  var dt = $(value).attr('datetime');
																	  if (dt != 'n/a') {
																	  	var d = new Date(Number(dt));
																	  	d = d.toLocaleString();
																	  	$(this).html(d);
																	  } else {
																		 $(this).html(dt);				  
																	  }
																	  
																});

	});
	
	
})( jQuery );
