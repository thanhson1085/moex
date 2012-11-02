<?php
/*
Template Name: About Us Page
*/
?>
<?php
	get_header();
?>
        <div id="GioiThieu">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
            <div class="content">
                <div class="head"><!----></div>                       
                <div class="cot1">
					<?php
					if ( have_posts() ) while ( have_posts() ) : the_post();
						the_content();
					endwhile;
					?>
                    <div>
                        <a class="btOK" href="<?php echo get_bloginfo("url")?>?page_id=177"><span><span><span class="bt1">Cuộc sống với Moex</span></span></span></a>
                        <a class="btOK" href="http://www.facebook.com/MoexJsc" target="_blank"><span><span><span class="bt2">Blog Moex</span></span></span></a>
                    </div>
                </div>
                <div class="cot2">
                    <div id="fb-root"></div>
                    <script>
                        (function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
                            fjs.parentNode.insertBefore(js, fjs);
                        } (document, 'script', 'facebook-jssdk'));
                    </script>
                    <div id="facebookLikebox">    
                        <div class="fb-like-box" data-href="http://www.facebook.com/MoexJsc" data-width="293" data-height="275" data-show-faces="true" data-border-color="#dcdcdc" data-stream="false" data-header="false"></div>    
                    </div>
                </div>
                <div class="cb h18"><!----></div>   
				<?php /*
                <div class="khungGioiThieu">
                    <div class="tab">
                        Đội ngũ ban lãnh đạo
                        <a class="next">&nbsp;</a>
                        <a class="prev">&nbsp;</a>
                    </div>             
                    <div class="jCarouselLiteGioiThieu">                    
                        <ul>
                            <li>
                                <div class="motmuc">
                                    <div class="khungAnh">
                                        <img src="<?php echo get_bloginfo("template_url")?>/pic/gioithieu/p1.jpg" />
                                        <div class="nenAnh"><!----></div>
                                    </div>
                                    <div class="tac">
                                        <b>Nguyễn Trung Thành</b><br />
                                        Giám đốc
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="motmuc">
                                    <div class="khungAnh">
                                        <img src="<?php echo get_bloginfo("template_url")?>/pic/gioithieu/p2.jpg" />
                                        <div class="nenAnh"><!----></div>
                                    </div>
                                    <div class="tac">
                                        <b>Nguyễn Văn Phong</b><br />
                                        Phó giám đốc
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="motmuc">
                                    <div class="khungAnh">
                                        <img src="<?php echo get_bloginfo("template_url")?>/pic/gioithieu/p3.jpg" />
                                        <div class="nenAnh"><!----></div>
                                    </div>
                                    <div class="tac">
                                        <b>Triệu Thị Liên</b><br />
                                        Trưởng phòng nhân sự
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="motmuc">
                                    <div class="khungAnh">
                                        <img src="<?php echo get_bloginfo("template_url")?>/pic/gioithieu/p4.jpg" />
                                        <div class="nenAnh"><!----></div>
                                    </div>
                                    <div class="tac">
                                        <b>Nguyễn Lan Hương</b><br />
                                        Trưởng phòng Marketing
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="motmuc">
                                    <div class="khungAnh">
                                        <img src="<?php echo get_bloginfo("template_url")?>/pic/gioithieu/p5.jpg" />
                                        <div class="nenAnh"><!----></div>
                                    </div>
                                    <div class="tac">
                                        <b>Nguyễn Đức Trường</b><br />
                                        Trưởng phòng Sales
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="motmuc">
                                    <div class="khungAnh">
                                        <img src="<?php echo get_bloginfo("template_url")?>/pic/gioithieu/p6.jpg" />
                                        <div class="nenAnh"><!----></div>
                                    </div>
                                    <div class="tac">
                                        <b>Triệu Thị Liên</b><br />
                                        Phó phòng nhân sự
                                    </div>
                                </div>
                            </li>
                        </ul>                    
                    </div>
                    <script type="text/javascript">
                         $(function () {//DisplaySubServiceCategories
                             $(".jCarouselLiteGioiThieu").jCarouselLite({
                                 btnNext: ".next", btnPrev: ".prev", auto: 6000, speed: 500, visible: 5, scroll: 1, vertical: false
                             });
                         });
                    </script>
                </div>
				*/
				?>
                <div class="cb h12"><!----></div>               
            </div>            
        </div>
<?php
	get_footer();
?>
