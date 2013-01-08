<?php

if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );

	/*	Register on WP callbacks */
function sixscan_signatures_loginsec_register(){	

	/*	Filter on error, that is displayed to user when authentication failed */
	add_filter( 'login_errors' , sixscan_signatures_loginsec_fault_message );	
	
	/*	User authorization was completed successfully */	
	add_action( 'set_current_user' , sixscan_signatures_loginsec_login_success );

	/* Action on authentication attempt */
	add_filter( 'authenticate' , sixscan_signatures_loginsec_analyze , 20 , 3 );

}

/*	Checks whether the time delta has passed */
function sixscan_signatures_loginsec_has_time_passed( $recorded_time , $requested_delta ){	
	return ( $recorded_time + $requested_delta <= time() );		
}

/*	Associative array per IP login */
function sixscan_signatures_loginsec_init_login_inf(){

	$login_inf = array();
	$login_inf[ 'login_start_time' ] = time();
	$login_inf[ 'failed_login_count' ] = 0;	
	$login_inf[ 'is_blocked' ] = false;
	$login_inf[ 'blocked_start_time' ] = 0;	

	return $login_inf;
}

/*	The logic to block/allow IPs logins */
function sixscan_signatures_loginsec_analyze( $user, $username, $password ){
	
	$is_to_block_user = false;
	$loginsec_options = get_option( SIXSCAN_OPTION_LOGIN_SETTINGS );	

	/*  Handle login count */
	if ( is_array( $loginsec_options ) && array_key_exists( SIXSCAN_LOGIN_LIMITS_ACTIVATED , $loginsec_options ) &&
		( $loginsec_options[ SIXSCAN_LOGIN_LIMITS_ACTIVATED ] == 'True' ) ){

		$user_ip = $_SERVER[ 'REMOTE_ADDR' ];
		$login_logs = get_option( SIXSCAN_LOGIN_LOGS , array() );		

		/* Check whether we need to add new struct to array */
		if ( ( ! is_array( $login_logs ) ) ||  ( ! array_key_exists( $user_ip , $login_logs ) ) )
			$login_inf = sixscan_signatures_loginsec_init_login_inf();	
		else
			/*	Not first login attempt */
			$login_inf = $login_logs[ $user_ip ];


		/* Checking, perhaps timeout has already occured, and we need to forget older requests */
		if ( $login_inf[ 'login_start_time' ] + $loginsec_options[ SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_SECONDS ] < time() ){
			$login_inf[ 'login_start_time' ] = time();
			$login_inf[ 'failed_login_count' ] = 0;	
			$login_inf[ 'username' ] = "";
		}

		/*	logging attempted username */
		/*	Filter non standard username chars */
		$username = preg_replace("/[^a-zA-Z0-9.-]/" , "_" , $username );
		
		/*	Separate usernames with commas */
		if ( array_key_exists( 'username' , $login_inf ) )
			$login_inf[ 'username' ] .= ", $username";
		else
			$login_inf[ 'username' ] = "$username";
		
		/* Increase the failed count */
		$login_inf[ 'failed_login_count' ]++;

		/*	Unblocking user */
		if ( ( $login_inf[ 'is_blocked' ] ) &&
			( $login_inf[ 'blocked_start_time' ] + $loginsec_options[ SIXSCAN_LOGIN_LOCKED_OUT_SECONDS ] < time() ) ){
			$login_inf[ 'failed_login_count' ] = 0;
			$login_inf[ 'is_blocked' ] = false;
		}

		/*	If we need to block (reached ciritical failed login count), also save the blocked time */
		if ( $login_inf[ 'failed_login_count'] > $loginsec_options[ SIXSCAN_LOGIN_LIMIT_LOGINS ] ){
		
			if ( $login_inf[ 'is_blocked' ] == false ){
				/* Every new failed request should not trigger email */
				sixscan_signatures_loginsec_notify_admin( $login_inf , $user_ip );
				$login_inf[ 'blocked_start_time' ] = time();
			}
			$login_inf[ 'is_blocked' ] = true;			
		}

		$is_to_block_user = $login_inf[ 'is_blocked' ];

		/*	Save current login counters */
		$login_logs[ $user_ip ] = $login_inf;	
		update_option( SIXSCAN_LOGIN_LOGS , $login_logs );		
		
	}

	/*	Error object means login failed. No message passed, just empty blocked login */
	if ( $is_to_block_user )
		return new WP_Error();	

	return $user;
}

