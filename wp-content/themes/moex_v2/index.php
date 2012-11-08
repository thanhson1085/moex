<?php get_header();
?>
<div class="content-container">
	<div class="content">
		<div class="left-menu-container">
			<div class="left-menu">
				<?php get_sidebar('left');?>
			</div>
		</div>
		<div class="left-part-container">
			<div class="left-part">
				<?php get_template_part('loop','index');?>
			</div>
		</div>
		<div class="right-part-container">
			<div class="right-part">
				<?php get_template_part('sidebar','index')?>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#search-submit').click(function(){
			window.location.href = '<?php echo get_bloginfo('url');?>/?page_id=50&from=' + $('#search-from').attr('value') + '&to=' + $('#search-to').attr('value');
		});
	});
</script>
<?php get_footer();?>
