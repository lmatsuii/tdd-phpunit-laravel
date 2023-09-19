FROM php:8.1.23-fpm-alpine3.16

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . /var/www/html

RUN composer install

RUN docker-php-ext-install pdo_mysql