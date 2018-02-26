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
  
  <?php
    
    // Get the current URL
    global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));


    if ( get_transient('tk_notifications_add') == 'SUCCESS' ) {
      echo '<p class="tk_notifications_add_success">Your subscription was successful and you should receive a confirmation email shortly.</p>';
    } elseif ( get_transient('tk_notifications_add') == 'FAILED' ) {
      echo '<p class="tk_notifications_add_failed">Subscription failed for some reason</p>';
    } elseif ( get_transient('tk_notifications_update') == 'SUCCESS' ) {
      echo '<p class="tk_notifications_update_success">Your subscription was updated successfully</p>';
    } elseif ( get_transient('tk_notifications_update') == 'FAILED' ) {
      echo '<p class="tk_notifications_update_failed">Updating your subscription failed for some reason</p>';
    } elseif ( get_transient('tk_notifications_email') == 'FAILED' ) {
      echo '<p class="tk_notifications_email_failed">Please enter a valid email address</p>';
    } elseif ( get_transient('tk_notifications_recaptcha') == 'FAILED' ) {
      echo '<p class="tk_notifications_recaptcha_failed">Please fill in the reCAPTCHA.</p>';
    }

  ?>
  
  <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
  <input type="hidden" name="action" value="contact_form">
  <input type="hidden" name="url" value="<?php echo $current_url; ?>">

  <p><label for="email">What you want to subscribe?</label></p>
  <p><input type="checkbox" name="post_type[]" value="post" /> Blog posts</p>
  <p><input type="checkbox" name="post_type[]" value="page" /> Pages</p>
  
  <p><input type="checkbox" name="category[]" value="category1" /> Category 1</p>
  <p><input type="checkbox" name="category[]" value="category2" /> Category 2</p>
  <p><input type="checkbox" name="category[]" value="moro" /> Moro</p>
  
  <select name="rating[]">
  <option value="best">best</option>
  <option value="ok">ok</option>
  <option value="worst">worst</option>
  </select>
  
  <p><input type="hidden" name="taxonomies[]" value="post_type"></p>
  <p><input type="hidden" name="taxonomies[]" value="category"></p>
  <p><input type="hidden" name="taxonomies[]" value="rating"></p>
  
  <p>E-mail address:</p>
  <p><input id="email" type="text" name="tk_notifications-email"></p>
  
  <?php tk_notifications_display_recaptcha(); ?>
  
  <p><input type="submit" value="Submit Subscription"></p>
  
  <?php wp_nonce_field( 'tk_notifications_form_action', 'tk_notifications_nonce_field', false ); ?>
  
  </form>
  
  <?php
  
}
add_shortcode('tknotification', 'tk_notifications_create_form'); // Muuta shortcode monikkoon!




// wp_enqueue_script( 'recaptcha', plugin_dir_path( __FILE__ ) . '/js/recaptcha.js' );
