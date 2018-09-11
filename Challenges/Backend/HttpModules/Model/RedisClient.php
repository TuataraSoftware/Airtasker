<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use \Redis;
use \RedisException;

/**
 * RedisClient stores requests counters per IP address in Redis which:
 *
 * 1. provides fast in-memory data access
 * 2. automatically expires obsolete records
 * 3. works correctly in race conditions
 * 4. dumps its database on disc
 *
 * Class RequestCounter
 * @package Airtasker\Challenges\Backend\HttpModules\Model
 */
final class RedisClient {

	const REDIS_DOCKER_CONTAINER_NAME = 'redis';
	const REDIS_DOCKER_CONTAINER_PORT = 6379;

	private $redis;

	private function __construct( Redis $redis ) {
		$this->redis = $redis;
	}

	public static function generate() : self {
		$redis = self::getRedis();

		$redisClient = new self( $redis );

		return $redisClient;
	}

	public function getCounter( string $key ) : int {
		$value = 0;

		try {
			$value = $this->redis->lLen( $key );
		}
		catch( RedisException $redisException ) {
			self::logRedisException( $redisException );
		}

		if( is_int( $value ) ) {
			return $value;
		}

		return 0;
	}

	public function setCounter( string $key, int $expirationTimeInSeconds ) {
		$redis = $this->redis;

		try {
			// incrementing counter
			if( $redis->exists( $key ) ) {
				$redis->rPushX( $key, $key );
				return;
			}

			// adding counter
			$redis->multi()
				->rPush( $key, $key )
				->expire( $key, $expirationTimeInSeconds )
				->exec();
		}
		catch( RedisException $redisException ) {
			self::logRedisException( $redisException );
		}
	}

	private static function getRedis() : Redis {
		$redis = new Redis();

		try {
			$redis->connect( self::REDIS_DOCKER_CONTAINER_NAME, self::REDIS_DOCKER_CONTAINER_PORT );
		}
		catch( RedisException $redisException ) {
			self::logRedisException( $redisException );
		}

		return $redis;
	}

	/**
	 * This function logs Redis exceptions when Redis goes down
	 *
	 * @param RedisException $redisException
	 */
	private static function logRedisException( RedisException $redisException ) {
		$errorMessage = $redisException->getMessage();
		error_log( $errorMessage );
	}
}
