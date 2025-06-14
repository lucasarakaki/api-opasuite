# Base Image
FROM php:8.3-fpm
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN apt-get update && apt-get install -y \
zip \
unzip \
git \
libmariadb-dev && \
install-php-extensions xdebug gd curl intl opcache bcmath mbstring dom xml zip zlib iconv ctype fileinfo
WORKDIR /var/www/html