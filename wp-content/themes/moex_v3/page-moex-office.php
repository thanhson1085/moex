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
                    <div class="khungAnhMember"><img src="<?php echo get_bloginfo("template_url")?>/pic/member/user1.jpg" /></div>
                    <div class="right">
                        <span class="ctext fwb">Nguyễn Thị Hương Ly</span> - <span class="date">1 giờ trước</span><br />                                                Email: cogaikhongbietyeu@yahoo.com<br />                        Mình thấy dịch vụ này khá hay, mình muốn mua sản phẩm tivi Samsung LED 42” ở Siêu thị điện máy Topcare. Các bạn mua và gửi về địa chỉ: Số 123 Xuân Thủy, Cầu Giấy, Hà Nội. Cám ơn.
                    </div>
                    <div class="cb"><!----></div>
                    <div class="traloi">
                        <div class="arrow"><!----></div>
                        <div class="khungAnh2"><img src="<?php echo get_bloginfo("template_url")?>/pic/member/moex.jpg" /></div>
                        <div class="right2">                            
                            <span class="ctext fwb">Công ty moEx</span><br />
                            Cám ơn Hương Ly đã sử dụng dịch vụ của moEx. Bên mình sẽ mua và giao hàng tận nơi cho bạn. Chúc bạn 1 ngày vui !                            
                        </div>
                        <div class="cb"><!----></div>
                    </div>
                    <div class="cb h15"><!----></div>
                    <div class="khungAnhMember"><img src="<?php echo get_bloginfo("template_url")?>/pic/member/user2.jpg" /></div>
                    <div class="right">
                        <span class="ctext fwb">Nguyễn Thị Hương Tràm</span> - <span class="date">30 phút trước</span><br />                                                Email: tieuthudanhda@gmail.com<br />                        Mình muốn mua một bộ váy vải trơn tại địa chỉ: Shop Huda, 125 Hàng Giầy, Hai Bà Trưng, Hà Nội. Nhờ moEx mua giúp mình nhé. Các bạn gửi về địa chỉ của mình: số 12 Nguyễn Trãi, Thanh Xuân, Hà Nội.
                    </div>
                    <div class="cb h15"><!----></div>
                    <div class="header ctext sdtext">Gửi bình luận</div>

                    <div class="btBrowse">
                        <div class="bgTextBox">
                            <div class="fl pl10 fsti lh18">Avatar:</div>
                            <div class="fl pl5">
                                <input id="tbAvatarText" type="text" style="width:180px;background:none;border:0"/>
                            </div>
                            <div class="cb"><!----></div>
                        </div>                        
                        <input id="tbAvatar" type="file" onchange="FillValueToTextControl(this,'tbAvatarText')"/>                        
                        <script type="text/javascript">
                            function FillValueToTextControl(scontrol, dcontrolid) {
                                document.getElementById(dcontrolid).value = scontrol.value;
                            }
                        </script>
                    </div>
                    <div class="cb h10"><!----></div>
                    <div class="boxcontent">
                        <div class="boxcontent2">
                            <div class="cb h10"><!----></div>
                            <div class="cot1c"><label for="tbHoTen">Họ tên:</label></div>
                            <div class="cot2c">
                                <input id="tbHoTen" type="text" class="textbox"/>
                            </div>
                            <div class="cb h8"><!----></div>
                            <div class="cot1c"><label for="tbEmail">Email:</label></div>
                            <div class="cot2c">
                                <input id="tbEmail" type="text" class="textbox"/>                    
                            </div>                         
                            <div class="cb h8"><!----></div>
                            <div class="cot1c"><label for="tbNoiDung">Nội dung</label></div>
                            <div class="cot2c">
                                <textarea id="tbNoiDung" cols="20" rows="2" class="textbox" style="height:70px" ></textarea>                    
                            </div>                                                            
                            <div class="cb h10"><!----></div> 
                        </div>
                    </div>    
                    <div class="pt10">
                        <a class="btOK" href="#"><span><span>Gửi</span></span></a>                    
                    </div>                
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
