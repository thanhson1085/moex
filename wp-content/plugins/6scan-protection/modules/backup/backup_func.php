<?php

function sixscan_backup_func_controller( $backup_type , &$backup_err_descr ){
	/* Empty error message */	

	/* Check whether backup can be run on this system */
	$run_backup_prerequisites = sixscan_backup_func_can_run( $backup_type );

	/* If there was a false in prerequisites */
	if ( array_search( FALSE , array_values( $run_backup_prerequisites ) , TRUE ) !== FALSE ){
		# return prerequisites only for database
		$backup_err_descr[ 'prerequisites' ] = $run_backup_prerequisites;	
		$backup_err_descr[ 'success' ] = FALSE;
		return FALSE;
	}
	
	/* Set enough time for backup to work. */
	set_time_limit( SIXSCAN_BACKUP_MAX_RUN_SECONDS );	
	ini_set( 'max_input_time' , SIXSCAN_BACKUP_MAX_RUN_SECONDS );		
	
	/*	If a backup is in progress, and server has requested to continue with a chunk id, do not create new archive */		
	$bckp_first_chunk = isset( $_REQUEST[ SIXSCAN_NOTICE_BCKP_PART_ID_REQUEST ] ) ? intval( $_REQUEST[ SIXSCAN_NOTICE_BCKP_PART_ID_REQUEST ] ) : 0;

	/* Run backup according to what was requested */
	if ( $backup_type == SIXSCAN_BACKUP_DATABASE_REQUEST ){		

		 $backup_result = sixscan_backup_func_db( $backup_name_file_result );					
	}
	else if ( $backup_type == SIXSCAN_BACKUP_FILES_REQUEST ){

		if ( $bckp_first_chunk == 0 ){
			$backup_result = sixscan_backup_func_files( $backup_name_file_result );
		}
		else{			
			/*	We continue with former archive */
			$backup_name_file_result = get_option( SIXSCAN_BACKUP_LAST_FS_NAME );
			/*	If get_option returned FALSE, we will exit with error */
			$backup_result = ( $backup_name_file_result !== FALSE );			
		}
	}
	else {
		 $backup_err_descr[ 'success' ] = FALSE;
		 $backup_err_descr[ 'user_result_message' ] = "Bad action type requested";
		 $backup_err_descr[ 'internal_message' ] = "Bad action type requested";
		 return FALSE;
	}
	
	/*	An error occured */
	if ( $backup_result === FALSE){
	 	$backup_err_descr = array_merge(  $backup_err_descr , $backup_name_file_result );		
		return FALSE;				
	}

	/* Save to amazon */
	$amazon_save_val = sixscan_backup_comm_save_file( $backup_name_file_result , $bckp_first_chunk );	

	/* Cleanup */
	if ( file_exists( $backup_name_file_result ) ){
		$backup_err_descr[ 'md5' ] = md5_file( $backup_name_file_result );
		$backup_err_descr[ 'size' ] = filesize( $backup_name_file_result );
		unlink( $backup_name_file_result );
	}
		
	/* If we have failed uploading the file to amazon - return the error description */
	if ( $amazon_save_val !== TRUE ){
	
		$backup_err_descr = array_merge(  $backup_err_descr ,$amazon_save_val );
		return FALSE;
	}	
	
	$backup_err_descr[ 'success' ] = TRUE;
	return TRUE;
}

function sixscan_backup_func_can_run($backup_type){
	$requirements_table = array();

	@set_time_limit( SIXSCAN_BACKUP_MAX_RUN_SECONDS );
	if ( $backup_type == SIXSCAN_BACKUP_FILES_REQUEST )
	{
		/* We don't run on Windows now */
		if ( sixscan_common_is_windows_os() == TRUE ){		
			$requirements_table[ 'Operating system' ] =  FALSE;
		}
		else{
			$requirements_table[ 'Operating system' ] =  TRUE;	
		}

		/* Testing whether we can execute functions */
		$disabled_functions = ini_get( "disable_functions" );
		if ( strstr( $disabled_functions , "passthru" ) !== FALSE ){
			$requirements_table[ 'passthru() enabled' ] = FALSE;
		}
		else{
			$requirements_table[ 'passthru() enabled' ] = TRUE;
		}

		/*	Can't run in safe mode */
		if ( ini_get( 'safe_mode' ) ){
			$requirements_table[ 'Safe mode disabled' ] =  FALSE;
		}
		else{
			$requirements_table[ 'Safe mode disabled' ] =  TRUE;	
		}

		/* We need to be able to change execution time */		
		if ( ini_get( 'max_execution_time' ) != SIXSCAN_BACKUP_MAX_RUN_SECONDS ){
			$requirements_table[ 'Max execution time' ] =  FALSE;
		}
		else{
			$requirements_table[ 'Max execution time' ] =  TRUE;	
		}
	}

	/* Requires libcurl for file upload */
	if ( function_exists( 'curl_init' ) == FALSE ){
		$requirements_table[ 'libcurl installed' ] = FALSE;
	}
	else{
		$requirements_table[ 'libcurl installed' ] = TRUE;
	}

	/* Check libcurl for SSL support */
	$libcurl_version = curl_version();
	$libcurl_is_ssl_supported = ( $libcurl_version[ 'features' ] & CURL_VERSION_SSL );
	if ( (bool)$libcurl_is_ssl_supported != TRUE ){
		$requirements_table[ 'libcurl SSL support' ] = FALSE;
	}
	else{
		$requirements_table[ 'libcurl SSL support' ] = TRUE;
	}

	return $requirements_table;
}

