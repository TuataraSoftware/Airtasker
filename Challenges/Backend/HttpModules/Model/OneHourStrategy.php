<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

final class OneHourStrategy extends ThrottlingStrategy {

	const HITS_LIMIT = 100;
	const RETRY_TIME_IN_SECONDS = 3600;
	const TIME_LIMIT_IN_SECONDS = 3600;
	const HTTP_RESPONSE_CODE = 429;

	public function throttle() {

		$ip = $this->httpRequestContext->getIp();

		$hitsCount = RequestCounter::getHits( $ip );

		if( $hitsCount >= self::HITS_LIMIT ) {
			$this->isThrottled = true;
			return;
		}

		$this->isThrottled = false;
		RequestCounter::incrementHits( $ip, self::TIME_LIMIT_IN_SECONDS );
	}
}
