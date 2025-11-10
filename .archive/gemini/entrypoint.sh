#!/bin/sh

# Запуск Apache в фоновом режиме
httpd -D FOREGROUND -k start &

# Запуск PHP-FPM в фоновом режиме (важно использовать правильный бинарник php84-fpm)
/usr/sbin/php-fpm84 -F

# Ожидание завершения
wait
