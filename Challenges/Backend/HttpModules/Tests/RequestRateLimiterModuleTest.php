<?php

require_once( __DIR__ . '/../RequestRateLimiterModule.php' );

use PHPUnit\Framework\TestCase;

final class RequestRateLimiterModuleTest extends TestCase {

	const ENDPOINT_URL = 'http://127.0.0.1/Airtasker/index.php';
	const ENDPOINT_URL_FLUSH = 'http://127.0.0.1/Airtasker/flushRedis.php';

	const HTTP_CODE_OK = 200;
	const HTTP_CODE_RATE_LIMITED = 429;
	const HTTP_CODE_ERROR = 500;

	private static function sendHttpRequest( string $url = self::ENDPOINT_URL_FLUSH ) : int {
		$ch = curl_init( $url );
		curl_exec( $ch );

		$httpCode = self::HTTP_CODE_ERROR;

		if( ! curl_errno( $ch ) ) {
			$httpCode = curl_getinfo( $ch, CURLINFO_RESPONSE_CODE );
		}

		curl_close( $ch );

		return $httpCode;
	}

	private static function flushRedis() : void {
		self::sendHttpRequest( self::ENDPOINT_URL_FLUSH );
	}

	private static function getHttpCode() : int {
		return self::sendHttpRequest( self::ENDPOINT_URL );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRun99Times() : void {
		self::flushRedis();

		for( $i = 0; $i < 99; $i ++ ) {
			$this->assertEquals( self::getHttpCode(), self::HTTP_CODE_OK );
		}
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRun100Times() : void {
		self::flushRedis();

		for( $i = 0; $i < 100; $i ++ ) {
			$this->assertEquals( self::getHttpCode(), self::HTTP_CODE_OK );
		}
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRun101Times() : void {
		self::flushRedis();

		for( $i = 0; $i < 100; $i ++ ) {
			$this->assertEquals( self::getHttpCode(), self::HTTP_CODE_OK );
		}

		$this->assertEquals( self::getHttpCode(), self::HTTP_CODE_RATE_LIMITED );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testRun200Times() : void {
		self::flushRedis();

		for( $i = 0; $i < 100; $i ++ ) {
			$this->assertEquals( self::getHttpCode(), self::HTTP_CODE_OK );
		}

		for( $i = 0; $i < 100; $i ++ ) {
			$this->assertEquals( self::getHttpCode(), self::HTTP_CODE_RATE_LIMITED );
		}
	}
}
