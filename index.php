<?php

/**
 * This is an example file demonstrating usage of RequestRateLimiterModule.
 * To run it, simply open http://127.0.0.1/Airtasker/index.php in browser.
 */
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/Airtasker/Challenges/Backend/HttpModules/RequestRateLimiterModule.php' );

use Airtasker\Challenges\Backend\HttpModules\RequestRateLimiterModule;

RequestRateLimiterModule::run();
