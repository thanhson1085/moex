<?php
get_header();
$user_id = get_current_user_id();
$code_display = 0;
global $post;
$order = (isset($_GET['order']))?$_GET['order']:0;
$order = ($order)?'DESC':'ASC';
$args = array( 'numberposts' => 100, 'post_type'=> 'quatet', 'meta_key' => 'gia_quatet', 'orderby' => 'meta_value_num', 'order' => $order );
$myposts = get_posts( $args );
?>
					<a href="<?php echo get_bloginfo("url")?>/qua-tet/"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/Event_Banner_quatet.gif" class="anhQC"/></a>                
	<div class="cb h15"></div>
<div style="border-bottom: solid 1px #CCC;height: 30px; padding-left: 20px; font-size: 12px;">
<?php
$taxonomy     = 'quatet_cat';
$orderby      = 'name'; 
$show_count   = 0;      // 1 for yes, 0 for no
$pad_counts   = 0;      // 1 for yes, 0 for no
$hierarchical = 1;      // 1 for yes, 0 for no
$title        = '';

$args = array(
  'taxonomy'     => $taxonomy,
  'orderby'      => $orderby,
  'show_count'   => $show_count,
  'pad_counts'   => $pad_counts,
  'hierarchical' => $hierarchical,
  'title_li'     => $title
);
?>
<ul class="quatet-order">
<?php wp_list_categories( $args ); ?>
</ul>
<style>
	.quatet-order, .quatet-order2{padding: 0;margin:0;display: block-inline;list-style: none;}
	.quatet-order li, .quatet-order2 li{font-weight: bold; float:left; padding-right: 10px;
		border-right: solid 1px #CCC;
		margin-right: 10px;
		line-height: 20px;
	}
	.quatet-order2{float:right;}
	.quatet-order2 li{ font-weight: normal;}
</style>
<ul class="quatet-order2">
<li style="color: red; font-weight: normal;"><span>Sắp xếp: </span></li>
<li><a href="<?php echo get_bloginfo("url")?>/qua-tet/?order=0">Giá tăng dần</a></li>
<li><a href="<?php echo get_bloginfo("url")?>/qua-tet/?order=1">Giá giảm dần</a></li>
</ul>
</div>
	<div class="cb h15"></div>
<div class="driver-info-container" style="overflow: auto">
<div class="center-col">
        <div class="people clearfix">

		<?php
		foreach( $myposts as $post ) :	setup_postdata($post); ?>
          <div class="person" code="<?php echo $post->ID;?>">
				<?php $code_display = $post->ID?>
				<?php $img_id = get_post_meta($post->ID, 'anh_quatet', true);
					$img_url = wp_get_attachment_url( $img_id); 
				?>
				<?php $image = ($img_url)?$img_url:get_bloginfo("template_url")."/pic/no-image.jpg";?>
            <a href="<?php the_permalink();?>">
              <img src="<?php echo $image;?>" alt="<?php the_title();?>">
              <p><?php the_title();?>
				<br><span>Mã: </span><span><?php echo get_post_meta($post->ID, 'ma_quatet', true);?></span>
				<br><span>Giá: </span><span class="quatet-price" style="color: red;font-size: 14px;font-weight: bold"><?php echo get_post_meta($post->ID, 'gia_quatet', true);?></span></p>
            </a>
             <!-- /.short-profile -->
          </div> <!-- /.person -->
		<?php 
			endforeach;
		?>
        </div> <!-- /.people -->
        <div class="selected-profile">
		<p style="text-align: right; height: 20px;padding:0;margin:0;">
		<img src="<?php echo get_bloginfo("template_url");?>/pic/close.png" id="quatet-close">
		</p>
		<?php
		foreach( $myposts as $post ) :	setup_postdata($post);
		?>
			
        	<div id="<?php echo $post->ID;?>" class="short-profile" style="display: none;">
				<?php $img_id = get_post_meta($post->ID, 'anh_quatet', true);
					$img_url = wp_get_attachment_url( $img_id); 
				?>
				<?php $image = ($img_url)?$img_url:get_bloginfo("template_url")."/pic/no-image.jpg";?>
              <a href="<?php the_permalink()?>" title="<?php the_title()?>"><img src="<?php echo $image;?>" alt="" style="max-width: 350px;"></a>
              <h3><?php the_title();?></h3>
				<p style="color: #1e1e1e"><span>Mã: </span><span style="font-weight: bold;"><?php echo get_post_meta($post->ID, 'ma_quatet', true);?></span></p>
				<p style="color: #1e1e1e;"><span>Giá: </span><span class="quatet-price" style="color: red; font-size: 13px; font-weight:bold;"><?php echo ''.get_post_meta($post->ID, 'gia_quatet', true);?></span></p>
              <p class="job-title"><?php the_content()?> 
				<br />
			  </p>
            </div>
		<?php
			endforeach;
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#<?php echo $code_display?>").css("display", "block");
				$(".selected-profile").css('display','block');
				$(".person").hover(function(){
					$(".short-profile").each(function(){
						$(this).css("display", "none");
					});
					$(".selected-profile").css('display','block');
					$("#" +$(this).attr("code")).css("display","block");
				});
				$("#quatet-close").click(function(){
					$(".selected-profile").css('display','none');
				});
			});
		</script>
		</div> <!-- /.selected-profile -->
