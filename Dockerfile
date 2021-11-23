FROM php:7.4-fpm

COPY ./ /opt/clockworkit
WORKDIR /opt/clockworkit

RUN apt-get update -y && \
    apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
        build-essential \
        curl \
        gifsicle \
        git \
        jpegoptim \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        locales \
        optipng \
        pngquant \
        unzip \
        zip && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install mysqli && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-install intl; \
    docker-php-ext-install exif; \
    docker-php-ext-enable exif; \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    cd /opt/clockworkit && \
    /usr/local/bin/composer dump-autoload -o && \
    /usr/local/bin/composer install --dev

USER root
EXPOSE 9000
CMD ["php-fpm"]
