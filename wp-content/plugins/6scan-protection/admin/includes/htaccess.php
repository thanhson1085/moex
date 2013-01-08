<?php
if ( ! defined( 'ABSPATH' ) ) 
	die( 'No direct access allowed' );

function sixscan_htaccess_install( $htaccess_sixscan_version = "") {
	global $wp_filesystem;
	$ret_error = array();

	$is_direct = ( $wp_filesystem->method == 'direct' );	
	$local_htaccess_path = sixscan_common_get_htaccess_file_path( $is_direct );
	$htaccess_sixscan = trim ( $wp_filesystem->get_contents( sixscan_common_get_src_htaccess( $is_direct ) . $htaccess_sixscan_version ) ) . "\n\n";		

	if ( ! $wp_filesystem->copy( sixscan_common_get_gate_src( $is_direct ) , sixscan_common_get_htaccess_dest_path( $is_direct ) , TRUE , 0755 ) ) {
		$ret_error[ 'user_message' ] = 'Failed copying ' . sixscan_common_get_htaccess_dest_path( $is_direct ) . ' during installation';
		$ret_error[ 'short_description' ] = 'Failed copying htaccess during installation';
		return $ret_error;
	}		

	if ( ! $wp_filesystem->copy( sixscan_common_get_signature_src( $is_direct ), sixscan_common_get_signature_dest_path( $is_direct ) , TRUE , 0755 ) ) {
		$ret_error[ 'user_message' ] = 'Failed copying ' . sixscan_common_get_signature_src( $is_direct ) . ' during installation';
		$ret_error[ 'short_description' ] = 'Failed copying signature during installation';
		return $ret_error;
	}
	
	if ( $wp_filesystem->exists( $local_htaccess_path ) ) {
		$htaccess_content = $wp_filesystem->get_contents( $local_htaccess_path );
		$htaccess_sixscan .= preg_replace( '@# Created by 6Scan plugin(.*?)# End of 6Scan plugin@s' , '' , $htaccess_content ) ;
		$wp_filesystem->delete( $local_htaccess_path );
	}
	
	if ( $wp_filesystem->put_contents( $local_htaccess_path , $htaccess_sixscan ) === FALSE ){
		$ret_error[ 'user_message' ] = 'Failed opening htaccess during installation';
		$ret_error[ 'short_description' ] = 'Failed opening htaccess during installation';
		return $ret_error;
	}			
	
	return TRUE;
}
	
function sixscan_htaccess_uninstall() {
	global $wp_filesystem;

	if ( $wp_filesystem == NULL )
		WP_Filesystem();

	$is_direct = ( $wp_filesystem->method == 'direct' );
	$local_htaccess_path = sixscan_common_get_htaccess_file_path( $is_direct );

	try {
		if ( $wp_filesystem->exists( $local_htaccess_path ) ) {
			$htaccess_content = $wp_filesystem->get_contents( $local_htaccess_path );
			$a = preg_replace( '@# Created by 6Scan plugin(.*?)# End of 6Scan plugin@s', '', $htaccess_content) ;
	
			if ( $wp_filesystem->put_contents( $local_htaccess_path , $a ) === FALSE )
				throw new Exception('Failed to open htaccess during installation');		
		}
			
		if ( $wp_filesystem->exists( sixscan_common_get_htaccess_dest_path( $is_direct ) ) )
			$wp_filesystem->delete( sixscan_common_get_htaccess_dest_path( $is_direct ) );	
			
		if ( $wp_filesystem->exists( sixscan_common_get_signature_dest_path( $is_direct ) ) )
			$wp_filesystem->delete ( sixscan_common_get_signature_dest_path( $is_direct ) ) ;
		
	} catch( Exception $e ) {
		return( $e );
	}
	
	return TRUE;
}
?>