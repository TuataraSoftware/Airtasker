<?php

namespace Airtasker\Challenges\Backend\HttpModules\Controller;

use Airtasker\Challenges\Backend\HttpModules\Model\ThrottlingStrategy;
use Airtasker\Challenges\Backend\HttpModules\View\ThrottlingView;
use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;

abstract class ThrottlingController {

	protected $throttlingStrategy;
	protected $throttlingView;

	protected function __construct( ThrottlingStrategy $throttlingStrategy, ThrottlingView $throttlingView ) {
		$this->throttlingStrategy = $throttlingStrategy;
		$this->throttlingView = $throttlingView;
	}

	public static function run( HttpRequestContext $httpRequestContext ) {
		$throttlingController = static::getInstance( $httpRequestContext );

		$throttlingController->proceed();
	}

	private static function getInstance( HttpRequestContext $httpRequestContext ) : ThrottlingController {
		$throttlingStrategy = static::generateStrategy( $httpRequestContext );
		$throttlingView = static::generateView();
		$throttlingController = static::generateController( $throttlingStrategy, $throttlingView );

		return $throttlingController;
	}

	private function proceed() {
		$throttlingStrategy = $this->throttlingStrategy;

		$throttlingStrategy->apply();
		$isRequestThrottled = $throttlingStrategy->isThrottled();

		if( $isRequestThrottled ) {
			$this->sendResponse();
		}
	}

	private function sendResponse() {
		$throttlingView = $this->throttlingView;
		$throttlingStrategy = $this->throttlingStrategy;

		$response = $throttlingView->render( $throttlingStrategy );
		$httpResponseCode = $throttlingStrategy->getHttpResponseCode();

		header( $response, true, $httpResponseCode );
		die();
	}

	abstract protected static function generateView() : ThrottlingView;

	abstract protected static function generateStrategy( HttpRequestContext $httpRequestContext ) : ThrottlingStrategy;

	abstract protected static function generateController( ThrottlingStrategy $throttlingStrategy, ThrottlingView $throttlingView ) : ThrottlingController;
}
