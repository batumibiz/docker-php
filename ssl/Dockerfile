FROM alpine:3.22

# Устанавливаем только нужные пакеты
RUN apk add --no-cache \
    apache2 \
    apache2-ssl \
    && mkdir -p /run/apache2 \
    && chown apache:apache /run/apache2

# Копируем конфиг
COPY httpd.conf /etc/apache2/httpd.conf
COPY httpd-vhosts.conf /etc/apache2/conf.d/

# Копируем сертификаты
COPY ssl/localhost.crt  /etc/ssl/certs/localhost.crt
COPY ssl/localhost.key  /etc/ssl/private/localhost.key
RUN chmod 600 /etc/ssl/private/localhost.key \
    && chown root:apache /etc/ssl/private/localhost.key

# Порт
EXPOSE 443

# Запуск в foreground
CMD ["/bin/sh", "-c", "httpd -D FOREGROUND"]
