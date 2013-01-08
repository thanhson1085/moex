<?php

$wp_load_location = sixscan_notice_find_wp_load_location();

if ( $wp_load_location == FALSE ){
	header( "HTTP/1.1 500 Can't initialize WP environment" );
	exit( 0 );
}

require( $wp_load_location );
require_once( '../../admin/includes/common.php' );

/*	Older Wordpress version contain several functions, that we use, in this file: */
require_once( ABSPATH . 'wp-admin/includes/file.php' );

if ( defined( 'SIXSCAN_VERSION' ) == FALSE ){
	header( "HTTP/1.1 500 Can't initialize environment" );
	exit( 0 );
}
	
if ( sixscan_common_is_regdata_present() != TRUE ){
	header( "HTTP/1.1 500 6Scan not registered" );
	exit( 0 );
}

/*	Backwards compatibility. Plugins of versions <1.0.5 had another "active" indication */
$backward_compat_active = get_option( 'sixscan_setupaccount' );
if ( ( $backward_compat_active == 'SETUP_STAGE_RUNNING' ) || ( $backward_compat_active == 'SETUP_STAGE_INSTALLED' ) ){
	/*	Cleanup and activate for new version */
	delete_option( 'sixscan_setupaccount' );
	sixscan_common_set_account_active( TRUE );	
}

/*	Verify process. Make sure that sites belongs to the user that registered it */
if ( isset( $_REQUEST[ SIXSCAN_NOTICE_VERIFICATION_NAME ] ) && ( isset( $_REQUEST[ SIXSCAN_NOTICE_AUTH_NAME ] ) ) ){
	
	$expected_auth_id = md5( sixscan_common_get_api_token() . sixscan_common_get_site_id() );
	if ( ( $_REQUEST[ SIXSCAN_NOTICE_VERIFICATION_NAME ] == sixscan_common_get_site_id() ) &&
		( $_REQUEST[ SIXSCAN_NOTICE_AUTH_NAME ] == $expected_auth_id ) ){
		
		echo SIXSCAN_VERIFICATION_DELIMITER . sixscan_common_get_verification_token() . SIXSCAN_VERIFICATION_DELIMITER;		
	}
	else{
		header( "HTTP/1.1 500 Bad verification token" );		
	}
	
	exit( 0 );
}
		
if ( sixscan_common_is_account_active() != TRUE ){
	header( "HTTP/1.1 500 6Scan not active" );
	exit( 0 );
}

$oracle_nonce = intval( $_REQUEST[ 'nonce' ] );
$last_nonce = intval( get_option( SIXSCAN_OPTION_COMM_LAST_SIG_UPDATE_NONCE ) );

if ( $last_nonce >= $oracle_nonce ){
	header( "HTTP/1.1 500 Bad nonce request" );
	exit( 0 );
}
	
$api_token = sixscan_common_get_api_token();
$site_id = sixscan_common_get_site_id();
$expected_token = md5( SIXSCAN_SIGNATURE_SCHEDULER_SALT . $oracle_nonce . $api_token );
$received_token = $_REQUEST[ 'token' ];

if ( $expected_token != $received_token ){
	header( "HTTP/1.1 418 I'm a teapot" );	//as defined in RFC2324: http://tools.ietf.org/html/rfc2324
	exit( 0 );
}

$error_list = "";

/*	From now on, all errors will be caught and shown */
sixscan_common_show_all_errors();

/*	Mark this nonce as already used */
update_option( SIXSCAN_OPTION_COMM_LAST_SIG_UPDATE_NONCE , $oracle_nonce );	

/*	Requested backup */
if ( isset( $_REQUEST[ SIXSCAN_NOTICE_BCKP_REQUEST ] ) && ( $_REQUEST[ SIXSCAN_NOTICE_BCKP_REQUEST ] == '1' )){
	require_once( '../backup/backup_func.php' );
	require_once( '../backup/backup_comm.php' );

	if ( isset ( $_REQUEST[ SIXSCAN_NOTICE_BCKP_TYPE ] ) ){
	
		$backup_result_description = array();
		$begin_time = time();

		/* Show 200 response (even if we will fail later on) */
		header( "HTTP/1.1 200 Backup" );		
		
		/* Run the backup according to requested command */
		$backup_result = sixscan_backup_func_controller( $_REQUEST[ SIXSCAN_NOTICE_BCKP_TYPE ] , $backup_result_description );		
		$backup_total_time = time() - $begin_time;
		
		$backup_result_description[ 'elapsed_time' ] = $backup_total_time;
		print ( SIXSCAN_COMMON_BACKUP_MSG_DELIMITER . json_encode( $backup_result_description ) . SIXSCAN_COMMON_BACKUP_MSG_DELIMITER );
				
		/*	If a backup was requested, no other actions should be run */
		die();
	}
}

