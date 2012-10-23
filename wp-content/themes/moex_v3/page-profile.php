<?php
get_header();
?>
    <div id="PageContent">
        <div id="QuanLyTaiKhoan">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/adv/slide.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>
            <div class="h5"><!----></div>
            <div class="content">
                <div class="pb15"><b>THAY ĐỔI THÔNG TIN TÀI KHOẢN:</b></div>
                <div class="cot1">Email</div>
                <div class="cot2">
                    <input id="tbEmail" type="text" class="textbox" style="width:290px" value="longnguyenpv@gmail.com"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Điện thoại</div>
                <div class="cot2">
                    <input id="tbDienThoai" type="text" class="textbox" style="width:210px" value="0982. 189.999"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Avatar</div>
                <div class="cot2">
                    <div class="btBrowse">
                        <input id="tbAvatarText" type="text" class="textbox" style="width:210px"/>
                        <input id="tbAvatar" type="file" onchange="FillValueToTextControl(this,'tbAvatarText')"/>                        
                        <script type="text/javascript">
                            function FillValueToTextControl(scontrol, dcontrolid) {
                                document.getElementById(dcontrolid).value = scontrol.value;
                            }
                        </script>
                    </div>                    
                </div>                
                <div class="cb h12"><!----></div>
                <div class="cot1">Địa chỉ nhận hàng</div>
                <div class="cot2">
                    <textarea id="tbDiaChiNhanHang" cols="20" rows="2" class="textbox" style="width:290px;height:55px">Số 1, ngách 44/45 Xuân Thủy, Dịch Vọng Hậu, Cầu Giấy, HàNội</textarea>                    
                </div>
                <div class="cb h12"><!----></div>
                <div class="pb15"><b>THAY ĐỔI MẬT KHẨU:</b></div>                   
                <div class="cot1">Mật khẩu</div>
                <div class="cot2">
                    <input id="tbMatKhau" type="password" class="textbox" style="width:210px" value="12345678"/>                    
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">Xác nhận mật khẩu</div>
                <div class="cot2">
                    <input id="tbXacNhanMatKhau" type="password" class="textbox" style="width:210px" value="12345678"/>                    
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot1">&nbsp;</div>
                <div class="cot2">
                    <a class="btOK" href="#"><span><span>Cập nhật</span></span></a>
                    <a class="btOK" href="#"><span><span>Huỷ bỏ</span></span></a>                    
                </div>
                <div class="cb h15"><!----></div> 
            </div>
        </div>
    </div>    
<?php
get_footer();
?>