</div>
	<div id="MoexFood" style="width: 460px;">
                    <div class="cb h15"><!----></div>
                    <div class="">
                        <div class="fl">
                            <!-- AddThis Button BEGIN -->
                            <div class="addthis_toolbox addthis_default_style ">
                            <!--<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>-->
                            <a class="addthis_button_facebook_like"></a>
                            <a class="addthis_button_tweet"></a>
                            <a class="addthis_button_google_plusone" g:plusone:size="normal"></a>
                            <!--<a class="addthis_counter addthis_pill_style"></a>-->
                            </div>
                            <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e70275244deb51b"></script>
                            <!-- AddThis Button END -->
                        </div>
                    </div>
                    <div class="cb h15"><!----></div>
<?php wp_reset_postdata(); ?>

					<?php comments_template('', true);?>
                    <div class="cb h25"><!----></div>
	</div>
<style>

:focus {/* remember to define focus styles! */
	outline: 0;
}

body {
	line-height: 1;
}
.selected-profile{
	z-index: 1000;
	background: white;
}

.selected-profile table {
	border-collapse: separate;
	border-spacing: 0;
	border: 0;
	color: #1e1e1e;
}
.selected-profile table tr td{
	border: 0;
	height: 20px;
}

caption,
th,
td {
	font-weight: normal;
	text-align: left;
}

blockquote:before,
blockquote:after,
q:before,
q:after {
	content: "";
}
blockquote,
q {
	quotes: "" "";
}

a img {
	border: 0;
}




/*----------------------------------------------------------------------- 
    Basic Elements
 ------------------------------------------------------------------------ */
html, 
.center-col {
	height: 100%; /* needed so overlay covers the entire page */
}

.center-col {
	color: #636363;
	font: normal 11px/14px Verdana, Arial, sans-serif; /* standard font for all pages unless listed differently below */
}



h2 {
	color: #505050;
	font: bold 12px/15px Verdana;
}

h3 {
	color: #3d79b5;
	font: bold 11px/14px Verdana;	
}

h4 {
	color: #505050;
	font: bold 11px/14px Verdana;	
}

strong {
	font-weight: bold;
}

em {
	font-style: italic;
}

pre {
	background-color: #f8f8f8;
	border: 1px solid #ccc;
	border-radius: 3px;
	font-size: 13px;
	line-height: 19px;
	margin-bottom: 15px;
	overflow: auto;
	padding: 6px 10px;
	width: 100%;
}

pre code {
	background: transparent;
	border: none;
	margin: 0;
	padding: 0;
	white-space: pre;
}

code {
	margin: 0 2px;
	padding: 0 5px;
	white-space: nowrap;
	border: 1px solid #eaeaea;
	background-color: #f8f8f8;
	border-radius: 3px;
}

/*----------------------------------------------------------------------- 
    Sub-Pages Structure
 ------------------------------------------------------------------------ */
.subpage .content {
	padding-top: 93px;
}

.left-col {
	float: left;
	margin: 33px 23px 0 38px;
	width: 188px;
}

.right-col {
	float: right;
	margin: 0;
	padding: 0;
}

