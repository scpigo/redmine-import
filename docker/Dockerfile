FROM php:8.1-fpm

RUN apt update

RUN apt-get update -y && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends git zlib1g-dev libpng-dev libxml2-dev libonig-dev \
    libfreetype6-dev libjpeg62-turbo-dev libzip-dev unzip ssh-client libc-client-dev libkrb5-dev \
    libldap2-dev libicu-dev procps \
    supervisor \
    redis-tools \
    nano \
    default-mysql-client \
    p7zip \
    && apt-get clean

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install bcmath gd mbstring opcache mysqli pdo_mysql xml zip ldap intl calendar \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /bin/composer

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl

RUN mkdir /.composer && chmod 0777 /.composer
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /app
