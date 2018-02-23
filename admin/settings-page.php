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
  
	<div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <!-- <form action="options.php" method="post"> -->
  
  <?php
  
  // output security fields
  // settings_fields( 'tk_notifications_options' );
  
  // output setting sections
  // do_settings_sections( 'tk_notifications' );
  
  // submit button
  // submit_button();
  
  ?>
  
  <!-- </form>  -->
  
  <?php tk_notifications_create_form(); ?>
  <h2>Subscription list</h2>
  
  
  <?php tk_notifications_create_subscription_display(); ?>

  <h2>Remove subscriptions</h2>
  
  <?php tk_notifications_create_remove_subscription_form(); ?>
  
	</div>
  
  
  
	<?php
}

function tk_notifications_create_subscription_display() {
  
  $subscription_list = tk_notifications_database_get_table();
  
  echo '<table class="subscription_list">';
  echo '<tr>';
  echo '<th>ID</th>';
  echo '<th>Email</th>';
  echo '<th>Subscription</th>';
  echo '</tr> ';
  
  foreach ($subscription_list as $key => $subscription) {
    
    $sub = json_decode( $subscription->tax_selection );
    
    $sub_1d = recursion( $sub );
    $sub_string = implode(", ", $sub_1d);
    
    var_dump($sub_string);
    
    echo '<tr>';
    echo '<td>';
    echo "$subscription->id";
    echo '</td><td>';
    echo "$subscription->email";
    echo '</td><td>';
    echo "$sub_string";
    echo '</td>';
    echo '</tr>';
  }
  echo '</table>';
}


//
// Convert multidimensional array to single dimensional array
//

function recursion( $array ) {
  
  if( !is_array( $array ) ) {
    return false;
    
  } else {
    
    $arr = array();
    foreach ( $array as $key => $value ) {
      if( is_array( $value )) {
        $arr = array_merge( $arr, recursion( $value ));
      } else {
        if ( $value != null ) {
          $arr[] = $value;
        }
      }
    }
  }
  return $arr;
}
