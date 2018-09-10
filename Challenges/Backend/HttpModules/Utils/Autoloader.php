<?php

spl_autoload_register( function( $className ) {

	$relativePath = str_replace( "\\", DIRECTORY_SEPARATOR, $className ) . '.php';
	$fullPath = $_SERVER[ 'DOCUMENT_ROOT' ] . DIRECTORY_SEPARATOR . $relativePath;

	require_once( $fullPath );
} );
