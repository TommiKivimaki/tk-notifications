<?php // TK Notifications - Form

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    
    exit;
    
}


//
// Subscription form. Defines a shortcode [tknotifications]
//

function tk_notifications_create_ajax_form() {
    
    ?>
    
    <form method="post" class="ajax-form">
    
    <?php tk_notifications_ajax_form_layout(); ?>
    
    <?php tk_notifications_display_recaptcha(); ?>
    
    <p><input type="submit" value="Submit Subscription"></p>
    </form>
    
    <div class="ajax-response"></div>
    
    <?php
    
}
add_shortcode('tknotificationsajax', 'tk_notifications_create_ajax_form'); 