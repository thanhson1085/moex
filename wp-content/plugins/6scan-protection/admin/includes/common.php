<?php

if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );

define ( 'SIXSCAN_VERSION' ,							'2.3.1.0' );
define ( 'SIXSCAN_HTACCESS_VERSION' ,					'1' );

if( empty( $_SERVER[ "HTTPS" ] ) )
	define( 'SERVER_HTTP_PREFIX',						'http://' );
else
	define( 'SERVER_HTTP_PREFIX',						'https://' );	

define ( 'SIXSCAN_SERVER_ADDRESS' ,						'api.wp.6scan.com' );

/*	The server communication is always through SSL */
define ( 'SIXSCAN_SERVER',								'https://' . SIXSCAN_SERVER_ADDRESS . '/' );	

define ( 'SIXSCAN_BODYGUARD_ERROR_REPORT_FORM_URL' ,	SIXSCAN_SERVER . 'dashboard/v1/error_feedback' );
define ( 'SIXSCAN_BODYGUARD_INTERNAL_ERROR_URL' ,		SIXSCAN_SERVER . 'dashboard/v1/error_feedback_internal' );
define ( 'SIXSCAN_BODYGUARD_REGISTER_URL' , 			SIXSCAN_SERVER . 'wpapi/v1/register' );
define ( 'SIXSCAN_BODYGUARD_REACTIVATE_URL',			SIXSCAN_SERVER . 'wpapi/v1/reactivate' );
define ( 'SIXSCAN_BODYGUARD_VERIFY_URL' , 				SIXSCAN_SERVER . 'wpapi/v3/verify' );
define ( 'SIXSCAN_BODYGUARD_FALLBACK_VERIFY_URL' , 		SIXSCAN_SERVER . 'wpapi/v2/verify' );
define ( 'SIXSCAN_BODYGUARD_6SCAN_UPDATE_SIG_URL' , 	SIXSCAN_SERVER . 'wpapi/v1/update-signatures' );
define ( 'SIXSCAN_BODYGUARD_6SCAN_UPDATE_APP_URL' , 	SIXSCAN_SERVER . 'wpapi/v1/update-application-code' );
define ( 'SIXSCAN_BODYGUARD_6SCAN_UPDATE_SEC_URL' , 	SIXSCAN_SERVER . 'wpapi/v1/update-security-environment' );
define ( 'SIXSCAN_BODYGUARD_6SCAN_UPDATE_LOG_URL' , 	SIXSCAN_SERVER . 'wpapi/v1/update-security-log' );
define ( 'SIXSCAN_BODYGUARD_DEACTIVATE_ACCOUNT' ,		SIXSCAN_SERVER . 'wpapi/v1/deactivate' );
define ( 'SIXSCAN_BODYGUARD_UNINSTALL_ACCOUNT' ,		SIXSCAN_SERVER . 'wpapi/v1/uninstall' );
define ( 'SIXSCAN_BODYGUARD_PING_URL' ,					SIXSCAN_SERVER . 'wpapi/v1/ping' );
define ( 'SIXSCAN_COMM_ORACLE_AUTH_DASHBOARD_URL' ,		SIXSCAN_SERVER . 'dashboard/v1?' );
define ( 'SIXSCAN_BODYGUARD_6SCAN_BACKUP_MPU_SIG_URL' ,	SIXSCAN_SERVER . 'wpapi/v1/backup_get_mpu_sig' );


define ( 'SIXSCAN_COMM_REQUEST_TIMEOUT_SEC' ,			12 );
define ( 'SIXSCAN_COMM_REQUEST_RETRIES' ,				4 );