.center-col {
	float: left;
	margin: 0 15px 0 0;
	position: relative;
}

.two-col .center-col {
	width: 495px;
}

.three-col .center-col {
	width: 670px;
}

.lg-photo .center-col,
.profiles .center-col,
.profile-individual .center-col {
	margin: 0 0 0 1px;
	width: 730px;
}

.lg-photo .caption {
	width: 210px;
}

.interactive .center-col {
	width: 475px;
}

/*----------------------------------------------------------------------- 
    Sub-Pages Styles
 ------------------------------------------------------------------------ */
.left-col li ul {
	border-left-color: #3d79b5;
	border-left-style: solid;
	border-left-width: 1px;
	margin-left: 7px;
}

.left-col li ul li {
	font-size: 12px;
	margin-left: 7px;
}

.left-col a {
	color: #7d7d7d;
	display: block;
	line-height: 19px;
	margin-bottom: 2px;
}

.left-col a:hover {
	color: #3d79b5;
	text-decoration: none;
}

.left-col .current {
	color: #3d79b5;
	font-weight: bold;
}

.center-col h1 + p,
.content-left p:first-child,
.intro {
	font: 12px/16px Verdana, Arial, sans-serif;
	margin-top: 0;
}

.center-col h2 + p {
	margin-top: 5px;
}

.center-col p {
	margin: 15px 0px;
}

.center-col h2 {
	margin-top: 20px;
}

.center-col ul {
	list-style: disc none outside;
	margin: 5px 0 15px 25px;
}

.center-col li {
	margin-bottom: 5px;
}


/*-- Expandable Content --*/
h2 + h3.accordion {
	margin-top: 10px;
}

h3.accordion {
	background: url(//haas.berkeley.edu/images/template-images/sprites.png) 0 -114px no-repeat;
	color: #505050;
	cursor: pointer;
	display: block;
	font-size: 11px;;
	line-height: 13px;
	margin-top: 10px;
	text-indent: 15px;
}

h3.accordion:hover,
h3.accordion:active {
	background-position: 0 -174px;
}

h3.selected {
	background-position: 0 -220px;
}

h3.selected:hover,
h3.selected:active {
	background-position: 0 -266px;
}

h3.accordion + div {
	line-height: 13px;
	margin-top: 10px;
}

.center-col h3.accordion + div p {
	font-size: 11px;
	line-height: 13px;
}

h3.accordion + div ul {
	margin-top: 0;
}

h3.accordion + div h3,
h3.accordion + div h3 + div {
	margin-left: 10px;
}

/*-- Whitebox --*/
.whitebox {
	background-color: #fff;
	border: 1px 0 0 1px solid #f7f7f7; 
	margin-top: 25px;
	-moz-box-shadow: 3px 3px 10px -2px #d4d4d4; 
	-webkit-box-shadow: 3px 3px 10px -2px #d4d4d4; 
	box-shadow: 3px 3px 10px -2px #d4d4d4; 
	-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=9, Direction=135, Color='#CCCCCC')"; 
	filter: progid:DXImageTransform.Microsoft.Shadow(Strength=9, Direction=135, Color='#CCCCCC'); 
}

.whitebox a:hover {
	color: #7d7d7d;
}

.center-col .whitebox {
	padding: 20px 18px;
	width: 380px;
}

.right-col .whitebox {
	padding: 6px 10px;
	width: 195px;
}

.right-col .whitebox p {
	margin: 10px 0;
}

.center-col .whitebox h2 {
	color: #3d79b5;
	font: bold 16px Verdana;
	letter-spacing: .1em;
	margin-top: 0;
}

.caption-col .whitebox {
	margin: 0;
	padding: 15px 12px;
	width: 196px;
}

/*--- Large Photo with Caption ---*/
img.lg-photo {
	background-color: #ccc;
	height: 320px;
	margin-bottom: 20px;
	width: 730px;
}

.content-left {
	float: left;
	width: 425px;
}

.caption-col {
	float: left;
	font-family: Verdana, Arial, sans-serif;
	margin-left: 40px;
	width: 220px;
}

.caption-col .whitebox h2 {
	color: #636363;
	font: bold 12px/12px Verdana;
	letter-spacing: 0;
	margin: 0;
}

