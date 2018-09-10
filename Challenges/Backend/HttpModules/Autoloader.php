<?php

spl_autoload_register( function( $className ) {

	$className = str_replace( "\\", DIRECTORY_SEPARATOR, $className );
	$fullPath = $_SERVER[ 'DOCUMENT_ROOT' ] . DIRECTORY_SEPARATOR .  $className . '.php';
	$fullPath = str_replace('Airtasker/', '', $fullPath);

	require_once( $fullPath );
} );
