<?php // TK Notifications - Remove Subscription Form

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

//
// Subscription form. Defines a shortcode [tknotifications]
//

function tk_notifications_create_remove_subscription_form() {

  ?>

  <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    <input type="hidden" name="action" value="contact_form">

    <p><label for="email">Cancel subscription by entering an email address.</label></p>

    <p><input id="email" type="text" name="tk_notifications_remove_email"></p>

    <p><input type="submit" value="Remove My Subscription"></p>

    <?php wp_nonce_field( 'tk_notifications_remove_subscription_form_action', 'tk_notifications_nonce_field', false ); ?>

  </form>

  <?php

}
add_shortcode('tknotificationremove', 'tk_notifications_create_remove_subscription_form');


