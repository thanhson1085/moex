$(document).ready(function(){
	menu_left = $('.left-menu-container');
	left_part = $('.left-part-container');
	right_part = $('.right-part-container');
	$("#loading").show();
	jQuery.post(ajax_link,{},
	function(data){ jQuery("#admin-area").html(data); $("#loading").hide();});
	$('#admin-area a').live('click', function(e){
		$("#loading").show();
		e.preventDefault();
		ajax_link = $(this).attr('href');
		jQuery.post(ajax_link,{},
		function(data){ jQuery("#admin-area").html(data); $("#loading").hide(); });
		me_window_resize();
	});	
	$('#admin-area form').live('submit', function(e){
		e.preventDefault();
		ajax_link = $(this).attr('action');
		$("#loading").show();
		jQuery.post(ajax_link, $(this).serialize(),
		function(data){ jQuery("#admin-area").html(data); $("#loading").hide();});
		me_window_resize();
	});	
});
var distance = 0;
function getRoute(){
    distance = 0;
    request.origin += province;
    request.destination += province;
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        distance = response.routes[0].legs[0].distance.value;
		money_value = countMoney();
        $('#search-result').html(money_value);
		if(submit_click == true){
			submit_click = false;
			$('#moex_corebundle_meorderstype_price').attr('value',money_value);
		}
        directionsDisplay.setDirections(response);
    }
    });
}

var price_level = [{distance: 0, price: 12}, {distance: 1, price: 10}, { distance: 5, price: 8}, {distance: 10, price: 7}, {distance:20, price: 6}]

function countMoney(){
    ret = 0;
    for ( value in price_level){
        if (distance > price_level[value].distance*1000){
            ret = price_level[value].price*distance
            ret = Math.round(ret/1000)*1000;
            ret = ret  + ' VND';    
        }
    }   
    return ret;
}
function me_window_resize(){
	menu_left = $('.left-menu-container');
	left_part = $('.left-part-container');
	right_part = $('.right-part-container');
	var max_height = Math.max(menu_left.height(), left_part.height(), right_part.height());
	if (max_height > left_part.height() + 50) left_part.css('height',max_height);
	window_resize();
	$(window).resize(function(){
		window_resize();
	});
}
function window_resize(){
	window_width = $(window).width();
	if (window_width < 1024){
		menu_left.css('display', 'none');
		left_part.css('width', '78%');
		right_part.css('width', '22%');
	}
	else{
		menu_left.css('display', 'block');
		menu_left.css('width', '16%');
		left_part.css('width', '62%');
		right_part.css('width', '22%');
	}
}
