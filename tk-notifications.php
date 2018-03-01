<?php
/*
Plugin Name:  TK Notifications
Description:  Sends notifications to subscribed users when a new post is published.
Plugin URI:
Author:       Tommi Kivimäki
Version:      0.2.0
Text Domain:  tk-notifications
Domain Path:  /languages
License:      
License URI:  

*/


// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}

//
// Version number for the database table structure
//
global $tk_notification_db_version;
$tk_notification_db_version = '1.0';


//
// Public includes
//

require_once plugin_dir_path( __FILE__ ) . 'public/tk-notifications-add-form.php';
require_once plugin_dir_path( __FILE__ ) . 'public/tk-notifications-add-ajax-form.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/tk-notifications-database-methods.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/tk-notifications-recaptcha.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/tk-notifications-rest-api.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/tk-notifications-form-layout.php';
require_once plugin_dir_path( __FILE__ ) . 'templates/tk-notifications-ajax-form-layout.php';


//
// Admin Menu includes
//

if ( is_admin() ) {
  require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callbacks.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';
}


//
// Returns plugin's default options if they are not found from the database.
//

function tk_notifications_options_default() {
  
  return array(
    'site_key_option'    => 'UNDEFINED',
    'site_secret_option' => 'UNDEFINED'
  ); 
}


//
// Activation
//

function tk_notifications_activate() {
  tk_notifications_database_create_table();
}
register_activation_hook( __FILE__, 'tk_notifications_activate' );


//
// Load admin area style
//

function tk_notifications_enqueue_admin_style() {
  
  $src = plugin_dir_url( __FILE__ ) . 'admin/css/tk-notifications-admin.css';
  
  wp_enqueue_style( 'tk-notifications-admin', $src, array(), null, 'all' );
  
}
add_action( 'admin_enqueue_scripts', 'tk_notifications_enqueue_admin_style');


//
// Enqueue scripts
//

function tk_notifications_enqueue_scripts() {
  
  wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js' );
  
  // Ajax script 
  
  // define script url
  $script_url = plugins_url( '/public/js/tk-notifications-ajax-public.js', __FILE__ );
  
  // enqueue script
  wp_enqueue_script( 'tk-notifications-ajax-public', $script_url, array( 'jquery' ) );
  
  // create nonce
  $nonce = wp_create_nonce( 'ajax_public' );
  
  // define ajax url
  $ajax_url = admin_url( 'admin-ajax.php' );
  
  // define script
  $script = array( 'nonce' => $nonce, 'ajaxurl' => $ajax_url );
  
  // localize script
  wp_localize_script( 'tk-notifications-ajax-public', 'ajax_public', $script );
  
}
add_action('wp_enqueue_scripts', 'tk_notifications_enqueue_scripts');


//
// Do stuff when plugin is deactivated
//

function tk_notifications_on_deactivation() {
  
  if ( ! current_user_can( 'activate_plugins') ) return;
  
  // tk_notifications_database_remove_table();
}
register_deactivation_hook( __FILE__, 'tk_notifications_on_deactivation' );


//
// Do stuff on uninstall
//

function tk_notifications_on_uninstall() {
  
  if ( ! current_user_can( 'activate_plugins') ) return;
  
  tk_notifications_database_remove_table();
  delete_option( 'tk_notifications_options' );
}
register_uninstall_hook( __FILE__, 'tk_notifications_on_uninstall' );


//
// Read the categories and tags related to a post
//

function tk_notifications_read_post_categories_tags( $ID, $post ) {
  
  $categories = [];
  $tags = [];
  
  // Get post type by post.
  $post_type = $post->post_type;
  
  // Get a list of categories and extract their IDs
  $post_categories = get_the_terms( $post->ID, 'category' );
  if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
    $categories = wp_list_pluck( $post_categories, 'term_taxonomy_id' );  
  }
  // Get a list of tags and extract their IDs
  $post_tags = get_the_terms( $post->ID, 'post_tag' );
  if ( ! empty( $post_tags ) && ! is_wp_error( $post_tags ) ) {
    $tags = wp_list_pluck( $post_tags, 'term_taxonomy_id' );  
  }
  
  tk_notifications_create_mailing_list( $categories, $tags, $ID, $post );
}
add_action( 'publish_post', 'tk_notifications_read_post_categories_tags', 10, 2 );


//
// Create a mailing list based on subscriptions
//

