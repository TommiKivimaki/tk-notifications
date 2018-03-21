=== TK Notifications ===

Plugin Name:  TK Notifications
Description:  Sends notifications to subscribed users when a new post is published.
Plugin URI:   https://github.com/TommiKivimaki/tk-notifications
Requires at least: 4.9
Tested up to: 4.9
Author:       Tommi Kivimäki
Version:      0.3.7
Text Domain:  tknotifications
Domain Path:  /languages
License:      GPLv2 or later
License URI:  http://www.gnu.org/licenses/gpl-2.0.html

Visitors can subscribe to posts via a form. They will receive an email notification when a new post matching their subscription options gets published. 

== Description ==

Site admin should customize the subscription form and also update two functions to handle taxonomies used in the form. 

How to take this plugin into use: 

1. Include all the necessary taxonomies in the subscription form (templates/tk-notifications-ajax-form-layout.php). 

2. Update tk_notifications_ajax_public_handler() in tk-notifications.php to take care of any custom taxonomies that you are using in the subscription form. 

3. When a new post is published the plugin runs tk_notifications_read_post_categories_tags( $ID, $post ) in tk-notifications.php. Update the function to get the terms of your custom taxonomies. Push all the taxonomies to $post_taxonomies.


== Installation ==

Install the plugin, activate it and add a subscription form to a post or a page by using a shortcode [tknotifications].

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 0.3.7 =

Custom taxonomies and post types supported. 