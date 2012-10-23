<?php
get_header();
?>
    <div id="PageContent">
        <div id="KetQuaTimKiem2">
            <div class="cb h10"><!----></div>
             <div class="cot1">
				<div id="map2"></div>
			</div>
			<div id="order-search">
				<?php
				if($_SERVER['REQUEST_METHOD'] == 'POST'){	
					$status = $wpdb->insert($wpdb->prefix."orders", array("order_name" => $_POST['tbHoTen'], 
												"phone" => $_POST["tbDienThoai"], "service_type" => $_POST['ddlDichVu'],
												"order_from" => $_POST["tbFrom"], "order_to" => $_POST["tbTo"],
												"order_info" => $_POST["tbYeuCauChiTiet"], "updated_at" => current_time('mysql'), 
												"created_at" => current_time('mysql')), array("%s", "%s", "%d", "%s", "%s", "%s"));
					if($status):
					?>
					<script type="text/javascript">
						alert("<?php echo _e("Đăng ký thành công, MoEx sẽ phục vụ bạn ngay!"); ?>");
						$(location).attr("href","<?php echo get_bloginfo("url");?>");
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
				<form method="POST" id="order-form" name="orderform" action="<?php get_bloginfo("template_url")?>?page_id=191">
                <div class="pb10">
                     <img src="<?php echo get_bloginfo("template_url");?>/pic/map/head.jpg" />
                 </div>
                 <div class="left">Điểm đi</div>
                 <div class="fl">:</div>
                 <div class="right">
                	<input id="input-from" name="tbFrom" type="text" class="textbox" value="<?php echo (isset($_GET['from']))?$_GET['from']:""; ?>"/>
				 </div>
                 <div class="cb h12"><!----></div>
                 <div class="left">Điểm đến</div>
                 <div class="fl">:</div>
                 <div class="right">
                	<input id="input-to" name="tbTo" type="text" class="textbox" value="<?php echo (isset($_GET['to']))?$_GET['to']:""; ?>"/>
				 </div>
                 <div class="cb h12"><!----></div>
                <div class="left">Dịch vụ đăng ký <span>*</span></div>
                <div class="fl">:</div>
                <div class="right">
                    <div id="khungddl">
                        <div class="cb h2"><!----></div>
                        <select id="ddlDichVu" name="ddlDichVu" onchange="FillBGColor('khungddl')">
                            <option style="background:#6b7b84" value="Moex Office">Moex Delivery</option>
                            <option style="background:#c8215d" value="Moex Go">Moex Go</option>
                            <option style="background:#8dc63f" value="Moex Food">Moex Food</option>
                            <option style="background:#f26522" value="Moex Shopping">Moex Shopping</option>
                            <option style="background:#20409a" value="Moex School">Moex School</option>
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
                 <div class="right"><span id="order-distance">4.75</span> Km</div>
                 <div class="cb h12"><!----></div>
                 <div class="left">Giá</div>
                 <div class="fl">:</div>
                 <div class="right"><span id="search-result">30.000</span> VNĐ</div>
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
                            ShowOrderForm();                            
                        }
                    </script>
                </div>
                 <div class="cred">* Giá có thể thay đổi. Vào giờ cao điểm (16:00-19:00) cộng thêm 2.000 VNĐ/km.</div>
                 <div class="pb10 pt10">
                    <img src="<?php echo get_bloginfo("template_url")?>/pic/map/phone.jpg" />
                 </div>
                 <!--div>
                    <b>Xin trân trọng cảm ơn!</b>
                 </div-->
			</form>
             <div class="cb h15"><!----></div>
            <div id="fadePopupOrder" onclick="HideOrderForm()"><!----></div>
            <div id="lightPopupOrder">
                <a id="btClose" href="javascript:HideOrderForm()">&nbsp;</a>
                <div class="header textColor">CÁM ƠN BẠN ĐÃ ĐĂNG KÝ DỊCH VỤ CỦA MOEX</div>
                <div class="header1">Bạn vui lòng xác nhận lại các thông tin bên dưới và gửi về cho chúng tôi</div>
                <div class="header2"><!----></div>
                <div class="left">Dịch vụ đăng ký</div>
                <div class="fl">:</div>
                <div class="right textColor" id="service-type">Moex Food</div>
                <div class="cb h16"><!----></div>
                <div class="left">Họ và tên</div>
                <div class="fl">:</div>
                <div class="right" id="order-name">Nguyễn Đình Phương Chi</div>
                <div class="cb h16"><!----></div>
                <div class="left">Điện thoại</div>
                <div class="fl">:</div>
                <div class="right" id="order-phone">0972.184.222</div>
                <div class="cb h16"><!----></div>
                <div class="left">Điểm đi</div>
                <div class="fl">:</div>
				<div class="right" id="order-from"></div>
                <div class="cb h16"><!----></div>
                <div class="left">Điểm đến</div>
                <div class="fl">:</div>
				<div class="right" id="order-to"></div>
                <div class="cb h16"><!----></div>
                <div class="left">Yêu cầu chi tiết</div>
                <div class="fl">:</div>
                <div class="right" id="order-info">Số 123 Xuân Thủy, Dịch Vọng Hậu, Cầu Giấy, Hà Nội.</div>
                <div class="cb h16"><!----></div>
                <div class="cb h16"><!----></div>
                <div class="cb h16"><!----></div>
                <div class="cb h16"><!----></div>
                <div class="cb h16"><!----></div>
                <div class="tac">
                    <a class="btOrder" href="javascript:void(0)" onclick="submitform()">Đăng ký</a>&nbsp;&nbsp;
                    <a class="btOrder" href="javascript:void(0)" onclick="HideOrderForm()">Quay lại</a>
                </div>
            </div>
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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&dirflg=r"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

<script type="text/javascript">
var province = ', Hà Nội, Việt Nam';
var request = {
    origin: '219 KHÂM THIÊN',
    destination: '99 PHỐ HUẾ',
    travelMode: google.maps.DirectionsTravelMode.WALKING
};
var distance = 0;
var submit_click = false;
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();
var oldDirections = [];
var currentDirections = null;
$(document).ready(function(){
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

    google.maps.event.addListener(directionsDisplay, 'directions_changed',
    function() {
        if (currentDirections) {
            var rleg = directionsDisplay.directions.routes[0].legs[0];
            distance = rleg.distance.value;
            distance = rleg.distance.value;
            money_value = countMoney();
            $('#search-result').html(money_value);
			$('#order-distance').html(distance/1000);
            $('#input-from').attr('value',rleg.start_address);
            $('#input-to').attr('value',rleg.end_address);
        }
        currentDirections = directionsDisplay.getDirections();
    });
});
</script>
<?php
get_footer();
?>
