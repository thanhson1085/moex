<?php
if ( have_posts() ) while ( have_posts() ) : the_post();
?>
	<h1 class="single-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
	<div class="single-content">
	<p class="news-time"><?php echo date_i18n( __( 'd/m/Y g:i A' ), strtotime( $post->post_date ) );?></p>
	<?php
		the_content();
	?>
	</div>
<?php
endwhile;
?>
