FROM php:7.2-fpm

RUN apt-get update && \
  apt-get install -y --no-install-recommends libpq-dev wget gnupg libfreetype6-dev libjpeg62-turbo-dev libpng-dev unzip

RUN apt-get update && \
  apt-get install -y \
  zlib1g-dev

RUN docker-php-ext-install mysqli pdo_pgsql pdo_mysql zip iconv

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

RUN docker-php-ext-install -j$(nproc) gd

# curl
RUN apt-get install -y curl

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && rm -f /tmp/composer-setup.*

# npm & node
RUN curl -sL https://deb.nodesource.com/setup_9.x | bash
RUN apt-get install -y nodejs npm \
  && update-alternatives --install /usr/bin/node node /usr/bin/nodejs 10
# build tools
RUN apt-get install -y build-essential

# yarn package manager
RUN apt-get install -y yarn

# install git
RUN apt-get install -y git
