FROM php:7.4-fpm-alpine
RUN apk update && apk upgrade && apk add git

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN mkdir -p /.composer/ && chmod 777 -R /.composer/

WORKDIR /var/www/html

CMD composer install; php-fpm

RUN apk add autoconf \
    && apk add build-base \
    && pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.client_host = host.docker.internal" >> \
/usr/local/etc/php/php.ini

EXPOSE 9000