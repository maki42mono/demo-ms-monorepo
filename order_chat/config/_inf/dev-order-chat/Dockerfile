FROM php:7.4-fpm-alpine
RUN apk update && apk upgrade && apk add git

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN mkdir -p /.composer/ && chmod 777 -R /.composer/

WORKDIR /var/www/html

CMD composer install; php-fpm
EXPOSE 9000