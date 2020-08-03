FROM php:7.4-fpm

MAINTAINER Dmitriy Isaev <isaevdimka@gmail.com>
LABEL Description="Application container"

RUN apt-get update && \
    apt-get install -y --force-yes --no-install-recommends \
        libmemcached-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        openssh-server \
        libmagickwand-dev \
        git \
        cron \
        nano \
        mc \
        htop \
        libxml2-dev \
        apt-utils

# Install soap extention
RUN docker-php-ext-install soap

# Install for image manipulation
RUN docker-php-ext-install exif

# Install the PHP pcntl extention
RUN docker-php-ext-install pcntl

# locale
RUN docker-php-ext-install intl
RUN docker-php-ext-enable intl

# Install the PHP zip extention
RUN apt-get install -y zip libzip-dev \
  && docker-php-ext-configure zip \
  && docker-php-ext-install zip

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

# Install the PHP pdo_pgsql extention
RUN docker-php-ext-install pdo_pgsql

# Install the PHP bcmath extension
RUN docker-php-ext-install bcmath

#####################################
# Imagick:
#####################################

RUN pecl install imagick && \
    docker-php-ext-enable imagick

#####################################
# GD:
#####################################

# Install the PHP gd library
RUN docker-php-ext-install gd && \
    docker-php-ext-configure gd

#####################################
# GMP:
#####################################

RUN apt-get update && \
    apt-get -y install libgmp-dev && \
    docker-php-ext-install gmp

#####################################
# PHP Memcached:
#####################################

# Install the php memcached extension
RUN pecl install memcached && docker-php-ext-enable memcached

# Install sockets extension
RUN docker-php-ext-install sockets

#####################################
# PHP opcache:
#####################################
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-install opcache

#####################################
# PHP mongoDB:
#####################################
RUN apt-get install -y \
  libssl-dev \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb

#####################################
# Composer:
#####################################

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/laravel/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

RUN composer global require 'hirak/prestissimo' --no-interaction --no-suggest --prefer-dist
# Source the bash
RUN . ~/.bashrc

#####################################
# Node.js:
#####################################
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - && \
    apt-get update && apt-get -y install \
    nodejs && \
    npm -g install npm@latest \
    --no-install-recommends

#####################################
# Set the locale:
#####################################
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y locales
RUN sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=en_US.UTF-8

RUN locale-gen \
    en_US.UTF-8

ENV LANGUAGE=en_US.UTF-8
ENV LC_ALL=en_US.UTF-8
ENV LC_CTYPE=UTF-8
ENV LANG=en_US.UTF-8

#####################################
# Set the timezone:
#####################################
ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

#####################################
# Laravel Schedule Cron Job:
#####################################
RUN apt-get update && apt-get -y install cron

# Give execution rights on the cron job
RUN echo "* * * * * root /usr/local/bin/php /var/www/laravel/artisan schedule:run >> /dev/null 2>&1"  >> /etc/cron.d/laravel-scheduler
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/laravel-scheduler
# Apply cron job
RUN crontab /etc/cron.d/laravel-scheduler
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
# Run the command on container startup
CMD cron && tail -f /var/log/cron.log

# php config
ADD ./php.ini /usr/local/etc/php/conf.d
ADD ./opcache.ini /usr/local/etc/php/conf.d

COPY ./docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
RUN ln -s /usr/local/bin/docker-entrypoint.sh /

#####################################
# Laravel Echo Server:
#####################################
#RUN npm install -g laravel-echo-server
#COPY laravel-echo-server.conf /etc/supervisor/conf.d

#####################################
# XDEBUG Server:
#####################################
ENV PHP_XDEBUG_VERSION 2.9.6
#RUN pecl install xdebug-${PHP_XDEBUG_VERSION} \
#    && docker-php-ext-enable xdebug
#ADD ./xdebug.ini /usr/local/etc/php/conf.d

#####################################
# Supervisor consumers:
#####################################
RUN apt-get update && apt-get install -y supervisor
COPY supervisord.conf /etc/supervisor
COPY horizon.conf /etc/supervisor/conf.d
COPY fpm.conf /etc/supervisor/conf.d

#####################################
# Final Touch
#####################################

RUN rm -r /var/lib/apt/lists/*

# Permission fix
RUN usermod -u 1000 www-data

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
