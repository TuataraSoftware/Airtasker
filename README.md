Airtasker Backend Challenge: Rate Limiter
===================================

Author: Alexey Gerasimov

---

Requirements
--------------------------------------

- Docker 17

Usage
--------------------------------------

- Include Airtasker/Challenges/Backend/HttpModules/RequestRateLimiterModule.php:

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

- For debug purposes: 

```bash
docker-compose -f Airtasker/Challenges/Backend/HttpModules/Docker/docker-compose-debug.yml up --build
```

Example
--------------------------------------

- Open in browser:

http://127.0.0.1/Airtasker/index.php

- Refresh the page for 100 times until it shows error 429
