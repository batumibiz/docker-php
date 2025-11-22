FROM alpine:3.22

# Установка Nginx, PHP 8.4 и нужных модулей
RUN apk add --no-cache \
    # Обязательные модули, без них контейнер неработоспособен
    nginx \
    php84 \
    php84-fpm \
    # Опциональные модули, можно включать/выключать по мере необходимости (комментируем выбранную строку #)
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
    mkdir -p /var/www/html /run/nginx /run/php && \
    chown -R app:app /var/www/html /run/nginx /run/php /var/lib/nginx

# Копируем конфигурацию Nginx
COPY config/nginx.conf /etc/nginx/nginx.conf

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

# Экспонируем порт
EXPOSE 80

# Запуск: Nginx в фоне → PHP-FPM в foreground
CMD ["sh", "-c", "nginx && php-fpm84 -F"]
