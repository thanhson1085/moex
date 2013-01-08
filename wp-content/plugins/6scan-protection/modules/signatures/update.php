<?php

if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );

/*	Used to request an update of databases from server */	
function sixscan_signatures_update_request_total( $site_id , $api_token ){	
	$signature_filename = ABSPATH . "/" . SIXSCAN_COMM_SIGNATURE_FILENAME;
	$error_list = "";	

	/*	Get the md5 of a current signature */
	$current_signature_md5 = sixscan_signatures_current_md5 ( $signature_filename );
		
	$update_success_status = sixscan_signature_engine_update_get ( $site_id , $api_token , SIXSCAN_VERSION );
	
	/*	sixscan_signature_engine_update_get() returns TRUE , or error description */		
	if ( $update_success_status !== TRUE )
		$error_list = " engine_update_get() " . "$update_success_status";
	
	$update_success_status =  sixscan_signatures_update_get( $site_id , $api_token , $current_signature_md5 );
	if ( $update_success_status !== TRUE )
		$error_list = $error_list . " signatures_update_get() " . "$update_success_status";
	
	return $error_list;	
}

function sixscan_signature_engine_update_get ( $site_id , $api_token , $current_engine_version ){

	/*	Craft an URL to request new signature */
	$version_update_url = SIXSCAN_BODYGUARD_6SCAN_UPDATE_APP_URL 	. "?site_id=" . $site_id 
																	. "&api_token=" . $api_token 
																	. "&current_version=" . $current_engine_version
																	. "&platform_version=" . sixscan_common_get_wp_version();
	
	/*Request the new version from server */
	$response = sixscan_common_request_network( $version_update_url , "" , "GET" );
			
	if ( is_wp_error( $response ) ) {
		return "wp_remote_get() failed : " . $response->get_error_message();		
	}
	$response_code = wp_remote_retrieve_response_code( $response );
	
	/*	The signatures do not need an update */
	if ( SIXSCAN_UPDATE_LAST_VERSION_RESPONSE_CODE == $response_code )
		return TRUE;	
		
	/*	If the response isn't "you have the latest version" , and "Ok, there is new version" - return error */
	if ( SIXSCAN_UPDATE_OK_RESPONSE_CODE != $response_code )
		return "wp_remote_get() returned status code " . $response_code;
	
	/*	Handle the gzipped program here */	
	$zipped_program = wp_remote_retrieve_body( $response );
	
	/*	Get the headers , and extract the openssl signature from there */	
	$response_headers = wp_remote_retrieve_headers( $response );
	
	/* Check the authenticity of new signatures. have to be signed by 6Scan private key */
	$ssl_check_result = sixscan_signatures_update_check_ssl_signature( $zipped_program , $response_headers );
	if ( $ssl_check_result !== TRUE )
		return $ssl_check_result;
	
	if ( sixscan_signatures_init_wp_filesystem( $response_headers ) == NULL ){
		return "Failed initializing wp_filesystem()";
	}

	global $wp_filesystem;
	/*	Prepare temporary names */
	$temp_upgrade_dir_local = trailingslashit( WP_CONTENT_DIR ) . trailingslashit( "6scan_update" );
	$temp_upgrade_dir = $wp_filesystem->wp_content_dir() . trailingslashit( "6scan_update" );
	$temp_zip_file_local = trailingslashit( WP_CONTENT_DIR ) . "bguard.zip";
	$temp_zip_file = $wp_filesystem->wp_content_dir() . "bguard.zip";

	/*	Create temp directory for update */	
	if ( $wp_filesystem->exists( $temp_upgrade_dir ) )
		$wp_filesystem->delete ( $temp_upgrade_dir , TRUE );
	
	if ( ($wp_filesystem->is_dir( $temp_upgrade_dir ) == FALSE )  && ( $wp_filesystem->mkdir( $temp_upgrade_dir ) == FALSE ) )
		return "Failed creating temp directory for update at " . $temp_upgrade_dir;		
		
	/*	Write the zip file */
	if ( $wp_filesystem->exists( $temp_zip_file ) )
		$wp_filesystem->delete( $temp_zip_file );
	
	if ( $wp_filesystem->put_contents( $temp_zip_file , $zipped_program ) == FALSE )
		return "Failed writing file to " . $temp_zip_file;	
	
	/*	unzip_file returns mixed on failure. It uses global $wp_filesystem. */	
	if ( unzip_file( $temp_zip_file_local , $temp_upgrade_dir ) !== TRUE ){		
		return "unzip_file() from $temp_zip_file to $temp_upgrade_dir failed";
	}
	
	/*	Remove the no longer required zip file */
	$wp_filesystem->delete( $temp_zip_file );
	
	$plugin_main_directory = plugin_dir_path( __FILE__ ) . "../../";
	$plugin_main_directory = $wp_filesystem->wp_plugins_dir() . SIXSCAN_PLUGIN_DIRNAME;

	$temp_upgrade_dir_internal = sixscan_signatures_update_find_plugin_dir( $temp_upgrade_dir_local );

	if ( $temp_upgrade_dir_internal == "" )
		return "Couldn't find plugin dir in the unzipped folder $temp_upgrade_dir_local";
	
	$temp_upgrade_dir_internal = untrailingslashit( $wp_filesystem->find_folder( $temp_upgrade_dir_internal ) );

	/*	Now bulk copy the rest of files to their places: */
	sixscan_signatures_update_move_dir_recursive( $temp_upgrade_dir_internal , $plugin_main_directory );
		
	/*	Remove the tmp directory */	
	$wp_filesystem->delete ( $temp_upgrade_dir , TRUE );
	
	return TRUE;
}

