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
<?php
    wp_head();
?>
</head>
<body>
<div class="top-container">
	<div class="top">
		<div class="logo">
			<img src="<?php echo get_bloginfo('template_url')?>/images/logo.png"/>
		</div>
	</div>
</div>
<div class="top-menu-container">
	<div class="top-menu">
		<ul>
			<li><a href="">lorem ipsum</a></li>
			<li><a href="">lorem ipsum</a></li>
			<li><a href="">lorem ipsum</a></li>
			<li><a href="">lorem ipsum</a></li>
		</ul>
	</div>
</div>
<div class="nav-container">
	<div class="nav">
		<div class="search-container">
			<div class="nav-filter">
				<ul>
					<li><a href="">All</a></li>
					<li><a href="">lorem ipsum</a></li>
					<li><a href="">lorem ipsum</a></li>
					<li><a href="">lorem ipsum</a></li>
					<li><a href="">lorem ipsum</a></li>
				</ul>
			<div>
			<div class="search">
				<input type="text" value=""/>
				<input type="button" value="Search"/>
			</div>
		</div>
	</div>
</div>
<div class="cat-container">
	<div class="cat">
		<ul>
			<li><span>lorem ipsum</span>
				<div class="sub-cat">
					<h3>lorem ipsum</h3>
					<ul>
						<li><a href="">lorem ipsum</a></li>
						<li><a href="">lorem ipsum</a></li>
						<li><a href="">lorem ipsum</a></li>
						<li><a href="">lorem ipsum</a></li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
</div>
