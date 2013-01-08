<?php

if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );	


function sixscan_installation_manager()
{	

	/* If running from partner install, the logic is a bit different */
	if ( ( sixscan_common_is_partner_version() ) && ( sixscan_installation_partner_is_to_install() === FALSE ) )
		return;

	/* Before install of any kind progresses, user is shown a registration page.
	On the first load it shows a registration screen and returns False.
	If user clicks 'Agree' - the page reloads with &agree=yes parameter, sixscan_registration_at_install() returns True and registration continues.	
	No data is transferred to the server until user clicks "Agree" */
	if ( sixscan_registration_at_install() === FALSE )
		return; 	

	/* If we are waiting for user to input wpfs data */
	$tmp_key = sixscan_common_generate_random_string();
	if ( sixscan_installation_wpfs_init( $tmp_key ) == FALSE)
		return;
	
	/* Run the install */
	$install_result = sixscan_installation_install( $tmp_key );
	if ( $install_result !== TRUE ){

		/*	If the install failed - print error message and deactivate the plugin */				
		if ( sixscan_common_is_partner_version() === FALSE ){
			print $install_result;		
			
			/* Makes 6Scan not-active */
			$sixscan_plugin_name = plugin_basename( realpath( dirname( __FILE__ ) . "/../../6scan.php" ) );
		
			/*	This dirty patch is required because some hostings (free?) have a short sql timeout. When it timeouts, 6Scan can't
			disable itelf, and user gets stuck in infinite deactivate loop. 
			We can't enlarge the timeout, since it requires sql root access. We can only reconnect to the SQL.
			This hack reconnects to SQL and deactivates the plugin */
			if ( mysql_errno() != 0 ){
				global $wpdb;
				$wpdb = new wpdb( DB_USER , DB_PASSWORD , DB_NAME , DB_HOST );
				wp_set_wpdb_vars();
			}

			/*	deactivate myself */			
			deactivate_plugins( $sixscan_plugin_name );			
		}
		else if ( sixscan_installation_partner_run_first_time() === FALSE  ){
			/* If we are in partner version, but not running for the first time - we can show the error */
			print $install_result;
		}
		
	}
	else{		
		/*	No redirects in partner version */
		if ( sixscan_common_is_partner_version() === FALSE ){
			
			sixscan_registration_forward_to_dashboard( "&sixscan_activated=1" );
		}
	}
	
	/*	Zeroize our databse flag, so that we only try installing one time */
	if ( sixscan_common_is_partner_version() )
		sixscan_installation_partner_mark_install_tried();

	/* Since registration is now on the client side, we no longer have to wait for server activation */
	sixscan_common_set_account_operational( TRUE );
}

function sixscan_registration_forward_to_dashboard( $additional_flags = "" ){
	$reg_page_address = get_bloginfo( "wpurl" ) . "/wp-admin/admin.php?page=" . SIXSCAN_COMMON_DASHBOARD_URL . $additional_flags;
	/*	If the install has succeeded - forward user to the registration page */		
			
	/* If user's JavaScript is disabled, he will see this notice to upgrade */
	sixscan_installation_account_setup_required_notice();
	/*	Forward user to the registration screen */
	print <<<EOT
		<script type="text/javascript">
			document.getElementById('6scan_dashboard_redirect_caption').style.display = 'none';
			window.location = "$reg_page_address";
		</script>
EOT;
}

