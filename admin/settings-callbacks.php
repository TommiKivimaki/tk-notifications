<?php // TK Notifications - Settings callbacks


// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    
	exit;
    
}


//
// Validate settings
//

function tk_notifications_settings_callback_validate($input) {

    return $input;
}


//
//  Show settings to add subscribers
//

function tk_notifications_callback_section_add() {

	echo '<p>Add subscribers.</p>';

}


//
//  Show settings to remove subscribers
//


function tk_notifications_callback_section_remove() {

	echo '<p>Add subscribers.</p>';

}


//
//  Show settings to list all subscribers
//

function tk_notifications_callback_section_list() {

	echo '<p>Add subscribers.</p>';

}
