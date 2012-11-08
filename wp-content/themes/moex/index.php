<?php get_header();?>
<div class="header-container">
	<div class="header">
		<div class="main-intro">
			<?php 
				$slogan_id = 20;
				$slogan = get_post($slogan_id);?>
			<p class="main-header"><?php echo $slogan->post_title;?>
				<?php if (current_user_can('edit_post')):?>
				<a href="<?php echo admin_url()?>/post.php?post=<?php echo $slogan->ID?>&action=edit" class="title-edit-link" target="_blank">(edit)</a>
				<?php endif;?>
			</p>
			<p class="main-intro-content">
				<?php echo $slogan->post_content;?>
			</p>
		</div>
		<div class="main-form">
			<p><input class="txt-main" id="search-from" name="from" type="text" placeholder="From..."/></p>
			<p><input class="txt-main" id="search-to" name="to" type="text" placeholder="To..."/></p>
			<p><span class="btn-main" id="search-submit">Submit</span></p>

		</div>
	</div>
</div>
<div class="content-container">
	<div class="content">
		<div class="left-part-container">
			<div class="left-part">
				<?php get_template_part('loop','index');?>
			</div>
		</div>
		<div class="right-part-container">
			<div class="right-part">

				<?php get_template_part('sidebar','index');?>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo get_bloginfo('template_url')?>/js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#search-submit').click(function(){
			window.location.href = '<?php echo get_bloginfo('url');?>/?page_id=50&from=' + $('#search-from').attr('value') + '&to=' + $('#search-to').attr('value');
		});
	});
</script>
<?php get_footer();?>
