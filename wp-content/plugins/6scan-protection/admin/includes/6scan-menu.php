<?php
	
if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );
	
function sixscan_menu_install(){

	/*	We show the amount of non-fixed vulnerabilities near 6Scan icon. If there are 0 - we do not show anything */
	$vulnerability_count = get_option( SIXSCAN_OPTION_VULNERABITILY_COUNT );

	if ( $vulnerability_count == 0){
		$sixscan_menu_title = "6Scan";
	}else{
		/*	Only way to show number near menu is to use the same class, that is used by Plugins menu (when showing how many plugins are out of date ) */
		$sixscan_menu_title = "6Scan<span class='update-plugins count-" . $vulnerability_count . "'><span class='plugin-count'>" . number_format_i18n( $vulnerability_count ) . "</span></span>";
	}
	
	if  ( ( isset( $_GET[ 'sixscan_activated' ] ) && ( $_GET[ 'sixscan_activated' ] == '1' ) ) ||		
		( ( isset( $_GET['activate'] ) ) && ( $_GET['activate'] == 'true' ) && ( sixscan_common_is_account_active() == FALSE ) ) ){		
		$sixscan_menu_title .= sixscan_installation_error_description( 'OK' , '' , 'REGISTER_STARTED' , '0' );
	}	

	add_menu_page( '6Scan' , $sixscan_menu_title , 'manage_options' , SIXSCAN_COMMON_DASHBOARD_URL , '' , SIXSCAN_PLUGIN_URL . 'data/img/logo_small.png' );
	add_submenu_page( SIXSCAN_COMMON_DASHBOARD_URL , '6Scan Dashboard' , 'Dashboard' , 'manage_options' , SIXSCAN_COMMON_DASHBOARD_URL , 'sixscan_menu_dashboard' );
	if ( sixscan_common_is_account_operational() == TRUE )
		add_submenu_page( SIXSCAN_COMMON_DASHBOARD_URL , '6Scan Settings' , 'Settings' , 'manage_options' , SIXSCAN_COMMON_SETTINGS_URL , 'sixscan_menu_settings' );
	add_submenu_page( SIXSCAN_COMMON_DASHBOARD_URL , '6Scan Support' , 'Support' , 'manage_options' , SIXSCAN_COMMON_SUPPORT_URL , 'sixscan_menu_support' );
}

function sixscan_menu_is_ticket_requested(){

	return ( ( isset( $_GET[ 'ticket_submitted' ] ) ) && ( $_GET[ 'ticket_submitted' ] == 1 ) )	;
}

function sixscan_menu_support(){
	
	/*	If user has already submitted a ticket, show him a "Thank you" */
	if ( sixscan_menu_is_ticket_requested() ){
		sixscan_menu_submission_ok_msg();
		return;
	}
	
	$custom_message = "<br>For any questions, please visit our <a href='http://6scan.com/support' target='_blank'>support community</a>.<br/><br/>";
	$custom_message .= "If there is a problem with 6Scan's plugin, our support team would like to help you solve it.<br/>  Please verify your email below, add any comments you may have, and <b>click Submit to automatically open a support ticket.</b>\n<br><br>";
	$err_form = sixscan_menu_get_error_submission_form( "" , $custom_message );
	print $err_form;
}

function sixscan_menu_settings(){
	
	/*	Create dashboard frame with settings redirect request */
	sixscan_menu_create_dashboard_frame( SIXSCAN_COMMON_DASHBOARD_URL_SETTINGS );
}

function sixscan_menu_dashboard(){

	/* Create dashboard frame with default redirect request (to the main dashboard) */
	sixscan_menu_create_dashboard_frame();
}

function sixscan_menu_submission_ok_msg(){
print <<<EOD
	<center>
	<div style="padding-top: 20px;"></div>
	<div style="width: 680px; margin: 0; padding: 30px 10px; font-size: 16px; font-family:arial, 'Times New Roman', Times, serif; background-color: rgb(220, 219, 219); border: 1px inset #bbbbbb; box-shadow: 1px 0px 0px #bbbbbb inset; border-radius: 6px 6px 6px 6px; border-bottom: 1px solid #f0f0f0; border-right: 1px solid #f0f0f0;" class="rounded_box">
	Thank you for your submission.  6Scan support will be in touch shortly.
	</div>
	</center>
EOD;
}


