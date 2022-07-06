FROM php:7.4-fpm-alpine

WORKDIR '/app'

ARG UID
ARG GID

RUN apk update && apk upgrade\
    && apk add --no-cache curl libbz2 bash openssh\
    libtool \
    libpng-dev \
    imagemagick-c++ \
    imagemagick-dev \
    libmcrypt-dev \
    libxml2-dev \
    yaml-dev \
    bzip2 \
    aspell-dev \
    autoconf \
    build-base \
    linux-headers \
    libaio-dev \
    zlib-dev \
    libzip-dev \
    git \
    subversion \
    freetype-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev \
    bzip2-dev \
    libstdc++ \
    libxslt-dev \
    openldap-dev \
    hiredis-dev \
    make \
    unzip \
    ffmpeg \
    wget \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && apk add --update icu-dev \
    && docker-php-ext-install intl

RUN apk add --update supervisor && rm -rf /tmp/* /var/cache/apk/*
RUN apk add openrc --no-cache

RUN apk add rabbitmq-c-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir /.composer
RUN chmod -R 0777 /.composer
RUN docker-php-ext-install gd bcmath zip bz2 pdo pdo_mysql simplexml opcache sockets pcntl xsl pspell
RUN docker-php-ext-configure gd

RUN curl -L -o /usr/local/bin/pickle https://github.com/FriendsOfPHP/pickle/releases/latest/download/pickle.phar \
	&& chmod +x /usr/local/bin/pickle

RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN docker-php-ext-install soap

RUN chmod -R 0777 /var/log/

RUN mkdir -p /var/run/
RUN chmod -R 0777 /var/run/

EXPOSE 22