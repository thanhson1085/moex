<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>
<?php
    /*
     * Print the <title> tag based on what is being viewed.
     */
    global $page, $paged;

    wp_title( '-', true, 'right' );

    // Add the blog name.
    bloginfo( 'name' );

// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
	echo " - $site_description";
?>
</title>
<link href="<?php echo get_bloginfo("template_url")?>/pic/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

<script src="<?php echo get_bloginfo("template_url")?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo get_bloginfo("template_url")?>/js/display.js" type="text/javascript"></script>
<script src="<?php echo get_bloginfo("template_url")?>/js/mescript.js" type="text/javascript"></script>
<script src="<?php echo get_bloginfo("template_url")?>/js/jcarousellite_1.js" type="text/javascript"></script>
<script type="text/javascript">
	var ajax_link = "<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php";
</script>

<link href="<?php echo get_bloginfo("template_url")?>/cs/Default.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/fontface.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/CommonControls.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/Homepage.css" rel="stylesheet" type="text/css" />

<link href="<?php echo get_bloginfo("template_url")?>/cs/GioiThieu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/DangKy.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/QuanLyTaiKhoan.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/MoexGo.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/MoexOffice.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/MoexShopping.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/MoexFood.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/MoexSchool.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/LienHe.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/Blog.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_bloginfo("template_url")?>/cs/Order.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo get_bloginfo("template_url")?>/cs/KetQuaTimKiem2.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo get_bloginfo("template_url")?>/cs/KetQuaTimKiem.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo get_bloginfo("template_url")?>/style.css" rel="stylesheet" type="text/css" />
<?php
    wp_head();
?>
</head>
<body class="mo">  
	<div class="page-moex-container">
	<div class="page-moex">
    <div id="PageContent">
        <div id="Header">
            <div id="Phone"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/phone.jpg" /></div>
            <div id="Logo">
                <div class="logo"><a href="<? echo get_bloginfo("url") ?>"><img alt="" alt="" src="<?php echo get_bloginfo("template_url")?>/pic/logo.jpg" class="anhQC"/></a></div>
                <div class="promotion"><a href="<?php echo get_bloginfo("url")?>/promotion.html" target="_blank"><img alt="" alt="" src="<?php echo get_bloginfo("template_url")?>/pic/promotion.jpg" class="anhQC"/></a></div>
            </div>                        
            <div class="fr pt10">
                <div id="Language">
                    <a href="#" title="Tiếng Việt"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/language/flVie.jpg" /></a>
                    <a href="#" title="English"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/language/flEng.jpg" /></a>
                </div>
				<?php get_search_form()?>
                <div id="MenuTop">
					<?php if (!is_user_logged_in()): ?>
						<div class="fr"><a class="mnt" href="<?php echo get_bloginfo("url")?>?page_id=154" title="Đăng nhập">Đăng nhập</a></div>
						<div class="mntSplit"><!----></div>
						<div class="fr"><a class="mnt" href="<?php echo get_bloginfo("url")?>?page_id=157" title="Đăng ký">Đăng ký</a></div>
						<div class="mntSplit"><!----></div>
					<?php else: ?>
                    	<div class="fr">
							<a class="mnt" href="<?php echo wp_logout_url( home_url() ); ?>"><span>Đăng xuất</span></a>
						</div>
						<div class="mntSplit"><!----></div>
						<?php
						$user_id = get_current_user_id();
						$orders = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."orders WHERE customer_id=".$user_id);
						if($orders):
						?>
                    	<div class="fr">
							<a class="mnt" href="<?php echo get_bloginfo("url"); ?>/order-history"><span>Đơn hàng</span></a>
						</div>
						<?php
						endif;
						?>
						<div class="mntSplit"><!----></div>
					<?php endif;?>
					<?php
						$current_user = wp_get_current_user();
					?>
                    <div class="fr">Xin chào 
						<a class="mnt" href="<?php echo get_bloginfo("url")?>?page_id=159" title="Xin chào Bạn">						
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
					</div>
                    <div class="cb"><!----></div>
                </div>
                <div class="cb"><!----></div>
            </div>
            <div class="cb"><!----></div>
        </div>
    
        <div id="MenuMain">
            <div class="fl">
                <a class="mnm bgmnm0" href="<?php echo get_bloginfo("url")?>?page_id=164" title="Moex Delivery">Moex Delivery</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm1" href="<?php echo get_bloginfo("url")?>?page_id=161" title="Moex go">Moex go</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm2" href="<?php echo get_bloginfo("url")?>?page_id=170" title="Moex food">Moex food</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm3" href="<?php echo get_bloginfo("url")?>?page_id=172" title="Moex shopping">Moex shopping</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm4" href="<?php echo get_bloginfo("url")?>?page_id=166" title="Moex school">Moex school</a>            
            </div>
            <div class="cb h10"><!----></div>
        </div>
