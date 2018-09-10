<?php

namespace Airtasker\Challenges\Backend\HttpModules;

require_once( __DIR__ . '/Utils/Autoloader.php' );

use Airtasker\Challenges\Backend\HttpModules\Controller\TimeLimitController;
use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;

final class RequestThrottler {

	const HTTP_REQUEST_ERROR_MESSAGE = 'Unable to throttle the request. Request context is invalid.';

	public static function run() {
		$httpRequestContext = new HttpRequestContext();

		$isRequestContextValid = $httpRequestContext->isValid();

		if( ! $isRequestContextValid ) {
			error_log( self::HTTP_REQUEST_ERROR_MESSAGE );
			return;
		}

		// dependency injection of global request context into the module's controller
		TimeLimitController::run( $httpRequestContext );
	}
}
