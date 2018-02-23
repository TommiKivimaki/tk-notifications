<?php
/*
Plugin Name:  TK Notifications
Description:  Sends notifications to subscribed users when a new post is published.
Plugin URI:
Author:       Tommi Kivimäki
Version:      0.1
Text Domain:  tk-notifications
Domain Path:  /languages
License:      
License URI:  

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/
*/


// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {

  exit;

}


//
// Version number for the database table structure
//
global $tk_notification_db_version;
$tk_notification_db_version = '0.1';


//
// Public includes
//

require_once plugin_dir_path( __FILE__ ) . 'includes/tk-notifications-form.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/tk-notifications-remove-subscription-form.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/tk-notifications-database-methods.php';


//
// Admin Menu includes
//

if ( is_admin() ) {
  require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callbacks.php';
}


//
// Load admin area style
//
function tk_notifications_enqueue_admin_style() {

  $src = plugin_dir_url( __FILE__ ) . 'admin/css/tk-notifications-admin.css';
  
  wp_enqueue_style( 'tk-notifications-admin', $src, array(), null, 'all' );

}
add_action( 'admin_enqueue_scripts', 'tk_notifications_enqueue_admin_style');




//
// Do stuff when plugin is deactivated
//

function tk_notifications_on_deactivation() {

  if ( ! current_user_can( 'activate_plugins') ) return;

  tk_notifications_database_remove_table();
}
register_deactivation_hook( __FILE__, 'tk_notifications_on_deactivation' );


//
// Do stuff on uninstall
//

function tk_notifications_on_uninstall() {

  if ( ! current_user_can( 'activate_plugins') ) return;

  tk_notifications_database_remove_table();
}
register_uninstall_hook( __FILE__, 'tk_notifications_on_uninstall' );








//
// Process the submitted subscription
//

function tk_notifications_process_subscription() {

	// get the nonce
	if ( isset( $_POST['tk_notifications_nonce_field'] ) ) {

		$nonce = $_POST['tk_notifications_nonce_field'];

	} else {

		$nonce = false;

	}

	// process the form
	if ( isset( $_POST['tk_notifications-email'] ) ) {

		// verify nonce
		if ( ! wp_verify_nonce( $nonce, 'tk_notifications_form_action' ) ) {

      wp_die( 'Incorrect nonce!' );

    } else {

      $email = sanitize_email( $_POST[ 'tk_notifications-email' ] );

      $form_taxonomies = $_POST[ 'taxonomies' ];
      $user_selection = [];  // User selection

      if ( ! empty( $email ) ) {

        echo '<p>You selected: Just for debugging to see the array is correct</p>';
        foreach ($form_taxonomies as $key => $value) {
          echo '<p>taxonomies "'. $key .' - '. $value .'"</p>';
          if ( $_POST[ $value ] != null ) {
            array_push($user_selection, $_POST[ $value ]);
          }
        }

        echo '<p>*** USER SELECTED ***</p>';
        foreach ($user_selection as $key => $value) {
          echo '<p>taxonomies "'. $key .' - '. $value .'"</p>';
          foreach ($value as $key => $valueb) {
            echo '<p>sub-array: "'. $key .' - '. $valueb .'"</p>';
          }
        }
        // Check if email exists
        $exists = tk_notifications_database_table_data_exists( $email );

        if ( $exists == true ) {

          $success = tk_notifications_database_update_table_data( $email, $user_selection );

          if ($success === false ) {
            echo "Updating an existing subscription failed.";
          } else {
            echo "Subscription successfully updated.";
          }

        } else {

          $success = tk_notifications_database_create_table_data( $email, $user_selection );

          if ( $success === false ) {
            echo "Adding a new subscription failed.";
          } else {
            echo "New subscription successfully added.";
          }
        }

      } else {
        echo '<p>Please enter a valid email address</p>';
			}
		}
	}
}
add_action( 'admin_post_nopriv_contact_form', 'tk_notifications_process_subscription' );
add_action( 'admin_post_contact_form', 'tk_notifications_process_subscription' );


