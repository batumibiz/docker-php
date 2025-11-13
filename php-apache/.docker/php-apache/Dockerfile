FROM alpine:3.22

# Установка Apache, PHP 8.4 и нужных модулей
# Обязательные модули: apache2, apache2-ssl, apache2-proxy, php84, php84-fpm,
# остальные можно включать / выключать (комментируем выбранную строку #)
RUN apk add --no-cache \
    apache2 \
    apache2-ssl \
    apache2-proxy \
    php84 \
    php84-ctype \
    php84-curl \
    #php84-dom \
    #php84-fileinfo \
    php84-fpm \
    php84-gd \
    #php84-iconv \
    #php84-intl \
    php84-json \
    php84-mbstring \
    php84-mysqli \
    php84-opcache \
    #php84-openssl \
    php84-pdo \
    php84-pdo_mysql \
    #php84-pdo_pgsql \
    #php84-pdo_sqlite \
    #php84-pgsql \
    #php84-phar \
    php84-session \
    #php84-simplexml \
    #php84-sqlite3 \
    php84-xml \
    php84-zip \
    tzdata

# Синхронизация UID/GID с хостом
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN addgroup -S -g ${GROUP_ID} app && \
    adduser -S -D -H -u ${USER_ID} -G app app && \
    mkdir -p /app/www /run/apache2 /run/php && \
    chown -R app:app /var/www /app/www /run/apache2 /run/php

# Копируем конфигурацию Apache
COPY config/httpd.conf /etc/apache2/httpd.conf
COPY config/httpd-vhosts.conf /etc/apache2/conf.d/
COPY config/httpd-ssl.conf /etc/apache2/conf.d/ssl.conf

# Копируем конфигурацию PHP
COPY config/php-fpm.conf /etc/php84/php-fpm.d/www.conf
COPY config/sendmail.ini /etc/php84/conf.d/
COPY config/php.ini /etc/php84/php.ini

# Копируем SSL сертификаты
COPY ssl  /etc/apache2/ssl
RUN chown -R app:app /etc/apache2/ssl && \
    chmod 600 /etc/apache2/ssl/localhost.key

# Рабочая директория
WORKDIR /app/www

# Порт
EXPOSE 80 443

# Start PHP-FPM in background and Apache in foreground
CMD ["/bin/sh", "-c", "php-fpm84 & httpd -D FOREGROUND"]