function sixscan_registration_at_install(){
	/* The logic is as following:
	When the plugin just got activated - forward user to the dashboard.
	When the current page is 6Scan Dashboard - show him the registration page 
	*/
	$just_activated = isset( $_REQUEST[ 'activate' ] ) ? strtolower( $_REQUEST[ 'activate' ] ) : '';
	$current_page = isset( $_REQUEST[ 'page' ] ) ? strtolower( $_REQUEST[ 'page' ] ) : '';
	$agree_val = isset( $_REQUEST[ 'agree' ] ) ? strtolower( $_REQUEST[ 'agree' ] ) : '';
	
	if ( $just_activated == 'true' ){
		sixscan_registration_forward_to_dashboard();
		return FALSE;
	}

	if ( $current_page != SIXSCAN_COMMON_DASHBOARD_URL )
		return FALSE;

	/* First entry to registration page */
	if ( $agree_val == '' ){		
		/* Registration page content */
		$registration_page = file_get_contents( SIXSCAN_PLUGIN_DIR . SIXCAN_REGISTRATION_PAGE_FILENAME );

		/* Add nonce token and email address to the Registration form (Nothing is sent to the server) */ 	
		$replaced_values = array( '_nonce_value_sixscan' , '_email_value_sixscan' , '_page_value_sixscan' , '_reg_logo_link_sixscan');
		$new_values =  array( wp_create_nonce( 'sixscan_registration_html' ) , get_option( 'admin_email' ) , SIXSCAN_COMMON_DASHBOARD_URL , SIXSCAN_PLUGIN_URL . 'data/img/reg_logo.png' );
	 	$registration_page = str_replace ( $replaced_values , $new_values , $registration_page );
	 	
	 	print $registration_page;
	 	return FALSE;
	}

	/* Origin verification */
	if (! wp_verify_nonce( $_REQUEST[ '_sixscannonce' ], 'sixscan_registration_html') ) die( 'Security failure' );

	/* User clicked 'yes'. Continue to registration */
	if ( $agree_val == 'yes' ){
		return TRUE;
	}

	return FALSE;
}

function sixscan_installation_partner_is_to_install(){
	
	/*	We arrive to this function when 6Scan is not yet installed.
		Now we have to decide, whether to run install.
		First case to run install is when we are at 6scan dashboard page (one of them) - that means 6Scan is not installed, but user
		has requested to see his dashboard */
	$current_page = $_GET[ 'page' ];	

	if ( ( $current_page == SIXSCAN_COMMON_DASHBOARD_URL ) && ( sixscan_menu_is_ticket_requested() == FALSE ) ){		
		/*	Return TRUE to install means :
		1) We are not installed
		2) We are not in ticket support
		3) We have just requested 6Scan dashboard.
		*/
		return TRUE;
	}
	
	/*	Second option - 6Scan is not yet installed, but we have arrived to admin panel for the first time - try registering */
	if ( sixscan_installation_partner_run_first_time() )
		return TRUE;
		
	return FALSE;		
}

function sixscan_installation_partner_run_first_time(){
	/* For the first time the install key is not set */
	return get_option( SIXSCAN_PARTNER_INSTALL_KEY ) == "" ;
}

function sixscan_installation_partner_mark_install_tried(){
	update_option( SIXSCAN_PARTNER_INSTALL_KEY , "softacolous_sixscan" );
}

