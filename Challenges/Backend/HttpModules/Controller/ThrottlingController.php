<?php

namespace Airtasker\Challenges\Backend\HttpModules\Controller;

use Airtasker\Challenges\Backend\HttpModules\Model\ThrottlingStrategy;
use Airtasker\Challenges\Backend\HttpModules\View\ThrottlingView;
use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;
use Exception;

/**
 * ThrottlingController provides basic rate-limiting logic:
 * 1. applies rate-limiting strategy
 * 2. throttles request if limit is reached
 * 3. generates a response and sends it back
 *
 * Class ThrottlingController
 * @package Airtasker\Challenges\Backend\HttpModules\Controller
 */
abstract class ThrottlingController {

	protected $throttlingStrategy;
	protected $throttlingView;

	protected function __construct( ThrottlingStrategy $throttlingStrategy, ThrottlingView $throttlingView ) {
		$this->throttlingStrategy = $throttlingStrategy;
		$this->throttlingView = $throttlingView;
	}

	public static function run( HttpRequestContext $httpRequestContext ) {
		$throttlingController = self::getInstance( $httpRequestContext );

		$throttlingController->throttlingStrategy->apply();
		$isRequestLimitReached = $throttlingController->throttlingStrategy->isRequestLimitReached();

		if( $isRequestLimitReached ) {
			$throttlingController->throttleRequest();
		}
	}

	private function throttleRequest() {
		$response = $this->throttlingView->render( $this->throttlingStrategy );
		$httpResponseCode = $this->throttlingStrategy->getHttpResponseCode();

		$this->sendResponse( $response, $httpResponseCode );

		// stop request processing if request limit is reached
		die();
	}

	private function sendResponse( string $response, int $httpResponseCode ) {
		try {
			// header function might throw an exception when called after response is sent to client
			header( $response, true, $httpResponseCode );
		}
		catch( Exception $exception ) {
			$errorMessage = $exception->getMessage();
			error_log( $errorMessage );
		}
	}

	private static function getInstance( HttpRequestContext $httpRequestContext ) : self {
		$throttlingStrategy = static::generateStrategy( $httpRequestContext );
		$throttlingView = static::generateView();
		$throttlingController = static::generateController( $throttlingStrategy, $throttlingView );

		return $throttlingController;
	}

	abstract protected static function generateView() : ThrottlingView;

	abstract protected static function generateStrategy( HttpRequestContext $httpRequestContext ) : ThrottlingStrategy;

	abstract protected static function generateController( ThrottlingStrategy $throttlingStrategy, ThrottlingView $throttlingView ) : self;
}
