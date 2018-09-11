**Airtasker Backend Challenge: Rate Limiter**

Author: Alexey Gerasimov

---

**Requirements:**

- Docker 17.*

**Usage:**

- Include Airtasker/Challenges/Backend/HttpModules/RequestRateLimiterModule.php:

`require_once ( $_SERVER[ 'DOCUMENT_ROOT' ] . '/Airtasker/Challenges/Backend/HttpModules/RequestRateLimiterModule.php' );`

- Call RequestRateLimiterModule::run() method:

`Airtasker\Challenges\Backend\HttpModules\RequestRateLimiterModule::run();`

**Deployment:**

- Download repository:

`git clone https://github.com/TuataraSoftware/AirtaskerBackendChallenge`

- Build & run Docker container:
 
`docker-compose -f Airtasker/Challenges/Backend/HttpModules/Docker/docker-compose.yml up --build`

- For debug purposes: 

`docker-compose -f Airtasker/Challenges/Backend/HttpModules/Docker/docker-compose-debug.yml up --build`

---

Example:

- Enter in browser:

http://127.0.0.1/Airtasker/index.php

- Refresh the page for 100 times until it shows error 429