//
// Process the removal of subscription form
//

function tk_notifications_process_remove_subscription() {

	// get the nonce
	if ( isset( $_POST['tk_notifications_nonce_field'] ) ) {

		$nonce = $_POST['tk_notifications_nonce_field'];

	} else {

		$nonce = false;

	}

	// process the form
	if ( isset( $_POST['tk_notifications_remove_email'] ) ) {

		// verify nonce
		if ( ! wp_verify_nonce( $nonce, 'tk_notifications_remove_subscription_form_action' ) ) {

      wp_die( 'Incorrect nonce!' );

    } else {

      $email = sanitize_email( $_POST[ 'tk_notifications_remove_email' ] );

      $form_taxonomies = $_POST[ 'taxonomies' ];
      $user_selection = [];  // User selection

      if ( ! empty( $email ) ) {

          $success = tk_notifications_database_remove_table_data( $email );

          if ( $success === false ) {
            echo "Removing subscription failed.";
          } else {
            echo "Subscription removed successfully.";
          }

      } else {

        echo '<p>Please enter a valid email address</p>'; // Tämä viesti pitää ohjata admin paneeliin!!!
			}
		}
	}
}
add_action( 'admin_post_nopriv_contact_form', 'tk_notifications_process_remove_subscription' );
add_action( 'admin_post_contact_form', 'tk_notifications_process_remove_subscription' );


//
// Read the categories and tags related to a post
//

 function tk_notifications_read_post_categories_tags( $ID, $post ) {
   // // Get post by post ID.
   // if ( ! $post = get_post() ) {
   //   return '';
   // }

   // Get post type by post.
   $post_type = $post->post_type;

   // Get a list of categories and extract their names
   $post_categories = get_the_terms( $post->ID, 'category' );
   if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
     $categories = wp_list_pluck( $post_categories, 'name' );
   }
   // Get a list of tags and extract their names
   $post_tags = get_the_terms( $post->ID, 'post_tag' );
   if ( ! empty( $post_tags ) && ! is_wp_error( $post_tags ) ) {
     $tags = wp_list_pluck( $post_tags, 'name' );
   }

   // // Combine Categories and tags, with category first
   // $categories_tags = array_merge( $categories, $tags );
print_r( $categories );
print_r( $tags );

   tk_notifications_create_mailing_list( $categories, $tags, $ID, $post );
 }
add_action( 'publish_post', 'tk_notifications_read_post_categories_tags', 10, 2 );


//
// Create a mailing list baesd on subscriptions
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

      $subscription = json_decode( $user->tax_selection ); // Decode user's subscription

      // loop through arrays in tax_selection column
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
    // echo '<pre>';
    // echo '<div>*** PRINT MAILING LIST: </div>';
    // print_r( $mailing_list );
    // echo '</pre>';

    tk_notifications_send_email( $mailing_list, $ID, $post );
  }
}


//
// Send an email to subscribers
//

function tk_notifications_send_email( $mailing_list, $ID, $post ) {

  echo '<pre>';
  echo '<div>*** Sending mail to a list: </div>';
  print_r( $mailing_list );
  echo "About the post ID: ";
  print_r( $ID );
  echo " POST: ";
  print_r( $post );
  echo '</pre>';

}



//
// Method to send the emails
//

function post_published_notification( $ID, $post ) {
    $author = $post->post_author; /* Post author ID. */
    $name = get_the_author_meta( 'display_name', $author );
    $email = get_the_author_meta( 'user_email', $author );
    $title = $post->post_title;
    $permalink = get_permalink( $ID );
    $edit = get_edit_post_link( $ID, '' );
    $to[] = sprintf( '%s <%s>', $name, $email );
    $subject = sprintf( 'Published: %s', $title );
    $message = sprintf ('Congratulations, %s! Your article “%s” has been published.' . "\n\n", $name, $title );
    $message .= sprintf( 'View: %s', $permalink );
    $headers[] = '';
    wp_mail( $to, $subject, $message, $headers );
}
// add_action( 'publish_post', 'post_published_notification', 10, 2 );
