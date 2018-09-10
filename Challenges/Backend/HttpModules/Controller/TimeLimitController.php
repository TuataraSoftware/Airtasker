<?php

namespace Airtasker\Challenges\Backend\HttpModules\Controller;

use Airtasker\Challenges\Backend\HttpModules\Model\{
	ThrottlingStrategy, TimeLimitStrategy
};

use Airtasker\Challenges\Backend\HttpModules\View\{
	ThrottlingView, TimeLimitView
};

use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;

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
