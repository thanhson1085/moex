var ajax_link = "http://192.168.116.128/moex/core/web/app.php/order/"
$(document).ready(function(){
	jQuery.post(ajax_link,{},
	function(data){ jQuery("#admin-area").html(data); });
	$('#admin-area a').live('click', function(e){
		e.preventDefault();
		ajax_link = $(this).attr('href');
		jQuery.post(ajax_link,{},
		function(data){ jQuery("#admin-area").html(data); });
	});	
	$('#admin-area form').live('submit', function(e){
		e.preventDefault();
		ajax_link = $(this).attr('action');
		jQuery.post(ajax_link, $(this).serialize(),
		function(data){ jQuery("#admin-area").html(data); });
	});	
});
