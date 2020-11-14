# Laravel Mix
FROM node:14.13.1-alpine3.12 AS nodebuild

WORKDIR /app

COPY src/package.json src/package-lock.json ./

RUN npm install

COPY src/ /app

RUN npm run production

# Laravel

FROM php:7.3-fpm-alpine3.12

# Copy composer.lock and composer.json
COPY src/composer.lock src/composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apk --update --no-cache add \
    alpine-sdk \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    gettext \
    gettext-dev \
    libzip-dev \
    zip \
    jpegoptim \ 
    optipng \
    pngquant \
    gifsicle \
    unzip \
    pcre-dev

# Install extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-configure gettext \
    && docker-php-ext-install \
    intl \
    gettext

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl opcache

RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/

RUN docker-php-ext-install gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user for laravel application
RUN addgroup -g 1000 www

RUN adduser --disabled-password --no-create-home -u 1000 -G www www

# Copy existing application directory contents
COPY --chown=www:www --from=nodebuild /app /var/www

RUN COMPOSER_MEMORY_LIMIT=-1 /usr/bin/composer install --prefer-dist --no-scripts

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Chequeo de Healthcheck
#HEALTHCHECK --interval=5s --timeout=3s CMD netstat -an | grep 9000 > /dev/null; if [ $? != 0 ]; then echo "[Healthcheck] probe failed."; exit 1; fi;

COPY --chown=www:www docker/docker-entrypoint.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
