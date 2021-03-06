<?php
get_header();
?>
<div class="form-container">
	<div class="form-row"><input id="input-from" type="text"></div>
	<div class="form-row"><input id="input-to" type="text"></div>
	<div class="form-row">
		<select id="ddlDichVu" name="ddlDichVu">
			<option value="0">Dịch vụ đăng ký</option>
			<option value="1">Moex Delivery</option>
			<option value="2">Moex Go</option>
			<option value="3">Moex Food</option>
			<option value="4">Moex Shopping</option>
			<option value="5">Moex School</option>
		</select>
	</div>
	<div class="form-row">
		<div class="search-result">Giá trị đơn hàng: <span id="search-result"></span></div>
		<button class="moex-button" id="search-submit" type="button">Go</button>
		<div class="map-status">
		<a id="show-map"><img width="32px;" src="<?php echo get_bloginfo("template_url")?>/images/map-enable.png"></a>
		<a id="hidden-map"><img width="32px;" src="<?php echo get_bloginfo("template_url")?>/images/map-disable.png"></a>
		</div>
		<div class="order-now"><a href="<?php echo get_bloginfo("url")?>/order"><img src="<?php echo get_bloginfo("template_url")?>/images/buy.png"></a></div>
	</div>
	<script type="text/javascript">
		$("#show-map").click(function(){
			$("#map").css("display", "block");
			$("#hidden-map").css("display", "block");
			$("#show-map").css("display", "none");
		});
		$("#hidden-map").click(function(){
			$("#map").css("display", "none");
			$("#show-map").css("display", "block");
			$("#hidden-map").css("display", "none");
		});
	</script>
</div>
<div id="map"></div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

<script type="text/javascript">
var request = {
    origin: '219 KHÂM THIÊN',
    destination: '99 PHỐ HUẾ',
    travelMode: google.maps.DirectionsTravelMode.WALKING
};
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();
var oldDirections = [];
var currentDirections = null;
$(document).ready(function(){
    $("#ddlDichVu option").each(function(){
        if($(this).val() == <?php echo (isset($_GET['service']))?$_GET['service']:1?>){
            $(this).attr('selected','selected');
            service_type = $(this).val();
            display_price();
        }
    });
    $('input[type="text"]').live('click', function(){
        $(this).select();
    });
    var inputFrom = document.getElementById('input-from');
    var inputTo = document.getElementById('input-to');
    var options = {
      types: ['geocode'],
      componentRestrictions: {country: 'vn'}
    };
    autocomplete = new google.maps.places.Autocomplete(inputFrom, options);
    autocomplete = new google.maps.places.Autocomplete(inputTo, options);
    var myOptions = {
        zoom:7,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var myOptions = {
        zoom:7,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsDisplay = new google.maps.DirectionsRenderer({
        'draggable': true
    });
              
   directionsDisplay.setMap(map);
    $("#search-from").html(request.origin);
    $("#search-to").html(request.destination)
    if ($('#input-from').val() != ""){
        request.origin = $('#input-from').val();
    }
    if ($('#input-to').val() != ""){
        request.destination = $('#input-to').val();
    }
    getRoute();
    $("#search-submit").click(function(){
        submit_click = true;
        request.origin = $('#input-from').attr('value');
        request.destination = $('#input-to').attr('value');
        getRoute();
    });

    $('#input-to').bind('keypress',function(e){
        if(e.keyCode == 13){
            if ($('#input-from').val() != "" && $('#input-to').val() != ""){
                submit_click = true;
                request.origin = $('#input-from').attr('value');
                request.destination = $('#input-to').attr('value');
                getRoute();
            }
        }
    });
   $('#input-from').bind('keypress',function(e){
        if(e.keyCode == 13){
            if ($('#input-from').val() != "" && $('#input-to').val() != ""){
                submit_click = true;
                request.origin = $('#input-from').attr('value');
                request.destination = $('#input-to').attr('value');
                getRoute();
            }
        }
    });
    $("#ddlDichVu").change(function(){
        service_type = $(this).val();
        display_price();
    });

    google.maps.event.addListener(directionsDisplay, 'directions_changed',
    function() {
        if (currentDirections) {
            var rleg = directionsDisplay.directions.routes[0].legs[0];
            distance = rleg.distance.value/1000;
            money_value = countMoney();
            display_price();
            $('#order-distance').html(moex_distance);
            $('#input-from').attr('value',rleg.start_address);
            $('#input-to').attr('value',rleg.end_address);
            $('#input-lat').attr('value', rleg.start_location.lat());
            $('#input-lng').attr('value',rleg.end_location.lng());

        }
        currentDirections = directionsDisplay.getDirections();
    });
});
</script>

<?php
get_footer();
?>
