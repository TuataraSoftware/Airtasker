<?php

namespace Airtasker\Challenges\Backend\HttpModules\Model;

use \Redis;
use \RedisException;

/**
 * RedisClient stores requests counters in Redis which:
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

	/**
	 * Generates RedisClient instance and connects to Redis container in Docker
	 *
	 * @return RedisClient
	 */
	public static function generate() : self {
		$redis = new Redis();
		$redisClient = new self( $redis );

		$redisClient->connect();

		return $redisClient;
	}

	/**
	 * Returns counter for $key or 0 if Redis is down
	 *
	 * @param string $key
	 *
	 * @return int
	 */
	public function getCounter( string $key ) : int {
		$counter = 0;

		try {
			$counter = $this->redis->lLen( $key );
		}
		catch( RedisException $redisException ) {
			self::logRedisException( $redisException );
		}

		return $counter;
	}

	/**
	 * Adds/increments counter for $key if Redis is up
	 *
	 * @param string $key
	 * @param int $expirationTimeInSeconds
	 */
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

	/**
	 * Connects to Redis
	 */
	private function connect() {
		try {
			$this->redis->connect( self::REDIS_DOCKER_CONTAINER_NAME, self::REDIS_DOCKER_CONTAINER_PORT );
		}
		catch( RedisException $redisException ) {
			self::logRedisException( $redisException );
		}
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
