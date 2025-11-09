FROM php:8.4-fpm

# Получаем USER_ID и GROUP_ID из docker compose
ARG USER_ID=1000
ARG GROUP_ID=1000

# Обновление системы
RUN apt-get update && apt-get upgrade -y

# Назначаем USER_ID / GROUP_ID
RUN groupmod -o -g ${GROUP_ID} www-data && \
    usermod -o -u ${USER_ID} -g www-data www-data

# Установка необходимых библиотек
RUN apt-get install -y --no-install-recommends \
    libpng-dev \
    libwebp-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libcurl4-openssl-dev \
    libzip-dev \
    unzip \
    zip \
    msmtp \
    && rm -rf /var/lib/apt/lists/*

# Установка необходимых расширений
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli curl zip opcache

# Копируем конфигурационные файлы
COPY config/php.ini "$PHP_INI_DIR/conf.d/"
COPY config/sendmail.ini "$PHP_INI_DIR/conf.d/"
COPY config/msmtprc /etc/

WORKDIR /app
