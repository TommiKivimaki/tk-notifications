<?php // TK Notifications - Register Admin Settings


// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// register plugin settings
function tk_notifications_register_settings() {

	register_setting(
		'tk_notifications_settings',  // option_group
		'tk_notifications_settings',  // option_name
		'tk_notifications_settings_callback_validate'  // sanitize_callback
	);

    add_settings_section(
        'tk_notifications_section_add',          // section ID
        'Add Subscribers',                       // section TITLE
        'tk_notifications_callback_section_add', // callback
        'tk_notifications'                       // page where to display this section
    );

    add_settings_section(
        'tk_notifications_section_list',          // section ID
        'Subscription list',                      // section TITLE
        'tk_notifications_callback_section_list', // callback
        'tk_notifications'                        // page where to display this section
    );
    
    add_settings_section(
        'tk_notifications_section_remove',          // section ID
        'Remove Subscribers',                       // section TITLE
        'tk_notifications_callback_section_remove', // callback
        'tk_notifications'                          // page where to display this section
    );

}
add_action( 'admin_init', 'tk_notifications_register_settings' );
