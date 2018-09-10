<?php

namespace Airtasker\Challenges\Backend\HttpModules\View;

require_once( __DIR__ . '/../Model/ThrottlingStrategy.php' );

use Airtasker\Challenges\Backend\HttpModules\Model\{
	ThrottlingStrategy
};

abstract class ThrottlingView {

	public function render( ThrottlingStrategy $throttlingStrategy ) : string {
		$message = static::formatMessage( $throttlingStrategy );

		return $message;
	}

	abstract protected function formatMessage( ThrottlingStrategy $throttlingStrategy ) : string;
}
