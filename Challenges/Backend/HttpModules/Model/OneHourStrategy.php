<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

final class OneHourStrategy extends ThrottlingStrategy {

	const HITS_PER_HOUR_LIMIT = 100;
	const RETRY_TIME_IN_SECONDS = 3600;
	const ONE_HOUR_IN_SECONDS = 3600;
	const HTTP_RESPONSE_CODE = 429;

	public function throttle() {
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
