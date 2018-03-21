=== Plugin Name ===

Plugin Name:  TK Notifications
Description:  Sends notifications to subscribed users when a new post is published.
Plugin URI:   
Author:       Tommi Kivimäki
Version:      0.3.6
Text Domain:  tknotifications
Domain Path:  /languages
License:      
License URI:  

Short description.

== Description ==

How to take this plugin into use: 

1. Include all the necessary taxonomies in the subscription form (templates/tk-notifications-ajax-form-layout.php). 

2. Update tk_notifications_ajax_public_handler() in tk-notifications.php to take care of any custom taxonomies that you are using in the subscription form. 

3. When a new post is published the plugin runs tk_notifications_read_post_categories_tags( $ID, $post ) in tk-notifications.php. Update the function to get the terms of your custom taxonomies. Then pass your custom taxonomy arrays to tk_notifications_create_mailing_list( $categories, $tags, $ratings, $ID, $post ). 

4. Update the tk_notifications_create_mailing_list( $categories, $tags, $ratings, $ID, $post ) function to take your custom taxonomy arrays as an input. Also update the if (in_array...) condition to use your custom taxonomies when collecting an email list.



== Installation ==

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.0 =
