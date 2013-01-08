<?php

if ( ! defined( 'ABSPATH' ) ) 
        die( 'No direct access allowed' );

/* Called by the backup controller to send files to Amazon storage */
function sixscan_backup_comm_save_file( $backed_filename , $bckp_first_chunk ){
        
        if ( file_exists( $backed_filename ) == FALSE ){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "File $backed_filename not found";
                $backup_save_result[ 'user_result_message' ] = "6Scan failed to create the backup file.  This could indicate a permissions problem in your hosting environment.";
                return $backup_save_result;
        }

        if ( ( isset( $_REQUEST[ 'AWSAccessKeyId' ] ) == FALSE ) || ( isset( $_REQUEST[ 'backup_id' ]) == FALSE) )
                return NULL;

        $backup_id = $_REQUEST[ 'backup_id' ];
        $backup_id = is_numeric( $backup_id ) ? $backup_id : "";
        $aws_public_key = $_REQUEST[ 'AWSAccessKeyId' ];

        /* bckp_first_chunk represents our upload process. If it is 0 , we begin the upload. Otherwise - we continue from that chunk*/
        if ( $bckp_first_chunk == 0 ){
                $upload_id = sixscan_backup_comm_get_init_mpu_req( $aws_public_key , $backup_id );
                if ( is_array( $upload_id ) ){
                        /*      An error has occured, and upload id is the description */
                        return $upload_id;
                }

                $etag_arr = array();
        }
        else{
                $upload_id = isset( $_REQUEST[ 'upload_id' ] ) ? $_REQUEST[ 'upload_id' ] : 0;                                

                $etag_arr = explode( ',' , $_REQUEST[ 'etag' ] );                
        }
                         
        $filesize = filesize( $backed_filename );
        $max_accepted_file_size = ( double )$_REQUEST[ 'backup_size_limit' ]; 
         if ( $filesize > $max_accepted_file_size){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "File too large. Size is $filesize , max allowed: $max_accepted_file_size";
                $backup_save_result[ 'user_result_message' ] = "Backup file is too large (" .
                        round( $filesize / 1048576 , 2 ) . " MB); your account limit is " . round( $max_accepted_file_size / 1048576 , 2 ) . "MB.";
                return $backup_save_result;
        } 

        $chunks_count = ( $filesize / SIXSCAN_BACKUP_CHUNK_SIZE ) + 1;        //Always one more, to include the remainder bytes 
        $left_to_send = $filesize;
        $chunk_offset = 0;
        $mpu_chunk_etag = "";

        /* If we begin the backup, first part is 1. Otherwise - the chunk we haven't uploaded yet (received from server ) */
        if ( $bckp_first_chunk == 0 ){
                $part_id = 1;
        }
        else{
                $part_id = $bckp_first_chunk;
                $left_to_send -= ( ( $bckp_first_chunk - 1 ) * SIXSCAN_BACKUP_CHUNK_SIZE );
                $chunk_offset += ( ( $bckp_first_chunk - 1 ) * SIXSCAN_BACKUP_CHUNK_SIZE );
        }
                 
        for ( ; $part_id < $chunks_count + 1 ; $part_id++ ){
                if ( $left_to_send == 0 ){
                        //      If filesize was exact multiple of chunk_size, we need to skeep the remainder bytes (nothing left to write) 
                        break;
                }

                if ( $left_to_send > SIXSCAN_BACKUP_CHUNK_SIZE ){
                        $current_chunk_size = SIXSCAN_BACKUP_CHUNK_SIZE;                        
                }
                else{
                        $current_chunk_size = $left_to_send;
                }                                
                
                $mpu_chunk_etag = sixscan_backup_comm_get_chunk_mpu_req( $aws_public_key , $backup_id , $backed_filename , $current_chunk_size , $chunk_offset , $part_id , $upload_id , $mpu_chunk_etag);
                if ( is_array ( $mpu_chunk_etag ) ){  //      Error returned 
                        return $mpu_chunk_etag;
                }
                
                $etag_arr[] = $mpu_chunk_etag;        //      Add ETag to array                              

                $left_to_send -= $current_chunk_size;
                $chunk_offset +=  $current_chunk_size;
        }       
        
        /*      Send backup completed command */
        $ret_status = sixscan_backup_comm_get_complete_chunk_mpu_req( $aws_public_key , $backup_id ,$upload_id , $etag_arr );        
        
        sixscan_backup_comm_mpu_upload_status( $ret_status === TRUE , $backup_id );        

        /*      Clear the etag array */
        update_option( SIXSCAN_BACKUP_ETAG_ARRAY , array() );
        return  $ret_status;        
}