function sixscan_installation_install( $tmp_key ) {	

	try {		
		/*	Clear the operational flag. It will be set, if activation is successful  */
		sixscan_common_set_account_operational( FALSE );		
		
		global $wp_filesystem;
		$current_wp_filesystem = ( $tmp_key == "" ) ? 'direct' : 'ftp' ;

		if ( is_multisite() ){
			$err_message = "6Scan Install <b>Error</b>: 6Scan currently does not support multisite installs. The support will be added soon";
			return sixscan_menu_wrap_error_msg( $err_message ) . sixscan_installation_error_description( "Multisite install failed" , $current_wp_filesystem );
		}

		/*	Make sure we can create signature file and update the site's .htaccess file */
		if ( sixscan_common_test_dir_writable( $wp_filesystem->abspath() ) == FALSE ){				
			$err_message = "6Scan Install <b>Error</b>: Failed creating signature file at Wordpress directory " . ABSPATH . SIXSCAN_COMM_SIGNATURE_FILENAME .
			"<br/><br/>Please see <a href='http://codex.wordpress.org/Changing_File_Permissions' target='_blank'>this Wordpress article</a> for more information on how to add write permissions." .
			"<br/><br/>If you have additional questions, please visit our <a href='http://6scan.com/support' target='_blank'>community</a>";
			return sixscan_menu_wrap_error_msg( $err_message ) . sixscan_installation_error_description( "Failed creating signature file" , $current_wp_filesystem );
		}
		
		if ( ( $wp_filesystem->exists( SIXSCAN_HTACCESS_FILE ) ) && ( sixscan_common_test_file_writable( SIXSCAN_HTACCESS_FILE ) == FALSE ) ){
			$err_message = "6Scan Install <b>Error</b>: Failed writing .htaccess file " . SIXSCAN_HTACCESS_FILE . 
			"<br/><br/>Please see <a href='http://codex.wordpress.org/Changing_File_Permissions' target='_blank'>this Wordpress article</a> for more information on how to add write permissions." .
			"<br/><br/>If you have additional questions, please visit our <a href='http://6scan.com/support' target='_blank'>community</a>";
			return sixscan_menu_wrap_error_msg( $err_message ) . sixscan_installation_error_description( "Failed writing .htaccess file" , $current_wp_filesystem );
		}
		
		if  ( $wp_filesystem->is_writable( SIXSCAN_PLUGIN_DIR . "/6scan.php" ) == FALSE ){			
			$err_message = "6Scan Install <b>Error</b>: Can't modify 6Scan directory. This usually happens when security permissions do not allow writing to the Wordpress directory." . 
			"<br/><br/>Please see <a href='http://codex.wordpress.org/Changing_File_Permissions' target='_blank'>this Wordpress article</a> for more information on how to add write permissions." .
			"<br/><br/>If you have additional questions, please visit our <a href='http://6scan.com/support' target='_blank'>community</a>";
			return sixscan_menu_wrap_error_msg( $err_message ) . sixscan_installation_error_description( "Failed initializing WP_Filesystem()" , $current_wp_filesystem );
		}
						
		if ( ( ini_get( "allow_url_fopen" ) == FALSE ) && ( ! function_exists( 'curl_init' ) ) ) {
			$err_message = "6Scan Install <b>Error</b>: No libcurl found <b>and</b> \"allow_url_fopen\" in your php.ini is disabled. 6Scan needs at least <b>one</b> transport layer to be enabled, in order to contact its server for automatic updates.<br>" . 
			"*Please see <a href='http://6scan.freshdesk.com/solution/articles/3257-installing-curl-extension-on-a-system' target='_blank'> this FAQ entry</a> in order to enable Curl<br>" .
			"*Please see <a href='http://6scan.freshdesk.com/solution/categories/3294/folders/6728/articles/2681-i-am-seeing-an-error-that-is-similar-to-could-not-open-handle-for-fopen-' target='_blank'>this FAQ entry</a> for instructions on how to enable the \"allow_url_fopen\" flag<br>" .
			"<br/><br/>If you have additional questions, please visit our <a href='http://6scan.com/support' target='_blank'>community</a>";
			return sixscan_menu_wrap_error_msg( $err_message ) . sixscan_installation_error_description( "No libcurl found and allow_url_fopen is disabled" , $current_wp_filesystem );
		}		
		
		/*	Rewrite the htaccess and 6scan-gate file */
		$htaccess_install_result = sixscan_htaccess_install();
		if ( $htaccess_install_result !== TRUE ){
			return sixscan_menu_wrap_error_msg( $htaccess_install_result[ 'user_message' ] ) . sixscan_installation_error_description( $htaccess_install_result[ 'short_description' ] , $current_wp_filesystem );		
		}
		
		if ( sixscan_common_is_regdata_present() == TRUE ){
			if ( sixscan_communication_oracle_reg_reactivate( sixscan_common_get_site_id() , sixscan_common_get_api_token() ) == TRUE ){
			
			/* There is no real install to go on, just reactivation */
				sixscan_common_set_account_operational( TRUE );
				sixscan_common_set_account_active( TRUE );
				return TRUE;
			}
			else{
				sixscan_common_erase_regdata();
			}
		}
		
		/*	Register process */
		$server_registration_result = sixscan_installation_register_with_server( $tmp_key );
		
		if ( $server_registration_result !== TRUE ){
			/* If something went wrong in the registration/verification process */
			sixscan_common_erase_regdata();
			return $server_registration_result . sixscan_installation_error_description( "Server registration failed" , $current_wp_filesystem );
		}
		
		/*	Account is now active, but not yet operational ( operation is set by server, when user completes the registration */
		sixscan_common_set_account_active( TRUE );
						
		/*	Preparing options for further use */		
		update_option( SIXSCAN_OPTION_COMM_ORACLE_NONCE , 1 );
		update_option( SIXSCAN_OPTION_COMM_LAST_SIG_UPDATE_NONCE , 0 );	
		update_option( SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT , 0 );
		update_option( SIXSCAN_OPTION_STAT_OK_REQ_COUNT , 0);
		update_option( SIXSCAN_OPTION_WAF_REQUESTED , array() );
		update_option( SIXSCAN_OPTION_LOGIN_SETTINGS , array() );				
		update_option( SIXSCAN_VULN_MESSAGE_DISMISSED , FALSE );

	} catch( Exception $e ) {
		/* Exception aborts the process */
		sixscan_common_erase_regdata();
		sixscan_common_set_account_active( FALSE );
		sixscan_common_set_account_operational( FALSE );
		
		return $e . sixscan_installation_error_description( "Exception occured while installing" , $current_wp_filesystem );
	}		
		
	return TRUE;
}

