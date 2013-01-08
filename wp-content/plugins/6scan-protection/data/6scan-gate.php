<?php

$sixscan_htaccess_ver 	= getenv( 'SIXSCAN_HTACCESS_VERSION' );
$sixscan_base_dir 		= getenv( 'SIXSCAN_WP_BASEDIR' );

/* This is the original URL the user tried to access before the rewrite,
 e.g. /dir/file.php or /images/img.jpg */
$url = $_SERVER[ 'REQUEST_URI' ];
$qspos = strpos( $url , '?' );
if ( $qspos !== FALSE ){
	$url = substr( $url , 0 , $qspos );
}

if ( $sixscan_htaccess_ver == "1"){
	/*	New version also has support for servers, that have DOCUMENT_ROOT != server root */	
	$base_dir_length = strlen( $sixscan_base_dir ) - 1;	/*	Minus the head slash */
	if ( $base_dir_length == 0 )	/*	If we are actually in the root */
		$site_document_root = getcwd();
	else
		$site_document_root = substr ( getcwd() , 0 , -1 * $base_dir_length );		
}
else{
	$site_document_root = $_SERVER[ 'DOCUMENT_ROOT' ];	
}
		
/*	Construct real path  , while eliminating the extra '/' and '..' and other chars from request*/
$path = realpath( $site_document_root . $url );		

/*	"Subtract" cwd() from full path , to get the relative path to the vuln script */
$path_from_cwd = substr( $path , strlen( getcwd() ) );

/*	If there is windows style path , make it linux */	
$path_from_cwd = str_replace( "\\" , "/" , $path_from_cwd );

if ( file_exists( '6scan-signature.php' ) ) {
	require_once( '6scan-signature.php' );	

/* $path = this is the file that would map to the URL without any rewriting 
	$path_from_cwd = path to the vuln script, from current directory 
	If there is no such file , we are referred here by permalinks redirection. 
	Sanitize rules expect "/index.php" as vulnerable url in that case */
		
	if  ( is_file( $path ) == FALSE )
		sixscan_sanitize_input( "/index.php" );
	else
		sixscan_sanitize_input( $path_from_cwd );
}
/*	We continue to the requested file , or to index.php , if we were redirected by permalinks (and does not really exist) */
if ( is_file( $path ) ){
	/* Update the server environment so the PHP script thinks it was called
	 right off the bat. */
	$_SERVER[ 'SCRIPT_FILENAME' ] = $path;
	$_SERVER[ 'SCRIPT_NAME' ] = $path_from_cwd;
	$_SERVER[ 'PHP_SELF' ] = $path_from_cwd;

	chdir( dirname( $path ) );
	require $path;
}
else{
	/*	Updates for index.php */
	if ($sixscan_base_dir != '/')
		$sixscan_base_dir .= '/';
	$_SERVER[ 'SCRIPT_FILENAME' ] = realpath( $site_document_root ) . '/' . $sixscan_base_dir . 'index.php';
	$_SERVER[ 'SCRIPT_NAME' ] = $sixscan_base_dir . "index.php";
	$_SERVER[ 'PHP_SELF' ] = $sixscan_base_dir . "index.php";	
	require( "index.php" );
}

?>