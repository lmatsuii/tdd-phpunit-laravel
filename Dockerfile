FROM php:8.1.23-fpm-alpine3.16

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pdo_mysql