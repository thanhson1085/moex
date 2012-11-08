<?php 
get_header();
$category = get_category($cat);
$page = (isset($_GET['page']) && is_numeric($_GET['page']))?$_GET['page']:1;
$number_posts = get_option('posts_per_page');
$total_page = ceil($category->count/$number_posts);
$args = array( 'numberposts' => $number_posts, 'post_status'=>'publish', 'category' => $cat, 'post_type'=>"post",'orderby'=>"post_date", "paged" => $page);
$postslist = get_posts( $args );

?>
<div class="content-container">
    <div class="content">
		<div class="left-menu-container">
			<div class="left-menu">
				<?php get_sidebar('left');?>
			</div>
		</div>
        <div class="left-part-container">
            <div class="left-part page">
			<h1 class="page-title">
				<a href=""><?php echo $category->name;?></a>
			</h1>
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
                <?php get_sidebar('index'); ?>
            </div>
        </div>
		<div style="clear:both"></div>
	</div>
</div>


<?php get_footer();?>
