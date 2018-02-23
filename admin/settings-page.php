<?php // TK Notifications - Settings Page

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}


//
// Show settings page
//

function tk_notifications_display_settings_page() {
  
	// check if user is allowed access
	if ( ! current_user_can( 'manage_options' ) ) return;
  
	?>
  
	<div class="admin-settings-page">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <!-- <form action="options.php" method="post"> -->
  
  <?php
  
  // output security fields
  settings_fields( 'tk_notifications_settings' );
  
  // output setting sections
  do_settings_sections( 'tk_notifications' );
  
  // submit button
  // submit_button();
  
  ?>
  
  </form> 
  
	</div>
  
  
  
	<?php
}




