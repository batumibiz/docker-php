FROM alpine:3.22

# Установка Apache, PHP 8.4 и нужных модулей
RUN apk add --no-cache \
    apache2 \
    apache2-ssl \
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
    mkdir -p /app/www /run/apache2 /run/php && \
    chown -R app:app /var/www /app/www /run/apache2 /run/php /etc/ssl

# Копируем конфигурационные файлы в контейнер
COPY config/httpd.conf /etc/apache2/httpd.conf
COPY config/httpd-vhosts.conf /etc/apache2/conf.d/
COPY config/httpd-ssl.conf /etc/apache2/conf.d/ssl.conf
COPY config/php-fpm.conf /etc/php84/php-fpm.d/www.conf
COPY config/sendmail.ini /etc/php84/conf.d/
COPY config/php.ini /etc/php84/php.ini

# Копируем сертификаты
COPY ssl/localhost.crt  /etc/ssl/certs/localhost.crt
COPY ssl/localhost.key  /etc/ssl/private/localhost.key

# Рабочая директория
WORKDIR /app/www

# Порт
EXPOSE 80 443

# Start PHP-FPM in background and Apache in foreground
CMD ["/bin/sh", "-c", "php-fpm84 & httpd -D FOREGROUND"]
