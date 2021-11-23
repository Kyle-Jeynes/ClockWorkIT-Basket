FROM php:7.4-fpm

COPY ./ /opt/clockworkit
WORKDIR /opt/clockworkit

RUN apt-get update -y && \
    apt-get upgrade -y && \
    apt-get install git -y && \
    docker-php-ext-install pdo pdo_mysql && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    cd /opt/clockworkit && \
    /usr/local/bin/composer dump-autoload -o && \
    /usr/local/bin/composer install --dev

USER root
EXPOSE 9000
CMD ["php-fpm"]
