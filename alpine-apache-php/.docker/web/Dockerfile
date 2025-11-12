FROM alpine:3.22

# Установка Apache, PHP 8.4 и нужных модулей
RUN apk add --no-cache \
    apache2 \
    apache2-proxy \
    php84 \
    php84-fpm \
    php84-gd \
    php84-opcache \
    php84-mysqli \
    php84-session \
    php84-json \
    php84-curl \
    php84-mbstring \
    php84-xml \
    php84-tokenizer \
    php84-fileinfo \
    php84-dom \
    php84-pdo \
    php84-pdo_mysql \
    php84-phar \
    php84-ctype \
    php84-iconv \
    php84-zip \
    tzdata

# Синхронизация UID/GID с хостом
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN addgroup -S -g ${GROUP_ID} app && \
    adduser -S -D -H -u ${USER_ID} -G app app && \
    mkdir -p /run/apache2 /run/php && \
    chown -R app:app /var/www /run/apache2 /run/php
#RUN groupmod -g ${GROUP_ID} apache && \
#    usermod -u ${USER_ID} -g ${GROUP_ID} apache && \
#    mkdir -p /run/apache2 /run/php && \
#    chown -R apache:apache /var/www /run/apache2 /run/php

# Копируем конфигурационные файлы в контейнер
COPY config/httpd.conf /etc/apache2/
COPY config/httpd-vhosts.conf /etc/apache2/conf.d/
COPY config/php-fpm.conf /etc/php84/php-fpm.d/www.conf

# Рабочая директория
WORKDIR /var/www/localhost/htdocs

# Порт
EXPOSE 80

# Start PHP-FPM in background and Apache in foreground
CMD ["/bin/sh", "-c", "php-fpm84 & httpd -D FOREGROUND"]
