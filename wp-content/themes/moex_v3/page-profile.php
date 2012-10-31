<?php
get_header();
$current_user = wp_get_current_user(); 
if ($current_user->ID == 0):
?>
	<script type="text/javascript">
		$(location).attr('href','<?php echo get_bloginfo("url");?>?page_id=157;');
	</script>
<?php
	exit;
endif;
?>
    <div id="PageContent">
        <div id="QuanLyTaiKhoan">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
			<?php	
			if($_SERVER['REQUEST_METHOD'] == 'POST'){	
				if (!empty($_POST["tbMatKhau"]) && $_POST["tbMatKhau"] == $_POST["tbXacNhanMatKhau"]):
					wp_update_user(array('ID' => $current_user->ID, 'user_pass' => esc_attr($_POST["tbMatKhau"])));
				?>	
					<script type="text/javascript">
						alert('Bạn đã thay đổi mật khẩu thành công, vui lòng đăng nhập lại');
						$(location).attr("href","<?php echo get_bloginfo("url")?>?page_id=154");
					</script>
				<?php
				endif;
				wp_update_user(array('ID' => $current_user->ID, 'user_email' => esc_attr($_POST["tbEmail"])));
				update_user_meta($current_user->ID, 'last_name' , esc_attr($_POST['tbHoTen']));
				update_user_meta($current_user->ID, 'first_name' , esc_attr($_POST['tbFirstname']));
			}
			?>
            <div class="head"><!----></div>
            <div class="h5"><!----></div>
            <div class="content">
				<form method="POST" id="form-profile" action="">
                <div class="pb15"><b>THAY ĐỔI THÔNG TIN TÀI KHOẢN:</b></div>
                <div class="cot1">Email</div>
                <div class="cot2">
                    <input id="tbEmail" name="tbEmail" type="text" class="textbox" style="width:290px" value="<?php echo $current_user->user_email; ?>"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Điện thoại</div>
                <div class="cot2">
                    <input id="tbDienThoai" name="tbDienThoai"  type="text" class="textbox" style="width:210px" value="<?php echo $current_user->user_login ?>"/>
                </div>
                <div class="cb h12"><!----></div>
                <!--div class="cot1">Avatar</div>
                <div class="cot2">
                    <div class="btBrowse">
                        <input id="tbAvatarText" type="text" class="textbox" style="width:210px"/>
                        <input id="tbAvatar" type="file" onchange="FillValueToTextControl(this,'tbAvatarText')"/>                        
                        <script type="text/javascript">
                            function FillValueToTextControl(scontrol, dcontrolid) {
                                document.getElementById(dcontrolid).value = scontrol.value;
                            }
                        </script>
                    </div>                    
                </div-->                
                <div class="cot1">Họ đệm</div>
                <div class="cot2">
                    <input id="tbFirstname" name="tbFirstname" type="text" class="textbox" style="width:210px" value="<?php echo $current_user->user_firstname ?>"/>
                </div>
                <div class="cot1">Tên riêng</div>
                <div class="cot2">
                    <input id="tbHoTen" name="tbHoTen" type="text" class="textbox" style="width:210px" value="<?php echo $current_user->user_lastname ?>"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="pb15"><b>THAY ĐỔI MẬT KHẨU:</b></div>                   
                <div class="cot1">Mật khẩu mới</div>
                <div class="cot2">
                    <input id="tbMatKhau" name="tbMatKhau" type="password" class="textbox" style="width:210px" value=""/>                    
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Xác nhận mật khẩu</div>
                <div class="cot2">
                    <input id="tbXacNhanMatKhau" name="tbXacNhanMatKhau" type="password" class="textbox" style="width:210px" value=""/>                    
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">&nbsp;</div>
                <div class="cot2">
                    <a class="btOK" href="javascript:void(0)" onclick="submitform()"><span><span>Cập nhật</span></span></a>
                    <a class="btOK" href="#"><span><span>Huỷ bỏ</span></span></a>                    
                </div>
                <div class="cb h15"><!----></div> 
				<script type="text/javascript">
					function submitform(){
						if($("#tbMatKhau").attr("value") && $("#tbMatKhau").attr("value") != $("#tbXacNhanMatKhau").attr("value")){
							alert("Xác nhận mật khẩu không chính xác");
							return;
						}
						$("#form-profile").submit();
					}
				</script>
				</form>
            </div>
        </div>
    </div>    
<?php
get_footer();
?>
