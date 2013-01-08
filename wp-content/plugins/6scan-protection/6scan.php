<?php
/*
Plugin Name: 6Scan Security
Plugin URI: http://www.6scan.com/
Description: 6Scan Security provides enterprise-grade security with a firewall, automatic backup, analytics and much more.
Author: 6Scan
Version: 2.3.1
Author URI: http://www.6scan.com
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );
	
if ( defined( 'SIXSCAN_PLATFORM_TYPE' ) ){
	/*	Do not allow double install of 6Scan plugins */
	if ( SIXSCAN_PLATFORM_TYPE == 'wordpress_backup' ){			
		die( 'You already have the 6Scan Backup plugin installed.  The 6Scan Backup plugin also provides full security functionality and vulnerability scanning -- simply access your dashboard and click the Scan Report tab.' );
	}
	
	die('You already have this plugin installed.  To prevent conflicts, you cannot install two copies of this plugin.  Please access your 6Scan Dashboard to see the status of your plugin and change settings.');
}

/* Get the current plugin directory name and URL , while we are at the root */
define( 'SIXSCAN_PLUGIN_DIR' ,			trailingslashit( dirname(__FILE__) ) );	
define( 'SIXSCAN_PLUGIN_URL' ,			trailingslashit( plugins_url( basename ( dirname (__FILE__) ) ) ) );
define( 'SIXSCAN_PLUGIN_DIRNAME' ,		basename( dirname ( __FILE__ ) ) );

/* Platform type */
define ( 'SIXSCAN_PLATFORM_TYPE' , 	'wordpress' );

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once( 'admin/includes/common.php' );
require_once( 'admin/includes/htaccess.php' );
require_once( 'admin/includes/installation.php' );
require_once( 'admin/includes/events/deactivation.php' );
require_once( 'admin/includes/events/uninstall.php' );
require_once( 'modules/communication/oracle-reg.php' );
require_once( 'modules/communication/oracle-auth.php' );
require_once( 'modules/signatures/analyzer.php' );
require_once( 'modules/signatures/loginsec.php' );
require_once( 'modules/signatures/update.php' );
require_once( 'admin/includes/6scan-menu.php' );
require_once( 'modules/stat/analytics.php' );

if ( is_admin() ) { 
	/*	We do not use the usual activation hook, since we want to show extended error message, if something went sideways */
	register_deactivation_hook( __FILE__ , 	'sixscan_events_deactivation' );
	register_uninstall_hook( __FILE__ , 	'sixscan_events_uninstall' );		
	
	/*	This action installs the plugin */
	if ( sixscan_common_is_account_active() == FALSE ){		
		add_action( 'admin_notices' , 'sixscan_installation_manager' );
	}
	else{
		/*	This action checks whether the plugin has registered, and if not - shows the "don't forget to register" notice to the user 
		This is only shown, if the plugin is active */
		add_action( 'admin_notices' , 'sixscan_installation_account_setup_required_notice' );
	}
	
	/*	6Scan menu in Wordpress toolbar */
	add_action( 'admin_menu' , 'sixscan_menu_install' );	

	/*	Vulnerabilities found warning */
	add_action( 'admin_notices' , 'sixscan_menu_show_vulnerabilities_warning' );	
}

/*	Setting/clearing admin auth cookie */	
add_action( 'init' , 'sixscan_wordpress_admin_set_cookie_callback' );	

sixscan_signatures_analyzer_suspicious_request();

sixscan_signatures_loginsec_register();

if ( sixscan_common_run_signature_check_request() == FALSE ){
	header('HTTP/1.1 403 Forbidden');
	exit();
}

?>