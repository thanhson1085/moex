$(document).ready(function(){
	onecall();
});

//var price_level = [{distance: 0, price: 12}, {distance: 1, price: 10}, { distance: 5, price: 8}, {distance: 10, price: 7}, {distance:20, price: 6}]
var distance = 0;
var moex_distance = 0;
var price_level = 9900;
var moexDelivery = 0;
var moexGo = 0;
var limit = 5;
function countMoney(){
	d = distance;
	if (d > 5){
		moexDelivery = Math.ceil(price_level*d/5000)*5000;
    }   
	else{
    	moexDelivery = 5 * Math.ceil(price_level/1000)*1000; 
	}
	if (d > 2){
		moexGo = Math.ceil((price_level*d)/5000)*5000;
    }   
	else{
    	moexGo = 2 * Math.ceil(price_level/1000)*1000; 
	}
	if (limit == 5) ret = moexDelivery;
	if (limit == 2) ret = moexGo;
	if (d > limit){
		moex_distance = ret/price_level;
	}
	else{
		if (d > 2 && d < 5){
			moex_distance = moexGo/price_level;
		}
		else{
			moex_distance = distance; 
		}
	}
	moex_distance = Math.round(moex_distance*1000)/1000;
	return ret;
}
function getRoute(){
    distance = 0;
    //request.origin += province;
    //request.destination += province;
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        distance = response.routes[0].legs[0].distance.value/1000;
        money_value = countMoney();
        $('#search-result').html(money_value.formatMoney(0,'','.',','));
		$('#order-distance').html(moex_distance);
        directionsDisplay.setDirections(response);
    }
    });
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
function onecall(){
	window.onscroll = DisalbeRightAdv;
	window.onresize = DisalbeRightAdv;
	//Gọi hàm lần đầu
	DisalbeRightAdv();       
	function DisalbeRightAdv() {
		//Kiểm tra điều kiện width
		var show1 = false;   
		var khungQCT = $('#OnCall');
		//980 là chiều rộng của website
		//Ẩn quảng cáo nếu chiều rộng của trình duyệt nhỏ hơn chiều rộng website + chiều rộng quảng cáo phải
		if (GetWindowWidth() < (980 + khungQCT.outerWidth()))
			show1 = false;
		else
			show1 = true;

		//Kiểm tra điều kiện scroll
		var show2 = false;
		if (f_scrollTop() > $("#Header").outerHeight())
			show2 = true;
		else
			show2 = false;

		if (show1 && show2)
			khungQCT.fadeIn();
		else
			khungQCT.fadeOut();
	}     

	function f_clientWidth() {
		return f_filterResults(
			window.innerWidth ? window.innerWidth : 0,
			document.documentElement ? document.documentElement.clientWidth : 0,
			document.body ? document.body.clientWidth : 0
		);
	}
	function f_clientHeight() {
		return f_filterResults(
			window.innerHeight ? window.innerHeight : 0,
			document.documentElement ? document.documentElement.clientHeight : 0,
			document.body ? document.body.clientHeight : 0
		);
	}
	function f_scrollLeft() {
		return f_filterResults(
			window.pageXOffset ? window.pageXOffset : 0,
			document.documentElement ? document.documentElement.scrollLeft : 0,
			document.body ? document.body.scrollLeft : 0
		);
	}
	function f_scrollTop() {
		return f_filterResults(
			window.pageYOffset ? window.pageYOffset : 0,
			document.documentElement ? document.documentElement.scrollTop : 0,
			document.body ? document.body.scrollTop : 0
		);
	}
	function f_filterResults(n_win, n_docel, n_body) {
		var n_result = n_win ? n_win : 0;
		if (n_docel && (!n_result || (n_result > n_docel)))
			n_result = n_docel;
		return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
	}
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
