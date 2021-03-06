<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>
<?php
    /*
     * Print the <title> tag based on what is being viewed.
     */
    global $page, $paged;

    wp_title( '', true, 'right' );

    // Add the blog name.
	if (!is_home()){
	echo " | ";
    bloginfo( 'name' );
	}
		

// Add the blog description for the home/front page.
/*
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
	echo " | $site_description";
*/
?>
</title>
<link href="<?php echo get_bloginfo("template_url")?>/pic/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
<script type="text/javascript">
/*
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
if (isMobile){
	window.location = "<?php echo WP_MOBILE_THEME;?>";
}
*/
</script>
<script type="text/javascript">
	var ajax_link = "<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php";
</script>
    <link rel="stylesheet" href="<?php echo get_bloginfo("template_url")?>/style.css" rel="stylesheet" type="text/css" />
<?php
    wp_head();
?>
<script src="<?php echo get_bloginfo("template_url")?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo get_bloginfo("template_url")?>/js/display.js" type="text/javascript"></script>
<script src="<?php echo get_bloginfo("template_url")?>/js/mescript.js" type="text/javascript"></script>
<script src="<?php echo get_bloginfo("template_url")?>/js/jcarousellite_1.js" type="text/javascript"></script>
<script src="<?php echo plugins_url()?>/yop-poll/js/yop-poll-public.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo get_bloginfo("template_url")?>/js/jquery.cycle.all.min.js"></script>
</head>
<body class="mo">  
	<div class="page-moex-container">
	<div id="float-banner" style="top: 0;width: 1350px;position: fixed;left: 50%;margin-left: -680px;">
		<div class="img-left"><a href="<?php echo get_bloginfo("url")?>/qua-tet/"><img src="<?php echo get_bloginfo("template_url")?>/pic/left-banner.jpg"></a></div>
		<div class="img-right"><a href="<?php echo get_bloginfo("url")?>/qua-tet/"><img src="<?php echo get_bloginfo("template_url")?>/pic/right-banner.jpg"></a></div>
	</div>
	<div class="page-moex">
    <div id="PageContent">
        <div id="Header">
            <div id="Phone"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/hotline.jpg" /></div>
            <div id="Logo">
                <div class="logo">
					<a href="<? echo get_bloginfo("url") ?>"><img alt="" alt="" src="<?php echo get_bloginfo("template_url")?>/pic/logo_NY.jpg" class="anhQC"/></a>
				</div>
			<div class="promotion">
				<div id="metaContainer">
					<!-- The sliderr works with virtually any HTML element (div, span etc) but for the sake of simplicity I have used images in this demo -->
					<div id="slideContainer2">
						<div id="slideShim2" style="left: -1px;">
							<a href="<?php echo get_bloginfo("url");?>/qua-tet/"><img src="<?php echo get_bloginfo("template_url")?>/pic/banner_NY_01.jpg" alt="" /></a>
							<a href="<?php echo get_bloginfo("url");?>/qua-tet/"><img src="<?php echo get_bloginfo("template_url")?>/pic/banner_NY_02.jpg" alt="" /></a>
						</div>
					</div>
				</div>

            </div>
			<script type="text/javascript">
			$(document).ready(function(){
				$('#slideShim2').cycle({
						fx:     'fade',
						speed:  400,
						timeout: 2000,
						prev:   '#back',
						next:   '#forward',
						pause:  1,
						pager:  '#pager2'
					});
			});
			</script>
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
                <a class="mnm bgmnm0" href="<?php echo get_bloginfo("url")?>/moex-delivery/" title="Giao hàng nhanh trong 2 giờ">Moex Delivery</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm1" href="<?php echo get_bloginfo("url")?>/moex-go/" title="Đưa đón hành khách mọi lúc mọi nơi">Moex go</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm2" href="<?php echo get_bloginfo("url")?>/moex-food/" title="Mua đồ ăn nhanh phong phú đa dạng">Moex food</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm3" href="<?php echo get_bloginfo("url")?>/moex-shopping/" title="Mua sắm nhanh chóng tiện lợi">Moex shopping</a>            
            </div>
            <div class="mnmSplit"><!----></div>
            <div class="fl">
                <a class="mnm bgmnm4" href="<?php echo get_bloginfo("url")?>/moex-school/" title="Đưa đón học sinh an toàn chu đáo">Moex school</a>            
            </div>
            <div class="cb h10"><!----></div>
        </div>
