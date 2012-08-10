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
	</div>
<?php
endwhile;
?>
