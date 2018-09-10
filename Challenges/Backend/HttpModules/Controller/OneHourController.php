<?php

namespace Airtasker\Challenges\Backend\HttpModules\Controller;

use Airtasker\Challenges\Backend\HttpModules\Model\{
	ThrottlingStrategy, OneHourStrategy
};

use Airtasker\Challenges\Backend\HttpModules\View\{
	ThrottlingView, TryAgainView
};

use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;

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
