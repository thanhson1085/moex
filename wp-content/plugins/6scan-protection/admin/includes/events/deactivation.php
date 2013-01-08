<?php

if ( ! defined( 'ABSPATH' ) )  
	die( 'No direct access allowed' );

function sixscan_events_deactivation() {
	
	if ( sixscan_common_is_account_active() == TRUE ) {
		sixscan_communication_oracle_reg_deactivate( sixscan_common_get_site_id() , sixscan_common_get_api_token() );
		
		/*	"Not active" , this will disallow scanner to work on this host */
		sixscan_common_set_account_active( FALSE );
	}
	
	/* Revert the .htaccess to "pre-6scan" state */
	sixscan_htaccess_uninstall();
}	

?>