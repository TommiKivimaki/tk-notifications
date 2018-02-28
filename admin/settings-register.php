<?php // TK Notifications - Register Admin Settings


// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// register plugin settings
function tk_notifications_register_settings() {

	register_setting(
		'tk_notifications_options', 				 	 // option_group
		'tk_notifications_options', 				 	 // option_name
		'tk_notifications_callback_validate_options'  	// sanitize_callback
	);

	add_settings_section(
        'tk_notifications_section_recaptcha', 							// section ID
        'Google reCAPTCHA settings',			  				// section TITLE
        'tk_notifications_callback_section_recaptcha',  		// callback
        'tk_notifications'                        				// page where to display this section
	);

	add_settings_field(
		'recaptcha_site_key',                                      // settings field ID
		'Site Key',                                  // settings field Title
		'tk_notifications_callback_text_field', 		   	   // settings field callback
		'tk_notifications',                                    	   // page where to display this field
		'tk_notifications_section_recaptcha',	                   // section name where to display this field.
		['id' => 'site_key_option', 'label' => 'Paste the Site Key from Google here.' ]
	);

	add_settings_field(
		'recaptcha_site_secret',                                      // settings field ID
		'Site Secret',                              // settings field Title
		'tk_notifications_callback_text_field', 			 // settings field callback
		'tk_notifications',                                    	 // page where to display this field
		'tk_notifications_section_recaptcha',	                 // section name where to display this field.
		['id' => 'site_secret_option', 'label' => 'Paste the Site Secret from Google here.' ]
	);
    
}
add_action( 'admin_init', 'tk_notifications_register_settings' );
