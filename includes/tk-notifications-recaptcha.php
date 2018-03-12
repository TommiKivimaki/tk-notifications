<?php // TK Notifications - reCAPTCHA methods

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}

//
// Display reCAPTCHA
//

function tk_notifications_display_recaptcha() {
  
  $options = get_option( 'tk_notifications_options', tk_notifications_options_default() );
  $tk_notification_site_key = $options['site_key_option'];
  
  echo '<div class="g-recaptcha" data-sitekey='. $tk_notification_site_key .' data-callback="recaptcha_callback"></div>';
}





//
// Verify reCAPTCHA (Ajax version) with site secret key
//

function tk_notifications_verify_captcha_ajax( $captcha ) { 
  
  $options = get_option( 'tk_notifications_options', tk_notifications_options_default() );
  $tk_notification_site_secret = $options['site_secret_option'];
  
  if( $captcha != null ) {
    $response = json_decode(wp_remote_retrieve_body( wp_remote_get( "https://www.google.com/recaptcha/api/siteverify?secret={$tk_notification_site_secret}&response=" .$captcha) ), true );
    
    if( $response["success"] ) {
      return $response["success"];
    }
  }
  
  return false;
}
