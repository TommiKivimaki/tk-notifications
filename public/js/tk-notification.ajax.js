/*
	
	Ajax Example - JavaScript for Admin Area
	
*/
(function($) {
	
	$(document).ready(function() {
		
		// when user clicks the link
		$('.ajax-form').on( 'submit', function(event) {
			
			// prevent default (Do not redirect after submitting form)
			event.preventDefault();
			
			// add loading message
			$('.ajax-response').html('Loading...');
			
			// define url
			var author_id = $(this).data('tk_test_email');
			// var author_id = $('#tk_test_email').val();
        
            // submit the data
			$.post(ajax_public.ajaxurl, {
				
				nonce:     ajax_public.nonce,
				action:    'public_hook',
				author_id: author_id
				
			}, function(data) {
				
				// log data
				console.log(data);
				
				// display data
				$('.ajax-response').html(data);
				
			});
			
		});
		
	});
	
})( jQuery );
