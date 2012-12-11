<?php
/*
Template Name: Login Page
*/
?>
<?php
get_header();
?>
    <div id="PageContent">
        <div id="DangKy" class="mh2">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
            <div class="head1"><!----></div>                       
            <div class="cb h25"><!----></div>
            <div class="content">
                <div class="cot3">Email</div>
				<form name="loginform" id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
                <div class="fl">
                    <input id="tbEmail" type="text" class="textbox" name="log" style="width:210px" tabindex="1"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot3">Mật khẩu</div>
                <div class="fl">
                    <input id="tbMatKhau" type="password" class="textbox" name="pwd" style="width:210px" tabindex="2"/>                    
                </div>              
                <div class="cb h12"><!----></div>   
                <div class="cot3">&nbsp;</div>
				<div class="fl"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="3"> Nhớ tài khoản</label></div>
                <div class="cb h12"><!----></div>   
                <div class="cot3">&nbsp;</div>
                <div class="fl">
                    <a class="btOK" href="javascript:void(0)" onclick="submitform()" tabindex="5"><span><span>Đăng nhập</span></span></a>
                    <a class="help" href="<?php echo get_bloginfo("url")?>/wp-login.php?action=lostpassword">Quên mật khẩu?</a>                    
                </div>
				</form>
				<script type="text/javascript">
				$(document).ready(function(){
					$("#tbMatKhau").keypress(function(event) {
						if (event.which == 13) {
							event.preventDefault();
							$("#loginform").submit();
						}
					});
				});
				function submitform()
				{
				  document.loginform.submit();
				}
				</script>
                <div class="cb"><!----></div> 
            </div>
        </div>
	</div>
<?php 
get_footer();
?>
