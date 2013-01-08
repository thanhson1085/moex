<?php

if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );

function sixscan_communication_oracle_auth_get_link( $redirect_link ) {
	/*	Nonce increases everytime user accesses dashboard */
	$current_nonce = get_option( SIXSCAN_OPTION_COMM_ORACLE_NONCE ) + 1;	
	update_option( SIXSCAN_OPTION_COMM_ORACLE_NONCE , $current_nonce );

	return sixscan_communication_oracle_auth_dashboard_get( $current_nonce ,$redirect_link );
}

function sixscan_communication_oracle_auth_dashboard_get( $nonce , $redirect_link ) {
	
	if ( ( sixscan_common_get_dashboard_token() == FALSE ) || ( sixscan_common_get_site_id() == FALSE ) )
		return FALSE;
	
	$paypal_return_get =  isset( $_GET[ 'thankyou' ] ) ? 1 : 0;
	$fixnow_get = isset( $_GET[ 'fixnow' ] ) ? 1 : 0;

	$token_for_dashboard = md5( SIXSCAN_COMM_ORACLE_AUTH_SALT . $nonce . sixscan_common_get_dashboard_token() );
	$dashboard_url = SIXSCAN_COMM_ORACLE_AUTH_DASHBOARD_URL . 'site_id=' .  sixscan_common_get_site_id()
															 . '&nonce=' . $nonce . '&token=' . $token_for_dashboard 
															 . '&redirect_to=' . $redirect_link . "&thankyou=" . $paypal_return_get . "&fixnow=" . $fixnow_get;
	
	return $dashboard_url;	
}

?>