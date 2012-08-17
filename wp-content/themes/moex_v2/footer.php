<div class="footer-container">
	<div class="footer">
			Developed by TechBurg 2012.
	</div>
</div>
<script type="text/javascript">
	var menu_left = $('.left-menu-container');
	var left_part = $('.left-part-container');
	var right_part = $('.right-part-container');
	$(document).ready(function(){
		var max_height = Math.max(menu_left.height(), left_part.height(), right_part.height());
		if (max_height > left_part.height() + 50) left_part.css('height',max_height);
		window_resize();
		$(window).resize(function(){
			window_resize();
		});
	});	
	function window_resize(){
		window_width = $(window).width();
		if (window_width < 1024){
			menu_left.css('display', 'none');
			left_part.css('width', '78%');
			right_part.css('width', '22%');
		}
		else{
			menu_left.css('display', 'block');
			menu_left.css('width', '16%');
			left_part.css('width', '62%');
			right_part.css('width', '22%');
		}
	}
</script>
<script type="text/javascript" src="<?php echo get_bloginfo('template_url');?>/js/mescript.js"></script>
</div> <!-- end main-->
<?php
	wp_footer();
?>
</body>
