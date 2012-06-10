(function($) {
	$(function() {
		
		// Setup a click handler to initiate the Ajax request and handle the response
		$('#post_author_credit_checkbox').click(function(evt) {
			
			// Use jQuery's post method to send the request to the server. The 'ajaxurl' is a URL
			// provided by WordPress' Ajax API.
			$.post(ajaxurl, {
				
				action:				'save_post_author_credit',					// The function located in plugin.php for handling the request
				nonce: 				$('#ajax_post_author_credit_nonce').text(),	// The security nonce
				post_id:			$('#post_ID').val(),						// The ID of the post with which we're working
				post_author_credit: $(this).is(':checked')						// Whether or not the checkbox is checked when clicked
					
			}, function(response) {
			
				/* 
					Here, you can read the response data and handle the response however you want.
					You can add a new element to the DOM, toggle visibility of a page element, etc. 

					These values correspond to the 0, 1, and -1 located in ajax_save_post_author_credit() in
					plugin.php.
					
					if( 0 === response) {
					
						// The request was successful and saving of the data succeeded.
						
					} else if( 1 === response ) {
					
						// The request was successful but saving the data failed.
						
					} else if( -1 === response ) {
					
						// The request failed the security check.
						
					} // end if/else
			
				*/
				
			});
			
		});
		
	});
})(jQuery);