function sixscan_signatures_update_get( $site_id , $api_token , $current_signature_md5sum = "" ){
		
	/*	Craft an URL to request new signature */
	$version_update_url = SIXSCAN_BODYGUARD_6SCAN_UPDATE_SIG_URL 	. "?site_id=" . $site_id 
																	. "&api_token=" . $api_token 
																	. "&current_sig_md5=" . $current_signature_md5sum;														
	
	/*	Request signatures from the server */
	$response = sixscan_common_request_network( $version_update_url , "" , "GET" );
	
	if ( is_wp_error( $response ) )
		return "wp_remote_get() failed : " . $response->get_error_message();
		
	$response_code = wp_remote_retrieve_response_code( $response );
	
	/*	The signatures do not need an update */
	if ( SIXSCAN_UPDATE_LAST_VERSION_RESPONSE_CODE == $response_code )
		return TRUE;	
	
	$response_data = wp_remote_retrieve_body( $response );
	/*	Get the headers , and extract the openssl signature from there */	
	$response_headers = wp_remote_retrieve_headers( $response );	

	/* Check the authenticity of new signatures. have to be signed by 6Scan private key */
	$ssl_check_result = sixscan_signatures_update_check_ssl_signature( $response_data , $response_headers );
	if ( $ssl_check_result !== TRUE )
		return $ssl_check_result;
	
	/*	Server has returned an error */
	if ( SIXSCAN_UPDATE_OK_RESPONSE_CODE != $response_code )	
		return "wp_remote_get() returned status code " . $response_code;

	if ( sixscan_signatures_init_wp_filesystem( $response_headers ) == NULL ){
		return "Failed initializing wp_filesystem()";
	}

	/*	OK - we need to update our signatures */
	return sixscan_signatures_update_parse( $response_data );	
}

function sixscan_signatures_update_parse( $raw_data ) {
	global $wp_filesystem;

	$signature_filename = $wp_filesystem->find_folder( ABSPATH ) . SIXSCAN_COMM_SIGNATURE_FILENAME;
	$signature_filename_tmp = $signature_filename . ".tmp";
	$signature_offset = strpos( $raw_data , SIXSCAN_SIGNATURE_MULTIPART_DELIMITER );
	

	if ($signature_offset === FALSE)
		return "Couldn't find MULTIPART_DELIMITER in signatures. Wrong format";
	
	if ($signature_offset == 0)	/*	No links in signature */
		$links_list = "";
	else
		$links_list = substr( $raw_data , 0 , $signature_offset - 1 );
		
	$signature_data = substr( $raw_data , $signature_offset + strlen( SIXSCAN_SIGNATURE_MULTIPART_DELIMITER ) );
	
	/*	Update the signatures file */
	$signature_data = sixscan_common_get_auth_cookie_code() . "\n" . $signature_data;
	
	if ( $wp_filesystem->put_contents( $signature_filename_tmp , $signature_data ) === FALSE )
		return "Failed writing signature data to $signature_filename_tmp";
		
	if ( $wp_filesystem->chmod( $signature_filename_tmp , 0755 ) === FALSE )
		return "Failed changing mode for $signature_filename_tmp";

	if ( sixscan_signatures_update_copy_file( $signature_filename_tmp , $signature_filename ) == FALSE )
		return "Failed moving signature data from $signature_filename_tmp to $signature_filename";
	
	/*	Update the htaccess with new signatures. When finished, return TRUE if OK, or error description , if failed */
	return sixscan_signatures_update_htaccess( $links_list );
}

