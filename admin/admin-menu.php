<?php // TK Notifications - Admin Menu

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

  exit;

}


//
// Create settings page
//
function tk_notifications_add_toplevel_menu() {

	/*
		add_menu_page(
			string   $page_title,
			string   $menu_title,
			string   $capability,
			string   $menu_slug,
			callable $function = '',
			string   $icon_url = '',
			int      $position = null
		)
	*/

	add_menu_page(
		'TK Notifications Settings',
		'TK Notifications',
		'manage_options',
		'tk_notifications',
		'tk_notifications_display_settings_page',
		'dashicons-admin-generic',
		null
	);
}
add_action( 'admin_menu', 'tk_notifications_add_toplevel_menu' );