/*	Clear the failed login count */
function sixscan_signatures_loginsec_login_success( $user_name ){

	/* If the user passed authorization - clear the counters. */
	if ( get_current_user_id() == 0 )
		return;

	$user_ip = $_SERVER[ 'REMOTE_ADDR' ];
	$login_logs = get_option( SIXSCAN_LOGIN_LOGS , array() );
	
	/*	Clear the logs */
	if ( array_key_exists( $user_ip , $login_logs) ){
		unset( $login_logs[ $user_ip ] );
	}

	update_option( SIXSCAN_LOGIN_LOGS , $login_logs );
}

/*	Removes failed login message */
function sixscan_signatures_loginsec_fault_message( $message ){
	$loginsec_options = get_option( SIXSCAN_OPTION_LOGIN_SETTINGS , array() );
	
	/* Hide login errors */
	if ( array_key_exists( SIXSCAN_LOGIN_ERRORS_HIDE_OPTION , $loginsec_options )  &&  ( $loginsec_options[ SIXSCAN_LOGIN_ERRORS_HIDE_OPTION ] == 'True' ) ){
		$message = "Login failed";
	}

	return $message;
}

function sixscan_signatures_loginsec_notify_admin( $locked_out_user , $user_ip ){
	$loginsec_options = get_option( SIXSCAN_OPTION_LOGIN_SETTINGS );

	$email_to_address = @$loginsec_options[ SIXSCAN_LOGIN_NOTIFY_ADMIN_EMAIL ];

	/*	If no email defined - do not send notification */
	if ( strlen( $email_to_address ) == 0 )
		return;

	$locked_for_minutes = ( $loginsec_options[ SIXSCAN_LOGIN_LOCKED_OUT_SECONDS ] / 60 );
	$login_attempts_minutes = ( $loginsec_options[ SIXSCAN_LOGIN_WITHIN_TIME_LIMIT_SECONDS ] / 60 );
	$email_from = get_bloginfo( 'admin_email' );
	
	$email_headers = 'MIME-Version: 1.0' . "\r\n";
	$email_headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
	$email_headers .= "From: $email_from\r\n";
	

	$email_subject = "6Scan Security has performed a lockout on " . home_url();
	$email_message = sixscan_signatures_loginsec_prepare_mail_content( $user_ip , $locked_for_minutes , $loginsec_options[ SIXSCAN_LOGIN_LIMIT_LOGINS ] ,
		$login_attempts_minutes, $locked_out_user[ 'username' ] );
	
	@mail( $email_to_address , $email_subject , $email_message , $email_headers );
}

function sixscan_signatures_loginsec_prepare_mail_content( $blocked_ip, $locked_for_minutes, $blocked_attempts, $blocked_attempts_during_time, $blocked_usernames ){

	$dashboard_link = get_admin_url() . "admin.php?page=six-scan-dashboard";
	$site_address = home_url();

	$template_vals = array("{{site.root_url}}" , "{{dashboard_link}}" , "{{locked.ip}}" , "{{locked.minutes}}" , "{{locked.failed_count}}" , 
	 "{{locked.failed_minutes}}" , "{{locked.usernames}}" );
	$replaced_vals = array( $site_address , $dashboard_link , $blocked_ip , $locked_for_minutes , $blocked_attempts , 
		$blocked_attempts_during_time , $blocked_usernames );

	$mail_template = file_get_contents( SIXSCAN_PLUGIN_DIR . SIXSCAN_SECURITY_LOCK_NOTIFY_FILENAME );

	return str_replace( $template_vals , $replaced_vals , $mail_template );

}

?>