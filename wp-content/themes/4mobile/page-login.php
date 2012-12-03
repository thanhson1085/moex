
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo("template_url");?>/style.css">
<script src="<?php echo get_bloginfo("template_url")?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo get_bloginfo("template_url")?>/js/mescript.js" type="text/javascript"></script>
</head>
<body>
<div class="moex-page-container">
	<div class="moex-page">
		<div class="form-container">
		<form name="loginform" id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
			<div class="form-row"><input type="text" class="textbox" name="log" style="width:210px" tabindex="1"/></div>
			<div class="form-row"><input type="password" class="textbox" name="pwd" style="width:210px" tabindex="2"/></div>
		<div class="form-row"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="3"> Nhớ tài khoản</label></div>
		<div class="form-row"><button type="submit">Đăng nhập</button>
		<a class="help" href="<?php echo get_bloginfo("url")?>/wp-login.php?action=lostpassword">Quên mật khẩu?</a></div>
		</form>
		</div>
	</div>
</div>
</body>
</html>
		