define ( 'SIXSCAN_OPTIONS_SETUP_ACCOUNT', 				'sixscan_is_account_active' );
define ( 'SIXSCAN_OPTION_MENU_IS_ACCOUNT_OPERATIONAL',	'sixscan_is_account_operational' );
define ( 'SIXSCAN_OPTION_MENU_SITE_ID' , 				'sixscan_registered_site_id' );
define ( 'SIXSCAN_OPTION_MENU_API_TOKEN' , 				'sixscan_registered_api_token' );
define ( 'SIXSCAN_OPTION_MENU_VERIFICATION_TOKEN' , 	'sixscan_registered_verification_token' );
define ( 'SIXSCAN_OPTION_MENU_DASHBOARD_TOKEN' , 		'sixscan_registered_dashboard_token' );
define ( 'SIXSCAN_OPTION_VULNERABITILY_COUNT' ,			'sixscan_vulnerability_count' );
define ( 'SIXSCAN_OPTION_WAF_REQUESTED' ,				'sixscan_waf_requested_options' );
define ( 'SIXSCAN_OPTION_LOGIN_SETTINGS' ,				'sixscan_login_settings_options' );
define ( 'SIXSCAN_OPTION_WPFS_CONFIG' ,					'sixscan_login_wpfs_param' );
define ( 'SIXSCAN_LOGIN_LOGS' ,							'sixscan_login_settings_logs' );
define ( 'SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT' ,	'sixscan_waf_suspicious_req_count' );
define ( 'SIXSCAN_OPTION_STAT_OK_REQ_COUNT' ,			'sixscan_waf_ok_count' );
define ( 'SIXSCAN_BACKUP_LAST_FS_NAME' ,				'sixscan_backup_last_fs_name' );
define ( 'SIXSCAN_BACKUP_LAST_DB_NAME' ,				'sixscan_backup_last_db_name' );
define ( 'SIXSCAN_BACKUP_ETAG_ARRAY' ,					'sixscan_backup_etag_array' );
define ( 'SIXSCAN_VULN_MESSAGE_DISMISSED' ,				'sixscan_vuln_message_dismissed' );
define ( 'SIXSCAN_COMMON_BACKUP_MSG_DELIMITER',			 '###' );

define ( 'SIXSCAN_LOGIN_ERRORS_HIDE_OPTION' ,			'login_errors_hide_enable' );
define ( 'SIXSCAN_LOGIN_LIMITS_ACTIVATED' ,				'login_limit_activated' );
define ( 'SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_SECONDS' ,	'login_time_limit_seconds' );
define ( 'SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_MINUTES' ,	'login_time_limit_minutes' );
define ( 'SIXSCAN_LOGIN_LIMIT_LOGINS' ,					'login_attempts_limit' );
define ( 'SIXSCAN_LOGIN_LOCKED_OUT_SECONDS' ,			'login_locked_out_seconds' );
define ( 'SIXSCAN_LOGIN_LOCKED_OUT_MINUTES' ,			'login_locked_out_minutes' );
define ( 'SIXSCAN_LOGIN_NOTIFY_ADMIN_EMAIL' ,			'login_notify_admin_email' );

define ( 'SIXSCAN_UPDATE_OK_RESPONSE_CODE',				200 );
define ( 'SIXSCAN_UPDATE_LAST_VERSION_RESPONSE_CODE',	304 );
define ( 'SIXSCAN_COMM_ORACLE_AUTH_SALT' , 				':ou6s:6EF{z*_,^+8_#cNg8!+u5zp)ix' );
define ( 'SIXSCAN_VERIFICATION_FILE_PREFIX' ,			'sixscan_' );
define ( 'SIXSCAN_VERIFICATION_DELIMITER' ,				'###############' );
define ( 'SIXSCAN_SIGNATURE_SCHEDULER_SALT' ,			'Ia]g^X6d{PbvOmX}scMOM87.<.F1.~W' );
define ( 'SIXSCAN_OPTION_COMM_ORACLE_NONCE' ,			'sixscan_nonce_val' );
define ( 'SIXSCAN_OPTION_COMM_LAST_SIG_UPDATE_NONCE',	'sixscan_sig_last_update_nonce' );
define ( 'SIXSCAN_NOTICE_UPDATE_NAME' ,					'upd' );
define ( 'SIXSCAN_NOTICE_VERIFICATION_NAME' ,			'verification_site_id' );
define ( 'SIXSCAN_NOTICE_AUTH_NAME' ,					'verification_auth_id' );
define ( 'SIXSCAN_NOTICE_SECURITY_ENV_NAME' ,			'upd-security-environment' );
define ( 'SIXSCAN_NOTICE_SECURITY_LOG_NAME' ,			'upd-security-logs' );
define ( 'SIXSCAN_NOTICE_ACCOUNT_ENABLED' ,				'upd-account-enabled' );
define ( 'SIXSCAN_NOTICE_VULN_COUNT' ,					'vuln-count' );
define ( 'SIXSCAN_NOTICE_BCKP_REQUEST' ,				'bkp' );
define ( 'SIXSCAN_NOTICE_BCKP_PART_ID_REQUEST' ,		'part_id' );
define ( 'SIXSCAN_AMAZON_OK_UPLOAD_HEADER' ,            'HTTP/1.1 204 No Content');
define ( 'SIXSCAN_BACKUP_MAX_RUN_SECONDS' ,				1800 );
define ( 'SIXSCAN_BACKUP_DATABASE_REQUEST' ,			'database' );
define ( 'SIXSCAN_BACKUP_FILES_REQUEST' ,				'files' );
define ( 'SIXSCAN_BACKUP_CHUNK_SIZE' ,					'52428800' );	//50MB