.caption-col .whitebox .job-title {
	color: #7d7d7d;
	font: bold 9px/11px Verdana;
}

.caption-col .whitebox ul {
	list-style: none;
	margin: 0;
}

.caption-col .whitebox .hasBullets {
	list-style: disc outside none;
	margin-top: 10px;
}

.caption-col .whitebox .hasBullets li {
	float: none;
	margin-left: 15px;
}

.caption-col .whitebox .hasBullets li a {
  color: #3d79b5;
  display: inline;
  margin-left: 0;
}

.caption-col .whitebox .hasBullets li a:hover {
  font-weight: normal;
}

.caption-col .whitebox .hasBullets li:first-child a {
	border: none;
	width: auto;
}

/*--- Interactive Element ---*/
.interactive-element {
	background-color: #ccc;
	height: 430px;
	width: 980px;
}

.content.interactive {
	background: none !important;
	padding-top: 0;
}


/*----------------------------------------------------------------------- 
    [First-Person] Profiles
 ------------------------------------------------------------------------ */

/*--- Applies to the Profiles pages ---*/
.profiles .center-col {
	background: #fff url(//haas.berkeley.edu/images/template-images/bg_profiles_page.jpg) top right repeat-y;
	min-height: 520px;
}

.people {
	float: left;
	margin: 0 0 29px;
	width: 505px;
}

.selected-profile {
	float: left;
	width: 196px;
	padding: 10px 20px 10px 20px;
	width: 371px;
	border: solid 1px #ddd;
	text-align: center;
	margin-bottom: 20px;
}

.person {
	display: inline;
	float: left;
	height: 200px;
	overflow: hidden;
	width: 167px;
	color: #1e1e1e;
}

.person a:hover {
	text-decoration: none;
}

.person img {
	height: 135px;
	margin-bottom: 8px;
	width: 135px;
}

.person a:hover img {
	height: 140px;
	width: 140px;
	-webkit-transition: all 0.2s;
	-moz-transition: all 0.2s;
	-o-transition: all 0.2s;
	transition: all 0.2s;
}

.person p {
	font: bold 11px/13px verdana;
	margin: 0;
	line-height: 1.5em;
}

.person p span {
	font-weight: normal;
}

.selected-profile img {
	margin-bottom: 16px;
}

.profile-box img {
	margin-bottom: 20px;
}

.selected-profile h3,
.profile-box h3 {
	font: bold 12px/11px Verdana;
	line-height: 1.5em;
}

.selected-profile p,
.profile-box p {
	font-size: 11px;
	line-height: 1.5em;
	margin: 15px 0 0;
	text-align: left;
	
}

.profile-box p {
	line-height: 12px;
}

.selected-profile .job-title,
.profile-box .job-title {
	font-weight: bold;
	line-height: 13px;
}

.selected-profile .read-on {
	margin: 12px 0;
}

.selected-profile a {
	color: #000;
}

.prev-next {
	background: #000 url(//haas.berkeley.edu/images/template-images/bg_prev-next.jpg) no-repeat;
	bottom: 0;
	left: 0;
	clear: left;
	height: 29px;
	position: absolute;
	width: 449px;
}

.prev-next a {
	color: #fff;
	display: block;
	float: right;
	font-weight: bold;
	margin-right: 10px;
	margin-top: 7px;
}

.prev-next a:hover {
	color: #ffcb06;
	text-decoration: none;
}

