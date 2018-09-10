<?php

namespace Airtasker\Challenges\Backend\HttpModules\View;

use Airtasker\Challenges\Backend\HttpModules\Model\ThrottlingStrategy;

final class TryAgainView extends ThrottlingView {

	const MESSAGE_TEMPLATE = 'Rate limit exceeded. Try again in %d seconds';

	protected function formatMessage( ThrottlingStrategy $throttlingStrategy ) : string {
		$retryTimeInSeconds = $throttlingStrategy->getRetryTimeInSeconds();
		$message = sprintf( self::MESSAGE_TEMPLATE, $retryTimeInSeconds );

		return $message;
	}
}