define ( 'SIXSCAN_NOTICE_BCKP_TYPE',					'__type' );
define ( 'SIXSCAN_NOTICE_BCKP_ACTION',					'__action' );
define ( 'SIXSCAN_COMM_SIGNATURE_FILENAME', 			'6scan-signature.php' );
define ( 'SIXSCAN_SIGNATURE_LINKS_DELIMITER',			"\n" );
define ( 'SIXSCAN_SIGNATURE_MULTIPART_DELIMITER',		'###UZhup3v1ENMefI7Wy44QNppgZmp0cu6RPenZewotclc2ZCWUDE4zAfXIJX354turrscbFBL2pOiKpiNLYosm6Z1Qp8b3PNjgd1xqtuskjcT9MC4fZvQfx7FPUDF11oTiTrMeayQr7JHk3UuEK7fR0###' );
define ( 'SIXSCAN_SIGNATURE_SCANNER_IP_LIST',			'108.59.1.37, 108.59.5.197, 108.59.2.209, 95.211.58.114, 95.211.70.82, 107.22.183.61, 78.47.11.131, 199.115.112.90' );
define ( 'SIXSCAN_SIGNATURE_DEFAULT_PLACEHOLDER_LINK',	'/just/a/random/dir/to/avoid/htaccess/mixups\.php' );

define ( 'SIXSCAN_PARTNER_INFO_FILENAME',				'partner.php' );
define ( 'SIXSCAN_PARTNER_INSTALL_KEY',					'sixscan_partner_installed' );

define ( 'SIXSCAN_ANALYTICS_INSTALL_CATEGORY',			'install' );
define ( 'SIXSCAN_ANALYTICS_INSTALL_INIT_ACT',			'init' );
define ( 'SIXSCAN_ANALYTICS_INSTALL_REG_ACT',			'registration' );
define ( 'SIXSCAN_ANALYTICS_INSTALL_VERIF_ACT',			'verification' );
define ( 'SIXSCAN_ANALYTICS_UNINSTALL_CATEGORY',		'uninstall' );
define ( 'SIXSCAN_ANALYTICS_DEACTIVATE_ACT',			'deactivate' );
define ( 'SIXSCAN_ANALYTICS_UNINSTALL_RM_ACT',			'remove' );
define ( 'SIXSCAN_ANALYTICS_NORMAL_CATEGORY',			'normal' );
define ( 'SIXSCAN_ANALYTICS_NORMAL_UPDATING_ACT',		'updating' );

define ( 'SIXSCAN_ANALYTICS_OK_STRING',					'ok' );
define ( 'SIXSCAN_ANALYTICS_FAIL_PREFIX_STRING',		'error_' );
define ( 'SIXSCAN_HTACCESS_FILE',  						ABSPATH . '/.htaccess' );

define ( 'SIXSCAN_HTACCESS_6SCAN_GATE_FILE_NAME', 		'6scan-gate.php' );

define( 'SIXSCAN_ADMIN_ACCESS_COOKIE_NAME',				'sixscan_wpblog_admin' );

define( 'SIXSCAN_ANALYZER_MAX_LOG_FILESIZE',			512000 );	
define( 'SIXSCAN_SECURITY_LOG_FILENAME',				'/data/security_log/logger.txt' );
define( 'SIXSCAN_SECURITY_LOCK_NOTIFY_FILENAME',		'/data/lockout_email.html' );
define( 'SIXCAN_REGISTRATION_PAGE_FILENAME' ,			'/data/regpage/reg.html' );
define( 'SIXSCAN_SECURITY_LOG_SEPARATOR',				"\n" );

