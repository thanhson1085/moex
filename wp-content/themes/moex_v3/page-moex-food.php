<?php
get_header();
?> 
    <div id="PageContent">
        <div id="MoexFood">
            <div id="mainAdv" style="background: url('<?php echo get_bloginfo("template_url")?>/pic/service/food.jpg') no-repeat;">
				<div class="form-count-container">
                    <div class="count-header ctext sdtext">Tính phí</div>
					<div class="countprice">
					<div class="form-row"><label>Quãng đường</label><input type="number" id="demo-distance" value="5"><span> km</span></div>
					<div class="form-row"><a class="btn-count" tabindex="5" href="javascript:void(0)" onclick="tinhcuoc()"><span><span>Tính cước</span></span></a></div>
					<div class="form-row"><label>Giá trị đơn hàng:</label><b><span id="result" class="ctext">49.500 VNĐ</span></b></div>
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
							demo_distance = $("#demo-distance").val();
							var rst = demo_distance*price_level;
							$("#result").html(rst.formatMoney(0,"",".", ",") + " VNĐ <br /> + 3% giá trị hàng hóa");
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
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexFood/b1.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 1:</span> Gọi <span class="ctext">1900 56 56 36</span> cho moEx biết món ăn yêu thích của bạn.</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocr">
                        <table>
                            <tr>                                
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 2:</span> nhân viên moEx nhận món ăn từ cửa hàng.</span></td>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexFood/b2.png" /></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocl">
                        <table>
                            <tr>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexFood/b3.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 3:</span> nhân viên moEx giao đồ cho khách.</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocr">
                        <table>
                            <tr>                                
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 4:</span> Thanh toán.</span></td>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexFood/b4.png" /></td>
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
