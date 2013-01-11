<?php
get_header();
?>
    <div id="PageContent" style="overflow: auto;">
        <div id="KetQuaTimKiem2">
            <div class="cb h10"><!----></div>
             <div class="cot1">
				<div id="map2"></div>
			</div>
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
                <div class="pb10" id="MoexSchool">
					<span class="header ctext sdtext" style="">Sử dụng dịch vụ</span>
                 </div>
                 <div class="left">Điểm đi</div>
                 <div class="fl">:</div>
                 <div class="right">
                	<input id="input-from" name="tbFrom" type="text" class="textbox" value="<?php echo (isset($_GET['from']))?$_GET['from']:""; ?>"/>
				 </div>
                 <div class="cb h12"><!----></div>
                 <div class="left">&nbsp</div>
                 <div class="fl"></div>
                 <div class="right">
<div id="moex_corebundle_meorderstype_ordermeta" data-prototype="&lt;div&gt;&lt;div id=&quot;moex_corebundle_meorderstype_ordermeta_$$name$$&quot;&gt;&lt;input type=&quot;text&quot; id=&quot;moex_corebundle_meorderstype_ordermeta_$$name$$_metaValue&quot; name=&quot;moex_corebundle_meorderstype[ordermeta][$$name$$][metaValue]&quot;    class=&quot; textbox order-position&quot; /&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;"></div>
					<div class="order-meta">
					<a href="#" class="jslink" style="font-weight: normal;text-decoration: underline; color: #20409A">
						Thêm điểm chuyển tiếp
					</a>
				</div>
				</div>
                 <div class="cb h12"><!----></div>
                 <div class="left">Điểm đến</div>
                 <div class="fl">:</div>
                 <div class="right">
                	<input id="input-to" name="tbTo" type="text" class="textbox" value="<?php echo (isset($_GET['to']))?$_GET['to']:""; ?>"/>
                	<input id="input-price" name="tbPrice" type="hidden"/>
                	<input id="input-lat" name="tbLat" type="hidden"/>
                	<input id="input-lng" name="tbLng" type="hidden"/>
				 </div>
                    <a class="btOK" id="search-submit" href="javascript:void(0)"><span><span>GO</span></span></a>
                 <div class="cb h12"><!----></div>
                <div class="left">Dịch vụ đăng ký <span>*</span></div>
                <div class="fl">:</div>
                <div class="right">
                    <div id="khungddl">
                        <div class="cb h2"><!----></div>
                        <select id="ddlDichVu" name="ddlDichVu" onchange="FillBGColor('khungddl')">
                            <option style="background:#6b7b84" value="1">Moex Delivery</option>
                            <option style="background:#c8215d" value="2">Moex Go</option>
                            <option style="background:#8dc63f" value="3">Moex Food</option>
                            <option style="background:#f26522" value="4">Moex Shopping</option>
                            <option style="background:#20409a" value="5">Moex School</option>
                        </select>
                        <script type="text/javascript">
                            function FillBGColor(parrentId) {
                                document.getElementById(parrentId).style.backgroundColor = ddlDichVu.options[ddlDichVu.selectedIndex].style.backgroundColor;                                
                            }
                        </script>
                    </div>
                </div>
                <div class="cb h12"><!----></div>
                <div class="left">Yêu cầu chi tiết</div>
                 <div class="fl">:</div>
                <div class="right">
                    <textarea id="tbYeuCauChiTiet" name="tbYeuCauChiTiet" cols="20" rows="2" class="textbox" style="height:55px" ></textarea>                    
                </div>
                <div class="cb h12"><!----></div>
                 <div class="left">Khoảng cách</div>
                 <div class="fl">:</div>
                 <div class="right"><span id="order-distance"></span> Km</div>
                 <div class="cb h12"><!----></div>
                 <div class="left">Giá</div>
                 <div class="fl">:</div>
                 <div class="right"><span id="search-result"></span></div>
                 <div class="cb h12"><!----></div>
                 <div class="left">Họ và tên <span>*</span></div>
                 <div class="fl">:</div>
                 <div class="right">
					<?php if(is_user_logged_in()):
						$current_user = wp_get_current_user();
					?>
                    <input id="tbHoTen" name="tbHoTen" type="text" class="textbox" value="<?php echo $current_user->user_lastname; ?>" />
					<?php
					else:
					?>
                    <input id="tbHoTen" name="tbHoTen" type="text" class="textbox" value="" />
					<?php
					endif;
					?>
				 </div>
                 <div class="cb h12"><!----></div>
                 <div class="left">Điện thoại <span>*</span></div>
                 <div class="fl">:</div>
                 <div class="right">
					<?php if(is_user_logged_in()):
						$current_user = wp_get_current_user();
					?>
                    <input id="tbDienThoai" name="tbDienThoai" type="text" class="textbox" value="<?php echo $current_user->user_login; ?>" />
					<?php
					else:
					?>
                    <input id="tbDienThoai" name="tbDienThoai" type="text" class="textbox" value="" />
					<?php
					endif;
					?>
				 </div>
                <div class="cot2">
                    <a class="btOK" href="javascript:void(0)" onclick="DangKy()"><span><span>Đăng ký</span></span></a>
                    <a class="btOK" href="javascript:void(0)" onclick="reset()" ><span><span>Huỷ bỏ</span></span></a>
                    <script type="text/javascript">
						function reset(){
							$('#order-form input[type="text"]').each(function(){
								$(this).attr('value','');
							});
						}
                        function DangKy() {
                            if (document.getElementById("tbHoTen").value.length < 1) {
                                alert("Vui lòng nhập Họ tên");
                                tbHoTen.focus();
                                return false;
                            }                            
                            if (document.getElementById("input-from").value.length < 1) {
                                alert("Vui lòng nhập điểm đi");
                                tbDienThoai.focus();
                                return false;
                            }
                            if (document.getElementById("input-to").value.length < 1) {
                                alert("Vui lòng nhập điểm đến");
                                tbDienThoai.focus();
                                return false;
                            }
                            if (document.getElementById("tbDienThoai").value.length < 1) {
                                alert("Vui lòng nhập Điện thoại");
                                tbDienThoai.focus();
                                return false;
                            }
							$("#service-type").html($("#ddlDichVu").find('option:selected').text());
							$("#order-phone").html($("#tbDienThoai").attr("value"));
							$("#order-name").html($("#tbHoTen").attr("value"));
							$("#order-info").html($("#tbYeuCauChiTiet").attr("value"));
							$("#order-from").html($("#input-from").attr("value"));
							$("#order-to").html($("#input-to").attr("value"));
                            //ShowOrderForm();                            
							submitform();
                        }
                    </script>
                </div>
                 <div class="cred">&nbsp;</div>
                 <div class="pb10 pt10">
                    <img src="<?php echo get_bloginfo("template_url")?>/pic/map/phone.jpg" />
                 </div>
                 <!--div>
                    <b>Xin trân trọng cảm ơn!</b>
                 </div-->
			</form>
				</div>
             </div>
                    <div class="cb h15"><!----></div>
				<div style="width: 980px; postion: relative; border-top: solid 1px #ccc;">
                    <div class="cb h15"><!----></div>
					<div id="MoexSchool" style="width: 450px;margin: 0 auto;">
                    <div class="">
                        <div class="fl">
                            <!-- AddThis Button BEGIN -->
                            <div class="addthis_toolbox addthis_default_style ">
                            <!--<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>-->
                            <a class="addthis_button_facebook_like"></a>
                            <a class="addthis_button_tweet"></a>
                            <a class="addthis_button_google_plusone" g:plusone:size="normal"></a>
                            <!--<a class="addthis_counter addthis_pill_style"></a>-->
                            </div>
                            <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e70275244deb51b"></script>
                            <!-- AddThis Button END -->
                        </div>
                    </div>
                    <div class="cb h15"><!----></div>
					<?php comments_template('', true);?>
                    <div class="cb h25"><!----></div>
					</div>
				</div>

            <script type="text/javascript">                
                function rgb2hex(rgb) {
                    var oldRgb = rgb;
                    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
                    if (rgb) {
                        function hex(x) {
                            return ("0" + parseInt(x).toString(16)).slice(-2);
                        }
                        return hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
                    }
                    else
                        return oldRgb.replace("#", "");
                }
				function submitform(){
					$("#order-form").submit();	
				}
                function ShowOrderForm() {
                    //Lấy màu
                    var color = ddlDichVu.options[ddlDichVu.selectedIndex].style.backgroundColor;
                    var colorhex = rgb2hex(color);
                    //Đặt lại màu cho các phần cần thiết
                    document.getElementById("lightPopupOrder").style.borderColor = "#" + colorhex;
                    $(".textColor").css("color", "#" + colorhex);
                    $("#btClose").css("background", "url(<?php echo get_bloginfo("template_url");?>/cs/pic/Order/btClose" + colorhex + ".png) no-repeat");
                    $(".btOrder").css("background", "url(<?php echo get_bloginfo("template_url");?>/cs/pic/Order/btOK" + colorhex + ".png) no-repeat");


                    var left = (GetWindowWidth() - 610) / 2;
                    document.getElementById('lightPopupOrder').style.left = left + "px";                    
                    $('#lightPopupOrder').fadeIn();
                    $('#fadePopupOrder').fadeIn();
                    //ScrollTo();
                }
                function HideOrderForm() {
                    $('#lightPopupOrder').fadeOut();
                    $('#fadePopupOrder').fadeOut();
                }
            </script>
        </div>
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

