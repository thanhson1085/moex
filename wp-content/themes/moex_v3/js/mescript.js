$(document).ready(function(){
	onecall();
});

var price_level = [{distance: 5, price: 9.9}];//[{distance: 0, price: 12}, {distance: 1, price: 10}, { distance: 5, price: 8}, {distance: 10, price: 7}, {distance:20, price: 6}]
var distance = 0;

function countMoney(){
    ret = 50000;
    for ( value in price_level){
        if (distance > price_level[value].distance*1000){
			d = Math.ceil(distance);
            ret = price_level[value].price*d;
			ret = ret.toFixed(0);
            //ret = Math.round(ret/1000)*1000;
            //ret = Math.ceil(ret/1000/5)*1000*5;
        }
    }   
    return ret;
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
		$('#order-distance').html(distance/1000);
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
