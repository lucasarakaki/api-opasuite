# Base Image
FROM php:7.4-fpm
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN apt-get update && apt-get install -y \
zip \
unzip \
git \
libmariadb-dev && \
install-php-extensions gd xdebug curl intl opcache bcmath mbstring dom xml zip
WORKDIR /var/www/html