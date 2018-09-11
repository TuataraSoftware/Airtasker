<?php

namespace Airtasker\Challenges\Backend\HttpModules;

require_once( __DIR__ . '/Utils/Autoloader.php' );

use Airtasker\Challenges\Backend\HttpModules\Controller\OneHourController;
use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;
use Exception;

/**
 * The rate limiting module. It has no state and requires no instantiation.
 * The module generates request context and injects it to a controller handling the request.
 *
 * Class RequestRateLimiter
 * @package Airtasker\Challenges\Backend\HttpModules
 */
final class RequestRateLimiterModule {

	/**
	 * This method provides public interface to the module.
	 * It processes request and ensures that all processing exceptions are handled
	 * and not breaking client code execution.
	 */
	public static function run() {
		$httpRequestContext = new HttpRequestContext();

		try {
			OneHourController::processRequest( $httpRequestContext );
		}
		catch( Exception $exception ) {
			$errorMessage = $exception->getMessage();

			error_log( $errorMessage );
		}
	}
}
