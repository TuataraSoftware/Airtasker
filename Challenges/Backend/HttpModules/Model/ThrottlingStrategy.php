<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use Airtasker\Challenges\Backend\HttpModules\Utils\HttpRequestContext;

/**
 * The base class for request-limiting strategies.
 * Provides a simple interface to instantiate and apply a strategy
 * and to get throttling throttling results.
 *
 * Class ThrottlingStrategy
 * @package Airtasker\Challenges\Backend\HttpModules\Model
 */
abstract class ThrottlingStrategy {

	// These constants should be overridden in children classes
	const RETRY_TIME_IN_SECONDS = 0;
	const HTTP_RESPONSE_CODE = 0;

	protected $httpRequestContext;
	protected $isRequestLimitReached;

	public function __construct( HttpRequestContext $httpRequestContext ) {
		$this->httpRequestContext = $httpRequestContext;
	}

	/**
	 * Public interface to apply rate limiting strategy
	 */
	public function apply() {
		$this->throttle();
	}

	public function getRetryTimeInSeconds() : int {
		return static::RETRY_TIME_IN_SECONDS;
	}

	public function getHttpResponseCode() : int {
		return static::HTTP_RESPONSE_CODE;
	}

	public function isRequestLimitReached() : bool {
		return $this->isRequestLimitReached ?? false;
	}

	/**
	 * Rate limiting logic should be implemented in this method
	 */
	abstract public function throttle();
}
