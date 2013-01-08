<?php

if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );
		

function sixscan_signatures_analyzer_is_env_flag_on( $flag_value ){

	if ( getenv( "REDIRECT_" . $flag_value ) == "1" || ( getenv( $flag_value ) == "1" ) ){
		return TRUE;
	}

	return FALSE;
}

function sixscan_signatures_analyzer_requests_count( $is_suspicious = FALSE ){
	global $wpdb;
	$count_option_name = SIXSCAN_OPTION_STAT_OK_REQ_COUNT;

	if ( $is_suspicious )
		$count_option_name = SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT;
	
	$counter_update_query = "update " . $wpdb->options . " set option_value=option_value+1 where option_name like '$count_option_name'";
	$wpdb->query( $counter_update_query );
}

function sixscan_signatures_analyzer_requests_reset(){
	global $wpdb;

	$counter_reset_query = "update " . $wpdb->options . " set option_value=0 where option_name like '" . SIXSCAN_OPTION_STAT_OK_REQ_COUNT . 
			"' or option_name like '" . SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT . "'";
	$wpdb->query( $counter_reset_query );
}

function sixscan_signatures_analyzer_requests_get(){
	global $wpdb;

	$counter_get_query = "select * from " . $wpdb->options . " where option_name like '" . SIXSCAN_OPTION_STAT_OK_REQ_COUNT . 
			"' or option_name like '" . SIXSCAN_OPTION_STAT_SUSPICIOUS_REQ_COUNT . "'";
	
	$analyzer_counter_array = $wpdb->get_results( $counter_get_query );

	$response_array = array();

	/* convert stdClass into associative array for ease of use */
	foreach ( $analyzer_counter_array as $one_request ){
		$response_array[ $one_request->option_name ] = $one_request->option_value;		
	}

	return $response_array;
}


/*	A 403 page for user */
function sixscan_signatures_analyzer_deny_access(){
	header("HTTP/1.0 403 Forbidden");
	die();
}

function sixscan_signatures_analyzer_suspicious_request(){	
	
	/* 	If we were accessed by one of our servers, do not count this request */
	if ( strstr( SIXSCAN_SIGNATURE_SCANNER_IP_LIST, $_SERVER[ 'REMOTE_ADDR' ] ) !== FALSE )
		return;

	/*	Only log suspicious requests, that were triggered by .htaccess rule */
	if ( sixscan_signatures_analyzer_is_env_flag_on( "sixscansecuritylog" ) == FALSE ){
		sixscan_signatures_analyzer_requests_count( FALSE );
		return;
	}

	/*	Suspicious request */
	sixscan_signatures_analyzer_requests_count( TRUE );
	
	if ( is_writeable (dirname ( SIXSCAN_ANALYZER_LOG_FILEPATH ) . "/" ) == FALSE )
		return;
	
	/* If it exists, we want to limit the filesize to some maximum */
	if ( is_file( SIXSCAN_ANALYZER_LOG_FILEPATH ) && ( filesize( SIXSCAN_ANALYZER_LOG_FILEPATH  ) > SIXSCAN_ANALYZER_MAX_LOG_FILESIZE ) )
		return;
	
	$data_log = "\"" . $_SERVER['SCRIPT_NAME'] . "\" \"" . addslashes( $_SERVER['QUERY_STRING'] ) . "\" \"" . addslashes( $_SERVER['HTTP_REFERER'] ) . "\" \"" . addslashes( $_SERVER['HTTP_USER_AGENT'] ) . "\"" . SIXSCAN_SECURITY_LOG_SEPARATOR;

	@file_put_contents( SIXSCAN_ANALYZER_LOG_FILEPATH , $data_log ,  FILE_APPEND );

	/* Check whether we should block this request */
	if ( sixscan_signatures_analyzer_is_to_block_request() )
		sixscan_signatures_analyzer_deny_access();
}

