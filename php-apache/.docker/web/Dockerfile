FROM alpine:3.22

# Установка Apache, PHP 8.4 и нужных модулей
RUN apk update && apk add --no-cache \
    # Обязательные модули, без них контейнер неработоспособен \
    apache2 \
    apache2-proxy \
    php84 \
    php84-fpm \
    # Опциональные модули, можно включать/выключать по мере необходимости (комментируем выбранную строку #) \
    php84-ctype \
    php84-curl \
    #php84-dom \
    #php84-fileinfo \
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

# Конфигурация Apache
COPY config/httpd.conf /etc/apache2/httpd.conf
COPY config/httpd-vhosts.conf /etc/apache2/conf.d/

# Конфигурация PHP
COPY config/php.ini /etc/php84/php.ini
RUN sed -i 's/^;error_log = log\/php84\/error.log/error_log = log\/php84\/php-error.log/' /etc/php84/php-fpm.conf
RUN sed -i 's/^listen = .*/listen = \/run\/php\/fpm.sock/' /etc/php84/php-fpm.d/www.conf && \
    sed -i 's/^user = .*/;user = .*/' /etc/php84/php-fpm.d/www.conf && \
    sed -i 's/^group = .*/;group = .*/' /etc/php84/php-fpm.d/www.conf && \
    sed -i 's/^;listen.owner = nobody/listen.owner = app/' /etc/php84/php-fpm.d/www.conf && \
    sed -i 's/^;listen.group = nobody/listen.group = app/' /etc/php84/php-fpm.d/www.conf && \
    sed -i 's/^;listen.mode = 0660/listen.mode = 0660/' /etc/php84/php-fpm.d/www.conf
RUN echo -e "SMTP = mail\nsmtp_port = 1025\nsendmail_path = /usr/sbin/sendmail -t -i -S mail:1025\n" > /etc/php84/conf.d/sendmail.ini

# Рабочая директория
WORKDIR /app/www

# Порт
EXPOSE 80

# Start PHP-FPM in background and Apache in foreground
CMD ["sh", "-c", "php-fpm84 & httpd -D FOREGROUND"]
