<?php // TK Notifications - Remove Subscription Form

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

//
// Subscription form. Defines a shortcode [tknotifications]
//

function tk_notifications_create_remove_subscription_form() {

    // Get the current URL
    global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));


    if ( get_transient('tk_notifications_remove') == 'SUCCESS' ) {
      echo '<p class="tk_notifications_remove_success">Subscription  successfully removed.</p>';
    } elseif ( get_transient('tk_notifications_remove') == 'FAILED' ) {
      echo '<p class="tk_notifications_remove_failed">Removing subscription failed for some reason</p>';
    } elseif ( get_transient('tk_notifications_email') == 'FAILED' ) {
      echo '<p class="tk_notifications_email_failed">Please enter a valid email address</p>';
    }

  ?>

  <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
    <input type="hidden" name="action" value="contact_form">
    <input type="hidden" name="url" value="<?php echo $current_url; ?>">

    <p><label for="email">Cancel subscription by entering an email address.</label></p>

    <p><input id="email" type="text" name="tk_notifications_remove_email"></p>

    <p><input type="submit" value="Remove My Subscription"></p>

    <?php wp_nonce_field( 'tk_notifications_remove_subscription_form_action', 'tk_notifications_nonce_field', false ); ?>

  </form>

  <?php

}
add_shortcode('tknotificationsremove', 'tk_notifications_create_remove_subscription_form');