/*	Server updates discovered vulnerability count */
if ( isset( $_REQUEST[ SIXSCAN_NOTICE_VULN_COUNT ] ) ){
	$old_count = intval( get_option( SIXSCAN_OPTION_VULNERABITILY_COUNT ) );
	$new_count = intval( $_REQUEST[ SIXSCAN_NOTICE_VULN_COUNT ] );

	/*	New vulnerability was discovered. Show user the warning message */
	if ( $new_count > $old_count )
		update_option( SIXSCAN_VULN_MESSAGE_DISMISSED , FALSE );

	update_option( SIXSCAN_OPTION_VULNERABITILY_COUNT , $new_count );
}
	
/*	Include the update functionality */
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( 'update.php' );

/* Activated this blog with 6Scan server */
if ( isset( $_REQUEST[ SIXSCAN_NOTICE_ACCOUNT_ENABLED ] ) ){
	if ( intval( $_REQUEST[ SIXSCAN_NOTICE_ACCOUNT_ENABLED ] ) == 1 ){
		sixscan_common_set_account_operational( TRUE );
	}	
}

/*	Change WAF options, according to request */
sixscan_waf_set_options_confuguration();

/*	Change secure login configuration */
sixscan_login_options_configuration();

/*	Default value, in case we don't need to send security env */
$security_result = TRUE;
if ( isset( $_REQUEST[ SIXSCAN_NOTICE_SECURITY_ENV_NAME ] ) && ( $_REQUEST[ SIXSCAN_NOTICE_SECURITY_ENV_NAME ] == 1 ) ){
	$security_result = sixscan_send_security_environment( $site_id , $api_token );
}

if ( isset( $_REQUEST[ SIXSCAN_NOTICE_SECURITY_LOG_NAME ] ) && ( $_REQUEST[ SIXSCAN_NOTICE_SECURITY_LOG_NAME ] == 1 ) ){
	$tmp_result = sixscan_send_security_log( $site_id ,  $api_token );
	
	/* Checking result values, and appending  error message, if needed */
	if ( $security_result === TRUE )
		$security_result = $tmp_result;
	else
		$security_result .= "  " . $tmp_result;	
}

/* Update signatures, if needed */
if ( isset( $_REQUEST[ SIXSCAN_NOTICE_UPDATE_NAME ] ) && ( $_REQUEST[ SIXSCAN_NOTICE_UPDATE_NAME ] == 1 ) ){
	$error_list .= sixscan_signatures_update_request_total( $site_id ,  $api_token );
}

if ( ( $security_result === TRUE ) && ( $error_list == "" ) ){
	header( 'HTTP/1.1 200 OK ' );
	print "OK";
}
else{
	$reported_error = "";
	
	if ( $security_result != TRUE )
		$reported_error .= $security_result;

	if ( $error_list != "" )
		$reported_error .= $error_list;	
	
	header( 'HTTP/1.1 500 ' . $reported_error );
}

/*	And exit */
exit( 0 );

function sixscan_login_options_configuration(){

	$sixscan_allowed_login_options = array( SIXSCAN_LOGIN_ERRORS_HIDE_OPTION , SIXSCAN_LOGIN_LIMITS_ACTIVATED , 
		SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_SECONDS , SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_MINUTES, SIXSCAN_LOGIN_LIMIT_LOGINS , SIXSCAN_LOGIN_LOCKED_OUT_SECONDS 
		, SIXSCAN_LOGIN_LOCKED_OUT_MINUTES , SIXSCAN_LOGIN_NOTIFY_ADMIN_EMAIL );

	$sixscan_login_options = get_option( SIXSCAN_OPTION_LOGIN_SETTINGS , array() );

	foreach ( $_REQUEST as $requested_option => $option_value ) {
	 	if ( in_array( $requested_option , $sixscan_allowed_login_options) ){
	 		/*	If requested option is one of the login options */

	 		if ( $requested_option == SIXSCAN_LOGIN_LOCKED_OUT_MINUTES ){
	 			/* Passed in minutes, have to convert to seconds */
	 			$requested_option = SIXSCAN_LOGIN_LOCKED_OUT_SECONDS ;
	 			$option_value = $option_value * 60;
	 		}

	 		if ( $requested_option == SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_MINUTES ) {
	 			/*	Passed in minutes, have to convert to seconds */
	 			$requested_option = SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_SECONDS;
	 			$option_value = $option_value * 60;
	 		}

	 		$sixscan_login_options[ $requested_option ] = $option_value;
	 	}
	}

	/* Set requested options */
	update_option ( SIXSCAN_OPTION_LOGIN_SETTINGS , $sixscan_login_options);

}


