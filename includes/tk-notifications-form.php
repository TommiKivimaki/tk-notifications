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
    <input type="hidden" name="action" value="contact_form">

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

    <!-- <div class="g-recaptcha" data-sitekey= "6Lej60cUAAAAAO6TOFk9LA4BDUyn0bKAdyh5jWTD
"></div> -->

    <p><input type="submit" value="Submit Subscription"></p>

    <?php wp_nonce_field( 'tk_notifications_form_action', 'tk_notifications_nonce_field', false ); ?>

  </form>

  <?php

}
add_shortcode('tknotification', 'tk_notifications_create_form'); // Muuta shortcode monikkoon!




// wp_enqueue_script( 'recaptcha', plugin_dir_path( __FILE__ ) . '/js/recaptcha.js' );
