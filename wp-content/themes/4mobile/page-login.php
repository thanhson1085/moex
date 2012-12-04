<?php
get_header();
?>	
	<div class="form-container">
		<form name="loginform" id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
			<div class="form-row"><input type="text" class="textbox" name="log" tabindex="1"/></div>
			<div class="form-row"><input type="password" class="textbox" name="pwd" tabindex="2"/></div>
		<div class="form-row"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="3"> Nhớ tài khoản</label></div>
		<div class="form-row"><button type="submit">Đăng nhập</button>
		<a class="help" href="<?php echo get_bloginfo("url")?>/wp-login.php?action=lostpassword">Quên mật khẩu?</a></div>
		</form>
	</div>
<?php
get_footer();
?>		
