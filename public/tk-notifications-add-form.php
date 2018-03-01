<?php // TK Notifications - Form

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}

//
// Subscription form. Defines a shortcode [tknotifications]
//

function tk_notifications_create_form() {
  
  ?>
  
  <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">


  <?php tk_notifications_form_layout(); ?>

  <?php tk_notifications_display_recaptcha(); ?>
  
  <p><input type="submit" value="Submit Subscription"></p>
  
  <?php wp_nonce_field( 'tk_notifications_form_action', 'tk_notifications_nonce_field', false ); ?>
  
  </form>
  
  <?php
  
}
add_shortcode('tknotifications', 'tk_notifications_create_form'); // Muuta shortcode monikkoon!

