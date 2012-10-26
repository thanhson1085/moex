<?php
get_header();
?> 
    <div id="PageContent">
        <div id="MoexFood">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/slide.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>                                   
            <div class="content">
               <div class="cot1">
                    <div class="header ctext sdtext">Dịch vụ và tiện ích</div>
                    <div class="dot">
                        <b>Chủ động</b>: Khi bạn không có thời gian mua sắm,  moEx sẽ giúp bạn có ngay những thứ bạn cần
                    </div>
                    <div class="dot">
                        <b>Tiện lợi</b>: Tất cả những việc bạn cần làm là đăng ký trên moex.vn hay gọi 1900 56 56 36, và moEx sẽ thu xếp mọi chuyện còn lại
                    </div>
                    <div class="dot">
                        <b>Đảm bảo</b>: moEx chăm sóc bạn như một người thân trong gia đình, và luôn đảm báo mua đúng món hàng bạn cần
                    </div>
                    <?php
                    if ( have_posts() ) while ( have_posts() ) : the_post();
                        the_content();
                    endwhile;
                    ?>
                    <div class="header ctext sdtext">Ý kiến bình luận</div>
					<?php comments_template('', true);?>
               </div>                
               <div class="cot2">
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
