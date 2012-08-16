<?php 
get_header();
?>
<div class="content-container">
    <div class="content">
		<div class="left-menu-container">
			<div class="left-menu">
			<?php
			get_sidebar('left');
			?>
			</div>
		</div>
		<div class="left-part-container">
			<div class="left-part page">
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
			</div>
		</div>
		<div class="right-part-container">
			<div class="right-part">
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
?>