function sixscan_menu_create_dashboard_frame( $redirect_request = "" ){

	$user_height = '100%';

	if ( $redirect_request == ""){
		/* Default redirects per different plugin types */
		if ( SIXSCAN_PLATFORM_TYPE == 'wordpress' )
			$redirect_request = SIXSCAN_COMMON_DASHBOARD_URL_MAIN;
		else if ( SIXSCAN_PLATFORM_TYPE == 'wordpress_backup' )
			$redirect_request = SIXSCAN_COMMON_DASHBOARD_URL_BACKUP;
	}

	/*	If user has already submitted a ticket, show him a "Thank you" */
	if ( sixscan_menu_is_ticket_requested() ){
		sixscan_menu_submission_ok_msg();	
		return;
	}
	
	/*	Widget has smaller iframe size */
	if ( $redirect_request == SIXSCAN_COMMON_DASHBOARD_URL_WIDGET )
		$user_height = '550px';

	print "<iframe id='sixscan_dashboard_iframe' src=\"" . sixscan_communication_oracle_auth_get_link( $redirect_request ) . "\" width='100%' height='$user_height'>\n";
	print "</iframe>\n";
?>	
	<script language='javascript'>
            var frame = document.getElementById('sixscan_dashboard_iframe');
            frame.height = document.body.scrollHeight - 127;
	</script>
<?php
}

function sixscan_menu_wrap_error_msg( $err_msg ){
	$result_html = "";
	
	$result_html .= "<center>\n";
	$result_html .=	"<div style=\"padding-top: 20px;\"></div>";
	$result_html .= "	<div class=\"rounded_box\" style=\"width: 600px; margin: 0; padding: 30px 10px; font-size: 16px; font-family:arial, 'Times New Roman', Times, serif; background-color: rgb(220, 219, 219); border: 1px inset #bbbbbb; box-shadow: 1px 0px 0px #bbbbbb inset; border-radius: 6px 6px 6px 6px; border-bottom: 1px solid #f0f0f0; border-right: 1px solid #f0f0f0;\">";
	$result_html .= $err_msg;
	$result_html .= "</div>\n";
	$result_html .= "</center>";

	return $result_html;
}

function sixscan_menu_get_error_submission_form( $err_data = "" , $custom_form_message = "" ){	
	
	$server_request_uri = htmlspecialchars( $_SERVER[ "REQUEST_URI" ] , ENT_QUOTES );
	$result_html = "";
	$error_details = base64_encode( "User error: " . $err_data . "\n\n" . sixscan_common_gather_system_information_for_anonymous_support_ticket() );
	$result_html .= "<center>\n";
	$result_html .= "<div style=\"padding-top: 20px;\"></div>";
	$result_html .= "<div class=\"rounded_box\" style=\"width: 680px; margin: 0; padding: 30px 10px; font-size: 16px; font-family:arial, 'Times New Roman', Times, serif; background-color: rgb(220, 219, 219); border: 1px inset #bbbbbb; box-shadow: 1px 0px 0px #bbbbbb inset; border-radius: 6px 6px 6px 6px; border-bottom: 1px solid #f0f0f0; border-right: 1px solid #f0f0f0;\">\n";
	if ( $custom_form_message == "" )
		$result_html .= "6Scan's support team would like to help you solve this problem!  Please verify your email below, add any comments you may have, and <b>click Submit to automatically open a support ticket.</b>\n<br><br>";	
	else
		$result_html .= $custom_form_message;
	$result_html .= "<script language='Javascript'> function sanity_error_report(){ if  (document.getElementById('admin_comments').value ==''){ alert('Please add the error desription into comments field'); return false;} else {return true;} }</script>\n";
	$result_html .= "<form action=\"" . SIXSCAN_BODYGUARD_ERROR_REPORT_FORM_URL . "\" method=POST onsubmit='return sanity_error_report();'>\n";
	$result_html .= "<input type=hidden name=root_url value=\"" . get_option( 'siteurl' ) . "\">\n";
	$result_html .= "<input type=hidden name=wordpress_version value=\"" . get_bloginfo('version') . "\">\n";
	$result_html .= "<input type=hidden name=6scan_version value=\"" . SIXSCAN_VERSION . "\">\n";	
	$result_html .= "<input type=hidden name=error_details value=\"" . $error_details . "\"><br>\n";
	$result_html .= "<table>\n";
	$result_html .= "<tr><td width='80'>Email:</td><td><input type=text name=admin_email value=\"" . get_option( "admin_email" ) . "\"></td></tr>\n";
	$result_html .= "<tr><td width='80'>Comments*:</td><td><textarea name=admin_comments id=admin_comments cols=60 rows=3></textarea></td></tr>\n";
	$result_html .= "<input type=hidden name=return_url value='" . SERVER_HTTP_PREFIX . $_SERVER[ "SERVER_NAME" ] . $server_request_uri . "&ticket_submitted=1'>\n";
	$result_html .= "<tr><td width='80'></td><td><input type=submit value='Submit error log'></td>\n";
	$result_html .= "</table>";
	$result_html .= "</form>\n";
	$result_html .= "<span style='font-size:0.8em'>We will automatically send troubleshooting information along with your ticket.  6Scan respects your privacy and will never use your information except to help you with your problem.</span>\n";
	$result_html .= "</div>\n";	
	$result_html .= "</center>\n";
	
	return $result_html;
}

