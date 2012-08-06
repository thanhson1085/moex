<?php get_header();?>
<div class="header-container single">
	<div class="header">
		<div class="main-intro">
		<?php
			$categories = wp_get_post_categories($post->ID);
			foreach ($categories as $category_id):
			$cat_id = $category_id;
			$cat = get_category($category_id)
		?>
			<p class="main-header"><?php echo $cat->name?></p>
		<?php 
			break;
			endforeach;
		?>
		</div>
	</div>
</div>
<div class="content-container">
	<div class="content">
		<div class="left-part-container">
			<div class="left-part single">
				<?php
					get_template_part('loop', 'single');
				?>
				<?php
				$args = array( 'numberposts' => 5, 'post_status'=>'publish', 'category' => $cat_id, 'post_type'=>"post",'orderby'=>"post_date");
				$postslist = get_posts( $args ); 
				?>
				<?php if (count($postslist) > 0):?>
					<div class="related-news">
						<h3>Related News</h3>
						<ul>
						<?php
						foreach ($postslist as $key => $post) : 
							setup_postdata($post); 
							$li_class = ($key % 2 == 0)?'even':'odd';
						?>
		
							<li class="<?php echo $li_class;?>">
								<a href="<?php the_permalink()?>"><?php the_title()?></a>
								<span><?php echo date_i18n( __( 'd/m/Y g:i A' ), strtotime( $post->post_date ) );?></span>
							</li>
						<?php endforeach; ?>
						</ul>
					</div>
				<?php endif;?>

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
