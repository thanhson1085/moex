<?php
/*
Template Name: Login Page
*/
?>
<?php
get_header();
?>
    <div id="PageContent">
        <div id="DangKy" class="mh2">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url");?>/pic/adv/slide.jpg" class="anhQC"/></a>                
            </div>
            <div class="head1"><!----></div>                       
            <div class="cb h25"><!----></div>
            <div class="content">
                <div class="cot3">Email</div>
				<form name="loginform" id="loginform" action="<?php echo get_option('home'); ?>/wp-login.php" method="post">
                <div class="fl">
                    <input id="tbEmail" type="text" class="textbox" name="log" style="width:210px"/>
                </div>
                <div class="cb h12"><!----></div>
                <div class="cot3">Mật khẩu</div>
                <div class="fl">
                    <input id="tbMatKhau" type="password" class="textbox" name="pwd" style="width:210px"/>                    
                </div>              
                <div class="cb h12"><!----></div>   
                <div class="cot3">&nbsp;</div>
                <div class="fl">
                    <a class="btOK" href="javascript:void(0)" onclick="submitform()"><span><span>Đăng nhập</span></span></a>
                    <a class="help" href="#">Cần trợ giúp?</a>                    
                </div>
				</form>
				<script type="text/javascript">
				function submitform()
				{
				  document.loginform.submit();
				}
				</script>
                <div class="cb"><!----></div> 
            </div>
        </div>
	</div>
    <script type="text/javascript">
        //#OnCall
        window.onscroll = DisalbeRightAdv;
        window.onresize = DisalbeRightAdv;
        //Gọi hàm lần đầu
        DisalbeRightAdv();       
        function DisalbeRightAdv() {
            //Kiểm tra điều kiện width
            var show1 = false;   
            var khungQCT = $('#OnCall');
            //980 là chiều rộng của website
            //Ẩn quảng cáo nếu chiều rộng của trình duyệt nhỏ hơn chiều rộng website + chiều rộng quảng cáo phải
            if (GetWindowWidth() < (980 + khungQCT.outerWidth()))
                show1 = false;
            else
                show1 = true;

            //Kiểm tra điều kiện scroll
            var show2 = false;
            if (f_scrollTop() > $("#Header").outerHeight())
                show2 = true;
            else
                show2 = false;

            if (show1 && show2)
                khungQCT.fadeIn();
            else
                khungQCT.fadeOut();
        }     

        function f_clientWidth() {
            return f_filterResults(
		        window.innerWidth ? window.innerWidth : 0,
		        document.documentElement ? document.documentElement.clientWidth : 0,
		        document.body ? document.body.clientWidth : 0
	        );
        }
        function f_clientHeight() {
            return f_filterResults(
		        window.innerHeight ? window.innerHeight : 0,
		        document.documentElement ? document.documentElement.clientHeight : 0,
		        document.body ? document.body.clientHeight : 0
	        );
        }
        function f_scrollLeft() {
            return f_filterResults(
		        window.pageXOffset ? window.pageXOffset : 0,
		        document.documentElement ? document.documentElement.scrollLeft : 0,
		        document.body ? document.body.scrollLeft : 0
	        );
        }
        function f_scrollTop() {
            return f_filterResults(
		        window.pageYOffset ? window.pageYOffset : 0,
		        document.documentElement ? document.documentElement.scrollTop : 0,
		        document.body ? document.body.scrollTop : 0
	        );
        }
        function f_filterResults(n_win, n_docel, n_body) {
            var n_result = n_win ? n_win : 0;
            if (n_docel && (!n_result || (n_result > n_docel)))
                n_result = n_docel;
            return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
        }
    </script>
<?php 
get_footer();
?>