/*	If this script is included from outside, we will not have SIXSCAN_PLUGIN_DIR defined, but we do not really need it */
if ( defined( 'SIXSCAN_PLUGIN_DIR' ) ){

	define( 'SIXSCAN_HTACCESS_6SCAN', 						SIXSCAN_PLUGIN_DIR . '/data/.htaccess.dat' );
	function sixscan_common_get_src_htaccess( $is_direct = TRUE ){
		if ( $is_direct == TRUE )
			return SIXSCAN_HTACCESS_6SCAN;

		global $wp_filesystem;
		return untrailingslashit ( $wp_filesystem->find_folder( SIXSCAN_HTACCESS_6SCAN ) );
	}


	define( 'SIXSCAN_SIGNATURE_SRC',						SIXSCAN_PLUGIN_DIR . '/data/' . SIXSCAN_COMM_SIGNATURE_FILENAME );
	function sixscan_common_get_signature_src( $is_direct = TRUE ){
		if ( $is_direct == TRUE )
			return SIXSCAN_SIGNATURE_SRC;
		
		global $wp_filesystem;
		return untrailingslashit( $wp_filesystem->find_folder( SIXSCAN_SIGNATURE_SRC ) );	
	}

	define( 'SIXSCAN_HTACCESS_6SCAN_GATE_SOURCE',  			SIXSCAN_PLUGIN_DIR . '/data/' . SIXSCAN_HTACCESS_6SCAN_GATE_FILE_NAME );
	function sixscan_common_get_gate_src( $is_direct = TRUE ){		
		if ( $is_direct == TRUE )
			return SIXSCAN_HTACCESS_6SCAN_GATE_SOURCE;
		
		global $wp_filesystem;
		return untrailingslashit( $wp_filesystem->find_folder( SIXSCAN_HTACCESS_6SCAN_GATE_SOURCE ) );		
	}	
	define( 'SIXSCAN_ANALYZER_LOG_FILEPATH',				SIXSCAN_PLUGIN_DIR . SIXSCAN_SECURITY_LOG_FILENAME );	
}


define( 'SIXSCAN_COMMON_DASHBOARD_URL',					'six-scan-dashboard' );
define( 'SIXSCAN_COMMON_SETTINGS_URL',					'six-scan-settings' );
define( 'SIXSCAN_COMMON_SUPPORT_URL',					'six-scan-support' );

define( 'SIXSCAN_COMMON_DASHBOARD_URL_MAIN',			'dashboard' );
define( 'SIXSCAN_COMMON_DASHBOARD_URL_SETTINGS',		'dashboard_settings' );
define( 'SIXSCAN_COMMON_DASHBOARD_URL_WIDGET',			'dashboard_widget' );
define( 'SIXSCAN_COMMON_DASHBOARD_URL_BACKUP',			'backup_dashboard' );

define( 'SIXSCAN_SIGNATURE_HEADER_NAME',				'x-6scan-signature' );
define( 'SIXSCAN_SIGNATURE_REQ_KEY',					'x-6scan-db_encryption_key' );

define( 'SIXSCAN_SIGNATURE_PUBLIC_KEY',	<<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1282HttE2wknm8qOX756
pQzR4uXSKCySGGx/xwAb3XHeX4AvHYB5NG7Cg6cGUIfRkD5JQImsGnm3rUPxw8AG
sEWRcEEsfZqEBB48h2rAXck0qpnroFRVGtxGD9ppiDvYBJKM0jsRnjdsBF9uryyB
jIEwViGKtHIU75AJGSWk/V3G2GVU8kd1he4lNNQTBcMIu6tQxb/HVqzVzcWhA7st
N6S5abDRWIEExuW8UTnnjMWDSt+g/tXZR4Po97rfGFio1fx4kROiq6/fcWpw7kq9
Mn+NflF/S+/biB5c+hbgGA6tpQj6Ta42ArCqIR6wJdetW2ljAbo2YCM/kmoTr7r4
kwIDAQAB
-----END PUBLIC KEY-----
EOD
);


function sixscan_common_get_htaccess_file_path( $is_direct ){
	if ( $is_direct == TRUE )
		return SIXSCAN_HTACCESS_FILE;

	global $wp_filesystem;
	return $wp_filesystem->abspath() . '.htaccess';
}

function sixscan_common_get_htaccess_dest_path( $is_direct = TRUE ){
	if ( $is_direct == TRUE )
		return ABSPATH . SIXSCAN_HTACCESS_6SCAN_GATE_FILE_NAME;

	global $wp_filesystem;
	return $wp_filesystem->abspath() . SIXSCAN_HTACCESS_6SCAN_GATE_FILE_NAME;
}

