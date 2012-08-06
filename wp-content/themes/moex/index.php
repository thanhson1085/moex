<?php get_header();?>
<div class="banner-container">
	<div class="banner">
		<div class="logo-container">
			<div class="logo">
				<img src="<?php echo get_bloginfo('template_url');?>/images/express_logo.gif"/>
			</div>
		</div>
	</div>
</div>
<div class="header-container">
	<div class="header">
		<div class="sub-menu-container">
			<div class="sub-menu">
				<ul>
					<li>
						<a href="<?php echo get_bloginfo('url');?>">Home</a>
					</li>
					<li>
						<a href="<?php echo get_bloginfo('url');?>">News</a>
					</li>
					<li>
						<a href="<?php echo get_bloginfo('url');?>">Search</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-intro">
			<p class="main-header">Lorem Ipsum</p>
			<p class="main-intro-content">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqu
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqu
			</p>
		</div>
		<div class="main-form">
			<p><input class="txt-main" name="from" type="text" placeholder="From..."/></p>
			<p><input class="txt-main" name="to" type="text" placeholder="To..."/></p>
			<p><span class="btn-main">Submit</span></p>

		</div>
	</div>
</div>
<div class="content-container">
	<div class="content">
		<div class="left-part-container">
			<div class="left-part">
				<?php get_template_part('loop','index');?>
			</div>
		</div>
		<div class="right-part-container">
			<div class="right-part">

				<?php get_template_part('sidebar','index');?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();?>
