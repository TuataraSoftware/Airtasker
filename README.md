Airtasker Backend Challenge: Rate Limiter
===================================
Rate limiting module for PHP web applications. 

Author: Alexey Gerasimov

Requirements
--------------------------------------

- Docker 17

Usage
--------------------------------------

- Include RequestRateLimiterModule.php in your web application:

```bash
require_once ( $_SERVER[ 'DOCUMENT_ROOT' ] . '/Airtasker/Challenges/Backend/HttpModules/RequestRateLimiterModule.php' );
```

- Call RequestRateLimiterModule::run() method:

```bash
Airtasker\Challenges\Backend\HttpModules\RequestRateLimiterModule::run();
```

Deployment
--------------------------------------

- Download repository:

```bash
git clone https://github.com/TuataraSoftware/Airtasker
```

- Build & run Docker container:
 
```bash
docker-compose -f Airtasker/Challenges/Backend/HttpModules/Docker/docker-compose.yml up --build
```

Testing
--------------------------------------

- Test environment: PHP 7.1 with Redis extension, PHPUnit 7.3.5

- Run:

```bash
phpunit ~/Airtasker/Challenges/Backend/HttpModules/Tests --teamcity
```

Example
--------------------------------------

- Open in browser:

http://127.0.0.1/Airtasker/index.php

- Refresh the page for 100 times until it shows error 429