function sixscan_installation_error_description( $err_msg , $filesystem_type , $event_occured = 'REGISTER_FAILED' ){
	return "<img id='err_img_id' src='" . sixscan_installation_error_link(  $err_msg , $filesystem_type , $event_occured ) . "'/>";
}

function sixscan_installation_error_link( $err_msg , $filesystem_type , $event_occured ){
	
	/*	Registration failed event */
	$failed_event_descr = array();
	$failed_event_descr[ "event" ] = $event_occured;
	$failed_event_descr[ "properties" ] = array();
	$failed_event_descr[ "properties" ][ "ip" ] = $_SERVER[ 'REMOTE_ADDR' ];
	$failed_event_descr[ "properties" ][ "token" ] = "2428d63178b2a0033c5329570f82d768";
	$failed_event_descr[ "properties" ][ "registration_error" ] = $err_msg;
	$failed_event_descr[ "properties" ][ "filesystem_type" ] = $filesystem_type;
	$failed_event_descr[ "properties" ][ "Install Source" ] = SIXSCAN_PLATFORM_TYPE;
	$failed_event_descr[ "properties" ][ "distinct_id" ] = get_option( 'siteurl' );
	$failed_event_descr[ "properties" ][ "mp_name_tag" ] = get_option( 'siteurl' );

	return "http://api.mixpanel.com/track/?data=" . urlencode( base64_encode( json_encode( $failed_event_descr ) ) ) . "&img=1";
}

function sixscan_installation_uninstall() {
	try {		
		/*	Notify the server, to disable account */
		sixscan_communication_oracle_reg_uninstall( sixscan_common_get_site_id() , sixscan_common_get_api_token() );
		
		/*	Remove verification file, if exists */			
		sixscan_communication_oracle_reg_remove_verification_file();

		/* Remove lines from htaccess */
		sixscan_htaccess_uninstall();			

		/* Clear the database */		
		delete_option( SIXSCAN_OPTIONS_SETUP_ACCOUNT );
		delete_option( SIXSCAN_OPTION_MENU_IS_BLOG_VERIFIED );			
		delete_option( SIXSCAN_OPTION_MENU_SITE_ID );
		delete_option( SIXSCAN_OPTION_MENU_API_TOKEN );
		delete_option( SIXSCAN_OPTION_MENU_VERIFICATION_TOKEN );
		delete_option( SIXSCAN_OPTION_MENU_DASHBOARD_TOKEN );
		delete_option( SIXSCAN_OPTION_MENU_IS_ACCOUNT_OPERATIONAL );
		delete_option( SIXSCAN_OPTION_COMM_ORACLE_NONCE );				
		delete_option( SIXSCAN_OPTION_COMM_LAST_SIG_UPDATE_NONCE );		
		delete_option( SIXSCAN_OPTION_VULNERABITILY_COUNT );
		delete_option( SIXSCAN_OPTION_LOGIN_SETTINGS );		
		delete_option( SIXSCAN_LOGIN_LOGS );		
		delete_option( SIXSCAN_OPTION_WPFS_CONFIG );
		delete_option( SIXSCAN_OPTION_WAF_REQUESTED );
		delete_option( SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT );
		delete_option( SIXSCAN_OPTION_STAT_OK_REQ_COUNT );
		delete_option( SIXSCAN_BACKUP_LAST_FS_NAME );
		delete_option( SIXSCAN_BACKUP_LAST_DB_NAME );
		delete_option( SIXSCAN_VULN_MESSAGE_DISMISSED );

	} catch( Exception $e ) {		
		die( $e );
	}
}

