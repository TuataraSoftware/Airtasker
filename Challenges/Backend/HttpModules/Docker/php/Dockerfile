FROM php:7-fpm

RUN apt-get update \
&& apt-get install git -y -q \
&& git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
&& docker-php-ext-install redis \
&& docker-php-ext-configure opcache --enable-opcache \
&& docker-php-ext-install opcache

COPY ./php.ini /usr/local/etc/php/

CMD ["php-fpm"]
