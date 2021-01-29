FROM php:8.0-cli

ENV COMPOSER_HOME=/.composer

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --1

# Composer cache
RUN mkdir -p /.composer/cache && chmod -R 777 /.composer/cache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# TODO: Replace after release
#RUN docker-php-ext-install xmlrpc
RUN pecl install xmlrpc-1.0.0RC2; \
    docker-php-ext-enable xmlrpc;

RUN pecl install xdebug; \
    docker-php-ext-enable xdebug;
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;