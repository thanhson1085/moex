<?php
function get_ajax_order_info(){
	global $wpdb, $moex_service_type;
	$html = '';
	$order_id = (isset($_POST['order_id']))?$_POST['order_id']:0;
	if($order_id){
		$orders = $wpdb->get_results(
						'SELECT o.*,d.driver_name, od.driver_id'
						.' FROM '.$wpdb->prefix.'orders o' 
						.' LEFT JOIN '.$wpdb->prefix.'order_driver od ON o.id = od.order_id'
						.' LEFT JOIN '.$wpdb->prefix.'drivers d ON d.id = od.driver_id'
						.' WHERE o.id = '.$order_id
						.' ORDER BY o.created_at DESC');
		foreach ($orders as $order):
		if ($order){
			$html .= "<table>";
			$html .= '<tr><td class="label">Điểm đi</td>';
			$html .= "<td>";
			$html .= $order->order_from;
			$html .= "</td></tr>";
			$html .= '<tr><td class="label">Điểm đến</td>';
			$html .= "<td>";
			$html .= $order->order_to;
			$html .= "</td></tr>";
			$html .= '<tr><td class="label">Giá</td>';
			$html .= "<td>";
			$html .= $order->price;
			$html .= "</td></tr>";
			$html .= '<tr><td  class="label">Dịch vụ đăng ký</td>';
			$html .= "<td>";
			$html .= $moex_service_type[$order->service_type];
			$html .= "</td></tr>";
			if ($order->driver_name):
				$html .= '<tr><td  class="label">Lái xe</td>';
				$html .= '<td><a href="'.get_bloginfo("url").'/driver-info/?driver_id='.$order->driver_id.'">';
				$html .= $order->driver_name;
				$html .= "</a></td></tr>";
			endif;
			$html .= "</table";

		}
		else{
			$html .= 'Không tìm thấy thông tin đơn hàng';
		}
		endforeach;
	}
	else{
		$html .= 'Không tìm thấy thông tin đơn hàng';
	}
	echo $html;
	die;
}
add_action( 'wp_ajax_nopriv_get_ajax_order_info', 'get_ajax_order_info' );
add_action( 'wp_ajax_get_ajax_order_info', 'get_ajax_order_info' );
?>
