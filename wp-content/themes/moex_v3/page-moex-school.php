   
<?php
	get_header();
?> 
    <div id="PageContent">
        <div id="MoexSchool">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/slide.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>                                   
            <div class="content">
               <div class="cot1">
                    <div class="header ctext sdtext">Dịch vụ và tiện ích</div>
                    <div class="dot">
                        <b>An tâm</b>: Đội ngũ lái xe moEx được tuyển chọn kỹ lưỡng và đào tạo chuyên nghiệp, luôn đặt sự an toàn của khách hàng lên hàng đầu
                    </div>
                    <div class="dot">
                        <b>Tiện lợi</b>: Chỉ cần 2 phút gọi đến tổng đài moEx, bạn sẽ có 26 tiếng mỗi ngày để để nâng cao chất lượng cuộc sống cho gia đình và bản thân
                    </div>
                    <div class="dot">
                        <b>Tiết kiệm</b>: Gia đình bạn sẽ tiết kiệm được chi phí và công sức khi đưa đón các bé mỗi ngày
                    </div>
                    <div class="dot">
                        <b>Chu đáo</b>: Với sự nhiệt tình và  tinh thần trách nhiệm cao nhân viên moEx sẽ đưa các em đi học đầy đủ và đúng giờ
                    </div>
                    <div class="dot">
                        <b>Tin cậy</b>:  Nhân viên moEx sẽ đưa các em đi học theo đúng lộ trình. Phụ huynh có thể theo dõi lộ trình đi lại của các em ngay tại website www.moEx.vn hoặc gọi đến tổng đài 1900 56 56 36
                    </div>
                    <div class="header ctext sdtext">Ý kiến bình luận</div>
                    <div class="khungAnhMember"><img src="<?php echo get_bloginfo("template_url");?>/pic/member/user1.jpg" /></div>
                    <div class="right">
                        <span class="ctext fwb">Nguyễn Thị Hương Ly</span> - <span class="date">1 giờ trước</span><br />                                                Email: cogaikhongbietyeu@yahoo.com<br />                        Mình thấy dịch vụ này khá hay, mình muốn mua sản phẩm tivi Samsung LED 42” ở Siêu thị điện máy Topcare. Các bạn mua và gửi về địa chỉ: Số 123 Xuân Thủy, Cầu Giấy, Hà Nội. Cám ơn.
                    </div>
                    <div class="cb"><!----></div>
                    <div class="traloi">
                        <div class="arrow"><!----></div>
                        <div class="khungAnh2"><img src="<?php echo get_bloginfo("template_url");?>/pic/member/moex.jpg" /></div>
                        <div class="right2">                            
                            <span class="ctext fwb">Công ty moEx</span><br />
                            Cám ơn Hương Ly đã sử dụng dịch vụ của moEx. Bên mình sẽ mua và giao hàng tận nơi cho bạn. Chúc bạn 1 ngày vui !                            
                        </div>
                        <div class="cb"><!----></div>
                    </div>
                    <div class="cb h15"><!----></div>
                    <div class="khungAnhMember"><img src="<?php echo get_bloginfo("template_url");?>/pic/member/user2.jpg" /></div>
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
                                <td><img src="<?php echo get_bloginfo("template_url");?>/pic/service/MoexSchool/b1.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 1:</span> Gọi 1900 56 56 36 đặt dịch vụ.</span></td>
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
</body>
</html>
