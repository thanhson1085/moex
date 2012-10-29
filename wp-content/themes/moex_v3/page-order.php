<?php
get_header();
//if($_SERVER['REQUEST_METHOD'] == 'POST'){	
	//if(strtolower($_SESSION['captcha']['code']) == strtolower($_POST['tbCaptcha'])){
		//$status = wp_create_user(esc_attr($_POST['user_login']), esc_attr($_POST['user_pass']), esc_attr($_POST['user_email']));
		//$register = true;
	//}
//}
$_SESSION['captcha'] = captcha();
?>
    <div id="PageContent">
        <div id="Order">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>  
            <div class="tac lh18">
				<?php
				if ( have_posts() ) while ( have_posts() ) : the_post();
					the_content();
				endwhile;
								
				if($_SERVER['REQUEST_METHOD'] == 'POST'){	
					$status = $wpdb->insert($wpdb->prefix."orders", array("order_name" => $_POST['tbHoTen'], 
												"phone" => $_POST["tbDienThoai"], "service_type" => $_POST['ddlDichVu'],
												"order_info" => $_POST["tbYeuCauChiTiet"], "updated_at" => current_time('mysql'), 
												"created_at" => current_time('mysql')), array("%s", "%s", "%d", "%s"));
					if($status){
					?>
						<script type="text/javascript">
							alert("<?php echo _e("Đăng ký thành công, MoEx sẽ phục vụ bạn ngay!"); ?>");
							$(location).attr("href","<?php echo get_bloginfo("url");?>");
						</script>
					<?php
					}
					else{
					?>
						<script type="text/javascript">
							alert("<?php echo _e("Đăng ký không thành công, vui lòng thử lại"); ?>");
						</script>
					<?php
					}
				}
				?>
            </div>         
            <div class="cb h25"><!----></div>
            <div class="content">
				<form method="POST" id="order-form" name="orderform" action="">
                <div class="cot1">Họ tên <span>*</span></div>
                <div class="cot2">
					<?php if(is_user_logged_in()):
					$current_user = wp_get_current_user();
					?>
                    <input id="tbHoTen" name="tbHoTen" type="text" class="textbox" value="<?php echo $current_user->user_lastname; ?>" />
					<?php else: ?>
                    <input id="tbHoTen" name="tbHoTen" type="text" class="textbox"/>
					<?php endif;?>
                </div>                                
                <div class="cb h12"><!----></div>
                <div class="cot1">Điện thoại <span>*</span></div>
                <div class="cot2">
					<?php if(is_user_logged_in()):
					$current_user = wp_get_current_user();
					?>
                    <input id="tbDienThoai" name="tbDienThoai" type="text" class="textbox" value="<?php echo $current_user->user_login;?>"/>
					<?php else: ?>
                    <input id="tbDienThoai" name="tbDienThoai" type="text" class="textbox"/>
					<?php endif;?>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Dịch vụ đăng ký <span>*</span></div>
                <div class="cot2">
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
                <div class="cot1">Yêu cầu chi tiết</div>
                <div class="cot2">
                    <textarea id="tbYeuCauChiTiet" name="tbYeuCauChiTiet" cols="20" rows="2" class="textbox" style="height:55px" ></textarea>                    
                </div>
                <div class="cb h12"><!----></div>   
                <div class="cot1">&nbsp;</div>
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
                            ShowOrderForm();                            
                        }
                    </script>
                </div>
                <div class="cb"><!----></div> 
				</form>
            </div>

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
                <div class="left">Yêu cầu chi tiết</div>
                <div class="fl">:</div>
                <div class="right" id="order-info">Số 123 Xuân Thủy, Dịch Vọng Hậu, Cầu Giấy, Hà Nội. Bạn vui lòng xác nhận lại các thông tin bên dưới và gửi về cho chúng tôi</div>
                <div class="cb h16"><!----></div>
                <div class="cb h16"><!----></div>
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
<?php
get_footer();
?>
