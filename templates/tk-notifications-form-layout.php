<?php // TK Notifications - Form

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
  
  exit;
  
}

//
// Subscription form. Defines a shortcode [tknotifications]
//

function tk_notifications_form_layout() {
 
  $all_categories = get_categories();
  $all_tags = get_tags();

  echo '<input type="hidden" name="action" value="contact_form">';

  echo '<p><label for="email">What you want to subscribe?</label></p>';
  echo '<p><input type="checkbox" name="post_type[]" value="post" /> Blog posts</p>';
  echo '<p><input type="checkbox" name="post_type[]" value="page" /> Pages</p>';
  
  foreach($all_categories as $category) {
    echo '<p><input type="checkbox" name="category[]" value="' . $category->cat_ID . '" /> ' . $category->name . '</p>';
  }

  foreach($all_tags as $tag) {
    echo '<p><input type="checkbox" name="tag[]" value="' . $tag->term_taxonomy_id . '" /> ' . $tag->name . '</p>';
  }

  // echo '<p><input type="checkbox" name="category[]" value="category1" /> Category 1</p>';
  // echo '<p><input type="checkbox" name="category[]" value="category2" /> Category 2</p>';
  // echo '<p><input type="checkbox" name="category[]" value="moro" /> Moro</p>';
  
  echo '<select name="rating[]">';
  echo '<option value="best">best</option>';
  echo '<option value="ok">ok</option>';
  echo '<option value="worst">worst</option>';
  echo '</select>';
  
  echo '<p><input type="hidden" name="taxonomies[]" value="post_type"></p>';
  echo '<p><input type="hidden" name="taxonomies[]" value="category"></p>';
  echo '<p><input type="hidden" name="taxonomies[]" value="tag"></p>';
  echo '<p><input type="hidden" name="taxonomies[]" value="rating"></p>';
  
  echo '<p>E-mail address:</p>';
  echo '<p><input id="email" type="text" name="tk_notifications-email"></p>';

}