function sixscan_common_get_signature_dest_path( $is_direct = TRUE ){
	if ( $is_direct == TRUE )
		return ABSPATH . SIXSCAN_COMM_SIGNATURE_FILENAME;

	global $wp_filesystem;
	return $wp_filesystem->abspath() . SIXSCAN_COMM_SIGNATURE_FILENAME;
}

function sixscan_common_set_site_id( $site_id ){
	update_option( SIXSCAN_OPTION_MENU_SITE_ID , $site_id );
}

function sixscan_common_get_site_id(){
	return get_option ( SIXSCAN_OPTION_MENU_SITE_ID );
}

function sixscan_common_set_api_token( $api_token ){
	update_option( SIXSCAN_OPTION_MENU_API_TOKEN , $api_token );
}

function sixscan_common_get_api_token(){
	return get_option( SIXSCAN_OPTION_MENU_API_TOKEN );
}

function sixscan_common_set_verification_token( $verification_token ){
	update_option( SIXSCAN_OPTION_MENU_VERIFICATION_TOKEN , $verification_token );
}

function sixscan_common_get_verification_token(){
	return get_option( SIXSCAN_OPTION_MENU_VERIFICATION_TOKEN );
}

function sixscan_common_set_dashboard_token( $dashboard_token ){
	update_option( SIXSCAN_OPTION_MENU_DASHBOARD_TOKEN , $dashboard_token );
}

function sixscan_common_get_dashboard_token(){
	return get_option( SIXSCAN_OPTION_MENU_DASHBOARD_TOKEN );
}

function sixscan_common_is_account_operational(){
	return get_option( SIXSCAN_OPTION_MENU_IS_ACCOUNT_OPERATIONAL );
}

function sixscan_common_set_account_operational( $reg_val ){
	update_option( SIXSCAN_OPTION_MENU_IS_ACCOUNT_OPERATIONAL , $reg_val );
}

function sixscan_common_is_account_active(){
	return get_option( SIXSCAN_OPTIONS_SETUP_ACCOUNT );
}

function sixscan_common_set_account_active( $active_val ){
	update_option( SIXSCAN_OPTIONS_SETUP_ACCOUNT , $active_val );
}

function sixscan_common_is_partner_version(){
	$partner_file_path = trailingslashit( dirname( __FILE__ ) ) . SIXSCAN_PARTNER_INFO_FILENAME;
	
	return file_exists( $partner_file_path );	
}

/*	Based on http://phpseclib.sourceforge.net/ package */
function sixscan_common_encrypt_string( $plain_data , $key ){
	
	if ( class_exists( 'Crypt_RC4' ) == FALSE ){
		require_once( SIXSCAN_PLUGIN_DIR . "modules/signatures/Crypt/RC4.php" );		
	}
	
	$rc4_encr = new Crypt_RC4(); 
	$rc4_encr->setKey( $key );
	return $rc4_encr->encrypt( $plain_data );
}

/*	Based on http://phpseclib.sourceforge.net/ package */
function sixscan_common_decrypt_string( $encr_data , $key ){
	
	if ( class_exists( 'Crypt_RC4' ) == FALSE ){
		require_once( SIXSCAN_PLUGIN_DIR . "modules/signatures/Crypt/RC4.php" );
	}
	
	$rc4_encr = new Crypt_RC4(); 
	$rc4_encr->setKey( $key );
	return $rc4_encr->decrypt( $encr_data ); 	
}

function sixscan_wordpress_admin_set_cookie_callback(){
	
	/*	Admin is getting auth cookie, other users do not */
	if ( current_user_can( 'administrator' ) ){
		if ( ! isset ( $_COOKIE[ SIXSCAN_ADMIN_ACCESS_COOKIE_NAME ] ) )
			setcookie( SIXSCAN_ADMIN_ACCESS_COOKIE_NAME , sixscan_common_get_auth_cookie_val() , time() + 3600 , COOKIEPATH , COOKIE_DOMAIN , false);	
	}
	else{
		if ( isset ( $_COOKIE[ SIXSCAN_ADMIN_ACCESS_COOKIE_NAME ] ) )
			setcookie( SIXSCAN_ADMIN_ACCESS_COOKIE_NAME , '' , time() - 3600 , COOKIEPATH , COOKIE_DOMAIN , false);
	}	
}

