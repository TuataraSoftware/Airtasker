<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

require_once( __DIR__ . '/../../Thirdparty/redis-cli.php' );

use redis_cli;

class Redis {

	private static $redisClient;

	public static function set( string $key, string $value ) {
		$redisClient = self::getRedisClient();

		$redisClient->cmd( 'SET', $key, $value )->set();
	}

	public static function updateHits( string $key, int $value ) {
		if( Redis::exists( $key ) ) {
			Redis::setHits( $key );
		}
		else {
			Redis::incrementHits( $key, $value );
		}
	}

	public static function incrementHits( string $key, int $intervalLengthInSeconds ) {
		$redisClient = self::getRedisClient();

		$redisClient->cmd( 'RPUSH', $key, $key )->cmd( 'EXPIRE', $key, $intervalLengthInSeconds )->set();
	}

	public static function setHits( string $key ) {
		$redisClient = self::getRedisClient();

		$redisClient->cmd( 'RPUSHX', $key, $key )->set();
	}

	public static function get( string $key ) : string {
		$redisClient = self::getRedisClient();

		$value = $redisClient->cmd( 'GET', $key )->get();

		if( empty( $value ) ) {
			return '';
		}

		if( is_string( $value ) ) {
			return $value;
		}

		return '';
	}

	public static function getHits( string $key ) : int {
		$redisClient = self::getRedisClient();

		$value = $redisClient->cmd( 'LLEN', $key )->get();

		if( is_int( $value ) ) {
			return $value;
		}

		return 0;
	}

	public static function exists( string $key ) : bool {
		$redisClient = self::getRedisClient();

		$value = $redisClient->cmd( 'EXISTS', $key )->get();

		if( $value ) {
			return true;
		}

		return false;
	}

	private static function getRedisClient() : redis_cli {
		if( isset( self::$redisClient ) ) {
			return self::$redisClient;
		}

		$redisClient = self::initialiseRedisClient();

		self::$redisClient = $redisClient;

		return self::$redisClient;
	}

	private static function initialiseRedisClient() : redis_cli {
		$redisClient = new redis_cli();
		$redisClient->set_error_function( 'Airtasker\Challenges\Backend\HttpModules\Model\Redis::redisClientErrorCallback' );

		return $redisClient;
	}

	public static function redisClientErrorCallback( $error ) {
		error_log( $error );
	}
}
