<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use \Redis;
use \RedisException;

final class RequestCounter {

	const REDIS_DOCKER_CONTAINER_NAME = 'redis';
	const REDIS_DOCKER_CONTAINER_PORT = 6379;

	private static $redisClient;

	public static function getHits( string $key ) : int {
		$redisClient = self::getRedisClient();

		$value = $redisClient->lLen( $key );

		if( is_int( $value ) ) {
			return $value;
		}

		return 0;
	}

	public static function incrementHits( string $key, int $value ) {
		if( self::exists( $key ) ) {
			self::incrementHitsCounter( $key );
		}
		else {
			self::initializeHitsCounter( $key, $value );
		}
	}

	private static function initializeHitsCounter( string $key, int $intervalLengthInSeconds ) {
		$redisClient = self::getRedisClient();

		$redisClient->multi()
			->rPush( $key, $key )
			->expire( $key, $intervalLengthInSeconds )
			->exec();
	}

	private static function incrementHitsCounter( string $key ) {
		$redisClient = self::getRedisClient();

		$redisClient->rPushX( $key, $key );
	}

	private static function exists( string $key ) : bool {
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

		self::$redisClient = self::initializeRedisClient();

		return self::$redisClient;
	}

	private static function initializeRedisClient() : Redis {
		$redis = new Redis();

		try {
			$redis->connect( self::REDIS_DOCKER_CONTAINER_NAME, self::REDIS_DOCKER_CONTAINER_PORT );
		}
		catch( RedisException $redisException ) {
			$errorMessage = $redisException->getMessage();
			error_log( $errorMessage );
		}

		return $redis;
	}

	public static function redisClientErrorCallback( $error ) {
		error_log( $error );
	}
}
