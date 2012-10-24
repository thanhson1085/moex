<?php 
get_header();
?>
    <div id="PageContent">
        <div id="MoexOffice">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/slide.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>                                   
            <div class="content">
               <div class="cot1">
                    <div class="header ctext sdtext">Dịch vụ và tiện ích</div>
                    <div class="dot">
                        <b>Tốc độ</b>: moEx hiểu trong kinh doanh thời gian là tiền bạc, mọi tài liệu và hàng hóa của bạn sẽ được chuyển tời đích trong vòng 2h từ khi moEx nhận hàng
                    </div>
                    <div class="dot">
                        <b>An toàn</b>: mọi nhân viên của moEx đều được đào tạo chu đáo và biết cách bảo vệ tài sản của bạn như chính bản thân họ
                    </div>
                    <div class="dot">
                        <b>Bảo mật</b>: Hàng hóa của bạn sẽ được chuyển tới tận tay người nhận, mà không qua bất cứ người trung gian nào, bạn cũng có thể kiểm soát hàng hóa trong suốt quá trình vận chuyển 
                    </div>
                    <div class="header ctext sdtext">Ý kiến bình luận</div>
					<?php comments_template('', true);?>
               </div>                
               <div class="cot2">
                    <div class="header ctext sdtext">Hướng dẫn sử dụng dịch vụ</div>
                    <div class="cb h5"><!----></div>
                    <div class="motbuoctl">
                        <table>
                            <tr>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexOffice/b1.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 1:</span> Gọi 1900 56 56 36 thông tin hàng hóa và đích đến.</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocr">
                        <table>
                            <tr>                                
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 2:</span> moEx xác nhận thông tin cước vận chuyển và thời gian.</span></td>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexOffice/b2.png" /></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocl">
                        <table>
                            <tr>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexOffice/b3.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 3:</span> moEx nhận hàng và giao hàng trong vòng 2h.</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="motbuocr">
                        <table>
                            <tr>                                
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 4:</span> Theo dõi hàng hóa trong suốt quá trình vận chuyển.</span></td>
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexOffice/b4.png" /></td>
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
