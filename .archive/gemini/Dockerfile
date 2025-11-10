# Используем базовый образ Alpine Linux 3.22
FROM alpine:3.22

# Аргументы для UID и GID хост-пользователя
ARG PUID=1000
ARG PGID=1000

# --- 1. Установка Apache и PHP 8.4 FPM ---
RUN apk update && \
    apk add --no-cache \
        apache2 \
        apache2-proxy \
        php84-fpm \
        php84-json \
        php84-mysqli \
        php84-dom \
        php84-pdo_mysql \
        && rm -rf /var/cache/apk/*

# --- 2. Синхронизация Пользователя (UID/GID) ---
# 1. Удаляем стандартную группу и пользователя 'apache', созданных пакетом apache2,
#    чтобы освободить имя и иметь возможность создать их с нужными ID.
RUN delgroup apache 2>/dev/null || true && \
    deluser apache 2>/dev/null || true

# 2. Создаем группу и пользователя 'apache' с UID/GID хоста
RUN addgroup -g $PGID apache && \
    adduser -u $PUID -D -G apache apache

# --- 3. Настройка Apache HTTPD ---
# Включаем модули proxy и proxy_fcgi для передачи запросов к FPM
RUN sed -i -e 's/#LoadModule proxy_module modules\/mod_proxy.so/LoadModule proxy_module modules\/mod_proxy.so\nLoadModule proxy_fcgi_module modules\/mod_proxy_fcgi.so/' /etc/apache2/httpd.conf

# Создаем минимальную конфигурацию VirtualHost для проксирования PHP
RUN cat > /etc/apache2/conf.d/vhost.conf <<EOF
<VirtualHost *:80>
    DocumentRoot /var/www/localhost/htdocs
    <Directory /var/www/localhost/htdocs>
        AllowOverride All
        Require all granted
    </Directory>
    <FilesMatch \.php$>
        SetHandler "proxy:fcgi://127.0.0.1:9000"
    </FilesMatch>
    ErrorLog /dev/stderr
    CustomLog /dev/stdout combined
</VirtualHost>
EOF

# Удаляем стандартный файл конфигурации PHP-модуля (если он есть)
RUN rm -f /etc/apache2/conf.d/php8-module.conf

# --- 4. Настройка PHP 8.4 FPM ---
# Копируем стандартную конфигурацию FPM
RUN cp /etc/php84/php-fpm.d/www.conf /etc/php84/php-fpm.d/docker.conf && \
    # Изменяем пользователя и группу, от имени которых запускается FPM, на 'apache' (с синхронизированными ID)
    sed -i 's/^user = nobody/user = apache/' /etc/php84/php-fpm.d/docker.conf && \
    sed -i 's/^group = nobody/group = apache/' /etc/php84/php-fpm.d/docker.conf && \
    # Изменяем способ прослушивания на TCP-сокет
    sed -i 's/^listen = .*$/listen = 127.0.0.1:9000/' /etc/php84/php-fpm.d/docker.conf

# --- 5. Точка входа ---
# Скрипт entrypoint.sh остается прежним и должен быть в той же директории
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Устанавливаем рабочую директорию и открываем порт
WORKDIR /var/www/localhost/htdocs
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
