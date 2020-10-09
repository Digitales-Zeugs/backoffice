FROM php:7.2-fpm

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
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY src/ /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Chequeo de Healthcheck
HEALTHCHECK --interval=5s --timeout=3s CMD netstat -an | grep 9000 > /dev/null; if [ $? != 0 ]; then echo "[Healthcheck] probe failed."; exit 1; fi;

CMD ["php-fpm"]