function sixscan_waf_set_options_confuguration(){

	$waf_options = array ( 'waf_global_enable' , 'waf_non_standard_req_disable' , 'waf_sql_protection_enable' , 'waf_rfi_protection_enable' , 'waf_rfi_local_access_enable' ,
		'waf_xss_protection_enable' , 'waf_post_csrf_protection_enable' );

	$waf_global_options = get_option( SIXSCAN_OPTION_WAF_REQUESTED , array() );		

	foreach ( $waf_options as $one_waf_option ){

		/* If such an option was in request - add/remove it to/from options. If not - ignore this option */
		if ( isset( $_REQUEST[ $one_waf_option ] ) ){
			if( $_REQUEST[ $one_waf_option ] == 'True' ){			
				$waf_global_options[] = $one_waf_option;
			}
			else{
				unset($waf_global_options[array_search($one_waf_option, $waf_global_options)]);
			}
		}
	}

	/*	Saves WAF options */
	update_option( SIXSCAN_OPTION_WAF_REQUESTED, array_unique ( $waf_global_options ) ) ;
}

function sixscan_notice_find_wp_load_location(){
	$current_wp_load_location = "../../../../../wp-load.php";	
	$max_possible_nesting_levels = 5;
	
	for ( $i = 0; $i < $max_possible_nesting_levels ; $i++ ){
		if ( file_exists ( $current_wp_load_location ) == TRUE ){
			return $current_wp_load_location;
		}
		else{
			$current_wp_load_location = "../" . $current_wp_load_location;
		}
	}
	return FALSE;
}

function sixscan_send_security_environment( $site_id ,  $api_token ){

	$plugin_list = get_plugins();	
	$data_arr = array();
	
	foreach ( $plugin_list as $plugin => $plugin_data ){
		$plugin_info = array();
		$plugin_info[ "Name" ] = $plugin_data[ "Name" ];
		$plugin_info[ "Version" ] = $plugin_data[ "Version" ];
		$plugin_info[ "URL" ] = $plugin;
		$plugin_info[ "IsActive" ] = is_plugin_active( $plugin ) == TRUE ? "true" : "false";
		$data_arr[] = $plugin_info;		
	}
	
	$enc_data = json_encode( $data_arr );
	
	$version_update_url = SIXSCAN_BODYGUARD_6SCAN_UPDATE_SEC_URL 	. "?site_id=" . $site_id 
																	. "&api_token=" . $api_token;

	$response = sixscan_common_request_network( $version_update_url , $enc_data , "POST" );																	
	
	if ( is_wp_error( $response ) ) {
		return $response->get_error_message();
	}
		
	return TRUE;
}

function sixscan_send_security_log( $site_id ,  $api_token ){
	$version_update_url = SIXSCAN_BODYGUARD_6SCAN_UPDATE_LOG_URL 	. "?site_id=" . $site_id 
																	. "&api_token=" . $api_token;	
	
	$log_fname = "../../" . SIXSCAN_SECURITY_LOG_FILENAME;
	
	/*	Can't write/delete logs without configured FS */
	if ( get_option( SIXSCAN_OPTION_WPFS_CONFIG ) !== FALSE )
		return TRUE;

	if ( is_file( $log_fname ) === FALSE ){
		$log_data = "";
	}
	else{
		$log_data = file_get_contents( $log_fname );
		unlink( $log_fname );
		
		if ( $log_data === FALSE )
			$log_data = "";	#empty
	}
	
	
	/*	Get suspicious requests statistics from DB and reset it  */
	$suspicious_request_count = sixscan_signatures_analyzer_requests_get();
	sixscan_signatures_analyzer_requests_reset();

	/* If there are no counter fields in databse, it means we have upgraded from version, which didn't add those fields on install */
	if ( ( array_key_exists( SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT , $suspicious_request_count ) === false ) ||
			( array_key_exists( SIXSCAN_OPTION_STAT_OK_REQ_COUNT , $suspicious_request_count ) === false ) ){
		update_option( SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT , '0' );
		update_option( SIXSCAN_OPTION_STAT_OK_REQ_COUNT , '0' );
	}
	else{
		$version_update_url .= "&bad_requests=" . $suspicious_request_count[ SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT ] . 
			"&good_requests=" . $suspicious_request_count[ SIXSCAN_OPTION_STAT_OK_REQ_COUNT ];	
	}
	

	$response = sixscan_common_request_network( $version_update_url , $log_data , "POST" );	
	
	if ( is_wp_error( $response ) ) {
		return $response->get_error_message();
	}
	
	return TRUE;
}

?>