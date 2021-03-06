<?php
get_header();
$user_id = get_current_user_id();
$drivers = $wpdb->get_results(
				"
				SELECT * 
				FROM ".$wpdb->prefix."drivers
				"
			);
?>
<div class="driver-info-container" style="overflow: auto">
<div class="pb10">
	<span class="order-header" style="color:red; font-size: 28px;">Lái xe moEx</span>
 </div>
<div class="center-col">
        <div class="people clearfix">

		<?php
			foreach ($drivers as $driver):
		?>
          <div class="person" code="<?php echo $driver->id;?>">
				<?php $driver_id = $driver->id;?> 
				<?php $image = ($driver->image)?get_bloginfo("url")."/core/web/uploads/drivers/".$driver->image:get_bloginfo("template_url")."/pic/no-image.jpg";?>
            <a href="<?php echo get_bloginfo("url")?>/driver-info/?driver_id=<?php echo $driver->id?>">
              <img src="<?php echo $image;?>" alt="<?php echo $driver->driver_name;?>">
              <p><?php echo $driver->driver_name;?><br><span><?php echo $driver->driver_code;?></span></p>
            </a>
             <!-- /.short-profile -->
          </div> <!-- /.person -->
		<?php 
			endforeach;
		?>
        </div> <!-- /.people -->
        <div class="selected-profile">
		<?php
			foreach ($drivers as $driver):
		?>
			
        	<div id="<?php echo $driver->id;?>" class="short-profile" style="display: none;">
				<?php $image = ($driver->image)?get_bloginfo("url")."/core/web/uploads/drivers/".$driver->image:get_bloginfo("template_url")."/pic/no-image.jpg";?>
              <img src="<?php echo $image;?>" alt="<?php echo $driver->driver_name;?>" style="width: 195px; height: 195px;">
              <h3><?php echo $driver->driver_name;?><br><span><?php echo $driver->driver_code;?></span></h3>
              <p class="job-title"><?php echo $driver->position;?>
				<br />
				<?php echo $driver->driver_age;?>
			  </p>
			<p><?php echo ($driver->driver_info)?'"'.$driver->driver_info.'"':'';?></p>
            </div>
		<?php
			endforeach;
		?>
		<script type="text/javascript">
			$("#<?php echo $driver_id;?>").css("display","block");
			$(document).ready(function(){
				$(".person").hover(function(){
					$(".short-profile").each(function(){
						$(this).css("display", "none");
					});
					$("#" +$(this).attr("code")).css("display","block");
				});
			});
		</script>
		</div> <!-- /.selected-profile -->
</div>
<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	border: 0;
	font-family: inherit;
	font-size: 100%;
	font-style: inherit;
	font-weight: inherit;
	margin: 0;
	outline: 0;
	padding: 0;
	vertical-align: baseline;
}

:focus {/* remember to define focus styles! */
	outline: 0;
}

body {
	line-height: 1;
}

ol,
ul {
	list-style: none;
}

table {/* tables still need 'cellspacing="0"' in the markup */
	border-collapse: separate;
	border-spacing: 0;
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

a:link, a:visited {
    color: rgb(61, 121, 181);
    text-decoration: none;
}

article, 
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
menu,
nav,
section {
	display: block;
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

a:link,
a:visited {
	color: #3d79b5;
	text-decoration: none;
}

a:hover,
a:active {
	color: #000;
	text-decoration: underline;
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
	width: 670px;
}

.selected-profile {
	float: left;
	width: 196px;
	padding: 10px 20px 10px 20px;
	width: 202px;
	border: solid 1px #ddd;
	text-align: center;
}

.person {
	display: inline;
	float: left;
	height: 191px;
	overflow: hidden;
	width: 167px;
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
	color: #636363;
	font: bold 11px/13px verdana;
	margin: 0;
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
	color: #636363;
	font: bold 12px/11px Verdana;
}

.selected-profile p,
.profile-box p {
	color: #7d7d7d;
	font-size: 11px;
	line-height: 13px;
	margin: 15px 0 0;
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
