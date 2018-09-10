<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use \Redis;
use \RedisException;

/**
 * RequestCounter is the main model of the module.
 * It stores requests count for each IP address in Redis which:
 *
 * 1. provides fast in-memory data access
 * 2. automatically expires obsolete records
 * 3. works correctly in race conditions
 * 4. dumps its database on disc
 *
 * Class RequestCounter
 * @package Airtasker\Challenges\Backend\HttpModules\Model
 */
final class RequestCounter {

	const REDIS_DOCKER_CONTAINER_NAME = 'redis';
	const REDIS_DOCKER_CONTAINER_PORT = 6379;

	private static $redisClient;

	public static function getHitsSincePeriodStart( string $key ) : int {
		$redisClient = self::getRedisClient();

		$value = $redisClient->lLen( $key );

		if( is_int( $value ) ) {
			return $value;
		}

		return 0;
	}

	public static function incrementHits( string $key, int $intervalLengthInSeconds ) {
		if( self::exists( $key ) ) {
			self::incrementHitsCounter( $key );
		}
		else {
			self::initializeHitsCounter( $key, $intervalLengthInSeconds );
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
}
