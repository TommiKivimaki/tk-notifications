//
// TK Notifications - Script to submit values from the form to backend. 
//

(function($) {
	
	$(document).ready(function() {
		
		// when user submits the form
		$('.ajax-form').on( 'submit', function(event) {
			
			// prevent form submission, prevents reload
			event.preventDefault();
			
			// add loading message, instead updates the response field
			$('.ajax-response').html('Loading...');
			
			// define values
			var email = $('#email').val();
			var $this = $(this);
			
			// submit the data
			$.post(ajax_public.ajaxurl, {
				
				nonce:  ajax_public.nonce,
				action: 'public_hook',
				email:    email,
				data: $this.serializeArray(),
				
			}, function(data) {
				
				// log data
				console.log(data);
				
				// display data
				$('.ajax-response').html(data);
				
			});
			
		});
		
	});
	
})( jQuery );