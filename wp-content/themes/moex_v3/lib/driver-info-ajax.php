<?php
function get_ajax_driver_info(){
	global $wpdb, $moex_service_type;
	$html = '';
	$driver_id = (isset($_POST['driver_id']))?$_POST['driver_id']:0;
	if($driver_id){
		$drivers = $wpdb->get_results(
						'SELECT d.*'
						.' FROM '.$wpdb->prefix.'drivers d' 
						.' WHERE d.id = '.$driver_id);
		$html .= "<table><tr>";
		foreach ($drivers as $driver):
		if ($driver){
			if ($driver->image):
				$html .= '<td><img src="'.get_bloginfo("url").'/core/web/uploads/drivers/'.$driver->image.'" /></td>';
			endif;
			$html .= '<td class="align-top"><table class="driver-content">';
			$html .= '<tr><td class="label">Mã lái xe</td>';
			$html .= "<td>";
			$html .= $driver->driver_code;
			$html .= "</td></tr>";
			$html .= '<tr><td class="label">Tên lái xe</td>';
			$html .= "<td>";
			$html .= $driver->driver_name;
			$html .= "</td></tr>";
			$html .= '<tr><td class="label">Tuối</td>';
			$html .= "<td>";
			$html .= $driver->driver_age;
			$html .= "</td></tr>";
			$html .= '<tr><td class="label">Số điện thoại</td>';
			$html .= "<td>";
			$html .= $driver->phone;
			$html .= "</td></tr>";
			$html .= '<tr><td class="label">Địa điểm</td>';
			$html .= "<td>";
			$html .= $driver->position;
			$html .= "</td></tr>";
			$html .= "</table></td>";

		}
		else{
			$html .= 'Không tìm thấy thông tin lái xe';
		}
		endforeach;
		$html .= "<table></tr>";
	}
	else{
		$html .= 'Không tìm thấy thông tin lái xe';
	}
	echo $html;
	die;
}
add_action( 'wp_ajax_nopriv_get_ajax_driver_info', 'get_ajax_driver_info' );
add_action( 'wp_ajax_get_ajax_driver_info', 'get_ajax_driver_info' );
?>
