<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use \Redis;
use \RedisException;

final class RedisWrapper {

	private static $redisClient;

	public static function updateHits( string $key, int $value ) {
		if( RedisWrapper::exists( $key ) ) {
			RedisWrapper::setHits( $key );
		}
		else {
			RedisWrapper::incrementHits( $key, $value );
		}
	}

	public static function incrementHits( string $key, int $intervalLengthInSeconds ) {
		$redisClient = self::getRedisClient();

		$redisClient->multi()
			->rPush( $key, $key )
			->expire( $key, $intervalLengthInSeconds )
			->exec();
	}

	public static function setHits( string $key ) {
		$redisClient = self::getRedisClient();

		$redisClient->rPushX( $key, $key );
	}

	public static function getHits( string $key ) : int {
		$redisClient = self::getRedisClient();

		$value = $redisClient->lLen( $key );

		if( is_int( $value ) ) {
			return $value;
		}

		return 0;
	}

	public static function exists( string $key ) : bool {
		$redisClient = self::getRedisClient();

		$value = $redisClient->exists( $key );
		if( $value ) {
			return true;
		}

		return false;
	}

	private static function getRedisClient() {
		if( isset( self::$redisClient ) ) {
			return self::$redisClient;
		}

		self::$redisClient = self::initialiseRedisClient();

		return self::$redisClient;
	}

	private static function initialiseRedisClient() : Redis {
		$redis = new Redis();

		try {
			$redis->connect( 'redis', 6379 );
		}
		catch( RedisException $redisException ) {
			error_log( $redisException->getMessage() );
		}

		return $redis;
	}

	public static function redisClientErrorCallback( $error ) {
		error_log( $error );
	}
}
