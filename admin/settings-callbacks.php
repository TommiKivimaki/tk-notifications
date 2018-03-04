<?php // TK Notifications - Settings callbacks


// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
	exit;
  
}


//
//
// Callbacks for sections
//
/////////////////////////

//
//  Show section to add subscribers
//

function tk_notifications_callback_section_recaptcha() {
  
  echo '<p>You need to set the reCAPTCHA keys here before site visitors can use the subscription forms. You can get the keys from Google reCAPTCHA page. </p>';
  
}


//
//
// Callbacks for settings fields
//
////////////////////////////////


//
//  Text field processing
//

function tk_notifications_callback_text_field( $args ) {
  
	$options = get_option( 'tk_notifications_options', tk_notifications_options_default() );
  
  $id    = isset( $args['id'] )    ? $args['id']    : '';
  $label = isset( $args['label'] ) ? $args['label'] : '';
  
  $value = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';
  
  echo '<input id="tk_notifications_options_'. $id .'" name="tk_notifications_options['. $id .']" type="text" size="40" value="'. $value .'"><br />';
	echo '<label for="tk_notifications_options_'. $id .'">'. $label .'</label>';
}


//
//  Show remove subscription field
//

// function tk_notifications_callback_field_add_subscribers() {
  
//   echo '<p>Pick the topics you want to subscribe and type in an email address.</p>';
  
//   tk_notifications_create_form();
// }


//
//  Show settings field to list all subscribers
//

function tk_notifications_callback_field_list_subscribers() {
  
  // echo '<p>Subscriptions</p>';
  
  $subscription_list = tk_notifications_database_get_table();
  
  // echo '<table class="subscription_list">';
  // echo '<tr>';
  // echo '<th class="sub-id sub-head">ID</th>';
  // echo '<th class="sub-email sub-head">Email</th>';
  // echo '<th class="sub-tax sub-head">Subscription</th>';
  // echo '</tr> ';

  $html = '<table class="subscription_list">';
  $html .= '<tr>';
  $html .= '<th class="sub-id sub-head">ID</th>';
  $html .= '<th class="sub-email sub-head">Email</th>';
  $html .= '<th class="sub-tax sub-head">Subscription</th>';
  $html .= '</tr> ';
  echo $html;
  
  foreach ($subscription_list as $key => $subscription) {
    
    $sub_hash = $subscription->sub_hash;
    $remove_link = home_url() . '/wp-json/tk_notifications/v1/unsubscribe?hash' . '=' . $sub_hash;
    
    $sub = json_decode( $subscription->tax_selection );
    
    $sub_1d = tk_notifications_unwrap_arrays( $sub );
    $sub_string = implode(", ", $sub_1d);
    
    $html = '<tr>';
    $html .= '<td class="sub-id">';
    $html .= '<a id="' . $sub_hash . '" href="'. $remove_link .'">';
    $html .= "$subscription->id";
    $html .= '</a>';
    $html .= '</td><td class="sub-email">';
    $html .= "$subscription->email";
    $html .= '</td><td class="sub-tax">';
    $html .= "$sub_string";
    $html .= '</td>';
    $html .= '</tr>';
    echo $html;

    // echo '<tr>';
    // echo '<td class="sub-id">';
    // echo '<a id="' . $sub_hash . '" href="'. $remove_link .'">';
    // echo "$subscription->id";
    // echo '</a>';  
    // echo '</td><td class="sub-email">';
    // echo "$subscription->email";
    // echo '</td><td class="sub-tax">';
    // echo "$sub_string";
    // echo '</td>';
    // echo '</tr>';
  }
  echo '</table>';
  echo '<div class="koepala"></div>'; // ********************
}


//
// Handles updating the subscription list table
//

function tk_notifications_ajax_table_refresh_handler() {

  check_ajax_referer( 'ajax_admin', 'nonce' );

  if ( ! current_user_can( 'manage_options' ) ) return;

  $sub_hash = isset( $_POST['sub_hash']) ? $_POST['sub_hash'] : false;
  write_log($_POST);

  // echo 'sub_hash oli' . $sub_hash . ' - ';
  echo '{tk_notifications_database_get_table()}';

  wp_die();
}
add_action( 'wp_ajax_admin_hook', 'tk_notifications_ajax_table_refresh_handler' );


// DEBUG ONLY
function write_log ( $log )  {
  if ( true === WP_DEBUG ) {
    if ( is_array( $log ) || is_object( $log ) ) {
      error_log( print_r( $log, true ) );
    } else {
      error_log( $log );
    }
  }
}

//
//  Show settings field to list all subscribers
//

function tk_notifications_callback_field_remove_subscribers() {
  
  // echo '<p>This field will show the remove subscribers form.</p>';
  
  tk_notifications_create_remove_subscription_form();
}



//
// Helper function to unwrap multi-dimensional arrays
//

//
// Convert multidimensional array to single dimensional array
//

function tk_notifications_unwrap_arrays( $array ) {
  
  if( !is_array( $array ) ) {
    return false;
    
  } else {
    
    $arr = array();
    foreach ( $array as $key => $value ) {
      if( is_array( $value )) {
        $arr = array_merge( $arr, tk_notifications_unwrap_arrays
        ( $value ));
      } else {
        if ( $value != null ) {
          $arr[] = $value;
        }
      }
    }
  }
  return $arr;
}
