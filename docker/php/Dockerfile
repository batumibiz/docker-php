# docker/php/Dockerfile
FROM php:8.3-fpm-alpine3.22

# Установка утилит
RUN apk add --no-cache zip unzip

# Установка необходимых расширений
RUN docker-php-ext-install pdo pdo_mysql mysqli opcache \
    && docker-php-ext-enable opcache
