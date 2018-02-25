<?php // TK Notifications - Validate Settings



// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
	
}



// callback: validate options
function tk_notifications_callback_validate_options( $input ) {
	
	
	// site key
	if ( isset( $input['site_key_option'] ) ) {
		
		$input['site_key_option'] = sanitize_text_field( $input['site_key_option'] );
		
    }
    
    // site secret
	if ( isset( $input['site_secret_option'] ) ) {
		
		$input['site_secret_option'] = sanitize_text_field( $input['site_secret_option'] );
		
	}
	
	
	return $input;
	
}