function sixscan_signatures_analyzer_is_rfi_by_mask( $requested_url, $required_mask , $is_strict = FALSE ){

	$requested_url = strtolower( urldecode( $requested_url ) );
	$required_mask = strtolower( $required_mask );
	
	$rfi_pattern = "/(https?|ftp|gzip|bzip2):\/\/([a-z0-9.-\/]+)&?/i";
	
	/* Get the URL, that address points to */
	preg_match_all( $rfi_pattern, $requested_url, $rfi_matched );

	/*	Something went bad in the pattern matching */
	if ( !isset( $rfi_matched[2] ) )
		return FALSE;

	/* Check that every url satisfies the requested mask */
	foreach ( $rfi_matched[2] as $one_rfi){
		if ( $is_strict ){
			if ( strcmp( $one_rfi, $required_mask ) != 0 )
				return FALSE;	
		}
		else{
			if ( strncmp( $one_rfi, $required_mask, strlen( $required_mask ) ) != 0 )
				return FALSE;
		}
	}

	return TRUE;
}

function sixscan_signatures_analyzer_is_to_block_request(){
	$allowed_waf_rules = get_option( SIXSCAN_OPTION_WAF_REQUESTED );	

	/* WAF is disabled */
	if ( in_array( 'waf_global_enable' , $allowed_waf_rules ) == FALSE )		
		return FALSE;

	/* 	Filter strange requests */
	if ( sixscan_signatures_analyzer_is_env_flag_on( "sixscanstrangerequest" ) ){
		if ( in_array( 'waf_non_standard_req_disable' , $allowed_waf_rules ) )
			return TRUE;
	}

	/* 	Filter SQL injection */
	if ( sixscan_signatures_analyzer_is_env_flag_on( "sixscanwafsqli" ) ){
		if ( in_array( 'waf_sql_protection_enable' , $allowed_waf_rules ) )
			return TRUE;
	}

	/* 	Filter Cross Site Scripting */
	if ( sixscan_signatures_analyzer_is_env_flag_on( "sixscanwafxss" ) ){
		if ( in_array( 'waf_xss_protection_enable' , $allowed_waf_rules ) )
			return TRUE;
	}

	/* 	Filter CSRF on POST */
	if ( sixscan_signatures_analyzer_is_env_flag_on( "sixscanwafcsrf" ) ){
		if ( in_array( 'waf_post_csrf_protection_enable' , $allowed_waf_rules ) )
			return TRUE;
	}	

	/* 	Filter RFI */
	if ( sixscan_signatures_analyzer_is_env_flag_on( "sixscanwafrfi" ) ){
		if ( in_array( 'waf_rfi_protection_enable' , $allowed_waf_rules ) ){
			$allowed_rfi_scripts = array( '/wp-login.php' );
			
			/*	If link is OK to be used with URL as mask */
			if ( in_array( $_SERVER['SCRIPT_NAME'] ,  $allowed_rfi_scripts ) )		
				return FALSE;

			/*	Allow local inclusions */
			if ( in_array( 'waf_rfi_local_access_enable' , $allowed_waf_rules ) ){			

				$mixed_site_address = parse_url( home_url() );
				
				$current_hostname = $mixed_site_address[ 'host' ] ;

				/* 	If the RFI doesn't satisfy requested mask - block the request. 
					Have to add "/", to avoid turning good domains (www.site.com) into bad (www.site.com.badsite.com) */
				if ( ( sixscan_signatures_analyzer_is_rfi_by_mask( $_SERVER['QUERY_STRING'] , $current_hostname , TRUE ) == FALSE )
					&&	( sixscan_signatures_analyzer_is_rfi_by_mask( $_SERVER['QUERY_STRING'] , $current_hostname . "/" ) == FALSE ) )
					
					return TRUE;

				return FALSE;
			}

			/* RFI with no exclusions - always blocking */
			return TRUE;
		}		
	}	

	/* Trigger is not blocked */
	return FALSE;

}	
?>