function sixscan_signatures_current_md5( $sig_file_location ){
	
	if ( file_exists( $sig_file_location ) ){
		$sig_data = file_get_contents( $sig_file_location );		
		$sig_data = preg_replace( "/\s/" , "" , $sig_data );
	}
	else{
		$sig_data = "";
	}
	
	return md5( $sig_data );
}

function sixscan_signatures_update_htaccess( $links_list ) {
	global $wp_filesystem;

	$htaccess_fpath = sixscan_common_get_htaccess_file_path( $wp_filesystem->method == 'direct' );

	if ( $wp_filesystem->exists( $htaccess_fpath ) ) {
		$htaccess_content = $wp_filesystem->get_contents( $htaccess_fpath );
		/*	Remove old 6Scan signature contents */
		$new_content = trim( preg_replace( '@# Created by 6Scan plugin(.*?)# End of 6Scan plugin@s', '', $htaccess_content) );
	}
	else {
		$new_content = "";
	}
	
	$mixed_site_address = parse_url( home_url() );
	
	if ( ( ! isset( $mixed_site_address[ 'path' ] ) ) || ( strlen( $mixed_site_address[ 'path' ] ) == 0 ) || ( $mixed_site_address[ 'path' ] == '/' ) ) 
		$wordpress_base_dirname = "/";
	else
		$wordpress_base_dirname = untrailingslashit( $mixed_site_address[ 'path' ] );	
		
	/*	Those symbols have to be escaped , if written into htaccess file as RuleCond 		
	*/
	$chars_to_escape_arr = array( '.' , '^' , '$' , '+' , '{' , '}' , '[' , ']' , '(' , ')' );
	$escaped_chars_arr = array( '\.' , '\^' , '\$' , '\+' , '\{' , '\}' , '\[' , '\]' , '\(' , '\)' );		
	
	/*	We need the site relative path */
	$rel_path = isset( $mixed_site_address[ 'path' ] ) ? $mixed_site_address[ 'path' ] : "";	
	
	/*	Escape the dot of current hostname for regexps */
	$current_hostname = str_replace( "." , "\." , $mixed_site_address[ 'host' ] );

	$vuln_urls = "#Broad-spectrum protection: User agent/referrer injections. XSS,RFI and SQLI prevention
RewriteCond %{REQUEST_METHOD} ^(OPTIONS|HEAD|PUT|DELETE|TRACE|CONNECT|PATCH|TRACK|DEBUG) [NC]\n";
	
	if (sixscan_signatures_is_to_block_non_standard_requests())
		$vuln_urls .= "RewriteRule ^(.*)$ - [F,L]\n";
	else
		$vuln_urls .= "RewriteRule .*  - [E=sixscansecuritylog:1,E=sixscanstrangerequest:1] -\n";
	
	$vuln_urls.= "

RewriteCond %{QUERY_STRING} (http(s)?(:|%3A)(/|%2F)(/|%2F)|ftp(:|%3A)(/|%2F)(/|%2F)|zlib(:|%3A)|bzip2(:|%3A)) [NC]
RewriteRule .*  - [E=sixscansecuritylog:1,E=sixscanwafrfi:1] -

RewriteCond %{REQUEST_METHOD} ^(POST) [NC]
RewriteCond %{HTTP_REFERER} !^$
RewriteCond %{HTTP_REFERER} !^https?://" . $current_hostname . " [NC]
RewriteRule .*  - [E=sixscansecuritylog:1,E=sixscanwafcsrf:1] -

RewriteCond %{QUERY_STRING} (<|%3c).*(script|iframe|src).*(>|%3e) [NC]
RewriteRule .*  - [E=sixscansecuritylog:1,E=sixscanwafxss:1] -

RewriteCond %{QUERY_STRING} union.*select [NC,OR]
RewriteCond %{QUERY_STRING} (concat|delete|right|ascii|left|mid|version|substring|extractvalue|benchmark|load_file).*\(.*\)	[NC,OR]
RewriteCond %{QUERY_STRING} (into.*outfile) [NC,OR]
RewriteCond %{QUERY_STRING} (having.*--) [NC]
RewriteRule .*  - [E=sixscansecuritylog:1,E=sixscanwafsqli:1] -\n\n";

//#skip the rfi rule, if accessing wp-login page
//RewriteCond %{REQUEST_URI} ^" . trailingslashit( $wordpress_base_dirname ) . "wp-login [NC]
//RewriteRule ^(.*)$ - [S=1]

	if ( strlen( $links_list ) > 0 ) {
		$links = explode( SIXSCAN_SIGNATURE_LINKS_DELIMITER , $links_list );
		/* Prepare rules for the htaccess */
		foreach ( $links as $one_link ){
			$one_link = trailingslashit( $rel_path ) .  substr( $one_link , 1 );
			$one_link = str_replace( $chars_to_escape_arr , $escaped_chars_arr , $one_link );				
			
			/* We also change / to /+ , so that any path with multiple slashes will be treated ( "dir///path" = "dir/path" ) */
			$one_link = str_replace( '/' , '/+' , $one_link );				
			
			$vuln_urls .= "RewriteCond %{REQUEST_URI} ^" . trim( $one_link ) . " [NC,OR]\n";		
		}	
	}	
	
	$vuln_urls .= "RewriteCond %{REQUEST_URI} ^" . SIXSCAN_SIGNATURE_DEFAULT_PLACEHOLDER_LINK . "\n";
	$vuln_urls .= "RewriteRule .* " . trailingslashit( $wordpress_base_dirname ) . "6scan-gate.php [E=sixscaninternal:accessgranted,L]\n";	
	
	$htaccess_links = "#Patrol's IPs needs access, to check whether rules update is required\n";
				
	/*  IP's , that are allowed to see non-filtered version of scripts. This is to enable 6Scan backend's decision ,
		whether the patch is still required , or can be removed */	 	
	$ip_list_arr = explode( ',' , SIXSCAN_SIGNATURE_SCANNER_IP_LIST );
		
	foreach ( $ip_list_arr as $ip_index => $one_ok_ip ){
		$one_ok_ip = str_replace ( "." , "\\." , $one_ok_ip );		
		$htaccess_links .= "RewriteCond %{REMOTE_ADDR} ^" . trim( $one_ok_ip ) . "$";
		
		/*	Last IP should not have [OR] flag */
		if ( $ip_index != count( $ip_list_arr ) - 1 )
			$htaccess_links .= " [OR]\n";
		else
			$htaccess_links .= "\n";
	}
	
	/*	If an IP maches one of the listed , skip the next 6 rules (automatic exploit detection/6scan_gate forwarding) */
	$htaccess_links .= "RewriteRule ^(.*)$ - [S=6]\n\n";

	/*	Now add the URL rules */
	$htaccess_links .= $vuln_urls;		
	
	$tmp_htaccess_file = $htaccess_fpath . ".tmp";	
	
	$new_content = "# Created by 6Scan plugin
#Those are used by 6Scan Gateway
SetEnv SIXSCAN_HTACCESS_VERSION	" . SIXSCAN_HTACCESS_VERSION . "
SetEnv SIXSCAN_WP_BASEDIR			" . $wordpress_base_dirname . "

#don't show directory listing and apache information
ServerSignature Off

<IfModule mod_rewrite.c>
RewriteEngine On

#avoid direct access to the 6scan-gate.php file
RewriteCond %{ENV:REDIRECT_sixscaninternal} !^accessgranted$
RewriteCond %{ENV:sixscaninternal} !^accessgranted$
RewriteCond %{REQUEST_URI} 6scan-gate\.php$
RewriteRule ^(.*)$ - [F]
					
#This is not really a must, but speeds things up a bit
RewriteRule ^6scan-gate\.php$ - [L]\n\n" . 
$htaccess_links . 
"</IfModule>
# End of 6Scan plugin\n\n" .
					$new_content;
		
	$wp_filesystem->put_contents( $tmp_htaccess_file , $new_content );
		
	if ( sixscan_signatures_update_copy_file( $tmp_htaccess_file , $htaccess_fpath ) == FALSE )
		return "Failed moving htaccess from $tmp_htaccess_file to " . $htaccess_fpath;
		
	return TRUE;
}

