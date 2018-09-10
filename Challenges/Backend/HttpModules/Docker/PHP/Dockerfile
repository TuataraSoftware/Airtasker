FROM php:7-fpm

RUN pecl install xdebug && docker-php-ext-enable xdebug \
&& apt-get update \
&& apt-get install git -y -q \
&& git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
&& docker-php-ext-install redis

COPY ./php.ini /usr/local/etc/php/
COPY ./xdebug.ini /usr/local/etc/php/conf.d/

CMD ["php-fpm"]
