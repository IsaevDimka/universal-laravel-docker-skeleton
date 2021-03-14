FROM php:8.0.2-cli

LABEL Description="high-performance PHP application server, load balancer and process manager"

######################################
## Set the timezone:
######################################
ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

RUN apt-get update && apt-get install -y --no-install-recommends \
  nano \
  mc \
  htop \
  libzip-dev \
  unzip \
  procps \
  inotify-tools \
  unzip \
  wget

######################################
## Set the locale:
######################################
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

# Install PHP Extensions
RUN docker-php-ext-install zip \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

# Install sockets extension
RUN docker-php-ext-install sockets

#####################################
# PHP mongoDB:
#####################################
RUN apt-get install -y \
  libssl-dev \
  && pecl install mongodb \
  && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Download and install RoadRunner
ARG RR_VERSION
RUN mkdir /tmp/rr \
  && cd /tmp/rr \
  && echo "{\"require\":{\"spiral/roadrunner\":\"${RR_VERSION}\"}}" >> composer.json \
  && composer install --no-dev --ignore-platform-reqs \
  && vendor/bin/rr get-binary -l /usr/local/bin \
  && rm -rf /tmp/rr

# Copy RoadRunner config
COPY config /etc/roadrunner

# Copy php config
COPY ./opcache.ini /usr/local/etc/php/conf.d

RUN rm -r /var/lib/apt/lists/*

WORKDIR /var/www/roadrunner

ENTRYPOINT ["/usr/local/bin/rr", "serve", "-d", "-c", "/etc/roadrunner/.rr.yaml"]