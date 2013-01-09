<?php
get_header();
?>
<?php
$user_id = get_current_user_id();
$code_display = 0;
?>
					<a href="<?php echo get_bloginfo("url")?>/qua-tet/"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/Event_Banner_quatet.gif" class="anhQC"/></a>                
	<div class="cb h15"></div>
<div style="border-bottom: solid 1px #CCC;height: 30px; padding-left: 20px; font-size: 12px;">
<?php get_sidebar();?>

<style>
	.quatet-order{padding: 0;margin:0;display: block-inline;list-style: none; position: relative;}
	.quatet-order li{font-weight: bold; float:left; padding-right: 10px;}
	.quatet-order h4{display: none;}
	.quatet-order p{display: inline;}
	.quatet-order ul{display: inline;}
	.taxonomy-drilldown-reset{display: none;}
	#terms-quatet_cat{display: inline;}
	#terms-quatet_gia{display: inline}
</style>
</div>
	<div class="cb h15"></div>
<?php get_template_part('loop', 'quatet');?>
<?php
get_footer();
?>