/*  Run files backup */
function sixscan_backup_func_files( &$backed_up_filename ){
	
	/*	Generate random seed and random file name */
	srand( (double) microtime() * 1000000 );
	$tmp_random_seed = date("Y-m-d-H-i-s") . "_" . substr( md5 ( rand( 0, 32000 )) , 0 , 10 );	
	$temp_file_archived = get_temp_dir() . "files_backup_$tmp_random_seed.tar.gz";
	$tmp_backup_dir = "/tmp/6scan_backup_$tmp_random_seed/";

	/*	If a previous file was not deleted from some reason, delete it now */
	sixscan_backup_func_delete_previous( SIXSCAN_BACKUP_LAST_FS_NAME , $temp_file_archived );

	/* Prepare backup directory */	
	$backup_cmd = "mkdir $tmp_backup_dir; cp -r " . ABSPATH . " $tmp_backup_dir";
	ob_start(); passthru( $backup_cmd ); ob_end_clean();

	/* Linux backup is using tar.gz */		
	$backup_cmd = "tar -czf $temp_file_archived $tmp_backup_dir 2>&1";
	$ret_val = 0;

	/* Run the tar command, while ignoring its output */
	ob_start(); passthru( $backup_cmd , $ret_val ); $tar_output = ob_get_contents(); ob_clean();

	$cleanup_cmd = "rm -rf $tmp_backup_dir 2>&1";
	passthru( $cleanup_cmd );

	/* Check for error that might've occured while running tar. Retval 0 is ok */
	if ( $ret_val == 0 ){
		$backed_up_filename = $temp_file_archived;		 		
		return TRUE;
	}
	else{
		$backed_up_filename[ 'internal_message' ] = "Failed running tar. Retval = $ret_val , Tar error: $tar_output";
		$backed_up_filename[ 'user_result_message' ] = "Your hosting environment does not support the tar command, required for backup archiving.";
		$backed_up_filename[ 'success' ] = FALSE;
		return FALSE;
	}
}

