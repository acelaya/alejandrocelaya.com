FROM composer:1.8.4 as composer
COPY . /website
RUN cd /website && \
    composer install --no-dev --optimize-autoloader --apcu-autoloader --prefer-dist --no-interaction --no-progress && \
    rm ./composer.json


FROM node:10.15.3-alpine as node
COPY --from=composer /website /website
RUN cd /website && \
    npm install && \
    ./node_modules/.bin/grunt && \
    rm ./public/css/animate.css && \
    rm ./public/css/bootstrap.css && \
    rm ./public/css/icomoon.css && \
    rm ./public/css/style.css && \
    rm ./public/js/bootstrap.min.js && \
    rm ./public/js/jquery.min.js && \
    rm ./public/js/jquery.easing.1.3.js && \
    rm ./public/js/jquery.waypoints.min.js && \
    rm ./public/js/main.js && \
    rm package.json


FROM php:7.3.4-fpm-alpine3.9
LABEL maintainer="Alejandro Celaya <alejandro@alejandrocelaya.com>"
COPY --from=node /website /website

ENV APCu_VERSION 5.1.16
ENV APCuBC_VERSION 1.0.4
ENV PREDIS_VERSION 4.2.0

# Install APCu extension
RUN wget "https://pecl.php.net/get/apcu-${APCu_VERSION}.tgz" -O /tmp/apcu.tar.gz && \
    mkdir -p /usr/src/php/ext/apcu && \
    tar xf /tmp/apcu.tar.gz -C /usr/src/php/ext/apcu --strip-components=1 && \
    docker-php-ext-configure apcu && \
    docker-php-ext-install -j"$(nproc)" apcu && \
    rm /tmp/apcu.tar.gz

# Install APCu-BC extension
RUN wget "https://pecl.php.net/get/apcu_bc-${APCuBC_VERSION}.tgz" -O /tmp/apcu_bc.tar.gz && \
    mkdir -p /usr/src/php/ext/apcu-bc && \
    tar xf /tmp/apcu_bc.tar.gz -C /usr/src/php/ext/apcu-bc --strip-components=1 && \
    docker-php-ext-configure apcu-bc && \
    docker-php-ext-install -j"$(nproc)" apcu-bc && \
    rm /tmp/apcu_bc.tar.gz

# Load APCU.ini before APC.ini
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini && \
    echo extension=apcu.so > /usr/local/etc/php/conf.d/20-php-ext-apcu.ini

# Install redis extension
RUN wget "https://github.com/phpredis/phpredis/archive/${PREDIS_VERSION}.tar.gz" -O /tmp/phpredis.tar.gz && \
    mkdir -p /usr/src/php/ext/redis && \
    tar xf /tmp/phpredis.tar.gz -C /usr/src/php/ext/redis --strip-components=1 && \
    docker-php-ext-configure redis && \
    docker-php-ext-install -j"$(nproc)" redis && \
    rm /tmp/phpredis.tar.gz
