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
            ret = Math.ceil(ret/1000/5)*1000*5;
            ret = ret  + ' VND';    
        }
    }   
    return ret;
}
$(document).ready(function(){
    $('.filter #btn-clear').live('click', function(){
        $('.filter form input[type="text"]').each(function(){
            $(this).attr('value','');
        });
    });
	$(".dropdown dt a").click(function() {
		var toggleId = "#" + this.id.replace(/^link/,"ul");
		$(".dropdown dd ul").not(toggleId).hide();
		$(toggleId).toggle();
		if($(toggleId).css("display") == "none"){
			$(this).removeClass("selected");
		}else{
			$(this).addClass("selected");
		}

	});

	$(".dropdown dd ul li a").click(function() {
		var text = $(this).html();
		$(".dropdown dt a span").html(text);
		$(".dropdown dd ul").hide();
	});

	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (!$clicked.parents().hasClass("dropdown")){
			$(".dropdown dd ul").hide();
			$(".dropdown dt a").removeClass("selected");
		}

	});
	
	$(".icon-del").click(function(){
		return confirm_delete();
	});

});
function confirm_delete()
{
	var agree = confirm("Are you sure you wish to continue?");
	if (agree)
		return true ;
	else
		return false ;
}
