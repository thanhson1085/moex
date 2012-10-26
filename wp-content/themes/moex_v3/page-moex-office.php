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
                                <td><img src="<?php echo get_bloginfo("template_url")?>/pic/service/MoexOffice/b1.png" /></td>
                                <td><span class="buoc"><span class="ctext sdtext">BƯỚC 1:</span> Gọi <span class="ctext">1900 56 56 36</span> thông tin hàng hóa và đích đến.</span></td>
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
