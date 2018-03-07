<?php // TK Notifications - Email template

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}


//
// Send a notification email to users in the mailing list. Includes remove subscription link
//

function tk_notifications_email( $mailing_list, $ID, $post ) {
  
  $title = $post->post_title;
  $permalink = get_permalink( $ID );
  $mail_subject = sprintf( 'Published: %s', $title );
  $headers[] = 'From: WordPress <me@example.net>';
  
  foreach( $mailing_list as $subscriber ) {
    $user_email = $subscriber[0];
    $user_sub_hash = $subscriber[1];
    
    $remove_link = home_url() . '/wp-json/tk_notifications/v1/unsubscribe?hash' . '=' . $user_sub_hash;
    
    // Format the outgoing mail message
    $mail_message = '';
    $mail_message .= 'Hi!' . "\n\n" . 'You can view the article here: <a href="' . $permalink . '">' . $title . '</a>.' . "\n\n";
    $mail_message .= 'Click <a href="' . $remove_link . '"> this link</a> to cancel your subscription.';
    
    wp_mail( $user_email, $mail_subject, $mail_message, $headers );
  }
  
  
}