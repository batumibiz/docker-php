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

# Копируем конфигурацию PHP
COPY config/php-fpm.conf /etc/php84/php-fpm.d/www.conf
COPY config/sendmail.ini /etc/php84/conf.d/
COPY config/php.ini /etc/php84/php.ini

# Копируем SSL сертификаты
COPY ssl /etc/nginx/ssl
RUN chown -R app:app /etc/nginx/ssl && \
    chmod 600 /etc/nginx/ssl/localhost.key

# Экспонируем порт
EXPOSE 80 443

# Запуск: Nginx в фоне → PHP-FPM в foreground
CMD ["sh", "-c", "nginx && php-fpm84 -F"]