function sixscan_installation_partner_info_get( & $partner_id , & $partner_key ){
	$partner_file_path = trailingslashit( dirname( __FILE__ ) ) . SIXSCAN_PARTNER_INFO_FILENAME;
	
	$partner_id = "";
	$partner_key = "";
	
	if ( file_exists( $partner_file_path ) ){
		require_once( $partner_file_path );	
		
		$partner_id = isset( $sixscan_partner_id ) ? $sixscan_partner_id : "";
		$partner_key = isset( $sixscan_partner_key ) ? $sixscan_partner_key : "";
	}	
}

function sixscan_installation_register_with_server( $tmpkey ){
	
	$admin_email = isset( $_REQUEST['email'] ) ? $_REQUEST['email'] : "";
	
	/*	If there is partner file, partner_id and partner_key are filled */
	sixscan_installation_partner_info_get( $partner_id , $partner_key );

	$sixscan_register_result = sixscan_communication_oracle_reg_register( get_option( 'siteurl' ) ,
							$admin_email , SIXSCAN_PLUGIN_URL . "modules/signatures/notice.php" , 
							$sixscan_oracle_auth_struct , $partner_id , $partner_key , $tmpkey );			

	if ( $sixscan_register_result !== TRUE ){	
		$err_descr = "There was a problem registering your site with 6Scan: <b>$sixscan_register_result</b>.<br><br>";		
		$err_msg .= sixscan_menu_wrap_error_msg( $err_descr );
		$err_msg .= sixscan_menu_get_error_submission_form( $sixscan_register_result );
		return $err_msg;	/* Fail activation with error message and submission form */	
	}
		
	/*	Save the values from registration to database */ 
	sixscan_common_set_site_id( $sixscan_oracle_auth_struct[ 'site_id' ] );
	sixscan_common_set_api_token( $sixscan_oracle_auth_struct[ 'api_token' ] );
	sixscan_common_set_verification_token( $sixscan_oracle_auth_struct[ 'verification_token' ] );
	sixscan_common_set_dashboard_token( $sixscan_oracle_auth_struct[ 'dashboard_token' ] );		
	
	/*	Verify the site */
	$verification_result = sixscan_communication_oracle_reg_verification();
	
	if ( $verification_result !== TRUE ) {

		/*	If verification failed, try running older verification method */
		sixscan_communication_oracle_reg_create_verification_file();
		$verification_result = sixscan_communication_oracle_reg_verification( TRUE );		
		
		if ( $verification_result !== TRUE ) {			
			
			sixscan_communication_oracle_reg_remove_verification_file();
			$err_descr = "There was a problem verifying your site with 6Scan: <b>$verification_result</b><br><br>";					
			$err_msg .= sixscan_menu_wrap_error_msg( $err_descr );
			$err_msg .= sixscan_menu_get_error_submission_form( $verification_result );		
			return $err_msg; /* Fail activation with error message and submission form */		
		}	
	}
	
	return TRUE;
}

function sixscan_installation_verification_get_page_result( $page_url ){

	$response = sixscan_common_request_network( $page_url , "" , "GET" );
	return wp_remote_retrieve_response_code( $response );
}

