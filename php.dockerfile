FROM php:7-fpm

RUN docker-php-ext-install pdo pdo_mysql

COPY ./ /code
COPY config/php.ini /usr/local/etc/php/
