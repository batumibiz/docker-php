FROM php:8.4-apache

# Получаем UID и GID из docker compose
ARG USER_ID=1000
ARG GROUP_ID=1000

# Установка зависимостей
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libwebp-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    msmtp \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql opcache \
    && a2enmod rewrite headers \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# === Создаём пользователя с нужным UID/GID ===
RUN userdel www-data && \
    groupadd -g ${GROUP_ID} www-data && \
    useradd -u ${USER_ID} -g www-data --create-home --shell /bin/bash www-data && \
    usermod -aG sudo www-data

# Права на /var/www
RUN chown -R ${USER_ID}:${GROUP_ID} /var/www

# Установка Xdebug
#RUN pecl install xdebug && docker-php-ext-enable xdebug

#RUN echo "\
#zend_extension=xdebug.so\n\
#xdebug.mode=off\n\
#xdebug.start_with_request=trigger\n\
#xdebug.client_port=9003\n\
#xdebug.discover_client_host=true\n\
#xdebug.log=/tmp/xdebug.log\n\
#" > /usr/local/etc/php/conf.d/xdebug.ini

# Настройка msmtp
RUN echo "defaults\n\
auth off\n\
tls off\n\
logfile /proc/self/fd/2\n\
\n\
account default\n\
host mail\n\
port 1025\n\
from no-reply@local.dev\n\
" > /etc/msmtprc

RUN echo "sendmail_path = /usr/bin/msmtp -t -i" > /usr/local/etc/php/conf.d/sendmail.ini

# Переключаемся на пользователя
USER www-data

WORKDIR /var/www/html

EXPOSE 80
