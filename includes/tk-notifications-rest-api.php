<?php // TK Notifications - REST API

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}


// 
// Custom API callback to remove a subscription if it exist
//

function tk_notifications_api_callback( WP_REST_Request $request ) {
  
  $parameters = $request->get_query_params(); 
  $sub_hash = $parameters['hash'];
  $results = implode( $parameters );
  
  $return_value = '';
  
  if ( tk_notifications_database_table_data_exists( $sub_hash ) ) {
    $return_value = 'Subscription found and removed.';
    tk_notifications_database_remove_table_data( $sub_hash );
  } else {
    $return_value = 'Subscription does not exist.';
  }
  
  return $return_value;
}


// 
// Register custom route to the API
//

add_action( 'rest_api_init', function() {
  register_rest_route( 'tk_notifications/v1', 'unsubscribe', array(
    'methods' => 'GET',
    'callback' => 'tk_notifications_api_callback',
    'args' => array(
      'hash'
    )));
  });