/*	Used to compress database backup file */
function sixscan_backup_func_compress( $filename , $compressed_filename ){	
	require_once ( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
	$zip_archive = new PclZip( $compressed_filename );
	if ( $zip_archive->add( $filename , PCLZIP_OPT_REMOVE_ALL_PATH ) == 0 ){
		return $zip_archive->errorInfo( true );
	}
	return TRUE;
}


/* Run DB backup */
function sixscan_backup_func_db( &$backed_up_filename ){

	/*	Generate random seed and random file name */
	srand( (double) microtime() * 1000000 );
	$tmp_sql_dmp = "sql_dump" . date("Y-m-d-H-i-s") . "_" . substr( md5 ( rand( 0, 32000 )) , 0 , 10 );
	$temp_sql_file_name = get_temp_dir() . $tmp_sql_dmp . ".sql";	
	$temp_sql_file_zipped = get_temp_dir() . $tmp_sql_dmp.".zip";
	
	/*	If a previous file was not deleted from some reason, delete it now and save the current filename */
	sixscan_backup_func_delete_previous( SIXSCAN_BACKUP_LAST_DB_NAME , $temp_sql_file_zipped );		

	$sql_backup_result = sixscan_backup_sql( DB_HOST , DB_USER , DB_PASSWORD , DB_NAME , $temp_sql_file_name );
	if ( $sql_backup_result != TRUE ){
		$backed_up_filename[ 'internal_message' ] = "Could not create SQL file. Error returned: $sql_backup_result";
		$backed_up_filename[ 'user_result_message' ] = "Failed creating SQL backup (Reported error: $sql_backup_result )";
		$backed_up_filename[ 'success' ] = FALSE;		
		return FALSE;				
	}
	
	$zip_result = sixscan_backup_func_compress( $temp_sql_file_name, $temp_sql_file_zipped );
	if ( $zip_result !== TRUE )
	{
		$backed_up_filename[ 'internal_message' ] = "Could not create zip file. Error returned: $zip_result";
		$backed_up_filename[ 'user_result_message' ] = "Failed creating ZIP file (write permissions at " . get_temp_dir() . " ?)";
		$backed_up_filename[ 'success' ] = FALSE;		
		return FALSE;		
	}	
	
	@unlink( $temp_sql_file_name );

	$backed_up_filename = $temp_sql_file_zipped;
	return TRUE;
}

/*	If, from any reason (like OOM, server reset or anything else), the script was interrupted
	while creating/uploading a backup, we should cleanup the archive files. */
function sixscan_backup_func_delete_previous( $backup_type , $new_backup_filename ){

	$last_db_backup_name = get_option( $backup_type );
	if ( file_exists( $last_db_backup_name ) )
		@unlink( $last_db_backup_name );
	update_option( $backup_type , $new_backup_filename );
}


/* Backup the db OR just a table */
function sixscan_backup_sql( $host , $user , $pass , $name , $sql_output_file )
{
	/*	Dump to file every 1000 records, to avoid storing large chunks of data in memory */
  	define( 'DUMP_RECORD_COUNT' , 1000 ); 

  	/* Access the SQL database */
	$sql_link = mysql_connect( $host , $user , $pass );
	if ( $sql_link === FALSE )
		return "Failed connecting to MySQL database on $host";

	mysql_select_db( $name , $sql_link );
  
 	/*	Getting table list */
	$tables = array();
	$result = mysql_query( 'SHOW TABLES' , $sql_link );
	if ( $result == FALSE )
		return "Failed enumerating tables";

	while( $row = mysql_fetch_row( $result ) )
	{
		$tables[] = $row[0];
	}
  
  	$dumped_record_count = 0;
	$sql_dump_data = '';	
	foreach( $tables as $table ){
		$result = mysql_query( 'SELECT * FROM ' . $table , $sql_link );		
		if ( $result == FALSE )
			return "Failed reading table $table";

		$num_fields = mysql_num_fields( $result );

		/* Create tables syntax: */
		$sql_dump_data.= 'DROP TABLE IF EXISTS `' . $table . '`;';
		$row2 = mysql_fetch_row( mysql_query( 'SHOW CREATE TABLE `' . $table . '`' , $sql_link ) );
		$sql_dump_data .= "\n\n" . $row2[ 1 ] . ";\n\n";

		/* Dump each table's data */
		for ( $i = 0 ; $i < $num_fields ; $i++ ) {

			while( $row = mysql_fetch_row( $result ) ){
				/*	Prepare insert query for each row */
				$sql_dump_data .= 'INSERT INTO `' . $table .'` VALUES(';
				
				for( $j = 0 ; $j < $num_fields ; $j++ ) {
					$row[ $j ] = addslashes( $row[ $j ] );          
					$row[ $j ] = str_replace( "\n" , "\\n" , $row[ $j ] );

					/* Add the data itself */
					if ( isset( $row[ $j ] ) ){
						$sql_dump_data.= '"'.$row[$j].'"' ; 
					}
					else {
						$sql_dump_data.= '""'; 
					}

					/*	Separating the values with comma (except the last one ) */
					if ( $j < ( $num_fields - 1 ) ) {
						$sql_dump_data.= ','; 
					}
				}
			
				$sql_dump_data.= ");\n";
				$dumped_record_count++;
				if ( $dumped_record_count == DUMP_RECORD_COUNT ){
					// append data to avoid large memory demands
					if ( file_put_contents( $sql_output_file , $sql_dump_data , FILE_APPEND ) === FALSE )
						return "Failed writing data to SQL backup file $sql_output_file";

					$sql_dump_data = '';
					$dumped_record_count = 0;
				}
			}
		}

		$sql_dump_data.="\n\n\n";
		// append data to avoid large memory demands
		if ( file_put_contents( $sql_output_file , $sql_dump_data , FILE_APPEND ) === FALSE ){
			return "Failed writing data to SQL backup file $sql_output_file";
		}

		$sql_dump_data = '';
	}
	
	mysql_close( $sql_link );
	return TRUE;
}

?>