function sixscan_installation_account_setup_required_notice() {		
	
	/*	Show the notice "Don't forget to register" , only if we are not registered , we are not on the register page 
		and this is not a partner installed version*/
	if ( ( sixscan_common_is_account_operational() == FALSE ) && ( $_GET[ 'page' ] != SIXSCAN_COMMON_DASHBOARD_URL )
		&& ( sixscan_common_is_partner_version() == FALSE ) ){			
			echo '<div id="6scan_dashboard_redirect_caption" class="updated" style="text-align: center;"><p><p>6Scan: In order to enable protection, please <a href="admin.php?page=' . 
			SIXSCAN_COMMON_DASHBOARD_URL . '">create your account</a> now.</p></p></div>';
		}
}	

/*	Returns TRUE if wpfs is already initialized, FALSE if we are waiting for user to enter reg_data */
function sixscan_installation_wpfs_init( &$config_key ){
	/*	Wordpress doesn't always detect the fs method correctly. If we detect, that we can write to the filesystem directly - 
	we can force the method to be direct */
	$wpfs_detect_try = sixscan_installation_wpfs_detect();
	if ( $wpfs_detect_try == 'direct' )
		define( 'FS_METHOD' , 'direct' );
	else if ( $wpfs_detect_try == 'ftpext' )
		define( 'FS_METHOD' , 'ftpext' );	
	if ( WP_Filesystem() ){
		$config_key = "";
		return TRUE;
	}

	/*	We are not using nonce, because there is no way user can arrive to this location after install is complete */
	$url = 'admin.php?page=' . SIXSCAN_COMMON_DASHBOARD_URL;
	
	/*	Show user message only at the first time */
	if ( ( isset( $_GET[ 'page' ] ) == FALSE ) || ( $_GET[ 'page' ] != SIXSCAN_COMMON_DASHBOARD_URL ) )
		print "<p><h1>6Scan requires filesystem credentials to update signature files - fill the information below and click proceed</h1></p>";

	/* request_filesystem_credentials() has to pass $_POST['email'] to the next registration stage */
	if ( ( $creds = request_filesystem_credentials( $url , '' , FALSE , FALSE, array( 'email' , 'agree' , '_sixscannonce' ) ) ) !== FALSE ){	
		if ( ! WP_Filesystem( $creds ) ) {
			/* Current POST data failed, present new form . Error is now "TRUE" */
			request_filesystem_credentials( $url , '' , TRUE , FALSE , array( 'email' , 'agree' , '_sixscannonce' ) );
		}
		else{			
			update_option( SIXSCAN_OPTION_WPFS_CONFIG , base64_encode( sixscan_common_encrypt_string( serialize( $creds ) , $config_key ) ) );
			return TRUE;
		}
	}

	/* User now sees the credential input form */
	return FALSE;
}

/*	Since Wordpress FTP method detection is not always correct (The newly created test-file is compared to the owner of Wordpress scrit)
	We will run the test ourself */
function sixscan_installation_wpfs_detect(){

	/* First of all - we are checking whether the .htaccess is writable via direct */
	if ( file_exists( SIXSCAN_HTACCESS_FILE ) && ( sixscan_installation_try_direct_write_file( SIXSCAN_HTACCESS_FILE , FALSE ) == FALSE ) ){		
		if ( extension_loaded( 'ftp' ) ){
			return 'ftpext';	
		} 
		return FALSE;
	}

	/*	Taken from Wordpress file.php, with minor changes for our needs, we are testing direct file access */
	$temp_file_name = WP_CONTENT_DIR . 'temp-write-test-' . time();
	if ( sixscan_installation_try_direct_write_file( $temp_file_name , TRUE ) == TRUE )
		return 'direct';		

	return FALSE;
}

function sixscan_installation_try_direct_write_file( $fname , $is_to_delete = FALSE ){
	
	$temp_handle = @fopen( $fname , 'a+' );
	if ( $temp_handle ) {			
		@fclose( $temp_handle );
		
		if ( $is_to_delete == TRUE )
			@unlink( $fname );

		return TRUE;
	}

	return FALSE;
}
	
?>