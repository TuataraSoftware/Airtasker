<?php

namespace Airtasker\Challenges\Backend\HttpModules\Controller;

require_once (__DIR__ . '/../Model/HttpRequestContext.php' );
require_once( __DIR__ . '/../Model/ThrottlingStrategy.php' );
require_once( __DIR__ . '/../Model/TimeLimitStrategy.php' );
require_once( __DIR__ . '/../View/ThrottlingView.php' );
require_once( __DIR__ . '/../View/TimeLimitView.php');
require_once (__DIR__ . '/ThrottlingController.php');

use Airtasker\Challenges\Backend\HttpModules\Model\{
	HttpRequestContext, ThrottlingStrategy, TimeLimitStrategy
};
use Airtasker\Challenges\Backend\HttpModules\View\{
	ThrottlingView, TimeLimitView
};

final class TimeLimitController extends ThrottlingController {

	protected static function generateStrategy( HttpRequestContext $httpRequestContext ) : ThrottlingStrategy {
		return new TimeLimitStrategy( $httpRequestContext );
	}

	protected static function generateView() : ThrottlingView {
		return new TimeLimitView();
	}

	protected static function generateController( ThrottlingStrategy $throttlingStrategy, ThrottlingView $throttlingView ) : ThrottlingController {
		return new TimeLimitController( $throttlingStrategy, $throttlingView );
	}
}
