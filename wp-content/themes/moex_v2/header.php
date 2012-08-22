<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
    /*
     * Print the <title> tag based on what is being viewed.
     */
    global $page, $paged;

    wp_title( '|', true, 'right' );

    // Add the blog name.
    bloginfo( 'name' );

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        echo " | $site_description";

    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
        echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );
    ?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<script src="<?php echo get_bloginfo('template_url')?>/js/jquery.min.js" type="text/javascript"></script>
<?php
    wp_head();
?>
</head>
<body>
<div class="main">
<div class="menu-top-container">
    <div class="menu-top">
        <div class="menu-top-left">
			<ul>
				<li><img src="<?php echo get_bloginfo('template_url')?>/images/facebook.png"></li>
				<li><img src="<?php echo get_bloginfo('template_url')?>/images/twitter.png"></li>
				<li><img src="<?php echo get_bloginfo('template_url')?>/images/google.png"></li>
			</ul>
			<?php wp_nav_menu(array('theme_location' => ('second-menu'), 'container' => ''));?>
        </div>
        <div class="menu-top-right">
            <ul>
            <?php if ( is_user_logged_in() ) { ?>

                <li><a><i>Xin chào <?php echo wp_get_current_user()->user_login; ?></i></a></li>
                <?php if (current_user_can('edit_post')): ?>
                <li><a href="<?php echo get_admin_url(); ?>">Quản trị</a></li>
                <?php endif;?>
                <li> <a href="<?php echo wp_logout_url(); ?>">Đăng xuất</a></li>

            <?php }
                else{
            ?>
                <li><a href="<?php echo wp_login_url(); ?>">Đăng nhập</a></li>
            <?php
            }
            ?>
            </ul>
        </div>
    </div>
</div>
<div class="banner-container">
	<div class="banner">
		<div class="logo-container">
			<div class="logo">
				<img src="<?php echo get_bloginfo('template_url');?>/images/logo3.png"/>
			</div>
		</div>
		<div class="sub-menu-container">
			<div class="sub-menu">
				<?php wp_nav_menu(array('theme_location' => 'primary-menu'));?>
			</div>
		</div>
	</div>
</div>
