<?php
$category = get_category_by_slug('introduction');
$args = array( 'numberposts' => 3, 'post_status'=>'publish', 'category' => $category->term_id, 'post_type'=>"post",'orderby'=>"post_date");
$postslist = get_posts( $args );

foreach ($postslist as $post) : setup_postdata($post); ?>
<ul>
	<li>
		<div class="idx-item">
			<div class="idx-item-header"><?php the_title();?></div>
			<div class="idx-item-content">
		     <?php
		        echo the_content('');
       		 ?>
			</div>
			<div class="idx-item-readmore"><a href="<?php the_permalink()?>">Read mores ...</a></div>
		</div>
	</li>
</ul>
<?php endforeach; ?>
