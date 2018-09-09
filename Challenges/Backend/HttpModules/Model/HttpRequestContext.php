<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use DateTime;

final class HttpRequestContext {

	private $requestTime;
	private $ip;
	private $isValid;

	public function __construct() {
		$this->requestTime = new DateTime();
		$this->ip = self::parseIp();
		$this->isValid = self::validate( $this->ip );
	}

	public function isValid() : bool {
		return $this->isValid;
	}

	public function getIp() : string {
		return $this->ip;
	}

	public function getRequestTime() : DateTime {
		return $this->requestTime;
	}

	private static function validate( string $ip ) : bool {
		$isValid = ! empty( $ip );

		return $isValid;
	}

	private static function parseIp() : string {
		if( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
			return $_SERVER[ 'HTTP_CLIENT_IP' ];
		}

		if( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
			return $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
		}

		if( ! empty( $_SERVER[ 'REMOTE_ADDR' ] ) ) {
			return $_SERVER[ 'REMOTE_ADDR' ];
		}

		return '';
	}
}
