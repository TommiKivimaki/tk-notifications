<?php // TK Notifications - Database Methods

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
	
}


//
// Create a table to store subscriber information
//

function tk_notifications_database_create_table() {
	
	global $wpdb;
	global $tk_notification_db_version;
	
	$installed_ver = get_option( "tk_notification_db_version" );
	
	if ( $installed_ver != $tk_notification_db_version || $installed_ver == false ) {
		$table_name = $wpdb->prefix . 'tk_notifications';
		
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql = "CREATE TABLE $table_name (
			id int NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			tax_selection varchar(255) NOT NULL,
			sub_hash varchar(255) NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";
			
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
			
			update_option( "tk_notification_db_version", $tk_notification_db_version );
  }
  add_option( "tk_notification_db_version", $tk_notification_db_version );    
}
  
  
//
// Remove the table from database when plugin is de-activated
//
  
function tk_notifications_database_remove_table() {
    
  global $wpdb;
  
  $table_name = $wpdb->prefix . 'tk_notifications';
    
  $sql = "DROP TABLE IF EXISTS $table_name;";
    
  $wpdb->query($sql);
    
  delete_option("tk_notification_db_version");
}
  
  
//
// Create a new subscription row in to the table
//
  
function tk_notifications_database_create_table_data( $data, $args ) {		
  global $wpdb;
    
  $email = $data;
  $tax_selection_json = json_encode( $args );
  $sub_hash = md5( $email . $tax_selection_json );
  $success = 0;

  $table_name = $wpdb->prefix . 'tk_notifications';
    
  // New subscription added only if similar subscription does not exist yet
  if ( !tk_notifications_database_table_data_exists( $sub_hash ) ) { 
    $wpdb->insert(
      $table_name,
      array(
        'email' => $email,
        'tax_selection' => $tax_selection_json,
        'sub_hash' => $sub_hash,
        )
      );
    $success = $sub_hash;
  }
  return $success;
}
    
    
//
// Updates existing subscription row in the table
//
    
function tk_notifications_database_update_table_data( $data, $args ) {
      
  global $wpdb;
      
  $email = $data;
  $tax_selection_json = json_encode( $args );
      
  $table_name = $wpdb->prefix . 'tk_notifications';
      
  $success = $wpdb->update(
    $table_name,
    array(
      'email' => $email,
      'tax_selection' => $tax_selection_json,
    ),
    array(
      'email' => $email
      )
    );
      
  return $success;
}
      
      
//
// Removes an existing subscription row in the table
//
      
function tk_notifications_database_remove_table_data( $data ) {
        
  global $wpdb;
        
  $sub_hash = $data;
        
  $table_name = $wpdb->prefix . 'tk_notifications';
        
  $success = $wpdb->delete(
    $table_name,
    array(
      'sub_hash' => $sub_hash
      )
    );
          
  return $success;
}
        
        
//
// Checks if subsciption is found from the table.
// Return value: If subscription is found returns the ID otherwise returns false.
//
        
function tk_notifications_database_table_data_exists( $data ) {
          
  global $wpdb;
          
  $sub_hash = $data;
          
  $table_name = $wpdb->prefix . 'tk_notifications';
          
  $query = $wpdb->prepare(
    "
    SELECT sub_hash
    FROM $table_name
    WHERE EXISTS
    (SELECT sub_hash FROM $table_name WHERE sub_hash = %s)", $data
  );
          
  $results = $wpdb->query( $query );
          
  return $results;
          
}
        
        
//
// Get table. Reads all the rows and columns from the table.
//
        
function tk_notifications_database_get_table() {
          
  global $wpdb;
          
  $table_name = $wpdb->prefix . 'tk_notifications';
          
  $query = $wpdb->get_results(
    "
    SELECT *
    FROM $table_name
    "
  );
          
  return $query;
}
        