.prev-next a.prev {
	background: url(//haas.berkeley.edu/images/template-images/profiles-previous-arrows.png) no-repeat 0 1px;
	text-indent: 10px;
	width: 65px;
}

.prev-next a:hover.prev {
	background-position: 0 -13px;
}

.prev-next a.next {
	background: url(//haas.berkeley.edu/images/template-images/profiles-next-arrows.png) no-repeat 33px 1px;
	width: 41px;
}

.prev-next a:hover.next {
	background-position: 33px -13px;
}

/*--- Individual Profile Page ---*/

.profile-individual .center-col {
	margin-top: 40px;
}

.profile-box {
	background: url(//haas.berkeley.edu/images/template-images/bg_profile_2nd.jpg) repeat-y;
	float: left;
	margin-right: 20px;
	padding: 20px 15px;
	width: 195px;
}

.profile-box ul {
	margin: 15px 0 0 3px;
}

.ie8 .profile-box ul {
	width: 195px;
}

.profile-box li {
	float: left;
	list-style: none; 
}

.profile-box li a {
	border-right: 1px solid #111;
	color: #111;
	font-size: 10px;
	line-height: 11px;
	padding: 0 9px;
}

.ie8 .profile-box li a {
	padding: 0 5px;
}

.profile-box li:first-child a {
	padding-left: 0;
}

.profile-box li.last a {
	border: none;
	padding-right: 0;
}

.profile-box li a:hover {
	font-weight: bold;
	text-decoration: none;
}

.profile-story {
	float: left;
	margin-top: 20px;
	width: 425px;
}

.profile-story p:first-child {
	color: #707070;
	font-size: 14px;
	line-height: 20px;
}

/*--- Applies to sidebar profiles throughout site ---*/
.profile {
	padding: 20px 15px;
}

.homepage .profile {
	background: url(//haas.berkeley.edu/images/template-images/bg_profile_home.jpg) repeat-y;
	width: 240px;
}

.subpage .profile {
	background: url(//haas.berkeley.edu/images/template-images/bg_profile_2nd.jpg) repeat-y;
	width: 195px;
}

.profile h2,
.selected-profile h2,
.profile-box h2 {
	color: #636363;
	font: normal 10px 'LinotypeUniversW01-Blac 723739', Verdana, sans-serif;
	letter-spacing: 2px;
	margin-top: 0;
	margin-bottom: 8px;
	text-transform: uppercase;
}

.profile h2:before,
.selected-profile h2:before,
.profile-box h2:before { 
	content: '[ ';
	color: #3d79b5;
	font-weight: normal;
}

.profile h2:after,
.selected-profile h2:after,
.profile-box h2:after { 
	content: ' ]';
	color: #3d79b5;
	font-weight: normal;
}

.selected-profile h2 {
	margin: 21px 0 8px;
}

.profile img {
	margin: 4px 0 18px;
}

.homepage .profile img {
	height: 185px;
	max-width: 240px;
}

.subpage .profile img {
	max-width: 195px;
}

.profile hgroup {
	margin-bottom: 0;
}

.profile h3 {
	color: #636363;
	font: bold 12px/12px Verdana;
}

.profile p {
	color: #7d7d7d;
	font: normal 11px/14px Verdana;
	margin-top: 10px;
}

.profile .job {
	font-weight: bold;
}

.profile li,
.caption-col .whitebox li {
	float: left;
}

.profile .social-list li {
	float: none;
	height: 17px;
	text-indent: 20px;
}

.profile .social-list li:first-child a {
	border: none;
	width: auto;
}

.profile .social-list li a {
	margin-left: 0;
}

.profile .social-list li a:hover {
	font-weight: normal;
	text-decoration: underline;
}

.profile a {
	color: #000;
}

.profile li a,
.caption-col .whitebox li a {
	color: #000;
	display: block;
	font: normal 10px/11px Verdana;
	margin-left: 10px;
	margin-top: 10px;
}

.profile li a:hover,
.caption-col .whitebox li a:hover {
	font-weight: bold;
}

.profile li:first-child a,
.caption-col .whitebox li:first-child a {
	border-right: 1px solid #000;
	margin-left: 0;
	width: 55px;
}

/*----------------------------------------------------------------------- 
    Helper Classes
 ------------------------------------------------------------------------ */
/* Contain floats: nicolasgallagher.com/micro-clearfix-hack/ */ 
.clearfix:before,
.clearfix:after,
.ui-helper-clearfix:before,
.ui-helper-clearfix:after,
.content:before,
.content:after { content: ""; display: table; }

.clearfix:after,
.ui-helper-clearfix:after,
.content:after { clear: both; }

.clearfix,
.ui-helper-clearfix,
.content { zoom: 1; }

.alignleft {
	float: left;
	margin: 0 7px 7px 0;
}

.alignright {
	float: right;
	margin: 0 0 7px 7px;
}
.driver-info-container{ padding: 0 10px 0 30px; }
</style>
</div>
<?php
get_footer();
?>
