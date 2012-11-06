<?php
	get_header();
?>
    <div id="PageContent">
        <div id="MoexGo">
            <div id="mainAdv" style="background: url('<?php echo get_bloginfo("template_url")?>/pic/service/go.jpg') no-repeat;">
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
					<ul class="moex-online">
						<li><ul>
						<li><a href="ymsgr:sendIM?cskhmoex"><img border="0" src="http://mail.opi.yahoo.com/online?u=cskhmoex&m=g&t=0"> CSKH moEx 1</a></li>
						<li><a href="ymsgr:sendIM?mjnmin_bexinh_9x"><img border="0" src="http://mail.opi.yahoo.com/online?u=mjnmin_bexinh_9x&m=g&t=0"> CSKH moEx 2</a></li>
						</ul></li>
						<li><ul>
						<li><a href="ymsgr:sendIM?becon_co_duoi2610"><img border="0" src="http://mail.opi.yahoo.com/online?u=becon_co_duoi2610&m=g&t=0"> CSKH moEx 3</a></li>
						<li><a href="ymsgr:sendIM?small_angel140291"><img border="0" src="http://mail.opi.yahoo.com/online?u=small_angel140291&m=g&t=0"> CSKH moEx 4</a></li>
						</ul></li>
					</ul>
                    <div class="cb h15"><!----></div>
                    <div class="header ctext sdtext">Hướng dẫn sử dụng dịch vụ</div>
                    <div class="cb h5"><!----></div>
                    <div class="motbuoctl">
                        <table>
                            <tr>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexGo/b1.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 1:</span> Gọi <span class="ctext">1900 56 56 36</span> cho moEx biết điểm đến của bạn.</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocr">
                        <table>
                            <tr>                                
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 2:</span> moEx thông báo cước vận chuyển, và đón bạn trong 10 phút.</span></td>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexGo/b2.png" /></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocl">
                        <table>
                            <tr>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexGo/b3.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 3:</span> Vui lòng thanh toán cho người lái xe theo đúng mức cước đã thống nhất.</span></td>
                            </tr>
                        </table>
                    </div>                   
               </div>
               <div class="cb h15"><!----></div>
            </div>
        </div>
    </div>    
<?php
get_footer();
?>
