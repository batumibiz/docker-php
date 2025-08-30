# docker/php/Dockerfile
FROM php:8.3-fpm-alpine3.22

# Установка необходимых библиотек
RUN apk add --no-cache \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev

# Установка необходимых расширений
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql mysqli opcache \
    && docker-php-ext-enable opcache
