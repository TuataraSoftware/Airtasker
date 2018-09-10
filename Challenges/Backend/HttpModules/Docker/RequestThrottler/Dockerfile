FROM php:7-fpm

# Install XDebug extension for php
#RUN pecl install xdebug && docker-php-ext-enable xdebug
#RUN docker-php-ext-install sockets

#RUN pecl install -o -f redis \
#&&  rm -rf /tmp/pear \
#&&  docker-php-ext-enable redis

#RUN pecl install xdebug && docker-php-ext-enable xdebug \
#&& pecl bundle -d /usr/src/php/ext redis \
#&& rm /usr/src/php/ext/redis-*.tgz \
#&& docker-php-ext-install redis

#RUN apt-get update && apt-get install -y git libcurl4-gnutls-dev zlib1g-dev libicu-dev g++ libxml2-dev libpq-dev \
# && git clone -b php7 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
# && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql intl curl json opcache xml redis \
# && apt-get autoremove && apt-get autoclean \
# && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug && docker-php-ext-enable xdebug \
&& apt-get update \
&& apt-get install git -y -q \
#&& git clone -b php7 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
&& git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
&& docker-php-ext-install redis

#RUN pecl bundle -d /usr/src/php/ext redis \
#&& pecl install xdebug \
#&& rm /usr/src/php/ext/redis-*.tgz \
#&& docker-php-ext-install redis \
#&& docker-php-ext-enable xdebug

COPY ./php.ini /usr/local/etc/php/
COPY ./xdebug.ini /usr/local/etc/php/conf.d/

CMD ["php-fpm"]
