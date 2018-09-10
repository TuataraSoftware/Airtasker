<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

abstract class ThrottlingStrategy {

	const RETRY_TIME_IN_SECONDS = 0;
	const HTTP_RESPONSE_CODE = 0;

	protected $httpRequestContext;
	protected $isThrottled;

	public function __construct( HttpRequestContext $httpRequestContext ) {
		$this->httpRequestContext = $httpRequestContext;
	}

	public function getRetryTimeInSeconds() : int {
		return static::RETRY_TIME_IN_SECONDS;
	}

	public function getHttpResponseCode() : int {
		return static::HTTP_RESPONSE_CODE;
	}

	public function isThrottled() : bool {
		return $this->isThrottled ?? false;
	}

	public function apply() {
		$this->throttle();
	}

	abstract public function throttle();
}
