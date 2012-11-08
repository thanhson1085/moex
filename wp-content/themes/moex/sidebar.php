<h3>Latest News</h3>
<?php
$category = get_category_by_slug('news');
$args = array( 'numberposts' => 5, 'post_status'=>'publish', 'category' => $category->term_id, 'post_type'=>"post",'orderby'=>"post_date");
$postslist = get_posts( $args );

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
<h3 class="area-header">LIÊN KẾT</h3>
<?php
$bookmarks = get_bookmarks( array(
				'orderby'        => 'name',
				'order'          => 'ASC',
                          ));

// Loop through each bookmark and print formatted output
foreach ( $bookmarks as $bm ) { 
?>
<div class="news-item">
<?php
    printf( '<a class="relatedlink" href="%s">%s</a><br />', $bm->link_url, __($bm->link_name) );
?>
</div>
<?php
}
?>


