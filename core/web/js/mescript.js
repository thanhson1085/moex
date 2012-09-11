function initialize_order_new(){
}
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