/*	When unzipped in a temporary directory , find the source location (it will usually be "6scan" , but in any case... */
function sixscan_signatures_update_find_plugin_dir( $src_dir ){
	$file_list = scandir( $src_dir );

		foreach( $file_list as $current_file ) {
			if( $current_file == "." || $current_file == ".." ) {
				/* skip "current" and "previous" directory */
				continue;
			}
			
			if( is_dir( $src_dir . "/" . $current_file ) )
				return $src_dir . "/" . $current_file;
		}
		
		return "";	//Not found :(
}

/*	Recursively move directory with its contents */
function sixscan_signatures_update_move_dir_recursive( $source , $dest ){ 	
	
	global $wp_filesystem;

	if( $wp_filesystem->is_dir( $source ) ) {
	/* Source is a directory , and we need to go over it , copying all files inside */
		
		if ( ! $wp_filesystem->is_dir( $dest ) )
			$wp_filesystem->mkdir( $dest );
			
		//$file_list = scandir( $source );
		$file_list = $wp_filesystem->dirlist( $source );
		
		foreach( $file_list as $farray => $current_fname ) {
			$current_file = $current_fname['name'];
			
			if( $current_file == "." || $current_file == ".." ) {
				/* skip "current" and "previous" directory */
				continue;
			}
			
			if( $wp_filesystem->is_dir( $source . "/" . $current_file ) ) {	
			/*	If it is directory , we have to call the recursion.*/
				sixscan_signatures_update_move_dir_recursive( $source . "/" . $current_file, $dest. "/" . $current_file );
				/*	We can now remove the source directory */
				$wp_filesystem->delete( $source . "/" . $current_file );
			}
			else {
				/*just a file - simply move it */				
				sixscan_signatures_update_copy_file( $source. "/" .$current_file , $dest . "/" . $current_file );
			}
		}
		return;
	}
	else if( $wp_filesystem->is_file( $source ) ) {
		/*	Source is just a file - move it */
		return sixscan_signatures_update_copy_file( $source , $dest , TRUE );
	}
	
	/* Not a directory or a file */
	return;
} 

