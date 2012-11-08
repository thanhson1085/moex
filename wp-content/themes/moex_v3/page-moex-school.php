   
<?php
	get_header();
?> 
    <div id="PageContent">
        <div id="MoexSchool">
            <div id="mainAdv">
            <div id="mainAdv" style="background: url('<?php echo get_bloginfo("template_url")?>/pic/service/school.jpg') no-repeat;">
				<div class="form-count-container">
                    <div class="count-header ctext sdtext">Tính phí</div>
					<div class="countprice">
					<div class="form-row"><label>Quãng đường</label><input type="number" id="demo-distance" value="5"><span> km/lần</span></div>
					<div class="form-row"><label>Số lần đưa đón</label><input type="number" id="demo-time" value="2"><span> lần/ngày</span></div>
					<div class="form-row"><label>Số ngày đón</label><input type="number" id="demo-date" value="20"><span> ngày/tháng</span></div>
					<div class="form-row"><a class="btn-count" tabindex="5" href="javascript:void(0)" onclick="tinhcuoc()"><span><span>Tính cước</span></span></a></div>
					<div class="form-row"><label>Giá trị đơn hàng:</label><b><span id="result" class="ctext">49.500 VNĐ</span></b></div>
					</div>
				</div>
            </div>
            </div>
            <div class="head"><!----></div>                                   
            <div class="content">
               <div class="cot1">
                    <?php
                    if ( have_posts() ) while ( have_posts() ) : the_post();
                        the_content();
                    endwhile;
                    ?>
					<script type="text/javascript">
						$(document).ready(function(){
							tinhcuoc();
						});
						function tinhcuoc(){
							demo_distance = $("#demo-distance").val()*$("#demo-time").val()*$("#demo-date").val();
							if (demo_distance <= 250 && demo_distance > 170){
								demo_distance =  170 + (demo_distance-170)*((100-7)/100);
							}
							if (demo_distance > 250){
								demo_distance = 
								demo_distance =  170 + (250-170)*((100-7)/100) + (demo_distance-250)*((100-10)/100);
							}
							var rst = Math.max((demo_distance*price_level),20000);
							$("#result").html(rst.formatMoney(0,"",".", ",") + " VNĐ");
						}
					</script>
                    <div class="header ctext sdtext">Ý kiến bình luận</div>
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
                    <div class="cb h5"><!----></div>
					<?php comments_template('', true);?>
               </div>                
               <div class="cot2">
					<div class="header ctext sdtext">moEx Online</div>
					<?php get_template_part('loop','online'); ?>
                    <div class="cb h15"><!----></div>
                    <div class="header ctext sdtext">Hướng dẫn sử dụng dịch vụ</div>
                    <div class="cb h5"><!----></div>
                    <div class="motbuoctl">
                        <table>
                            <tr>
                                <td><img src="<?php echo get_bloginfo("template_url");?>/pic/service/MoexSchool/b1.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 1:</span> Gọi <span class="ctext">1900 56 56 36</span> đặt dịch vụ.</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocr">
                        <table>
                            <tr>                                
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 2:</span> Lựa chọn lái xe do moEx cung cấp và thống nhất lịch đưa đón.</span></td>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexSchool/b2.png" /></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocl">
                        <table>
                            <tr>
                                <td><img src="<?php echo get_bloginfo("template_url");?>/pic/service/MoexSchool/b3.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 3:</span> moEx đưa đón các em mỗi ngày.</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocr">
                        <table>
                            <tr>                                
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 4:</span> Gia đình có thể theo dõi lộ trình đi lại của các em trực tiếp.</span></td>
                                <td><img src="<?php echo get_bloginfo("template_url");?>/pic/service/MoexSchool/b4.png" /></td>
                            </tr>
                        </table>
                    </div>
               </div>
               <div class="cb h15"><!----></div>
            </div>
        </div>
<?php
get_footer();
?>
