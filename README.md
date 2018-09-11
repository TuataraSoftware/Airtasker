**Airtasker Backend Challenge: Rate Limiter**

Author: Alexey Gerasimov

---

**Requirements:**

- Docker 17.*

**Usage:**

- Include Airtasker/Challenges/Backend/HttpModules/RequestRateLimiter.php:

require_once ( $_SERVER[ 'DOCUMENT_ROOT' ] . '/Airtasker/Challenges/Backend/HttpModules/RequestRateLimiter.php' );

- Call RequestRateLimiter::run() method:

Airtasker\Challenges\Backend\HttpModules\RequestRateLimiter\RequestRateLimiter::run();

**Deployment:**

- Download repository:

git clone https://github.com/TuataraSoftware/AirtaskerBackendChallenge

- Build & run Docker container:
 
docker-compose -f Challenges/Backend/HttpModules/Docker/docker-compose.yml up --build

- For debug purposes: 

docker-compose -f Challenges/Backend/HttpModules/Docker-debug/docker-compose.yml up --build

---

Example:

- Enter in browser:

http://127.0.0.1/Airtasker/index.php

- Refresh the page for 100 times until it shows error 429

