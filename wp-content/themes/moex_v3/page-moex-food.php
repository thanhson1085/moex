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
                    <div class="header ctext sdtext">Ý kiến bình luận</div>
                    <div class="khungAnhMember"><img src="<?php echo get_bloginfo("template_url")?>/pic/member/user1.jpg" /></div>
                    <div class="right">
                        <span class="ctext fwb">Nguyễn Thị Hương Ly</span> - <span class="date">1 giờ trước</span><br />                        
                    </div>
                    <div class="cb"><!----></div>
                    <div class="traloi">
                        <div class="arrow"><!----></div>
                        <div class="khungAnh2"><img src="<?php echo get_bloginfo("template_url")?>pic/member/moex.jpg" /></div>
                        <div class="right2">                            
                            <span class="ctext fwb">Công ty moEx</span><br />
                            Cám ơn Hương Ly đã sử dụng dịch vụ của moEx. Bên mình sẽ mua và giao hàng tận nơi cho bạn. Chúc bạn 1 ngày vui !                            
                        </div>
                        <div class="cb"><!----></div>
                    </div>
                    <div class="cb h15"><!----></div>
                    <div class="khungAnhMember"><img src="<?php echo get_bloginfo("template_url")?>/pic/member/user2.jpg" /></div>
                    <div class="right">
                        <span class="ctext fwb">Nguyễn Thị Hương Tràm</span> - <span class="date">30 phút trước</span><br />                        
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
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexFood/b1.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 1:</span> Gọi 1900 56 56 36 cho moEx biết món ăn yêu thích của bạn.</span></td>
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