function sixscan_common_get_auth_cookie_val(){
	return 'cgcNkBEFpLrw82pgObc1' . md5 ( 'saltZ4uhPMtFYs6Ldn3jsxNS' . sixscan_common_get_verification_token() . sixscan_common_get_api_token() );
}

function sixscan_common_get_auth_cookie_code (){
		$reg_key = sixscan_common_get_auth_cookie_val();
		return '<?php function sixscan_is_admin() {
		if ( isset( $_COOKIE["' . SIXSCAN_ADMIN_ACCESS_COOKIE_NAME . '"] ) )
			if ( $_COOKIE["' . SIXSCAN_ADMIN_ACCESS_COOKIE_NAME . '"]  == "' . $reg_key . '" )
				return TRUE;
		return FALSE;
		}?>';
}

/*	Checks whether user is registered with the server */
function sixscan_common_is_regdata_present() {
	if ( ( sixscan_common_get_site_id() == FALSE ) || 
		( sixscan_common_get_api_token() == FALSE ) || 
		( sixscan_common_get_verification_token() == FALSE ) || 
		( sixscan_common_get_dashboard_token() == FALSE ) )
			return FALSE;
		
	return TRUE;
}

function sixscan_common_erase_regdata(){
	sixscan_common_set_site_id( FALSE );
	sixscan_common_set_api_token( FALSE );
	sixscan_common_set_verification_token( FALSE );
	sixscan_common_set_dashboard_token( FALSE );
}

function sixscan_common_run_signature_check_request(){
	require_once( ABSPATH . WPINC . '/pluggable.php' );	
	global $sixscan_signature_request;

	if ( isset( $sixscan_signature_request ) )
		foreach ( $sixscan_signature_request as $one_signature_req ){
			if ( call_user_func_array( $one_signature_req[ 'func_name' ] , $one_signature_req[ 'param_array' ] ) != $one_signature_req[ 'expected_result' ] )
				return false;
		}
	
	return true;
}

function sixscan_common_is_fopen_working(){

	$url = SIXSCAN_BODYGUARD_PING_URL;
	$arrContext = array( 'http' =>
			array(
				'method' => 'GET' ,
				'user_agent' => 'SIXSCAN_SUBMITTER' ,
				'max_redirects' => 6 ,
				'protocol_version' => (float) '1.1' ,
				'header' => '' ,
				'ignore_errors' => true ,
				'timeout' => 30 ,
				'ssl' => array(
						'verify_peer' => false ,
						'verify_host' => false
				)
			)
		);
		
	$proxy = new WP_HTTP_Proxy();	
	if ( $proxy->is_enabled() && $proxy->send_through_proxy( $url ) ) {
		$arrContext[ 'http' ][ 'proxy' ] = 'tcp://' . $proxy->host() . ':' . $proxy->port();
		$arrContext[ 'http' ][ 'request_fulluri' ] = true;

		if ( $proxy->use_authentication() )
			$arrContext[ 'http' ][ 'header' ] .= $proxy->authentication_header() . "\r\n";
	}
	$context = stream_context_create( $arrContext );	
	
	$handle = @fopen( $url , 'r' , false , $context );
	if ( ! $handle ){
		$last_error = error_get_last();
		$fopen_info = "failed. Last error: " . print_r( $last_error , TRUE ) . "\n";
		return $fopen_info;
	}
	else{		
		fclose( $handle );
		return TRUE;
	}
}

function sixscan_common_generate_random_string(){

	/*	Random enough for our needs */
	return ( sha1( 'OBpjnNrFXA' . md5( 'RZc3LJKCti' . mt_rand() . mt_rand() . time() ) ) );
}

function sixscan_common_get_wp_version(){

	/*	The global $wp_version is sometimes blocked by other plugins. Parse it ourselves: */
	$wpversion_file_data = file_get_contents( ABSPATH . '/wp-includes/version.php' );			
	$pattern = '/wp_version\s+=\s+\'([0-9.]+)\'/';
	preg_match($pattern, $wpversion_file_data, $matches);
	return $matches[1];		
}

/*	Windows servers sometimes require special handling */
function sixscan_common_is_windows_os(){
	return ( strtoupper( substr( PHP_OS , 0 , 3) ) === 'WIN' );    
}

