# Laravel Mix
FROM node:14.13.1-alpine3.12 AS nodebuild

ARG DEV

ENV NODE_ENV=production

ENV NODE_ENV=${DEV:+development}

WORKDIR /app

COPY src/package.json src/package-lock.json ./

# Only in prod
RUN if [ -z ${DEV+x} ] ; then npm install ; fi

COPY src/ /app

RUN npm run ${NODE_ENV}

# Laravel

FROM php:7.3-fpm

# Si existe DEV instala DEV_DEPS
ARG DEV

ENV DEV_DEPS=${DEV:+"vim git curl"}

# Copy composer.lock and composer.json
COPY src/composer.lock src/composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install --no-install-recommends -y \
    build-essential ${DEV_DEPS} \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    net-tools \
    && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/

RUN docker-php-ext-install gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user for laravel application
RUN groupadd -g 1000 www

RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY --from=nodebuild /app /var/www

COPY .env* /var/www/

# Running composer only in prod
RUN if [ -z ${DEV+x} ] ; then /usr/bin/composer install ; fi

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Chequeo de Healthcheck
HEALTHCHECK --interval=5s --timeout=3s CMD netstat -an | grep 9000 > /dev/null; if [ $? != 0 ]; then echo "[Healthcheck] probe failed."; exit 1; fi;

CMD ["php-fpm"]
