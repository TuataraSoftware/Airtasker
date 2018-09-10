<?php

/**
 * This function loads class files by their namespaces and names.
 * It avoids multiple require_once() calls across codebase and is called by PHP itself.
 */
spl_autoload_register( function( $classNameWithNamespace ) {

	$relativePath = str_replace( "\\", DIRECTORY_SEPARATOR, $classNameWithNamespace ) . '.php';
	$fullPath = $_SERVER[ 'DOCUMENT_ROOT' ] . DIRECTORY_SEPARATOR . $relativePath;

	require_once( $fullPath );
} );
