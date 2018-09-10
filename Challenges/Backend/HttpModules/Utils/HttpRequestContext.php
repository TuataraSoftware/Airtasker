<?php

namespace Airtasker\Challenges\Backend\HttpModules\Utils;

/**
 * HttpRequestContext provides basic functionality of getting IP address from PHP environment.
 * There is also a simple IP validation that guarantees that IP is present and
 * further rate limiting logic is possible.
 *
 * Class HttpRequestContext
 * @package Airtasker\Challenges\Backend\HttpModules\Utils
 */
final class HttpRequestContext {

	private $ip;
	private $isValid;

	public function __construct() {
		$this->ip = self::parseIp();
		$this->isValid = self::validate( $this->ip );
	}

	public function isValid() : bool {
		return $this->isValid;
	}

	public function getIp() : string {
		return $this->ip;
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
