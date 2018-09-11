<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

final class OneHourStrategy extends ThrottlingStrategy {

	const HITS_PER_HOUR_LIMIT = 100;
	const RETRY_TIME_IN_SECONDS = 3600;
	const ONE_HOUR_IN_SECONDS = 3600;
	const HTTP_RESPONSE_CODE = 429;
	const HTTP_REQUEST_ERROR_MESSAGE = 'Unable to throttle the request. Request context is invalid.';

	public function throttle() {
		$isRequestContextValid = $this->httpRequestContext->isValid();

		if( ! $isRequestContextValid ) {
			error_log( self::HTTP_REQUEST_ERROR_MESSAGE );
			return;
		}

		$ip = $this->httpRequestContext->getIp();

		$redisClient = RedisClient::generate();
		$hitsCount = $redisClient->getCounter( $ip );

		if( $hitsCount >= self::HITS_PER_HOUR_LIMIT ) {
			$this->isRequestLimitReached = true;

			return;
		}

		$this->isRequestLimitReached = false;
		$redisClient->setCounter( $ip, self::ONE_HOUR_IN_SECONDS );
	}
}
