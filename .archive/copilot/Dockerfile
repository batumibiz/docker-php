FROM alpine:3.22

# Установка Apache, PHP 8.4 и нужных модулей
RUN apk add --no-cache \
    apache2 \
    apache2-proxy \
    php84 \
    php84-fpm \
    php84-apache2 \
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
    php84-posix \
    php84-ctype \
    php84-iconv \
    shadow \
    tzdata

# UID/GID для синхронизации с хостом
ARG HOST_UID=1000
ARG HOST_GID=1000
RUN groupmod -g ${HOST_GID} apache && \
    usermod -u ${HOST_UID} -g ${HOST_GID} apache

# Настройка PHP-FPM: сокет, права, пользователь
RUN sed -i 's|^listen = .*|listen = /run/php84-fpm.sock|' /etc/php84/php-fpm.d/www.conf && \
    sed -i 's/^user = .*/user = apache/' /etc/php84/php-fpm.d/www.conf && \
    sed -i 's/^group = .*/group = apache/' /etc/php84/php-fpm.d/www.conf && \
    echo -e "listen.owner = apache\nlisten.group = apache\nlisten.mode = 0660" >> /etc/php84/php-fpm.d/www.conf

# Создание конфигурации Apache ↔ PHP-FPM
RUN echo -e "\
<FilesMatch \\.php$>\n\
    SetHandler \"proxy:unix:/run/php84-fpm.sock|fcgi://localhost\"\n\
</FilesMatch>" > /etc/apache2/conf.d/php84-fpm.conf

# Убедиться, что нужные модули Apache загружены
RUN sed -i 's|^#LoadModule proxy_module|LoadModule proxy_module|' /etc/apache2/httpd.conf && \
    sed -i 's|^#LoadModule proxy_fcgi_module|LoadModule proxy_fcgi_module|' /etc/apache2/httpd.conf && \
    echo "Include /etc/apache2/conf.d/php84-fpm.conf" >> /etc/apache2/httpd.conf

# Создание директорий для сокета и Apache
RUN mkdir -p /run/php84 /run/apache2

# Рабочая директория
WORKDIR /var/www/localhost/htdocs

# Порт и запуск
EXPOSE 80
CMD mkdir -p /run/php84 && php-fpm84 && httpd -DFOREGROUND
