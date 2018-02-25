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
	
    // add_settings_section(
    //     'tk_notifications_section_subscriptions', 					// section ID
    //     'Set, remove and monitor subscriptions',  					// section TITLE
    //     'tk_notifications_callback_section_subscriptions',  		// callback
    //     'tk_notifications'                        					// page where to display this section
	// );

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
	
    // add_settings_field(
	// 	'remove_subscription',                                 // settings field ID
	// 	'Remove subscription',                                 // settings field Title
	// 	'tk_notifications_callback_field_remove_subscribers',  // settings field callback
	// 	'tk_notifications',                                    // page where to display this field
	// 	'tk_notifications_section_subscriptions'               // section name where to display this field.
    // );
    
    // add_settings_field(
	// 	'list_subscriptions',                               // settings field ID
	// 	'Subscription list',                                // settings field Title
	// 	'tk_notifications_callback_field_list_subscribers', // settings field callback
	// 	'tk_notifications',                                 // page where to display this field
	// 	'tk_notifications_section_subscriptions'            // section name where to display this field.
	// );

	// add_settings_field(
	// 	'add_subscription',                                // settings field ID
	// 	'Add subscription',                                // settings field Title
	// 	'tk_notifications_callback_field_add_subscribers', // settings field callback
	// 	'tk_notifications',                                // page where to display this field
	// 	'tk_notifications_section_subscriptions'           // section name where to display this field.
    // );

    
}
add_action( 'admin_init', 'tk_notifications_register_settings' );
