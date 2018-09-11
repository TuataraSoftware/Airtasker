<?php

require_once ( $_SERVER[ 'DOCUMENT_ROOT' ] . '/Airtasker/Challenges/Backend/HttpModules/RequestRateLimiter.php' );

use Airtasker\Challenges\Backend\HttpModules\RequestRateLimiter;

RequestRateLimiter::run();
