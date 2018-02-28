<?php // TK Notifications - Notify subscribers about a new content.

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}


function tk_notifications_notification_email_setup( $to, $ID, $post ) {
  
  $mail_subject = 'You have subscribed to email notifications';
  $mail_message = 'Hi! \n\n You have subscribed to get email notifications from this website.';
  $headers[] = 'From: WordPress <me@example.net>';

}