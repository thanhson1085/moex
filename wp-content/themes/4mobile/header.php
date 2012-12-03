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
<div class="header-container">
	<div class="header">
		<div class="logo"><a href="<?php echo get_bloginfo("url");?>"><img src="<?php echo get_bloginfo("template_url")?>/images/logo.jpg"></a></div>
		<div class="slogan">
                  <?php if (!is_user_logged_in()): ?>
                        <a class="mnt" href="<?php echo get_bloginfo("url")?>/login" title="Đăng nhập">Đăng nhập</a>
                        <a class="mnt" href="<?php echo get_bloginfo("url")?>/register" title="Đăng ký">Đăng ký</a>
                    <?php else: ?>
                    <?php
                        $current_user = wp_get_current_user();
                    ?>
                    	Xin chào 
					   <a class="mnt" href="<?php echo get_bloginfo("url")?>/profile" title="Xin chào Bạn">                        
							<span>
							<?php
								if ( 0 == $current_user->ID ) {
									// Not logged in.
									echo "Bạn";
								} else {
									echo ($current_user->last_name)?$current_user->last_name:$current_user->user_login;
								}
							?>
							</span>
						</a>  
                        <a class="mnt" href="<?php echo wp_logout_url( home_url() ); ?>"><span>Đăng xuất</span></a>
				<?php endif;?>

			</div>
	</div>
</div>
<div class="moex-page-container">
	<div class="moex-page">
