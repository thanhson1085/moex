<?php
	get_header();
?>
    <div id="PageContent">
        <div id="MoexGo">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/slide.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>                                   
            <div class="content">
               <div class="cot1">
                    <div class="header ctext sdtext">Dịch vụ và tiện ích</div>
                    <div class="dot">
                        <b>Nhanh chóng</b>: Sau khi bạn gọi 1900 56 56 36, moEx sẽ đón bạn trong vòng 10 phút
                    </div>
                    <div class="dot">
                        <b>Thuận tiện:</b>: Dù bạn đang ở địa điểm nào, chỉ cần gọi, moEx sẽ đến tận nơi để phục vụ bạn 
                    </div>
                    <div class="dot">
                        <b>Đơn giản</b>: mức cước luôn được moEx thống nhất trước, nên bạn không cần mất thời gian mặc cả  
                    </div>
                    <div class="dot">
                        <b>Lịch sự</b>: Đội ngũ lái xe moEx được trang bị đồng phục và mũ bảo hiểm an toàn, với thái độ tận tình và luôn mong ước mang lại cho bạn niềm vui khi di chuyển  
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
