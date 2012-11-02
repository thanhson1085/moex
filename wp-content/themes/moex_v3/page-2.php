<?php
get_header();
?>
    <div id="PageContent">
        <div id="LienHe">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>  
            <div class="tac lh18">
                <img src="<?php echo get_bloginfo("template_url")?>/cs/pic/LienHe/ten.jpg" /><br />
                <b>Địa chỉ:</b> Biệt thự C2-6, Khu đô thị Yên Hòa, Cầu Giấy, Hà Nội<br />                <b>Điện thoại:</b> 04 378 24239 - <b>oneCall:</b> 1900 56 56 36<br />                <b>Email:</b> sale@moex.vn - <b>Website:</b> moex.vn<br /><br />
Nếu quý khách có góp ý về công ty, vui lòng gửi thư về địa chỉ sale@moex.vn. Moex sẽ phản hồi lại quý khách sớm nhất. Xin trân trọng cảm ơn!
            </div>         
            <div class="cb h15"><!----></div>
            <div class="content">
                <div class="content2">
					<?php
					if ( have_posts() ) while ( have_posts() ) : the_post();
						the_content();
					endwhile;
					?>
                    <div class="cb h10"><!----></div> 
                </div>
            </div>
            <div class="cb h12"><!----></div> 
            <div class="tac cb">
                <a class="btOK" href="javascript:void(0)" onclick="submitform()"><span><span>Gửi thư</span></span></a>
                <a class="btOK" href="javascript:void(0)" onclick="reset()"><span><span>Huỷ bỏ</span></span></a>                
				<script type="text/javascript">
					function reset(){
						$('form.wpcf7-form input[type="text"]').each(function(){
							$(this).attr("value","");
						});
					}
					function submitform(){
						$("form.wpcf7-form").submit();
					}
				</script>
            </div>
            <div class="cb h15"><!----></div> 
        </div>
    </div>
<?php
get_footer();
?>
