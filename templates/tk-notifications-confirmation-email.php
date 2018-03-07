<?php // TK Notifications - Confirmation email template

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}

function tk_notifications_confirmation_email( $to, $sub_hash ) {
    $mail_subject = 'You have subscribed to email notifications';
    $headers[] = 'From: WordPress <me@example.net>';

    $remove_link = home_url() . '/wp-json/tk_notifications/v1/unsubscribe?hash' . '=' . $sub_hash;
    
    // Format the outgoing mail message
    $mail_message = 'Hi!' . "\n\n" . 'You have subscribed to get email notifications from XXXXX.' . "\n\n";
    $mail_message .= 'Click <a href="' . $remove_link . '"> this link</a> to cancel your subscription.';

    wp_mail( $to, $mail_subject, $mail_message, $headers );
}