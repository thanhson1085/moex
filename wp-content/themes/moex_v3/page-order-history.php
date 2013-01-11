<?php
get_header();
$user_id = get_current_user_id();
?>
<div class="moex-container" style="overflow: auto">
<div class="pb10">
	<span class="order-header" style="color:red; font-size: 28px;">
		Thông tin đơn hàng
	</span>
 </div>
<?php
	$order_id = (isset($_GET["order_id"]))?$_GET["order_id"]:0;
?>
<?php
	$status = (isset($_GET['status']))?true:false;
	if ($status):
?>
<?php
$service_type = $wpdb->get_var($wpdb->prepare(
				"
				SELECT service_type 
				FROM ".$wpdb->prefix."orders
				WHERE id =".$order_id."
				"
			));
?>
<div class="order-notify <?php echo $moex_service_type_class[$service_type]?>"><?php echo _e("Cảm ơn bạn đã sử dụng dịch vụ, moEx sẽ phục vụ theo đúng yêu cầu của bạn. moEx luôn sẵn sàng với 1900 56 56 36!"); ?></p></div>
	<?php endif;?>
<div class="moex-content">
<div class="order-list">
<?php
$orders = $wpdb->get_results(
				"
				SELECT * 
				FROM ".$wpdb->prefix."orders
				WHERE customer_id = ".$user_id."
				AND order_status = 'ASSIGNED'
				"
			);
if ($orders):
?>
<h3>Đơn hàng đang được phục vụ</h3>
<ul>
<?php
foreach ($orders as $order){
	$li_class = ($order->id == $order_id)?'class="order-selected '.$moex_service_type_class[$order->service_type].'"':'';
?>	
	<li <?php echo $li_class;?>>
	<a href="<?php echo get_bloginfo("url");?>/order-history?order_id=<?php echo $order->id;?>">
<?php
	echo date_i18n(__('H:i:s d/m/Y'),strtotime($order->created_at));
?>
	</a>
	</li>
<?php
}
?>
</ul>
<?php
endif;
$orders = $wpdb->get_results(
				"
				SELECT * 
				FROM ".$wpdb->prefix."orders
				WHERE customer_id = ".$user_id."
				AND order_status = 'PENDING'
				"
			);
if ($orders):
?>
<h3>Đơn hàng đang chờ phục vụ</h3>
<ul>
<?php
foreach ($orders as $order){
	$li_class = ($order->id == $order_id)?'class="order-selected '.$moex_service_type_class[$order->service_type].'"':'';
?>	
	<li <?php echo $li_class;?>>
	<a href="<?php echo get_bloginfo("url");?>/order-history?order_id=<?php echo $order->id;?>">
<?php
	echo date_i18n(__('H:i:s d/m/Y'),strtotime($order->created_at));
?>
	</a>
	</li>
<?php
}
?>
</ul>
<?php
endif;
$orders = $wpdb->get_results(
				"
				SELECT * 
				FROM ".$wpdb->prefix."orders
				WHERE customer_id = ".$user_id."
				AND order_status = 'DONE'
				"
			);
if ($orders):
?>
<h3>Đơn hàng đã hoàn thành</h3>
<ul>
<?php
foreach ($orders as $order){
	$li_class = ($order->id == $order_id)?'class="order-selected '.$moex_service_type_class[$order->service_type].'"':'';
?>	
	<li <?php echo $li_class;?>>
		<a href="<?php echo get_bloginfo("url");?>/order-history?order_id=<?php echo $order->id;?>">
<?php
	echo $order->created_at;
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
<script type="text/javascript">
	$(document).ready(function(){
		jQuery.post(ajax_link,{ action: "get_ajax_order_info", order_id: <?php echo $order_id?>, modo: "ajaxget" },
		function(data){
			$("#order-info").html(data);
		});
	});
</script>
</div>
<?php
get_footer();
?>
