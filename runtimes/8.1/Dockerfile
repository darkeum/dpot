FROM php:8.1-fpm

LABEL maintainer="Komarov Ivan"

ARG WWWGROUP=1000
ARG WWWUSER=dpot
ARG NODE_VERSION=16
ARG POSTGRES_VERSION=14

# Установка рабочей директории
WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libfreetype6-dev \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    libwebp-dev \
    libxpm-dev \
    libmcrypt-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev

RUN apt-get install -y gnupg gosu ca-certificates supervisor python3

RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /usr/share/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y yarn 

# Очитка кэша
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка расщирений
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype;
RUN docker-php-ext-install gd
RUN docker-php-ext-install mysqli

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Добавление пользователя
RUN groupadd -g $WWWGROUP $WWWUSER
RUN useradd -u $WWWGROUP -ms /bin/bash -g $WWWUSER $WWWUSER

# Копирование директории приложения
COPY . /var/www/html

# Установка разрешений на директорию приложения
COPY --chown=$WWWUSER:$WWWUSER . /var/www/html

# Копирование настроек PHP
COPY php.ini /etc/php/8.1/cli/conf.d/99-dpot.ini

# Установка текущего пользователя
USER $WWWUSER

# Запуск PHP-FPM сервера
EXPOSE 9000
CMD ["php-fpm"]