FROM alpine:3.23

ARG USER_ID=1000
ARG GROUP_ID=1000
ARG PHP=84

# Установка Nginx, PHP 8.4 и нужных модулей
RUN apk update && apk add --no-cache \
    # Обязательные модули, без них контейнер неработоспособен
    nginx \
    php${PHP} \
    php${PHP}-fpm \
    # Опциональные модули, можно включать/выключать по мере необходимости (комментируем выбранную строку #) \
    php${PHP}-ctype \
    php${PHP}-curl \
    php${PHP}-dom \
    php${PHP}-fileinfo \
    php${PHP}-gd \
    php${PHP}-iconv \
    php${PHP}-intl \
    php${PHP}-json \
    php${PHP}-mbstring \
    php${PHP}-mysqli \
    php${PHP}-openssl \
    php${PHP}-pdo \
    php${PHP}-pdo_mysql \
    #php${PHP}-pdo_pgsql \
    #php${PHP}-pdo_sqlite \
    #php${PHP}-pgsql \
    #php${PHP}-phar \
    php${PHP}-session \
    php${PHP}-simplexml \
    #php${PHP}-sqlite3 \
    php${PHP}-tokenizer \
    php${PHP}-xml \
    php${PHP}-zip \
    tzdata \
    && if [ $PHP = 82 -o $PHP = 83 -o $PHP = 84 ]; then apk add --no-cache php${PHP}-opcache; fi

# Синхронизация UID/GID с хостом
RUN addgroup -S -g ${GROUP_ID} app && \
    adduser -S -D -H -u ${USER_ID} -G app app && \
    mkdir -p /var/www/html /var/log/php /run/nginx /run/php && \
    chown -R app:app /var/www/html /var/log/php /run/nginx /run/php /var/lib/nginx

# Копируем конфигурацию Nginx
COPY config/nginx.conf /etc/nginx/nginx.conf

# Конфигурация PHP
COPY config/php${PHP}.ini /etc/php${PHP}/php.ini
RUN sed -i "s/^;error_log = log\/php${PHP}\/error.log/error_log = log\/php\/php-error.log/" /etc/php${PHP}/php-fpm.conf
RUN sed -i 's/^listen = .*/listen = \/run\/php\/fpm.sock/' /etc/php${PHP}/php-fpm.d/www.conf && \
    sed -i 's/^user = .*/;user = .*/' /etc/php${PHP}/php-fpm.d/www.conf && \
    sed -i 's/^group = .*/;group = .*/' /etc/php${PHP}/php-fpm.d/www.conf && \
    sed -i 's/^;listen.owner = nobody/listen.owner = app/' /etc/php${PHP}/php-fpm.d/www.conf && \
    sed -i 's/^;listen.group = nobody/listen.group = app/' /etc/php${PHP}/php-fpm.d/www.conf && \
    sed -i 's/^;listen.mode = 0660/listen.mode = 0660/' /etc/php${PHP}/php-fpm.d/www.conf
RUN echo -e "SMTP = mail\nsmtp_port = 1025\nsendmail_path = /usr/sbin/sendmail -t -i -S mail:1025\n" > /etc/php${PHP}/conf.d/sendmail.ini

# Экспонируем порт
EXPOSE 80

# Запуск: Nginx в фоне → PHP-FPM в foreground
ENV PHP=${PHP}
CMD ["sh", "-c", "nginx && php-fpm${PHP} -F"]
