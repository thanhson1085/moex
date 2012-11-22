<?php
get_header();
$user_id = get_current_user_id();
$drivers = $wpdb->get_results(
				"
				SELECT * 
				FROM ".$wpdb->prefix."drivers
				"
			);
if ($drivers):
?>
<div class="pb10">
	<span class="order-header" style="color:red; font-size: 28px;">
		Thông tin lái xe
	</span>
 </div>
<div class="moex-content">
<div class="order-list">
<ul>
<?php
foreach ($drivers as $driver){
?>	
	<li>
	<a href="<?php echo get_bloginfo("url");?>/driver-info?driver_id=<?php echo $driver->id;?>">
<?php
	echo $driver->driver_name;
?>
	</a>
	</li>
<?php
}
?>
</ul>
<?php
endif;
?>
</div>
<div id="order-info"></div>
</div>
<?php
	$driver_id = (isset($_GET["driver_id"]))?$_GET["driver_id"]:0;
?>
<script type="text/javascript">
	$(document).ready(function(){
		jQuery.post(ajax_link,{ action: "get_ajax_driver_info", driver_id: <?php echo $driver_id?>, modo: "ajaxget" },
		function(data){
			$("#order-info").html(data);
		});
	});
</script>
<?php
get_footer();
?>
