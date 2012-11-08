<?php
get_header();
?>
	<?php 
	$allsearch = &new WP_Query("s=$s&showposts=-1"); 
	$count = $allsearch->post_count;
	wp_reset_query(); ?>
    <div id="PageContent">
        <div id="KetQuaTimKiem">
           <div class="head"><!----></div>
           <div class="fs12 pb15">Bạn vừa tìm kiếm từ khóa “<span class="cred"><?php echo get_search_query()?></span>”, có <?php echo $count;?> kết quả.</div>
           <?php 
				$k = 0;
				while ( have_posts() ) : the_post(); 
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
			<?php endwhile;?>
           <div class="cb h10"><!----></div>
        </div>
        </div>
    </div>    
<?php
get_footer();
?>
