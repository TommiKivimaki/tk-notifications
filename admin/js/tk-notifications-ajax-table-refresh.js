jQuery(document).ready(function($){ 
  $(".subscription_list").find("a").on( 'click', function(event) {
    
    event.preventDefault();
    
    var row_to_delete = $(this).closest("tr");
    
    var sub_hash = $(this).attr('id')
    
    $.post(ajaxurl, {
      nonce:  ajax_admin.nonce,
      action: 'admin_hook',
      sub_hash: sub_hash
      
    }, function(data) {
      // $(".subscription_list").fadeOut("slow");
      row_to_delete.fadeOut(500, function() {
        row_to_delete.remove()
      });
    });
  });
});