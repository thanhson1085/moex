<?php
get_header();
?>
<div class="form-container">
<div class="form-row"><input id="input-from" type="text"></div>
<div class="form-row"><input id="input-to" type="text"></div>
<div class="form-row"><div class="search-result">Giá trị đơn hàng: <span id="search-result"></span></div>
<button class="moex-button" id="search-submit" type="button">Go</button>
<button class="moex-button">Sử dụng moEx</button>
</div>
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
