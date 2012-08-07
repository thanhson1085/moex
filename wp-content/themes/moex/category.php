<?php 
get_header();
?>
<div class="header-container single">
    <div class="header">
        <div class="main-intro">
			<?php
				$category = get_category($cat)
			?>
            <p class="main-header"><?php echo $category->name?></p>
        </div>
    </div>      
</div> 

<?php
$args = array( 'numberposts' => 5, 'post_status'=>'publish', 'category' => $cat, 'post_type'=>"post",'orderby'=>"post_date");
$postslist = get_posts( $args );

?>
<div class="content-container">
    <div class="content">
        <div class="left-part-container">
            <div class="left-part single">
			<?php
			foreach ($postslist as $post) : setup_postdata($post); ?>
			<div class="news-item">
				<a href="<?php the_permalink()?>"><?php the_title()?></a>
				<p class="news-time"><?php echo date_i18n( __( 'd/m/Y g:i A' ), strtotime( $post->post_date ) );?></p>
				<p class="news-intro">
					<?php
					echo get_the_excerpt();
					?>
				</p>
			</div>
			<?php endforeach; ?>
			</div>
		</div>
        <div class="right-part-container">
            <div class="right-part">
                <?php get_sidebar(); ?>
            </div>
        </div>
	</div>
</div>


<?php get_footer();?>
