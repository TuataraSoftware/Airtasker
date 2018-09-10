<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use DateTime;
use JsonSerializable;

class IpRequestLog implements JsonSerializable {

	private $ip;
	private $periodStartDateTime;
	private $requestCount;

	public function getIp() {
		return $this->ip;
	}

	public function setIp( string $ip ) {
		$this->ip = $ip;
	}

	public function getPeriodStartDateTime() : DateTime {
		return $this->periodStartDateTime;
	}

	public function setPeriodStartDateTime( $periodStartDateTime ) {
		$this->periodStartDateTime = $periodStartDateTime;
	}

	public function getRequestCount() : int {
		return $this->requestCount;
	}

	public function setRequestCount( $requestCount ) {
		$this->requestCount = $requestCount;
	}

	public function __construct( string $ip, DateTime $periodStartDateTime, int $requestCount ) {
		$this->ip = $ip;
		$this->periodStartDateTime = $periodStartDateTime;
		$this->requestCount = $requestCount;
	}

	public static function generate( string $ip, DateTime $periodStartDateTime, int $requestCount ) : IpRequestLog {
		$instance = new IpRequestLog( $ip, $periodStartDateTime, $requestCount );

		$instance->upsert();

		return $instance;
	}

	private function upsert() : bool {
		$json = json_encode( $this );

		RedisWrapper::set( $this->ip, $json );
	}

	public static function find( string $ip ) {
		$json = RedisWrapper::get( $ip );

		if( empty( $json ) ) {
			return null;
		}

		$decodedValues = json_decode( $json );

		if( empty( $decodedValues ) ) {
			return null;
		}

		$ipRequestLog = IpRequestLog::deserialize( $ip, $decodedValues );

		return $ipRequestLog;
	}

	public function jsonSerialize() : array {
		return [
			's' => $this->periodStartDateTime,
			'c' => $this->requestCount
		];
	}

	public static function deserialize( string $ip, array $decodedValues ) : IpRequestLog {
		$periodStartDateTime = $decodedValues[ 's' ] ?? new DateTime();
		$requestCount = $decodedValues[ 'c' ] ?? 0;

		return new IpRequestLog( $ip, $periodStartDateTime, $requestCount );
	}
}