function tk_notifications_create_mailing_list( $post_categories, $post_tags, $ID, $post ) {
  
  // Read all subsribers
  global $wpdb;
  
  $table_name = $wpdb->prefix . 'tk_notifications';
  
  $query = "SELECT * FROM $table_name";
  
  $data = $wpdb->get_results( $query );
  
  $mailing_list = [];
  
  
  // Go through all subscribers and their subscriptions
  if ( null !== $data ) {
    foreach ($data as $key => $user) {  // Loop through rows
      $user_email = $user->email;
      
      $subscription = json_decode( $user->tax_selection, true ); // Decode user's subscription
      
      // loop through arrays in tax_selection array
      foreach ($subscription as $key => $taxonomy_arrays) {
        // loop through all taxonomy_arrays to get individual taxonomies
        foreach ($taxonomy_arrays as $key => $taxonomy) {
          // if users taxonomy selection matches post taxonomies push user to a mailing_list
          if (in_array( $taxonomy, $post_categories ) || in_array( $taxonomy, $post_tags )) {
            array_push( $mailing_list, $user_email );
            break 2;
          }
        }
      }
    }
    write_log($mailing_list);
    // tk_notifications_create_email( $mailing_list, $ID, $post, false );
  }
}


//
// Send an email to subscribers
//

function tk_notifications_create_email( $mailing_list, $ID, $post, $confirmation ) {
  
  $mail_subject = '';
  $mail_message = '';
  
  if ( $confirmation == true ) {  // Sends a confirmation message to a new subscriber
    $mail_subject = sprintf('You have subscribed to email notifications');
    $mail_message = sprintf('Hi! \n\n You have subscribed to get email notifications from this website.');
  } else {
    $title = $post->post_title;
    $permalink = get_permalink( $ID );
    $mail_subject = sprintf( 'Published: %s', $title );
    $mail_message = sprintf('Hi! \n\n You can view the article here: %s \n', $permalink );
  }
  
  tk_notification_send_email( $mailing_list, $mail_subject, $mail_message, $ID, $post );
}


//
// Method to send an email
//

function tk_notification_send_email( $mailing_list, $mail_subject, $mail_message, $ID, $post ) {
  
  $headers[] = 'From: WordPress <me@example.net>';
  wp_mail( $mailing_list, $mail_subject, $mail_message, $headers );
  
}


//
// Handles the POST from Ajax form
//

function tk_notifications_ajax_public_handler() {
  
  // check nonce
  check_ajax_referer( 'ajax_public', 'nonce' );
  
  
  write_log($_POST);
  
  $email = isset( $_POST['email'] ) ? sanitize_email( $_POST[ 'email' ] ) : false;
  $data = $_POST[ 'data' ];
  
  $post_type = [];
  $category = [];
  $tag = [];
  $rating = [];
  
  foreach( $data as $sub_array) {
    if ( $sub_array['name'] == 'category' ) {
      array_push( $category, $sub_array['value'] );
    } elseif ( $sub_array['name'] == 'tag' ) {
      array_push( $tag, $sub_array['value'] );
    } elseif ( $sub_array['name'] == 'rating') {
      array_push( $rating, $sub_array['value'] );
    } elseif ( $sub_array['name']== 'post_type') {
      array_push( $post_type, $sub_array['value'] );
    } elseif ( $sub_array['name']== 'g-recaptcha-response' ) {
      $captcha = $sub_array['value'];
    }
  }
  
  // If site visitor verify recaptcha. If admin there's no need to verify recaptcha
  if( tk_notifications_verify_captcha_ajax( $captcha ) ) {
    
    // $user_selection = array("post_types" => $post_type, "categories" => $category, "tags" => $tag, "ratings" => $rating );
    $user_selection = array($post_type, $category, $tag, $rating );
    
    $success = tk_notifications_database_create_table_data( $email, $user_selection );
    
    echo 'Your subscription was successful.' . "\n";
    
  } else {
    echo 'Please fill-in the reCAPTCHA';
  }
  
  // end processing
  wp_die();
  
}
// ajax hook for logged-in users: wp_ajax_{action}
add_action( 'wp_ajax_public_hook', 'tk_notifications_ajax_public_handler' );
// ajax hook for non-logged-in users: wp_ajax_nopriv_{action}
add_action( 'wp_ajax_nopriv_public_hook', 'tk_notifications_ajax_public_handler' );











//
// Just for DEBUG. REMOVE FROM PRODUCTION CODE
//

function write_log ( $log )  {
  if ( true === WP_DEBUG ) {
    if ( is_array( $log ) || is_object( $log ) ) {
      error_log( print_r( $log, true ) );
    } else {
      error_log( $log );
    }
  }
}
