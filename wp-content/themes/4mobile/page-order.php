<?php
get_header();
?>
           <div id="order-search">
                <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    $customer_id = get_current_user_id();
                    $status = $wpdb->insert($wpdb->prefix."orders", array("order_name" => esc_attr($_POST['tbHoTen']),
                                                "phone" => esc_attr($_POST["tbDienThoai"]), "service_type" => esc_attr($_POST['ddlDichVu']),
                                                "customer_id" => $customer_id, "price" => esc_attr($_POST['tbPrice']), "order_status" => "PENDING",
                                                "order_from" => esc_attr($_POST["tbFrom"]), "order_to" => esc_attr($_POST["tbTo"]),
                                                "order_info" => esc_attr($_POST["tbYeuCauChiTiet"]), "updated_at" => current_time('mysql'),
                                                "lat" => esc_attr($_POST["tbLat"]), "lng" => esc_attr($_POST["tbLng"]),
                                                "created_at" => current_time('mysql')), array("%s", "%s", "%d", "%s", "%s", "%s"));
                    if($status):
                    ?>
                    <script type="text/javascript">
                        //alert("<?php echo _e("Cảm ơn bạn đã sử dụng dịch vụ, moEx sẽ phục vụ theo đúng yêu cầu của bạn. moEx luôn sẵn sàng với 1900 56 56 36!"); ?>");
                        $(location).attr("href","<?php echo get_bloginfo("url");?>/order-history/?order_id=<?php echo $wpdb->insert_id;?>&status=1");
                    </script>
                    <?php
                    else:
                    ?>
                    <script type="text/javascript">
                        alert("<?php echo _e("Đăng ký không thành công, vui lòng thử lại"); ?>");
                    </script>
                    <?php
                    endif;
                }   
                ?>
             <div class="cot2">
                <form method="POST" id="order-form" name="orderform" action="">
                <div class="form-row">
                    <input id="input-from" placeholder="Điểm đi" name="tbFrom" type="text" class="textbox" value="<?php echo (isset($_GET['from']))?$_GET['from']:""; ?>"/>
                </div>
                <div class="form-row">
                   <input id="input-to" name="tbTo" placeholder="Điểm đến" type="text" class="textbox" value="<?php echo (isset($_GET['to']))?$_GET['to']:""; ?>"/>
                    <input id="input-price" name="tbPrice" type="hidden"/>
                    <input id="input-lat" name="tbLat" type="hidden"/>
                    <input id="input-lng" name="tbLng" type="hidden"/>
                </div>
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
                <div class="form-textarea">
                    <textarea placeholder="Yêu cầu chi tiết" name="tbYeuCauChiTiet" cols="20" rows="2" class="textbox" style="height:55px" ></textarea>
                </div>
                <div class="form-row"><label>Khoảng cách</label>
                <span id="order-distance"></span> Km</div>
                <div class="form-row"><label>Giá</label>
                <span id="search-result"></span></div>
                <div class="form-row">
                    <?php if(is_user_logged_in()):
                        $current_user = wp_get_current_user();
                    ?>
                    <input id="tbHoTen" placeholder="Họ và tên" name="tbHoTen" type="text" class="textbox" value="<?php echo $current_user->user_lastname; ?>" />
                    <?php
                    else:
                    ?>
                    <input id="tbHoTen" placeholder="Họ và tên" name="tbHoTen" type="text" class="textbox" value="" />
                    <?php
                    endif;
                    ?>
                 </div>
                 <div class="form-row">
                    <?php if(is_user_logged_in()):
                        $current_user = wp_get_current_user();
                    ?>
                    <input id="tbDienThoai" placeholder="Điện thoại" name="tbDienThoai" type="text" class="textbox" value="<?php echo $current_user->user_login; ?>" />
                    <?php
                    else:
                    ?>
                    <input id="tbDienThoai" placeholder="Điện thoại" name="tbDienThoai" type="text" class="textbox" value="" />
                    <?php
                    endif;
                    ?>
				</div>
				<div class="form-row">
					<button type="submit">Đăng ký</button>
					<button>Huỷ bỏ</button>
				</div>
			</form>
<div id="map2"></div>

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
    var map = new google.maps.Map(document.getElementById("map2"), myOptions);
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
/*
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
*/
    $("#ddlDichVu").change(function(){
        service_type = $(this).val();
        display_price();
    });
/*
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
*/
});
</script>

<?php
get_footer();
?>
