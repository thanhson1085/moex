<?php
if ( have_posts() ) while ( have_posts() ) : the_post();
?>
	<h1 class="page-title">
		<a href="<?php the_permalink();?>"><?php the_title();?></a>
		<p class="news-time"><?php echo date_i18n( __( 'd/m/Y g:i A' ), strtotime( $post->post_date ) );?></p>
	</h1>
	<div class="single-content">
	<?php
		the_content();
	?>
	<?php 
		$child_pages = get_pages(array('child_of' => $post->ID));
		foreach($child_pages as $page):
	?>
	<h2 class="page-title">
		<a href="<?php echo get_page_link($page->ID);?>"><?php echo $page->post_title;?></a>
	</h2>
	<div>
		<?php echo $page->post_content;?>
	</div>
	<?php
		endforeach;	
	?>
	</div>
<?php
endwhile;
?>
