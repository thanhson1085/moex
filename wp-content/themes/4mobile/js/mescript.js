var distance = 0;
var moex_distance = 0;
var price_level = 8000;
var service_type = 1;
var province = ',hà nội, việt nam';
var money_value = 0; 
var search_result = "";
var request = {
	origin: '219 KHÂM THIÊN',
	destination: '99 PHỐ HUẾ',
	travelMode: google.maps.DirectionsTravelMode.WALKING
};
function countMoney(){
	distance = (Math.ceil(Math.ceil(distance*10)/5)*5)/10;
	moex_distance = distance; 
	ret = distance*price_level;
	return ret;
}

function getRoute(){
    distance = 0;
    request.origin += province;
    request.destination += province;
    request.travelMode = google.maps.DirectionsTravelMode.WALKING;
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        distance = response.routes[0].legs[0].distance.value/1000;
        money_value = countMoney();
		display_price();
		//$('#order-distance').html(moex_distance);
        directionsDisplay.setDirections(response);
    }
    });
    request.travelMode = google.maps.DirectionsTravelMode.DRIVING;
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        driving_distance = response.routes[0].legs[0].distance.value/1000;
		if (driving_distance < distance){		
			distance = driving_distance;
			money_value = countMoney();
			display_price();
			//$('#order-distance').html(moex_distance);
			directionsDisplay.setDirections(response);
		}
    }
    });
    request.travelMode = google.maps.DirectionsTravelMode.WALKING;
}
function add(s) {
    var collectionHolder = $(s);
    var prototype = collectionHolder.attr('data-prototype');
    form = prototype.replace(/\$\$name\$\$/g, collectionHolder.children().length);
    collectionHolder.append(form);
}
function phone_validation(phonenumber){
	var phoneNumberPattern = /^\+?\(?(\d{2,3})\)?[- ]?(\d{3,4})[- ]?(\d{4})$/;
	if(!phoneNumberPattern.test(phonenumber)){
		return false;
	}
	return true;
}
function email_validation(email){
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email)) {
		return false;
	}                                
	return true;
}
function display_price(){
	search_result = money_value.formatMoney(0,"",".", ",") + " VNĐ";
	$('#search-result').html(search_result);
	$('#order-price').attr("value", money_value);
}
// Extend the default Number object with a formatMoney() method:
// usage: someVar.formatMoney(decimalPlaces, symbol, thousandsSeparator, decimalSeparator)
// defaults: (2, "$", ",", ".")
Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	symbol = symbol !== undefined ? symbol : "$";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var number = this, 
	    negative = number < 0 ? "-" : "",
	    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
	    j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};

