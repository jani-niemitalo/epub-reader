FROM php:7.2-apache
RUN apt update && apt install -y zlib1g-dev
RUN docker-php-ext-install -j$(nproc) mysqli zip
COPY . /var/www/html/
RUN date +%s >/var/www/html/version.txt
