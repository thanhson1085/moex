<?php
/*
Template Name: About Us Page
*/
?>
<?php
	get_header();
?>
        <div id="GioiThieu">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
            <div class="content">
                <div class="head"><!----></div>                       
                <div class="cot1">
					<?php
					if ( have_posts() ) while ( have_posts() ) : the_post();
						the_content();
					endwhile;
					?>
                    <div>
                        <a class="btOK" href="<?php echo get_bloginfo("url")?>?page_id=177"><span><span><span class="bt1">Cuộc sống với Moex</span></span></span></a>
                        <a class="btOK" href="http://www.facebook.com/MoexJsc" target="_blank"><span><span><span class="bt2">Blog Moex</span></span></span></a>
                    </div>
                    <div class="cb h15"><!----></div>
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/loi-ich.jpg'); height: 39px; width: 606px;"></div>
					<div>
						moEx cung cấp dịch vụ vận chuyển linh hoạt, cơ động bằng xe máy trong nội thành Hà Nội, nhằm giúp các bạn giải quyết những vấn đề trong cuộc sống hàng ngày:
						<ul>
							<li>Bạn có một hợp đồng cần chuyển gấp, mà không thể rời văn phòng</li>
							<li>Sếp vừa báo một cuộc họp gấp, lại trùng giờ bạn phải đón người thân chiều nay</li>
							<li>Hôm nay, nhà có khách mà bạn lại không có thời gian đi chợ</li>
							<li>Bên ngoài mưa và lạnh, mà bạn rất muốn thưởng thức một món ăn cho trưa hôm nay</li>
							<li>Văn phòng bạn không có chỗ gửi xe, và bạn muốn đi làm hàng ngày bằng xe máy</li>
						</ul>
						Bạn không nên bực bội,  hãy để moEx phục vụ bạn. Chỉ cần 2 phút gọi tới 1900 56 56 36, bạn sẽ có thể dành thời gian của mình vào những việc có ý nghĩa hơn.<br />

						moEx luôn sẵn sàng từ 6:30 đến 19:00 tất cả các ngày trong tuần (kể cả ngày lễ)<br />
					</div>

                    <div class="cb h15"><!----></div>
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/an-toan.png') no-repeat bottom left;padding-left: 120px;">
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/an-toan.jpg'); height: 37px; width: 447px;" class="moex-para"></div>
						Đội ngũ lái xe của moEx được tuyển chọn và đào tạo nghiêm túc:
						<ul>
							<li><strong>TUYỂN CHỌN: </strong>Tất cả các lái xe đều có lý lịch thân nhân tốt, có xác nhận của chính quyền đại phương và gia đình, giàu kinh nghiệm, nhiệt tình và trung thực.</li>
							<li><strong>ĐÀO TẠO</strong>: Đội ngũ lái xe sau khi được tuyển chọn, được moEx đào tạo qua những khóa học: lái xe an toàn, tác phong phục vụ khách hàng.  Những lái xe đạt tiêu chuẩn mới được cấp chứng chỉ đào tạo của moEx, và cung cấp dịch vụ.</li>
							<li><strong>PHẢN HỒI</strong>: Mỗi lái xe moEx đều có mã số nhân viên ghi rõ ràng trên áo phái trước và vai sau. Mỗi bạn muốn chia sẻ nhận xét của mình về người lái xe đó, moEx luôn lắng nghe</li>
						</ul>
					</div>
                    <div class="cb h15"><!----></div>
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/tien-loi.png') no-repeat bottom left;padding-left: 120px;">
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/tien-loi.jpg'); height: 37px; width: 447px;" class="moex-para"></div>
						<ul>
							<li><strong>NHANH:</strong> Lái xe moEx có mặt tại nhiều địa điểm trong thành phố, nhờ vậy, moEx luôn có thể cử được người gần bạn nhất để phục vụ,</li>
							<li><strong>ĐƠN GIẢN:</strong> Bạn chỉ cần gọi 1900.56.56.36, và để moEx lo mọi chuyện còn lại</li>
							<li><strong>MINH BẠCH:</strong> Mức giá luôn được moEx thống nhất ngay khi bạn yêu cầu dịch vụ, hoặc bạn có thể tự tính mức cước của mình trên ww.moex.vn, bạn không cần mặc cả hay lo lắng lái xe chạy lòng vòng</li>
						</ul>
					</div>
                    <div class="cb h15"><!----></div>
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/chu-dao.png') no-repeat bottom left;padding-left: 120px;min-height: 128px">
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/chu-dao.jpg'); height: 37px; width: 447px;" class="moex-para"></div>
						Với moEx, mọi công việc của bạn cũng là công việc của chính chúng tôi; moEx luôn chăm lo bạn như chăm lo chính một người thân trong gia đình mình. 
					</div>
                </div>
                <div class="cot2">
                    <div id="fb-root"></div>
                    <script>
                        (function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
                            fjs.parentNode.insertBefore(js, fjs);
                        } (document, 'script', 'facebook-jssdk'));
                    </script>
                    <div id="facebookLikebox">    
                        <div class="fb-like-box" data-href="http://www.facebook.com/MoexJsc" data-width="293" data-height="275" data-show-faces="true" data-border-color="#dcdcdc" data-stream="false" data-header="false"></div>    
                    </div>
                    <div class="cb h15"><!----></div>
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/online.jpg'); height: 44px; width: 310px;"></div>
                    <?php get_template_part('loop','online'); ?>
                    <div class="cb h15"><!----></div>
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/quy-trinh.jpg'); height: 34px; width: 313px;"></div>
					<div class="moex-para">
					<img src="<?php echo get_bloginfo('template_url')?>/pic/gioithieu/tong-dai.png" width="313px"/>
					<div style="padding-left: 100px;">
						1.	Gọi 1900 565636<br />
						2.	moEx báo giá dịch vụ<br />
						3.	moEx phục vụ khách hàng<br />
					</div>
					</div>
                    <div class="cb h15"><!----></div>
					<div style="background:url('<?php echo get_bloginfo('template_url') ?>/pic/gioithieu/viet-nam.jpg'); height: 34px; width: 313px;"></div>
					<div class="moex-para">
						moEx mong muốn những lái xe hợp tác với moEx được làm việc trong môi trường an toàn, được tôn trọng, và có thu nhập ổn định, đủ để chăm lo cho gia đình của họ. <br />
						moEx hi vọng bạn sẽ cùng moEx xây dựng một Việt Nam đẹp hơn từ những việc giản đơn hàng ngày. 
					</div>
                </div>
                <div class="cb h18"><!----></div>   
                <div class="cb h12"><!----></div>               
            </div>            
        </div>
<?php
	get_footer();
?>