<script type="text/javascript">
waypoints = [];
var request = {
    origin: '219 KHÂM THIÊN',
    destination: '99 PHỐ HUẾ',
    waypoints: waypoints,
    optimizeWaypoints: true,
    travelMode: google.maps.DirectionsTravelMode.WALKING
};  

var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();
var oldDirections = [];
var currentDirections = null;
$(document).ready(function(){
   $(".order-meta").delegate("a.jslink", "click", function(event){
        event.preventDefault();
        add('#moex_corebundle_meorderstype_ordermeta');
        $('.order-metakey-position').attr('value', '{{ order_position }}')
    });

	$("#ddlDichVu option").each(function(){
		if($(this).val() == <?php echo (isset($_GET['service']))?$_GET['service']:1?>){
			$(this).attr('selected','selected');
			service_type = $(this).val();
			display_price();
		}
	});
	FillBGColor('khungddl');
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
    $("#search-submit").click(function(){
        submit_click = true;
        items = $('input.order-position');
        waypoints = [];
        i = 0;
        items.each(function(){
            var address = $(this).attr('value');
            if (address !== "") {
                waypoints.push({
                    location: address + province,
                    stopover: true
                });
            }
        })

        request.waypoints = waypoints;

        request.origin = $('#input-from').attr('value');
        request.destination = $('#input-to').attr('value');
        getRoute();
    });

    $('#input-to').bind('keypress',function(e){
        if(e.keyCode == 13){
            if ($('#input-from').val() != "" && $('#input-to').val() != ""){
                submit_click = true;
				items = $('input.order-position');
				waypoints = [];
				i = 0;
				items.each(function(){
					var address = $(this).attr('value');
					if (address !== "") {
						waypoints.push({
							location: address + province,
							stopover: true
						});
					}
				})

				request.waypoints = waypoints;

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
				items = $('input.order-position');
				waypoints = [];
				i = 0;
				items.each(function(){
					var address = $(this).attr('value');
					if (address !== "") {
						waypoints.push({
							location: address + province,
							stopover: true
						});
					}
				})

				request.waypoints = waypoints;

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
            distance = 0;
            routes = directionsDisplay.directions.routes[0].legs;
            for (var i = 0; i < routes.length; i++) {
                distance = distance + routes[i].distance.value/1000;
            }

            money_value = countMoney();
			display_price();
			$('#order-distance').html(moex_distance);
            //$('#input-from').attr('value',rleg.start_address);
            //$('#input-to').attr('value',rleg.end_address);
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
