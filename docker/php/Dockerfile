FROM php:8.3-fpm-alpine3.22

# Получаем USER_ID и GROUP_ID из docker compose
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apk update && apk upgrade

# Установка необходимых библиотек
RUN apk add --no-cache \
    shadow \
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

RUN groupmod -o -g ${GROUP_ID} www-data && \
    usermod -o -u ${USER_ID} -g www-data www-data

RUN apk cache clean \

WORKDIR /app
