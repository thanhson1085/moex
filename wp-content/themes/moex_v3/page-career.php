<?php
get_header();
?>
	<?php 
	$allsearch = &new WP_Query("s=$s&showposts=-1"); 
	$count = $allsearch->post_count;
	wp_reset_query(); ?>
    <div id="PageContent">
			<div class="career-header" style="line-height: 50px;padding-left: 28px; color:red; font-size: 28px;">Nghề nghiệp</div>
        <div id="KetQuaTimKiem">
			<?php
				$cat_id = 16; 
				$category = get_category($cat_id);
				$page = (isset($_GET['page']) && is_numeric($_GET['page']))?$_GET['page']:1;
				$number_posts = get_option('posts_per_page');
				$total_page = ceil($category->count/$number_posts);
				$args = array( 'numberposts' => $number_posts, 'post_status'=>'publish', 'category' => $cat_id, 'post_type'=>"post",'orderby'=>"post_date", "paged" => $page);
				$postslist = get_posts( $args );
			?>	
           <?php 
				$k = 0;
				foreach ($postslist as $key => $post) : setup_postdata($post); 
				$k++;
			?>
			<?php if ($k % 2 == 1):?>
           <div class="motkq fl">
                <a class="title" href="<?php the_permalink();?>"><?php the_title();?></a>
                <?php echo get_the_excerpt();?>
           </div>
			<?php else:?>
			
           <div class="motkq fr">
                <a class="title" href="<?php the_permalink();?>"><?php the_title();?></a>
                <?php echo get_the_excerpt();?>
           </div>
			<?php endif;?>
			<?php endforeach;?>
           <div class="cb h10"><!----></div>
        </div>
        </div>
    </div>    
<?php
get_footer();
?>
