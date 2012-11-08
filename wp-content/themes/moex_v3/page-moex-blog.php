<?php
get_header();

?>
    <div id="PageContent">
        <div id="Blog">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/sub-banner_FA.jpg" class="anhQC"/></a>                
            </div>
            <div class="head"><!----></div>                                   
            <div class="content">
                <div class="motdm fl">
					<?php
						$cat_id = 14;
						$category = get_category($cat_id);
						$page = (isset($_GET['page']) && is_numeric($_GET['page']))?$_GET['page']:1;
						$number_posts = get_option('posts_per_page');
						$total_page = ceil($category->count/$number_posts);
						$args = array( 'numberposts' => $number_posts, 'post_status'=>'publish', 'category' => $cat_id, 'post_type'=>"post",'orderby'=>"post_date", "paged" => $page);
						$postslist = get_posts( $args );
					?>	
                    <div class="pb20">
                        <a class="btOK" href="#"><span><span><span class="bt1"><?php echo $category->name;?></span></span></span></a>
                    </div>
                    <div class="nd">
						<?php
						foreach ($postslist as $key => $post) : setup_postdata($post); 
						if($key == 0):
						?>
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
								<a href="<?php the_permalink()?>"><img src="<?php echo wp_get_attachment_url($attachment->ID);?>" class="anhND"/></a>
								<div class="nenAnh"><!----></div>
								</div>
								<?php
								break;
							}
						}
						?>
                        <a class="title" href="<?php the_permalink()?>"><?php the_title()?></a>
                        <div class="date"><?php echo date_i18n(__( 'd/m/Y' ), strtotime( $post->post_date ) )?> - <?php echo getPostViews($post->ID)?> lượt xem</div>
                        <div class="lh18">
							<?php
								echo get_the_excerpt();
							?>
						</div>
                        <div class="tar">
                            <a class="detail" href="<?php the_permalink()?>">Chi tiết</a>
                        </div>
                        <div class="cb h20"><!----></div>
                        <div class="vientren"><!----></div>
						<?php else:?>
                       	<a href="<?php the_permalink()?>" class="tinkhac"><?php the_title()?> <span>(<?php echo date_i18n( __( 'd/m' ), strtotime( $post->post_date ) )?>)</span></a>
						<?php endif;?>
						<?php endforeach;?>
                    </div>
                </div>
                <div class="motdm fr">
					<?php
						$cat_id = 15;
						$category = get_category($cat_id);
						$page = (isset($_GET['page']) && is_numeric($_GET['page']))?$_GET['page']:1;
						$number_posts = get_option('posts_per_page');
						$total_page = ceil($category->count/$number_posts);
						$args = array( 'numberposts' => $number_posts, 'post_status'=>'publish', 'category' => $cat_id, 'post_type'=>"post",'orderby'=>"post_date", "paged" => $page);
						$postslist = get_posts( $args );
					?>
                    <div class="pb20">
                        <a class="btOK" href="#"><span><span><span class="bt1"><?php echo $category->name;?></span></span></span></a>
                    </div>
                    <div class="nd">
						<?php
						foreach ($postslist as $key => $post) : setup_postdata($post); 
						if($key == 0):
						?>
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
								<a href="<?php the_permalink()?>"><img src="<?php echo wp_get_attachment_url($attachment->ID);?>" class="anhND"/></a>
								<div class="nenAnh"><!----></div>
								</div>
								<?php
								break;
							}
						}
						?>
                        <a class="title" href="<?php the_permalink()?>"><?php the_title()?></a>
                        <div class="date"><?php echo date_i18n( __( 'd/m/Y' ))?> - <?php echo getPostViews($post->ID)?> lượt xem</div>
                        <div class="lh18">
							<?php
								echo get_the_excerpt();
							?>
						</div>
                        <div class="tar">
                            <a class="detail" href="<?php the_permalink()?>">Chi tiết</a>
                        </div>
                        <div class="cb h20"><!----></div>
                        <div class="vientren"><!----></div>
						<?php else:?>
                       	<a href="<?php the_permalink()?>" class="tinkhac"><?php the_title()?> <span>(<?php echo date_i18n( __( 'd/m' ))?>)</span></a>
						<?php endif;?>
						<?php endforeach;?>
                    </div>
                </div>
                <div class="cb h15"><!----></div>
            </div>
        </div>
    </div>    
<?php get_footer();?>

