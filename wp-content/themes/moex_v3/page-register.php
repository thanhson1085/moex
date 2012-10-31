<?php
	get_header();

	/*
	include_once('/var/www/moex/wp-content/themes/moex_v3/lib/recaptcha/recaptchalib.php');
	$publickey = "6LeTMNgSAAAAAKlNtBAkDII_Nm6rYdTcZo8XrnaU";
	$privatekey = "6LeTMNgSAAAAANs1Nw78m0_-er0UMDYpN_FVZayu";

	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	*/

?>
    <div id="PageContent">
        <div id="DangKy" class="mh1">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>  
            <div class="tac lh18">
			<div class="moex-message">
				<?php 
					$register = (isset($_GET['register']))?$_GET['register']:false; 
					if(isset($register) && $register == true):
						echo '<p>Mật khẩu đã được gửi đến địa chỉ email bạn đăng kí, vui lòng kiểm tra email để lấy mật khẩu</p>';
					else:
				?>
                    <?php
                    if ( have_posts() ) while ( have_posts() ) : the_post();
                        the_content();
                    endwhile;
                    ?>
				<?php
					endif;
				?>
			</div>
            </div>         
            <div class="cb h25"><!----></div>
            <div class="content">
			<form name="registerform" id="register-form" method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="wp-user-form">
				<?php
				/*
				<div class="username">
					<label for="user_login"><?php _e('Username'); ?>: </label>
					<input type="text" name="user_login" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="101" />
				</div>
				<div class="password">
					<label for="user_email"><?php _e('Your Email'); ?>: </label>
					<input type="text" name="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" id="user_email" tabindex="102" />
				</div>
				*/
				?>
                <div class="cot1">Email <span>*</span></div>
                <div class="cot2">
                    <input id="tbEmail" name="user_email" type="text" class="textbox" style="width:215px"/>
                </div>
				<?php /*
                <div class="cb h12"><!----></div>
                <div class="cot1">Mật khẩu <span>*</span></div>
                <div class="cot2">
                    <input id="tbMatKhau" name="user_pass" type="password" class="textbox" style="width:215px"/>                    
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Mật khẩu <span>*</span></div>
                <div class="cot2">
                    <input id="tbMatKhau" type="password" class="textbox" style="width:215px"/>                    
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Xác nhận mật khẩu <span>*</span></div>
                <div class="cot2">
                    <input id="tbXacNhanMatKhau" type="password" class="textbox" style="width:215px"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Họ và tên <span>*</span></div>
                <div class="cot2">
                    <input id="tbHoVaTen" name="user_lastname" type="text" class="textbox" style="width:215px"/>
                </div>
				*/
				?>
                <div class="cb h12"><!----></div>
                <div class="cot1">Điện thoại <span>*</span></div>
                <div class="cot2">
                    <input id="tbDienThoai" name="user_login" type="text" class="textbox" style="width:215px"/>
                </div>
				<?php /*
                <div class="cb h12"><!----></div>
                <div class="cot1">&nbsp;</div>
                <div class="cot2">
					<?php echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />';?>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">&nbsp;</div>
                <div class="cot2">
                    <input id="tbCaptcha" name="tbCaptcha" type="text" class="textbox" style="width:150px" placeholder="Input image code"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Địa chỉ nhận hàng <span>*</span></div>
                <div class="cot2">
                    <textarea id="tbDiaChiNhanHang" cols="20" rows="2" class="textbox" style="width:290px;height:55px" ></textarea>                    
                </div>
				*/
				?>
				<div class="login_fields">
					<?php do_action('register_form'); ?>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?register=true" />
					<input type="hidden" name="user-cookie" value="1" />
				</div>
			</form>
                <div class="cb h12"><!----></div>   
                <div class="cot1">&nbsp;</div>
                <div class="cot2">
                    <a class="btOK" href="javascript:void(0)" onclick="DangKy()"><span><span>Đăng ký</span></span></a>
                    <a class="btOK" href="javascript:void(0)" onclick="Reset()"><span><span>Huỷ bỏ</span></span></a>
                    <script type="text/javascript">
						function Reset(){
							$('#register-form input[type="text"]').each(function(){
								$(this).attr("value","");
							});
						}
                        function DangKy() {
                            var email = document.getElementById("tbEmail").value;
                            if (email.length < 1) {
                                alert("Vui lòng nhập Email");
                                tbEmail.focus();
                                return false;
                            }
                            else {
                                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                if (!filter.test(email)) {
                                    alert('Email không hợp lệ!');
                                    tbEmail.focus();
                                    return false;
                                }                                
                            }
							/*
                            if (document.getElementById("tbMatKhau").value.length < 1) {
                                alert("Vui lòng nhập Mật khẩu");
                                tbMatKhau.focus();
                                return false;
                            }
                            if (document.getElementById("tbMatKhau").value != document.getElementById("tbXacNhanMatKhau").value) {
                                alert("Mật khẩu và Xác nhận mật khẩu không trùng nhau");
                                tbXacNhanMatKhau.focus();
                                return false;
                            }

                            if (document.getElementById("tbDiaChiNhanHang").value.length < 1) {
                                alert("Vui lòng nhập Địa chỉ nhận hàng");
                                tbDiaChiNhanHang.focus();
                                return false;
                            }
							*/
							phonenumber = document.getElementById("tbDienThoai").value;
							var phoneNumberPattern = /^\+?\(?(\d{2,3})\)?[- ]?(\d{3,4})[- ]?(\d{4})$/;
							if(!phoneNumberPattern.test(phonenumber)){
								alert('PhoneNumber không hợp lệ!');
								tbDienThoai.focus();
								return false;
							}
                            if (document.getElementById("tbDienThoai").value.length < 1) {
                                alert("Vui lòng nhập Điện thoại");
                                tbDienThoai.focus();
                                return false;
                            }

						    document.registerform.submit();
                        }
                    </script>
                </div>
                <div class="cb"><!----></div> 
                <div class="cb h12"><!----></div>   
                <div class="cb h12"><!----></div>   
            </div>
        </div>
    </div>    
<?php
get_footer();
?>
