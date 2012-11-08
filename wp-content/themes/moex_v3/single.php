<?php
get_header();
?>
    <div id="PageContent">
        <div id="Blog">
			<?php
				$post_id = $post->ID;
				$post_categories = wp_get_post_categories( $post->ID );
				$cat_id = $post_categories[0];
			?>
				<div id="mainAdv">
					<a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
				</div>
			<?php
				if ($cat_id == 16):
			?>
            	<a href="<?php echo get_bloginfo("url"); ?>/career/"><div class="head career"><!----></div></a>
			<?php
				else:
			?>
            	<a href="<?php echo get_bloginfo("url"); ?>/moex-blog/"><div class="head"><!----></div></a>
			<?php
				endif;	
			?>
            <div class="content">
                <div class="cb h12"><!----></div>
				<?php
				if ( have_posts() ) while ( have_posts() ) : the_post();
				?>
                <div class="cot1">                                  
					<?php
					$args = array(
						'post_type' => 'attachment',
						'numberposts' => null,
						'post_status' => null,
						'post_parent' => $post->ID
					); 
					$attachments = get_posts($args);
					if ($attachments) {
						foreach ($attachments as $attachment) {
							?>
							<div class="khungAnh">
							<img src="<?php echo wp_get_attachment_url($attachment->ID);?>" class="anhND"/>
							<div class="nenAnh"><!----></div>
                    		</div>
							<?php
							break;
						}
					}
					?>
                    <div class="titledetail"><?php the_title()?></div>
					<?php setPostViews($post->ID); ?>
                    <div class="date"><?php echo date_i18n( __( 'd/m/Y' ), strtotime( $post->post_date ) )?> - <?php echo getPostViews($post->ID);?> lượt xem</div>
                    <div class="lh18 fwb">
					<?php
                         //echo get_the_excerpt();
					?>
					</div>
                    <div class="cb pt10 lh20 taj">
						<?php the_content();?>
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
                    <div class="fr">
                        <a class="share top" href="#">Về đầu trang</a>
                        <a class="share email" href="#">Gửi email</a>
                        <a class="share print" href="#">In trang</a>
                    </div>
                    <div class="cb"><!----></div>
				<?php endwhile;?>
                </div>    
                <div class="cot2">
                    <div class="otitle">Các tin tức cùng danh mục</div>
					<?php
							
						foreach($post_categories as $cat_id){
							$category = get_category($cat_id);
							$page = (isset($_GET['page']) && is_numeric($_GET['page']))?$_GET['page']:1;
							$number_posts = get_option('posts_per_page');
							$total_page = ceil($category->count/$number_posts);
							$args = array( 'numberposts' => $number_posts, 'post_status'=>'publish', 'category' => $cat_id, 'post_type'=>"post",'orderby'=>"post_date", "paged" => $page);
							$postslist = get_posts( $args );
							break;
						}
					?>	
					<?php
						foreach ($postslist as $key => $post) : setup_postdata($post); 
							if ($post->ID == $post_id) continue;
					?>
                    <a href="<?php the_permalink()?>" class="tinkhac"><?php the_title()?> <span>(<?php echo date_i18n( __( 'd/m' ))?>)</span></a>
					<?php endforeach;?>
                </div>
                <div class="cb h15"><!----></div>
            </div>
        </div>
    </div>    
<?php
get_footer();
?>