function sixscan_common_gather_system_information_for_anonymous_support_ticket(){
	$submission_data = "\n";		
	
	$submission_data .= "OS: " . PHP_OS . " \n";
		
	$submission_data .= "Server info: " . print_r( $_SERVER , TRUE );
	
	$regdata_status = sixscan_common_is_regdata_present();
	$submission_data .= "Regdata present: $regdata_status\n";
	
	$write_method = ( get_option( SIXSCAN_OPTION_WPFS_CONFIG ) === FALSE ) ? "Direct_access" : "WP_filesystem";
	$submission_data .= "Write method: $write_method\n";	
	
	/* Check , whether site can access external resources */
	$url = SIXSCAN_BODYGUARD_REGISTER_URL;
	$proxy = new WP_HTTP_Proxy();	
	if ( $proxy->is_enabled() && $proxy->send_through_proxy( $url ) )
		$is_through_proxy = "true";
	else
		$is_through_proxy = "false";
	$submission_data .= "Is access through proxy: $is_through_proxy\n";
	
	$htaccess_contents = file_get_contents( sixscan_common_get_htaccess_file_path( TRUE ) );
	if ( $htaccess_contents == FALSE )
		$htaccess_contents = "Empty";
	$submission_data .= "Htaccess contents: $htaccess_contents\n";
	
	$plugin_list = get_plugins();		
	$submission_data .= "Plugins: " . print_r( $plugin_list , TRUE ) . "\n";
	
	$phpinif_info = ini_get_all();
	$submission_data .= "phpinfo(): " . print_r( $phpinif_info , true ) . "\n";
	
	return $submission_data;
}

function sixscan_common_error_handler( $error_level , $error_message , $error_file , $error_line ){
	print "Error level $error_level: Message: $error_message. Occured in file $error_file:$error_line\n";
}

function sixscan_common_fatal_error(){
	$error = error_get_last();
    if ( isset( $error ) ){
		if ( $error[ 'type' ] == E_ERROR || $error[ 'type' ] == E_PARSE || $error[ 'type' ] == E_COMPILE_ERROR || $error[ 'type' ] == E_CORE_ERROR ){
			print_r( $error );
		}
	}
}

function sixscan_common_test_dir_writable( $dir_name ){
	global $wp_filesystem;

	$tmp_fname = trailingslashit( $dir_name ) . 'sixscantmp_';
	
	$ftmp_result = $wp_filesystem->put_contents( $tmp_fname , 'write_test' );	
	
	if ( $ftmp_result === FALSE )
		return FALSE;

	/* Cleanup */
	$wp_filesystem->delete( $tmp_fname );
	return TRUE;
}

function sixscan_common_test_file_writable( $fname ){
	global $wp_filesystem;

	if ( $wp_filesystem->method != 'direct')
		return $wp_filesystem->is_writable( $fname );

	$fp = fopen( $fname , 'a+' );
	if ( $fp == FALSE )
		return FALSE;

	fclose( $fp );
	return TRUE;
}

function sixscan_common_show_all_errors(){
	
	/*	Enable reporting of all errors. (Except E_SCTRICT, which we don't need) */
	set_error_handler( 'sixscan_common_error_handler' , E_ALL );    
	register_shutdown_function( 'sixscan_common_fatal_error' );
}

function sixscan_common_request_network( $request_url , $request_data , $request_type = "GET" ){
	
	$request_params = array(
			'timeout' => SIXSCAN_COMM_REQUEST_TIMEOUT_SEC ,
			'redirection' => 5 ,
			'httpversion' => '1.1' ,
			'blocking' => true ,
			'sslverify' => false ,			 /*	We have found out , that there are lots of users , who don't have their ca-certificates configured , and SSL connect fails.
										If you want to force SSL CA verification , change this rule to 'true' */
			'headers' => array() ,
			'body' => $request_data ,
			'cookies' => array()
			);
	
	$req_function = ( $request_type == "GET" ) ? 'wp_remote_get' : 'wp_remote_post';
		
	/*	Retry the request several times, until failing */
	for ( $retry_counter = 0 ; $retry_counter < SIXSCAN_COMM_REQUEST_RETRIES ; $retry_counter++ ){		
		$response = $req_function( $request_url , $request_params );
		
		if ( is_wp_error( $response ) == FALSE )
			return $response;
	}
	
	return $response;
}


?>