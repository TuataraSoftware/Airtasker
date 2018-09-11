<?php

namespace Airtasker\Challenges\Backend\HttpModules\Controller;

use Airtasker\Challenges\Backend\HttpModules\Model\{
	ThrottlingStrategy, OneHourStrategy
};

use Airtasker\Challenges\Backend\HttpModules\View\{
	ThrottlingView, TryAgainView
};

use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;

/**
 * This class binds rate-limiting strategy and response rendering view.
 * To extend current logic with another strategy and/or view, we only need to inherit a new
 * class from ThrottlingController and implement its abstract methods.
 *
 * Class OneHourController
 * @package Airtasker\Challenges\Backend\HttpModules\Controller
 */
final class OneHourController extends ThrottlingController {

	protected static function generateStrategy( HttpRequestContext $httpRequestContext ) : ThrottlingStrategy {
		return new OneHourStrategy( $httpRequestContext );
	}

	protected static function generateView() : ThrottlingView {
		return new TryAgainView();
	}

	protected static function generateController( ThrottlingStrategy $throttlingStrategy, ThrottlingView $throttlingView ) : ThrottlingController {
		return new OneHourController( $throttlingStrategy, $throttlingView );
	}
}
