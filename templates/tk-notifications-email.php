<?php // TK Notifications - Email template

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}


function tk_notifications_email( $mailing_list, $ID, $post ) {
  
    $title = $post->post_title;
    $permalink = get_permalink( $ID );
    $mail_subject = sprintf( 'Published: %s', $title );
    $mail_message = sprintf('Hi! You can view the article here: %s ', $permalink );
    $headers[] = 'From: WordPress <me@example.net>';

    wp_mail( $mailing_list, $mail_subject, $mail_message, $headers );
}