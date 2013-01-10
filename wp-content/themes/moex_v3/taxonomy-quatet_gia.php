<?php
get_header();
?>
<?php
$user_id = get_current_user_id();
$code_display = 0;
?>
					<a href="<?php echo get_bloginfo("url")?>/qua-tet/"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/Event_Banner_quatet.gif" class="anhQC"/></a>                
	<div class="cb h15"></div>
<?php get_template_part('loop', 'quatet');?>
<?php
get_footer();
?>
