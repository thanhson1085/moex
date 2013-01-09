<?php
get_header();
?>
    <div id="PageContent">
        <div id="Blog">
			<?php
				$post_id = $post->ID;
			?>
				<div id="mainAdv">
					<a href="<?php echo get_bloginfo("url")?>/qua-tet/"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/Event_Banner_quatet.gif" class="anhQC"/></a>                
				</div>
			<?php
			?>
            <div class="content">
                <div class="cb h12"><!----></div>
				<?php
				if ( have_posts() ) while ( have_posts() ) : the_post();
				?>
                <div class="cot1">                                  
                    <div class="titledetail" style="font-size: 18px;"><?php the_title()?></div>
					<?php setPostViews($post->ID); ?>
                    <div class="date"><?php echo date_i18n( __( 'd/m/Y' ), strtotime( $post->post_date ) )?> - <?php echo getPostViews($post->ID);?> lượt xem</div>
                    <div class="lh18 fwb">
					<?php
                         //echo get_the_excerpt();
					?>
					</div>
                    <div class="cb pt10 lh20 taj">
						<div class="quatet">
							<span style="display: inline-block; width: 80px;">Mã: </span><span style="font-weight: bold; font-size: 13px; color: #1e1e1e"><?php echo get_post_meta($post->ID, 'ma_quatet', true);?></span>
                		<div class="cb h10"><!----></div>
							<span style="display: inline-block; width: 80px;">Giá: </span><span class="quatet-price" style="color: red;font-size: 16px;font-weight: bold"><?php echo get_post_meta($post->ID, 'gia_quatet', true);?></span>
                		<div class="cb h20"><!----></div>
						<span style="font-weight: bold">Bao gồm các sản phẩm:</span>
						<?php the_content();?>
						</div>
<style>
	.quatet table{
		border: 0;
	}
	.quatet table tr td{
		border: 0;
		font-size: 12px;
		width: 600px;
		height: 30px;
		border-bottom: solid 1px #ccc;
		padding-top: 7px;
	}
	.quatet table tr td:first-child{
		width: 5%;
	}
	
</style>
                    <div class="cb h20"><!----></div>
							<?php $img_id = get_post_meta($post->ID, 'anh_quatet', true);
								$img_url = wp_get_attachment_url( $img_id); 
							?>
							<?php $image = ($img_url)?$img_url:get_bloginfo("template_url")."/pic/no-image.jpg";?>
						  	<img style="max-width: 600px" src="<?php echo $image;?>" alt="<?php the_title();?>">
                    </div>
                    <div class="cb h20"><!----></div>
                    <div class="vientren"><!----></div>
                    <div class="fl">
                        <div class="fl">
                            <!-- AddThis Button BEGIN -->
                            <div class="addthis_toolbox addthis_default_style ">
                            <!--<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>-->
                            <a class="addthis_button_facebook_like"></a>
                            <a class="addthis_button_tweet"></a>
                            <a class="addthis_button_google_plusone" g:plusone:size="normal"></a>
                            <!--<a class="addthis_counter addthis_pill_style"></a>-->
                            </div>                            
                            <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e70275244deb51b"></script>
                            <!-- AddThis Button END -->
                        </div>
                    </div>
                    <div class="cb"><!----></div>
				<?php endwhile;?>
                </div>    
                <div class="cot2">
                    <div class="otitle">Tìm thêm sản phẩm khác</div>
					<?php get_sidebar();?>
					<style>
						.quatet-order{list-style: none;padding:0; margin: 0;}
						.taxonomy-drilldown-reset{display: none;}
					</style>
                </div>
                <div class="cb h15"><!----></div>
            </div>
        </div>
    </div>    
	<div id="MoexSchool" style="width: 500px; padding-left: 20px;">
					<?php wp_reset_postdata(); ?>

					<?php comments_template('', true);?>
                    <div class="cb h25"><!----></div>
	</div>
<?php
get_footer();
?>
