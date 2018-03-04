jQuery(document).ready(function($){
    $(".sub-id").children("a").click(function(event) {
        
        event.preventDefault();
        
        $('.koepala').html('Lähin lattaan....');
        
        var sub_hash = $(this).attr('id')
        
        $.post(ajaxurl, {
            nonce:  ajax_admin.nonce,
            action: 'admin_hook',
            sub_hash: sub_hash
        
        }, function(data) {
            $('.koepala').html(data);
            $(".subscription_list").fadeOut("slow");
        });
    });
});