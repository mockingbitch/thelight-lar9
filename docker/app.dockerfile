FROM php:8.1-fpm
COPY composer.lock composer.json /var/www/
COPY database /var/www/database
WORKDIR /var/www
RUN apt-get update && apt-get -y install git && apt-get -y install zip
# Setup GD extension
RUN apt-get update
RUN apt-get install -y \
  git \
  curl \
  vim \
  nano \
  net-tools \
  pkg-config \
  iputils-ping \
  apt-utils \
  zip \
  unzip
COPY . /var/www
RUN chown -R www-data:www-data \
       /var/www/storage \
       /var/www/bootstrap/cache

RUN php artisan cache:clear
RUN php artisan optimize
# RUN  apt-get install -y libmcrypt-dev \
#        && pecl install mcrypt-1.0.2 \
#        && docker-php-ext-install pdo_mysql \
#        && docker-php-ext-enable mcrypt
       
# RUN mv .env .env
RUN php artisan optimize