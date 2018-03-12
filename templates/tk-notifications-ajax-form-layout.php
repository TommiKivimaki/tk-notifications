<?php // TK Notifications - Form

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}


//
// Subscription form. 
//

function tk_notifications_ajax_form_layout() {
  
  $all_categories = get_categories();
  $all_tags = get_tags();
  
  ob_start();
  ?>
  
  <input type="hidden" name="action" value="contact_form">
  
  <p><label for="email">What you want to subscribe?</label></p>
  <p><input type="checkbox" name="post_type" value="post" /> Blog posts</p>
  <p><input type="checkbox" name="post_type" value="page" /> Pages</p>
  
  <?php foreach($all_categories as $category) { 
    echo '<p><input type="checkbox" name="category" value="' . $category->cat_ID . '" /> ' . $category->name . '</p>';
  }
  ?>
  
  <?php foreach($all_tags as $tag) {
    echo '<p><input type="checkbox" name="tag" value="' . $tag->term_taxonomy_id . '" /> ' . $tag->name . '</p>';
  }      
  ?>
  
  <select name="rating">
  <option value="best">best</option>
  <option value="ok">ok</option>
  <option value="worst">worst</option>
  </select>
  
  <p>E-mail address:</p>
  <p><input id="email" type="text" name="tk_notifications_ajax_email"></p>
  
  <?php
  ob_end_flush();
  
}