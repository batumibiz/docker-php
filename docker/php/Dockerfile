FROM php:8.3-fpm-alpine3.22

RUN apk update && apk upgrade

# Установка необходимых библиотек
RUN apk add \
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

ARG UID=1000
ENV UID ${UID}
ARG GID=1000
ENV GID ${GID}

RUN apk --no-cache add shadow && \
    groupmod -o -g ${GID} www-data && \
    usermod -o -u ${UID} -g www-data www-data

WORKDIR /app

RUN apk cache clean \
