<?php

require_once( '../../../../wp-load.php' );

$file = (int)$_GET[ 'file' ];

$file_url = wp_get_attachment_url( $file );

if( !empty( $file_url ) && $file_url ) {
	header( 'Pragma: public' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Cache-Control: private', false );
	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/force-download' );
	header( 'Content-Disposition: attachment; filename="' . basename( $file_url ) . '"' );
	header( 'Content-Transfer-Encoding: binary' );
	readfile( $file_url );
}

die();
?>