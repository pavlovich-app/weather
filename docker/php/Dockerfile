FROM php:8.2-fpm

# Install Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN apt-get install -y wget git unzip

USER www-data