<?php

require_once( __DIR__ . '/Challenges/Backend/HttpModules/Model/RedisClient.php' );

use \Airtasker\Challenges\Backend\HttpModules\Model\RedisClient;

$redis = new Redis();
$redis->connect( \Airtasker\Challenges\Backend\HttpModules\Model\RedisClient::REDIS_DOCKER_CONTAINER_NAME, RedisClient::REDIS_DOCKER_CONTAINER_PORT );
$redis->flushDB();