function sixscan_signatures_update_check_ssl_signature( $response_data , $response_headers ){
	
	if ( isset ( $response_headers[ SIXSCAN_SIGNATURE_HEADER_NAME ] ) ){
		$openssl_sha1_signature = $response_headers[ SIXSCAN_SIGNATURE_HEADER_NAME ];
	}
	else {
		return "SixScan signature not present in the response";
	}
	
	/*	Verify that program data was signed by 6Scan */
	if ( function_exists ( 'openssl_verify' ) ) {	
		$sig_ver_result = openssl_verify( $response_data , base64_decode ( $openssl_sha1_signature ) , SIXSCAN_SIGNATURE_PUBLIC_KEY );	
		if ( $sig_ver_result != 1 ){
			return "openssl_verify() failed with error code " . $sig_ver_result;
		}			
	}
	else {
		
		/*	If there is no openssl library, fallback to pure PHP implementation of RSA signature verification, take from
			http://phpseclib.sourceforge.net/   */

		include('Crypt/RSA.php');
		$rsa = new Crypt_RSA();
		/*	SHA1 key is chosen by default */
		$rsa->loadKey( SIXSCAN_SIGNATURE_PUBLIC_KEY );
		$rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1);		
		if ( $rsa->verify( $response_data , base64_decode ( $openssl_sha1_signature ) ) == FALSE )
			return "Crypt_RSA->verify() failed" ;
	}
	
	return TRUE;
}

function sixscan_signatures_update_copy_file( $src_file , $dst_file ){
	
	global $wp_filesystem;
	
	$wp_filesystem->delete( $dst_file );
	return $wp_filesystem->move( $src_file , $dst_file , TRUE );
}

function sixscan_signatures_is_to_block_non_standard_requests(){
	$allowed_waf_rules = get_option( SIXSCAN_OPTION_WAF_REQUESTED );

	if ( in_array( 'waf_global_enable' , $allowed_waf_rules ) == FALSE )
		return FALSE;

	/* 	Filter strange requests */
	if ( in_array( 'waf_non_standard_req_disable' , $allowed_waf_rules ) )
		return TRUE;	

	return FALSE;
}

function sixscan_signatures_init_wp_filesystem( $msg_headers ){

	global $wp_filesystem;

	/*	If filesystem is already initiated */
	if ( $wp_filesystem != NULL )
		return $wp_filesystem;

	if ( isset( $msg_headers[ SIXSCAN_SIGNATURE_REQ_KEY ] ) == FALSE )
		return WP_Filesystem();

	$config_key = $msg_headers[ SIXSCAN_SIGNATURE_REQ_KEY ];

	$wp_fs_param = get_option( SIXSCAN_OPTION_WPFS_CONFIG );
	if ( $wp_fs_param === FALSE ){
		return WP_Filesystem();
	}

	$cfg_arr = unserialize( sixscan_common_decrypt_string( base64_decode ( $wp_fs_param ) , $config_key ) );
	
	$wp_fs = WP_Filesystem( $cfg_arr );	

	return $wp_fs;
}

?>