function sixscan_menu_show_vulnerabilities_warning(){	

	/*	User has asked us not to show the message */
	if ( get_option( SIXSCAN_VULN_MESSAGE_DISMISSED ) == TRUE ){
		return;
	}

	/*	Don't show the message on the dashboard page */
	if ( isset( $_GET[ 'page' ] ) && ( $_GET[ 'page' ] == SIXSCAN_COMMON_DASHBOARD_URL ) ){
		return;
	}	

	/* Don't show this message to non-admins */
	if ( ! current_user_can( 'manage_options' ) ){
		return;
	}
	
	$current_vulns_found = intval( get_option( SIXSCAN_OPTION_VULNERABITILY_COUNT ) );
	/*	If we have 0 vulnerabilities, don't show the warning */
	if ( $current_vulns_found == 0 )
		return;
	
	echo '<div id="6scan_dashboard_redirect_caption" class="updated" style="text-align: center;"><p>You have ' . $current_vulns_found  . ' unfixed vulnerabilities. <a href="admin.php?page=' . SIXSCAN_COMMON_DASHBOARD_URL . '&fixnow=1">Click here</a> to fix them now.<a href="#" style="float:right" onClick="sixscan_vuln_warning_dismiss();">Dismiss this message</a></p></div>';
}

/*	Hiding the vulnerabilties warning */
add_action( 'admin_head', 'sixscan_menu_dismiss_warning_ajax' );
add_action( 'wp_ajax_sixscan_dismiss_vuln_warning' , 'sixscan_menu_dismiss_vulnerabilities_warning' );

function sixscan_menu_dismiss_warning_ajax(){
	$nonce = wp_create_nonce( 'sixscan_vuln_message' );
	?>
	<script  type='text/javascript'>		
		function sixscan_vuln_warning_dismiss(){
			jQuery.ajax({
				type: "post",url: "admin-ajax.php",data: { action: 'sixscan_dismiss_vuln_warning', _ajax_nonce: '<?php echo $nonce; ?>' },			
				success: function(html){ 					
					jQuery("#6scan_dashboard_redirect_caption").hide();					
				}
			}); //close jQuery.ajax(
		}
		</script>
	<?php
}

function sixscan_menu_dismiss_vulnerabilities_warning(){
	check_ajax_referer( 'sixscan_vuln_message' );

	/*	Update the options, to dismiss the warning message */
	update_option( SIXSCAN_VULN_MESSAGE_DISMISSED , TRUE );

	die();
}

/*	Put the widget on dashboard and push it up (for the first time) */
function sixscan_menu_dashboard_widget(){
	
	$current_vulns_found = intval( get_option( SIXSCAN_OPTION_VULNERABITILY_COUNT ) );
	
	/*	If we have 0 vulnerabilities, don't show the panel */
	if ( $current_vulns_found == 0 )
		return;

	wp_add_dashboard_widget( 'sixscan_widget' , '6Scan Wordpress Security' , 'sixscan_menu_widget_function');	
	
	global $wp_meta_boxes;

	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	$sixscan_widget = array( 'sixscan_widget' => $normal_dashboard[ 'sixscan_widget' ] );
	unset( $normal_dashboard[ 'sixscan_widget' ] );	
	$sorted_dashboard = array_merge( $sixscan_widget , $normal_dashboard );	
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;                
}

/*	Creates link to 6Scan dashboard widget */
function sixscan_menu_widget_function() {
	/* Create dashboard frame with settings redirect request */
	sixscan_menu_create_dashboard_frame( SIXSCAN_COMMON_DASHBOARD_URL_WIDGET );
} 



?>