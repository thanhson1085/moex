<?php
	function return_yop_poll( $id = -1 ) {
		global $yop_poll_public_admin;
		print $yop_poll_public_admin->return_yop_poll( $id );
	}
	
	function return_yop_poll_archive( ) {
		global $yop_poll_public_admin;
		print $yop_poll_public_admin->yop_poll_archive_shortcode_function( );
	}