/* use curl to contact amazon*/
function sixscan_backup_comm_multipart_curl( &$response, $type , $AWSAccessKeyId , $url ,
                                $signature , $date , $is_header_required = TRUE , $etag_xml = "" , $filename = "" , $chunk_size = 0 , $offset = 0 )
{             
        
        $header = array();
        $header[] = "Date: $date";
        $header[] = "Authorization: AWS $AWSAccessKeyId:$signature";
        $header[] = "Content-Type:";
        if ( $type == 'init' )
                $header[] = "Content-Length:";
        $header[] = "Accept:";
        $header[] = "Expect:";
        
        $curl_handle = curl_init();           
        curl_setopt( $curl_handle , CURLOPT_RETURNTRANSFER , TRUE ); 
        curl_setopt( $curl_handle , CURLOPT_HTTPHEADER , $header );
        curl_setopt( $curl_handle , CURLOPT_URL , $url );
        curl_setopt( $curl_handle , CURLOPT_HEADER , $is_header_required );
        curl_setopt( $curl_handle , CURLOPT_SSL_VERIFYPEER , FALSE );
        curl_setopt( $curl_handle , CURLOPT_SSL_VERIFYHOST , FALSE );

        if ( $type == 'part' )
        {
                curl_setopt( $curl_handle , CURLOPT_PUT , TRUE );                
                $fp = fopen( $filename , "rb" );
                fseek( $fp, $offset );
                curl_setopt( $curl_handle , CURLOPT_INFILE , $fp ); 
                curl_setopt( $curl_handle, CURLOPT_READFUNCTION, 'sixscan_backup_comm_curl_readfn' );
                global $data_chunk_size;
                $data_chunk_size = $chunk_size ;
                curl_setopt( $curl_handle , CURLOPT_INFILESIZE , $chunk_size );
        }
        else
        {
                if ( $type =='complete')
                {
                        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $etag_xml);
                }
                curl_setopt( $curl_handle , CURLOPT_POST , TRUE );        
        }            
        global $data_written;
        $data_written = 0;
        
        $response = curl_exec( $curl_handle );             

        if (curl_errno($curl_handle) != 0 ){                
                $response .=  curl_error( $curl_handle );                
                $http_code = 0;
        }else{
                $http_code = curl_getinfo( $curl_handle , CURLINFO_HTTP_CODE );        
        }        
        
        if ( ( $type == 'part' ) && ( $fp ) )        
                fclose( $fp );

        curl_close( $curl_handle );
 
        return $http_code;
}

function sixscan_backup_comm_curl_readfn($ch, $fp, $length)
{       
        global $data_written;
        global $data_chunk_size;

        if ($data_chunk_size <= $data_written)
                return FALSE;
        
        $data_written += $length;
        $data = fread( $fp, $length);
        
        return $data;
}

function sixscan_backup_comm_mpu_upload_status( $ret_status , $backup_id ){
        if ( $ret_status )
                $upload_status = "true";
        else
                $upload_status = "false";
        
        sixscan_backup_comm_req_signature_from_server( 'COMPLETE' , $upload_status , '' , $backup_id );
}

function sixscan_backup_comm_req_signature_from_server( $req_type , $params , $date , $backup_id , $prev_etag = "" ){
        $req_signature = array();

        $api_token = sixscan_common_get_api_token();
        $site_id = sixscan_common_get_site_id();
        $url = SIXSCAN_BODYGUARD_6SCAN_BACKUP_MPU_SIG_URL . "?site_id=" . $site_id . "&api_token=" . $api_token 
                                                        . "&backup_id=" . $backup_id .  "&req_type=" . $req_type 
                                                        . "&date=" . urlencode( $date ) . "&prev_etag=" . $prev_etag
                                                        . "&parameters=" . urlencode( $params );

        $result = sixscan_common_request_network( $url , NULL ); 
        if ( is_wp_error( $result ) == TRUE )
                return NULL;

        $result = json_decode( wp_remote_retrieve_body( $result ) );        
        $req_signature['url'] = base64_decode( urldecode( $result->url ) );
        $req_signature['signature'] = $result->signature;

        return $req_signature;
}

