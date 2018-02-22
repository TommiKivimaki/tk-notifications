jQuery("#submit").click(function(e){
  var data_2;
  jQuery.ajax({
    type: "POST",
    url: "http://themedevtest:8888/wordpress/wp-content/plugins/tk-notifications/includes/recaptcha.php",
    data: jQuery('#commentform').serialize(),
    async:false,
    success: function(data) {
      if(data.nocaptcha==="true") {
        data_2=1;
      } else if(data.spam==="true") {
        data_2=1;
      } else {
        data_2=0;
      }
    }
  });
  if(data_2!=0) {
    e.preventDefault();
    if(data_2==1) {
      alert("Please check the captcha");
    } else {
      alert("Please Don't spam");
    }
  } else {
    jQuery("#commentform").submit
  }
});
