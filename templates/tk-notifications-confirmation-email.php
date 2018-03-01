<?php // TK Notifications - Confirmation email template

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}

function tk_notifications_confirmation_email( $to ) {
    $mail_subject = 'You have subscribed to email notifications';
    $mail_message = 'Hi! You have subscribed to get email notifications from XXX';
    $headers[] = 'From: WordPress <me@example.net>';

    wp_mail( $to, $mail_subject, $mail_message, $headers );
}