function sixscan_backup_comm_get_init_mpu_req( $AWSAccessKeyId , $backup_id ){
        $backup_save_result = array();

        $date = gmdate( 'D, d M Y H:i:s +0000' , time() );        
        $server_sig = sixscan_backup_comm_req_signature_from_server( 'POST' , '?uploads' , $date , $backup_id );
        
        if ( $server_sig == NULL ){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "Failed getting signature from server. Init stage, POST, ?uploads";
                $backup_save_result[ 'user_result_message' ] = "6Scan failed to verify backup ownership";
                return $backup_save_result;
        }

        $curl_retval = sixscan_backup_comm_multipart_curl( $response , 'init' , $AWSAccessKeyId , $server_sig[ 'url' ] , $server_sig[ 'signature' ] , $date , FALSE );
        if ( $curl_retval != 200 ){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "Multipart curl init failed with http code $curl_retval, response - $response";
                $backup_save_result[ 'user_result_message' ] = "6Scan request to storage has failed";
                return $backup_save_result;                
        }

        $xml = simplexml_load_string( $response );
        $upload_Id = $xml->UploadId;
        return $upload_Id;
}


function sixscan_backup_comm_get_chunk_mpu_req( $AWSAccessKeyId , $backup_id , $filename , $chunk_size , $chunk_offset , $part_id , $upload_id , $mpu_chunk_etag ){
        $backup_save_result = array();
        $date = gmdate( 'D, d M Y H:i:s +0000' , time() );
        $upload_params = "?partNumber=$part_id&uploadId=$upload_id";
        $server_sig = sixscan_backup_comm_req_signature_from_server( 'PUT' , $upload_params , $date , $backup_id , $mpu_chunk_etag );
       
        if ( $server_sig == NULL ){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "Failed getting signature from server. Part $part_id stage, upload_id = $upload_id";
                $backup_save_result[ 'user_result_message' ] = "6Scan failed to verify backup ownership";
        }         

        $curl_retval = sixscan_backup_comm_multipart_curl( $response , 'part' , $AWSAccessKeyId , $server_sig[ 'url' ] , $server_sig[ 'signature' ] , $date , TRUE ,
                '' , $filename  , $chunk_size , $chunk_offset );
        if ( $curl_retval != 200 ){
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "Multipart curl chunk request failed with http code $curl_retval, response - $response";
                $backup_save_result[ 'user_result_message' ] = "6Scan request to storage has failed";
                return $backup_save_result;                
        }

        $pattern = '/ETag:\s+"(\w+)"/';
        if ( preg_match( $pattern , $response , $result )){
                 $response = $result[1];
        }
        else{
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "Multipart curl chunk request has returned no ETAG in response. Response = $response";
                $backup_save_result[ 'user_result_message' ] = "6Scan request to storage has failed";
                return $backup_save_result;                
        }
        
        return $response;       //eTag if success 
}

function sixscan_backup_comm_get_complete_chunk_mpu_req( $AWSAccessKeyId , $backup_id , $upload_id , $etag_arr ){
        $date = gmdate( 'D, d M Y H:i:s +0000' , time() );
        $complete_params = "?uploadId=$upload_id";

        $server_sig = sixscan_backup_comm_req_signature_from_server( 'POST' , $complete_params , $date , $backup_id );
        if ( $server_sig == NULL )
                return NULL;

        $etag_xml = "<CompleteMultipartUpload>";
        $part_number = 1;
        foreach ( $etag_arr as $one_etag ){
                $etag_xml .= "<Part><PartNumber>$part_number</PartNumber><ETag>$one_etag</ETag></Part>";
                $part_number++;
        }
        $etag_xml .= "</CompleteMultipartUpload>";
        
        $curl_http_response = sixscan_backup_comm_multipart_curl( $response, 'complete' , $AWSAccessKeyId , $server_sig[ 'url' ] , $server_sig[ 'signature' ] , 
                $date , TRUE , $etag_xml );

        if ( $curl_http_response == 200 ){
                return TRUE;
        }
        else{
                $backup_save_result = array();
                $backup_save_result[ 'success' ] = FALSE;
                $backup_save_result[ 'internal_message' ] = "Multipart curl complete request has returned http code $curl_http_response , and response $response";
                $backup_save_result[ 'user_result_message' ] = "6Scan request to storage has failed";
                return $backup_save_result;                 
        }        
}

?>